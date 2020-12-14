<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Model\Restaurant;
use App\Model\Extra;
use App\Model\Food;

trait BaseTrait
{
    public static function checkPermission($permission)
    {
        Log::info("permission: ");
        Log::info($permission);
        if (Auth::user()->role == 1 || Auth::user()->role == 2) return true;
        else {
            $permissions = Auth::user()->permissions;
            if (!$permissions) return false;
            $permissions = json_decode($permissions);
            if (in_array($permission, $permissions)) return true;
            else return false;
        }
    }
    // output is only png file
    public function base64ToImage($base64, $output)
    {
        $image_parts = explode(";base64,", $base64);
        $image_base64 = base64_decode($image_parts[1]);
        file_put_contents($output, $image_base64);
    }
    // check session data for basket
    public function checkBaskets($baskets) {
        $baskets = json_decode($baskets);
        $total_price = 0;
        if (!$baskets || !count($baskets)) return 0;
        for ($i = 0; $i < count($baskets); $i++) {
            $sub_total = 0;
            $food = Food::query()->where('id', $baskets[$i]->food_id)->first();
//            Log::info("======= food ========");Log::info($food);
            if (!$food) return 0;
            $sub_total += $food->price;
            for ($j = 0; $j < count($baskets[$i]->extras); $j++) {
//                Log::info($baskets[$i]->extras[$j][0]);
                $extra = Extra::query()->where('id', $baskets[$i]->extras[$j][0])->first();
//                Log::info(" ========== extra ============ ");
//                Log::info($extra);
                if (!$extra) return 0;
                $sub_total += $extra->price;
            }
            $total_price += $sub_total * (int)$baskets[$i]->food_amount;
        }
//        Log::info($total_price);
        return $total_price;
    }
    public function checkCardNumber($card_number) {
        // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
        $number=preg_replace('/\D/', '', $card_number);

        // Set the string length and parity
        $number_length=strlen($number);
        $parity=$number_length % 2;

        // Loop through each digit and do the maths
        $total=0;
        for ($i=0; $i<$number_length; $i++) {
            $digit=$number[$i];
            // Multiply alternate digits by two
            if ($i % 2 == $parity) {
                $digit*=2;
                // If the sum is two digits, add them together (in effect)
                if ($digit > 9) {
                    $digit-=9;
                }
            }
            // Total up the digits
            $total+=$digit;
        }

        // If the total mod 10 equals 0, the number is valid
        return ($total % 10 == 0) ? TRUE : FALSE;
    }
}
