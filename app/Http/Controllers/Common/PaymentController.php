<?php

namespace App\Http\Controllers\Common;

use App\Model\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use App\Model\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Redirect;
// PayPal
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\PaymentExecution;

// Stripe
use Cartalyst\Stripe\Stripe;
// Mail
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;


class PaymentController extends Controller
{
    use BaseTrait;
    private $_api_context;
    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
    public function payWithPaypal($order_item) {
        $order_amount = (float)$order_item['order_price'];
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName('Item 1')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($order_amount);  // $request->get('amount')

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($order_amount);  // $request->get('amount')

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Food Delivery Transaction ' . date("Y-m-d"));

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(url('/payment-success'))
            ->setCancelUrl(url('/payment-cancel'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $response = $payment->create($this->_api_context);
            Log::info("-------- payment is created using payment->create function: response");
            Log::info($response);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return redirect('/payment-status');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return redirect('/payment-status');
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_urls = $link->getHref();
                break;
            }
        }

        \Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_urls)) {
            \Session::put('order_payment_session', $order_item);
            return redirect()->away($redirect_urls);
        }

        \Session::put('error', 'Unknown error occurred');
        return redirect('/payment-status');
    }
    public function payWithStripe($order_item, $card_item) {
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        try {
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $card_item['number'],
                    'exp_month' => $card_item['exp_month'],
                    'exp_year' => $card_item['exp_year'],
                    'cvc' => $card_item['cvc'],
                ],
            ]);
            if (!isset($token['id'])) {
                \Session::put('error', 'Stripe payment is failed');
                return redirect('/payment-status');
            }
            $order_amount = (float)$order_item['order_price'];
            $charge = $stripe->charges()->create([
                'card' => $token['id'],
                'currency' => 'USD',
                'amount' => $order_amount,
                'description' => 'Food Delivery Order',
            ]);
            if($charge['status'] == 'succeeded') {
                Log::info(" ------------- stripe charge --------");
                Log::info($charge);
                $pay_amount = round((float)$charge['amount'] / 100, 2);
                if ($pay_amount < (float)$order_item['order_price']) {
                    \Session::put('error', 'Payment cost is less than estimated order price');
                    return redirect('/payment-status');
                }
                $order = new Order();
                $order->address = $order_item['address'];
                $order->city = $order_item['city'];
                $order->state = $order_item['state'];
                $order->postcode = $order_item['postcode'];
                $order->email = $order_item['email'];
                $order->phone = $order_item['phone'];
                $order->company = $order_item['company'];
                $order->remark = $order_item['remark'];
                $order->service_hours = $order_item['delivery_time'];
                $order->payment_method = $order_item['payment_method'];
                $order->order_data = $order_item['order_data'];
                $order->order_price = $pay_amount;
                $order->transaction_id = $charge['id'];
                $order->payment_status = 'success';
                $order->restaurant_id = $order_item['restaurant_id'];
                $order->save();
                Mail::to($order->email)->send(new OrderMail($order));
                \Session::put('success', 'Card Payment is success');
                return redirect('/payment-status');
            } else {
                \Session::put('order_make_errors','Money not add in wallet!!');
                return redirect('/order-make/'.$order_item['restaurant_id']);
            }
        } catch (\Exception $e) {
            \Session::put('order_make_errors',$e->getMessage());
            return redirect('/order-make/'.$order_item['restaurant_id']);
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            \Session::put('order_make_errors',$e->getMessage());
            return redirect('/order-make/'.$order_item['restaurant_id']);
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            \Session::put('order_make_errors',$e->getMessage());
            return redirect('/order-make/'.$order_item['restaurant_id']);
        }
    }
    public function paymentMethods(Request $request) {
        try {
            $order_item = array(
                'address' => $request['order_address'],
                'postcode' => $request['order_postcode'],
                'city' => $request['order_city'],
                'state' => $request['order_state'],
                'email' => $request['order_email'],
                'phone' => $request['order_phone'],
                'company' => $request['order_company'],
                'delivery_time' => $request['deliverytime'],
                'remark' => $request['message'],
                'payment_method' => $request['payment_method'],
                'order_data' => $request['order_baskets'],
                'restaurant_id' => $request['order_restaurant_id'],
            );
            $restaurant = Restaurant::query()->where('id', $order_item['restaurant_id'])->first();
            Log::info("========= order_item =============");
            Log::info($order_item);
            $check_baskets_price = $this->checkBaskets($order_item['order_data']);
            $order_item['order_price'] = round($check_baskets_price, 2);
            Log::info($check_baskets_price);
            if ($check_baskets_price <= 0) {
                $request->session()->put('order_make_errors', 'Your shopping cart is empty or something errors');
                return redirect('/order-make/'.$order_item['restaurant_id']);
            } else if ($check_baskets_price < (float)$restaurant->mini_order) {
                $request->session()->put('order_make_errors', 'Your order cost is less than minimum cost');
                return redirect('/order-make/'.$order_item['restaurant_id']);
            }
            if ($order_item['payment_method'] == 'paypal') {
                Log::info(' ===== paypal ========');
                return $this->payWithPaypal($order_item);
            }
            else if ($order_item['payment_method'] == 'visa' || $order_item['payment_method'] == 'master') {
                Log::info(' ===== stripe ========');
                $card_item = array(
                    'number'    => $request->get('card_no'),
                    'exp_month' => $request->get('expiry_month'),
                    'exp_year'  => $request->get('expiry_year'),
                    'cvc'       => $request->get('cvv'),
                );
                $check_card_number = $this->checkCardNumber($card_item['number']);
                if (!$check_card_number) {
                    $request->session()->put('order_make_errors', 'Your card number is incorrect');
                    return redirect('/order-make/'.$order_item['restaurant_id']);
                }
                if ((int)$card_item['exp_year'] < (int)date('Y')) {
                    $request->session()->put('order_make_errors', "Your card's expiration year is invalid");
                    return redirect('/order-make/'.$order_item['restaurant_id']);
                } else if ((int)$card_item['exp_year'] == (int)date('Y') && (int)$card_item['exp_month'] < (int)date('m')
                    || (int)$card_item['exp_month'] > 12) {
                    $request->session()->put('order_make_errors', "Your card's expiration month is invalid");
                    return redirect('/order-make/'.$order_item['restaurant_id']);
                }
                return $this->payWithStripe($order_item, $card_item);
            }
            else {
                $request->session()->put('error', 'Something unknown errors');
                return redirect('/payment-status');
            }
        } catch (\Exception $exception) {
            $request->session()->put('error', 'Something unknown errors');
            return redirect('/payment-status');
        }
    }

    public function paymentStatus(Request $request) {
        return view('frontend.payment_status');
    }
    public function paymentSuccess(Request $request) {
        Log::info("payment success for paypal .......");
        $payment_id = $request->session()->get('paypal_payment_id');
        $request->session()->forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            $request->session()->put('error', 'Payment failed');
            return redirect('/payment-status');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        Log::info(' ------------ payment result ---------------');
        Log::info($result);
        if ($result->getState() == 'approved') {
            $pay_transaction_id = $result->transactions[0]->related_resources[0]->sale->id;
            $pay_amount = round((float)$result->transactions[0]->amount->total, 2);
            Log::info($pay_transaction_id);
            Log::info($pay_amount);
            $order_session = $request->session()->get('order_payment_session');
            $request->session()->forget('order_payment_session');
            $session_amount = round((float)$order_session['order_price'], 2);
            if ($pay_amount < $session_amount) {
                $request->session()->put('error', 'Payment cost is less than estimated order price');
                return redirect('/payment-status');
            }
            $order = new Order();
            $order->address = $order_session['address'];
            $order->city = $order_session['city'];
            $order->state = $order_session['state'];
            $order->postcode = $order_session['postcode'];
            $order->email = $order_session['email'];
            $order->phone = $order_session['phone'];
            $order->company = $order_session['company'];
            $order->remark = $order_session['remark'];
            $order->service_hours = $order_session['delivery_time'];
            $order->payment_method = $order_session['payment_method'];
            $order->order_data = $order_session['order_data'];
            $order->order_price = $pay_amount;
            $order->transaction_id = $pay_transaction_id;
            $order->payment_status = 'success';
            $order->restaurant_id = $order_session['restaurant_id'];
            $order->save();
            Mail::to($order->email)->send(new OrderMail($order));
            $request->session()->put('success', 'Payment is success');
            return redirect('/payment-status');
        }
        $request->session()->put('error', 'Payment failed');
        return redirect('/payment-status');
    }
    public function paymentCancel(Request $request) {
        Log::info("payment cancel page .......");
        $request->session()->forget('order_payment_session');
        Log::info($request->session()->get('order_payment_session'));
        $request->session()->put('error', 'Payment is canceled');
        return redirect('/payment-status');
    }
}
