$(function () {
    showBasketItems();
});
function goToBasket() {
    $('#food_list_col').css('display', 'none');
    $('#order_price_wrapper').css('display', 'block');
}

function goBackFoods() {
    $('#food_list_col').css('display', 'block');
    $('#order_price_wrapper').css('display', 'none');
    $('.order_n_step').removeClass('show');
}

// check food extras and add to basket
function clickFoodItem(id) {
    var _token = $('#__token__').val();
    $.ajax({
        url: '/get-food-extras',
        method: 'post',
        data: {
            get_food_extras: 'Y',
            _token: _token,
            food_id: id,
        },
        success: function (res) {
            if (res.status === 'success') {
                console.log(res);
                showExtras(id, res.extras);
            } else {
                customAlert(res.message);
            }
        }
    })
}
function showExtras(food_id, extras) {
    $('div.media-extra-body').remove();
    var food_price = $('#food_item_' + food_id + ' .dish-price').attr('data-price');
    if (extras.length > 0) {
        var extra_html = '<div class="media-extra-body"><p>Extras:</p>';
        for (var i = 0; i < extras.length; i++) {
            extra_html += '<div class="custom-control custom-checkbox">' +
                '<input type="checkbox" class="custom-control-input extra-item" onchange="checkExtra('+ food_id +')" ' +
                'id="media_extra_id' + i +'" data-id="' + extras[i].id + '" data-name="' + extras[i].name +'" data-price="' + extras[i].price +'">' +
                '<label class="custom-control-label" for="media_extra_id' + i +'">' + extras[i].name + ' (+$' + extras[i].price + ')' +'</label></div>';
        }
        extra_html += '<div class="media-extra-actions">' +
            '<a href="javascript:void(0);" class="btn subtract" onclick="minusAmount('+ food_id +')"> - </a>' +
            '<span class="food-item-number" data-amount="'+ 1 +'">1</span>' +
            '<a href="javascript:void(0);" class="btn add" onclick="plusAmount('+ food_id +')">+</a>' +
            '<a class="btn btn-success" id="food_item_price" data-price="' + food_price + '"' +
            ' onclick="addToBasket('+ food_id +')">$ '+ food_price +' <i class="fa fa-arrow-right"></i> <i class="fa fa fa-shopping-basket"></i></a></div>';
        extra_html += '</div>';
        $('#food_item_' + food_id).append(extra_html);
    } else {
        setTimeout(function () {
            addToBasket(food_id);
        }, 200)
    }
}
function addToBasket(food_id) {
    var mediaExtraTagLength = $('div.media-extra-body').length;
    if (mediaExtraTagLength > 1) return;
    var data = {food_id: food_id};
    data.food_name = $('#food_item_' + food_id + ' .food-name').attr('data-name');
    if (mediaExtraTagLength === 1) {
        var extras = [];
        $('.extra-item:checkbox').each(function () {
            if (this.checked) {
                var extra_id = $(this).attr('data-id');
                var extra_name = $(this).attr('data-name');
                extras.push([extra_id, extra_name]);
            }
        });
        data.extras = extras;
        data.food_amount = parseInt($('.food-item-number').attr('data-amount'));
        data.price = $('#food_item_price').attr('data-price');
    } else if (mediaExtraTagLength === 0) {
        data.extras = [];
        data.food_amount = 1;
        data.price = $('#food_item_' + food_id + ' .dish-price').attr('data-price');
    }
    data.basket_way = 'add';
    data.basket_item_id = Date.now().toString();
    console.log('basket_item: ', data);
    saveBasketItem(data);
    $('div.media-extra-body').remove();
}
function plusAmount(food_id) {
    var food_amount = parseInt($('.food-item-number').attr('data-amount'));
    $('.food-item-number').attr('data-amount', (food_amount + 1));
    $('.food-item-number').text(food_amount + 1);
    checkExtra(food_id);
}
function minusAmount(food_id) {
    var food_amount = parseInt($('.food-item-number').attr('data-amount'));
    console.log(food_amount);
    if (food_amount < 2) return;
    $('.food-item-number').attr('data-amount', (food_amount - 1));
    $('.food-item-number').text(food_amount - 1);
    checkExtra(food_id);
}
function checkExtra(food_id) {
    var food_price = parseFloat($('#food_item_' + food_id + ' .dish-price').attr('data-price'));
    $('.extra-item:checkbox').each(function () {
        if (this.checked) {
            food_price += parseFloat($(this).attr('data-price'));
        }
    });
    var food_amount = parseInt($('.food-item-number').attr('data-amount'));
    var food_item_price = parseFloat((food_price * food_amount).toFixed(2));
    $('#food_item_price').attr('data-price', food_item_price);
    $('#food_item_price').html('$ ' + food_item_price + ' <i class="fa fa-arrow-right"></i> <i class="fa fa fa-shopping-basket"></i>');
}
function saveBasketItem(basket) {
    var basketStorage = localStorage.getItem('basket_items');
    var baskets = [];
    if (!basketStorage) {
        baskets.push(basket);
        localStorage.setItem('basket_items', JSON.stringify(baskets));
    }
    else {
        baskets = JSON.parse(basketStorage);
        baskets.push(basket);
        console.log('basket_items: ', baskets);
        localStorage.setItem('basket_items', JSON.stringify(baskets));
    }
    showBasketItems();
}
function showBasketItems() {
    var basket_items = JSON.parse(localStorage.getItem('basket_items'));
    var baskets_html = '';
    var sub_total_price= 0;
    var total_price = 0;
    if (!basket_items || !basket_items.length) {
        baskets_html = '<p class="order text-center">Add some tasty food from the menu and order your food.</p>'
    } else {
        baskets_html = '<ul class="order_wrapper list-unstyled" id="basket_list">';
        for (var i = 0; i < basket_items.length; i++) {
            baskets_html += '<li id="' + basket_items[i].basket_item_id + '">' +
                '<span class="basket-item-number">' + basket_items[i].food_amount + 'x</span>' +
                '<span class="dish_name">' + basket_items[i].food_name + '</span>' +
                '<div>' +
                '<a href="javascript:void(0);" class="btn subtract" onclick="minusBasketItem(' + basket_items[i].basket_item_id + ')">-</a>' +
                '<a href="javascript:void(0);" class="btn add" onclick="plusBasketItem(' + basket_items[i].basket_item_id + ')">+</a>' +
                '</div>' +
                '<span class="basket-item-price">$' + basket_items[i].price + '</span>' +
                '<a href="javascript:void(0);" onclick="removeBasketItem(' + basket_items[i].basket_item_id + ')"><i class="far fa-trash-alt"></i></a>' +
                '</li>';
            sub_total_price += parseFloat(basket_items[i].price);
            total_price += parseFloat(basket_items[i].price);
        }
        baskets_html += '</ul>';
    }
    $('#basket_items_panel').html(baskets_html);

    $('.rate .sub-total-span').text('$ ' + sub_total_price.toFixed(2));
    $('.rate .total-span').text('$ ' + total_price.toFixed(2));
    if (total_price > mini_order) {
        $('.order_button').css('background-color', 'green');
        $('.order_button').prop('disabled', false);
        $('#order_button_status').text('You have reached the minimum order amount of $' + mini_order_string + ' to checkout.');
    } else {
        $('.order_button').css('background-color', '#21252957');
        $('.order_button').prop('disabled', true);
        $('#order_button_status').text('Sorry, you can\'t order yet. Fresh\'s has set a minimum order amount of $' + mini_order_string + ' (excl. delivery costs).');
    }
    $('#order_baskets').val(localStorage.getItem('basket_items'));
}
function plusBasketItem(id) {
    var basket_items = JSON.parse(localStorage.getItem('basket_items'));
    var new_basket_items = [];
    for (var i = 0; i < basket_items.length; i++) {
        var basket_item = basket_items[i];
        if (basket_item.basket_item_id == id) {
            var price = parseFloat(basket_item.price);
            var food_amount = parseFloat(basket_item.food_amount);
            var item_price = parseFloat((price / food_amount).toFixed(2));
            basket_item.price = (price + item_price).toFixed(2);
            basket_item.food_amount += 1;
        }
        new_basket_items.push(basket_item);
    }
    localStorage.setItem('basket_items', JSON.stringify(new_basket_items));
    showBasketItems();
}
function minusBasketItem(id) {
    var basket_items = JSON.parse(localStorage.getItem('basket_items'));
    var new_basket_items =[];
    for (var i = 0; i < basket_items.length; i++) {
        var basket_item = basket_items[i];
        if (basket_items[i].basket_item_id == id) {
            var price = parseFloat(basket_item.price);
            var food_amount = parseFloat(basket_item.food_amount);
            var item_price = parseFloat((price / food_amount).toFixed(2));
            if (basket_item.food_amount > 1) {
                basket_item.food_amount -= 1;
                basket_item.price = (price - item_price).toFixed(2);
                new_basket_items.push(basket_item);
            }
        } else new_basket_items.push(basket_item);
    }
    localStorage.setItem('basket_items', JSON.stringify(new_basket_items));
    showBasketItems();
}
function removeBasketItem(id) {
    var basket_items = JSON.parse(localStorage.getItem('basket_items'));
    var new_basket_items =[];
    for (var i = 0; i < basket_items.length; i++) {
        if (basket_items[i].basket_item_id != id) {
            new_basket_items.push(basket_items[i]);
        }
    }
    localStorage.setItem('basket_items', JSON.stringify(new_basket_items));
    showBasketItems();
}
