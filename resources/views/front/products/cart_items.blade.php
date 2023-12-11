<?php use App\Models\Product; ?>
<!-- Products-List-Wrapper -->
<div class="table-wrapper u-s-m-b-60">
<table>
<thead>
<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@php $total_price = 0 @endphp
@foreach($getCartItems as $item)
<?php 
$getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'],$item['size']);
//echo "<pre>"; print_r($getDiscountAttributePrice); die;
?>
<tr>
<td>
    <div class="cart-anchor-image">
        <a href="{{ url('product/'.$item['product_id']) }}">
            <img src="{{ asset('front/images/product_images/small/'.$item['product']['product_image']) }}" alt="Product">
            <h6>
                {{ $item['product']['product_name'] }} ({{ $item['product']['product_code'] }}) - {{ $item['size'] }}  <br>
                Color: {{ $item['product']['product_color']  }}
            </h6>
        </a>
    </div>
</td>
<td>
<div class="cart-price">
  @if($getDiscountAttributePrice['discount']>0)
  <div class="price-template">
        <div class="item-new-price">
           Rs.{{ $getDiscountAttributePrice['final_price'] }}
        </div>
        <div class="item-old-price" style="margin-left:-40px;">
            Rs.{{ $getDiscountAttributePrice['product_price'] }}
        </div>
    </div>
    @else
     <div class="price-template">
   
        <div class="item-new-price">
            Rs.{{ $getDiscountAttributePrice['final_price'] }}
        </div>

    </div>
   @endif
</div>
</td>
<td>
    <div class="cart-quantity">
        <div class="quantity">
            <input type="text" class="quantity-text-field" value="{{ $item['quantity'] }}">
            <a class="plus-a updateCartItem" data-cartid="{{ $item['id'] }}"
            data-qty="{{ $item['quantity'] }}" data-max="1000">&#43;</a>
            <a class="minus-a updateCartItem" data-cartid="{{ $item['id'] }}"
            data-qty="{{ $item['quantity'] }}" data-min="1">&#45;</a>
        </div>
    </div>
</td>
<td>
    <div class="cart-price">
         Rs.{{ $getDiscountAttributePrice['product_price'] * $item['quantity'] }}
    </div>
</td>
<td>
    <div class="action-wrapper">
       <!--  <button class="button button-outline-secondary fas fa-sync"></button> -->
        <button class="button button-outline-secondary fas fa-trash deleteCartItem" data-cartid="{{ $item['id'] }}"></button>
    </div>
</td>
</tr>
@php $total_price = $total_price +  ($getDiscountAttributePrice['product_price'] * $item['quantity']) @endphp
@endforeach
</tbody>
</table>
</div>
<!-- Products-List-Wrapper /- -->
<!-- Coupon -->
<div class="coupon-continue-checkout u-s-m-b-60">
<div class="coupon-area">
<h6>Enter your coupon code if you have one.</h6>
<div class="coupon-field">
<label class="sr-only" for="coupon-code">Apply Coupon</label>
<input id="coupon-code" type="text" class="text-field" placeholder="Coupon Code">
<button type="submit" class="button">Apply Coupon</button>
</div>
</div>
<div class="button-area">
<a href="shop-v1-root-category.html" class="continue">Continue Shopping</a>
<a href="checkout.html" class="checkout">Proceed to Checkout</a>
</div>
</div>
<!-- Coupon /- -->

<!-- Billing -->
<div class="calculation u-s-m-b-60">
<div class="table-wrapper-2">
<table>
<thead>
<tr>
<th colspan="2">Cart Totals</th>
</tr>
</thead>
<tbody>
<tr>
    <td>
    <h3 class="calc-h3 u-s-m-b-0">Sub Total</h3>
    </td>
    <td>
    <span class="calc-text">Rs. {{ $total_price }}</span>
    </td>
</tr>

<tr>
    <td>
    <h3 class="calc-h3 u-s-m-b-0">Coupon Discount</h3>
    </td>
    <td>
    <span class="calc-text">Rs.0</span>
    </td>
</tr>

<tr>
<td>
<h3 class="calc-h3 u-s-m-b-0">Grand Total</h3>
</td>
<td>
<span class="calc-text">Rs. {{ $total_price }}</span>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<!-- Billing /- -->