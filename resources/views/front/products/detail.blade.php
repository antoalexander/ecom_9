<?php
use App\Models\Product;
use App\Models\ProductsFilter;
$productFilters = ProductsFilter::productFilters();
//dd($productFilters);
?>

@extends('front.layout.layout')
@section('content')


<!-- Page Introduction Wrapper -->
<div class="page-style-a">
<div class="container">
<div class="page-intro">
<h2>Detail</h2>
<ul class="bread-crumb">
<li class="has-separator">
<i class="ion ion-md-home"></i>
<a href="{{ url('/') }}">Home</a>
</li>
<li class="is-marked">
<a href="javascript:;">Detail</a>
</li>
</ul>
</div>
</div>
</div>
<!-- Page Introduction Wrapper /- -->
<!-- Single-Product-Full-Width-Page -->
<div class="page-detail u-s-p-t-80">
<div class="container">
<!-- Product-Detail -->
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12">

<!-- Product-zoom-area -->
<div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
    <a href="{{ asset('front/images/product_images/large/'.$productDetails['product_image']) }}">
       <img src="{{ asset('front/images/product_images/large/'.$productDetails['product_image']) }}" alt="" width="500" height="500" />
    </a>
<!-- <img id="zoom-pro" class="img-fluid"
src="{{ asset('front/images/product_images/large/'.$productDetails['product_image']) }}"
data-zoom-image="{{ asset('front/images/product_images/large/'.$productDetails['product_image']) }}"
alt="Zoom Image"> -->

</div>
<!-- Product-zoom-area /- -->

<div class="thumbnails">
  
  <a href="{{ asset('front/images/product_images/large/'.$productDetails['product_image']) }}" data-standard="{{ asset('front/images/product_images/small/'.$productDetails['product_image']) }}">
     <img width="100" height="100" src="{{ asset('front/images/product_images/small/'.$productDetails['product_image']) }}" alt="" />
 </a>


@foreach($productDetails['images'] as $image)
    <a href="{{ asset('front/images/product_images/large/'.$image['image']) }}"
    data-standard="{{ asset('front/images/product_images/small/'.$image['image']) }}">
       <img width="100" height="100" src="{{ asset('front/images/product_images/small/'.$image['image']) }}" alt="" />
    </a>
@endforeach


</div>

</div>
<div class="col-lg-6 col-md-6 col-sm-12">
<!-- Product-details -->
<div class="all-information-wrapper">

   @if(Session::has('error_message'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error: </strong> <?php echo Session::get('error_message')  ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif

    @if(Session::has('success_message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success: </strong> <?php echo Session::get('success_message') ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif

<div class="section-1-title-breadcrumb-rating">
<div class="product-title">
<h1>
<a href="javascript:;">{{ $productDetails['product_name'] }}</a>
</h1>
</div>
<ul class="bread-crumb">
<li class="has-separator">
   <a href="{{ url('/') }}">Home</a>
</li>
<li class="has-separator">
   <a href="javascript:;">{{ $productDetails['section']['name'] }}</a>
</li>
  <?php  echo $categoryDetails['breadcrumbs'] ?>
</ul>
<div class="product-rating">
<div class='star' title="4.5 out of 5 - based on 23 Reviews">
<span style='width:67px'></span>
</div>
<span>(23)</span>
</div>
</div>
<div class="section-2-short-description u-s-p-y-14">
<h6 class="information-heading u-s-m-b-8">Description:</h6>
<p>{{ $productDetails['description'] }}</p>
</div>
<div class="section-3-price-original-discount u-s-p-y-14">
    <?php 
       $getDiscountPrice = Product::getDiscountPrice($productDetails['id']); 
    ?>
    <span class="getAttributePrice"> 
    @if($getDiscountPrice>0)
   
    <div class="price">
      <h4>Rs. {{ $getDiscountPrice }}</h4>
    </div>
   
    <div class="original-price">
    <span>Original Price:</span>
    <span>Rs. {{ $productDetails['product_price'] }}</span>
    </div>
   @else
    <div class="price">
      <h4>Rs. {{ $productDetails['product_price'] }}</h4>
    </div>
  @endif
</span>
    <!-- <div class="discount-price">
    <span>Discount:</span>
    <span>15%</span>
    </div>
    <div class="total-save">
    <span>Save:</span>
    <span>$20</span>
    </div> -->
</div>
<div class="section-4-sku-information u-s-p-y-14">
<h6 class="information-heading u-s-m-b-8">Sku Information:</h6>

<div class="availability">
    <span>Product Code:</span>
    <span>{{ $productDetails['product_code'] }}</span>
</div>
<div class="availability">
    <span>Product Color:</span>
    <span>{{ $productDetails['product_color'] }}</span>
</div>

<div class="availability">
    <span>Availability:</span>
    @if($totalStock>0)
      <span>In Stock</span>
    @else
      <span style="color:red">Out Of Stock</span>
    @endif
</div>

@if($totalStock>0)
<div class="left">
    <span>Only:</span>
    <span>{{ $totalStock }} left</span>
</div>
@endif

</div>

 @if(isset($productDetails['vendor']))
  <div>Sold By <a href="/products/{{ $productDetails['vendor']['id'] }}"> {{ $productDetails['vendor']['vendorbusinessdetails']['shop_name'] }}</a></div>
 @endif

<form action="{{ url('cart/add') }}" class="post-form" method="post">@csrf
     <input type="hidden" name="product_id" value="{{ $productDetails['id'] }}">
<div class="section-5-product-variants u-s-p-y-14">

    @if(count($groupProducts)>0)
     <div >
       <div><strong>Product Colors</strong></div>
       <div>
         @foreach($groupProducts as $product)
           <a href="{{ url('product/'.$product['id']) }}"><img style="width:80px;margin-top:10px" src="{{ asset('front/images/product_images/small/'.$product['product_image']) }}">
           </a>
         @endforeach
       </div>
     </div>
    @endif


    <div class="sizes u-s-m-b-11" style="margin-top:10px ;">
        <span>Available Size:</span>
        <div class="size-variant select-box-wrapper">
        <select name="size" id="getPrice" product-id="{{ $productDetails['id'] }}" class="select-box product-size" required>
            <option value="">Select Size</option>
             @foreach($productDetails['attributes'] as $attribute)
            <option value="{{ $attribute['size']}}">{{ $attribute['size']}}</option>
             @endforeach
        </select>
        </div>
    </div>



</div>
<div class="section-6-social-media-quantity-actions u-s-p-y-14">
<?php /*
<div class="quick-social-media-wrapper u-s-m-b-22">
<span>Share:</span>
<ul class="social-media-list">
<li>
    <a href="#">
        <i class="fab fa-facebook-f"></i>
    </a>
</li>
<li>
    <a href="#">
        <i class="fab fa-twitter"></i>
    </a>
</li>
<li>
    <a href="#">
        <i class="fab fa-google-plus-g"></i>
    </a>
</li>
<li>
    <a href="#">
        <i class="fas fa-rss"></i>
    </a>
</li>
<li>
    <a href="#">
        <i class="fab fa-pinterest"></i>
    </a>
</li>
</ul>
</div>
 */ ?>
<div class="quantity-wrapper u-s-m-b-22">
<span>Quantity:</span>
<div class="quantity">
<!-- <input type="text" class="quantity-text-field" value="1">
<a class="plus-a" data-max="1000">&#43;</a>
<a class="minus-a" data-min="1">&#45;</a> -->
 <input type="number" class="quantity-text-field" name="quantity" value="1">
</div>
</div>
<div>
<button class="button button-outline-secondary" type="submit">Add to cart</button>
<button class="button button-outline-secondary far fa-heart u-s-m-l-6"></button>
<button class="button button-outline-secondary far fa-envelope u-s-m-l-6"></button>
</div>

</div>
</form>
</div>
<!-- Product-details /- -->
</div>
</div>
<!-- Product-Detail /- -->
<!-- Detail-Tabs -->
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="detail-tabs-wrapper u-s-p-t-80">
<div class="detail-nav-wrapper u-s-m-b-30">
<ul class="nav single-product-nav justify-content-center">
<li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#video">Product Video</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#detail">Product Details</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#review">Reviews (15)</a>
</li>
</ul>
</div>
<div class="tab-content">
<!-- Description-Tab -->
<div class="tab-pane fade active show" id="video">
<div>
  @if($productDetails['product_video'])
     
     <video controls>
        <source src="{{ url('front/videos/product_videos/'.$productDetails['product_video']) }}" type="video/mp4">
     </video>

  @else
     <p>Product Video Does Not Exists</p>
  @endif

</div>
</div>
<!-- Description-Tab /- -->
<!-- details-Tab -->
<div class="tab-pane fade" id="detail">
<div class="specification-whole-container">

<div class="spec-table u-s-m-b-50">
<h4 class="spec-heading">Product Details</h4>
<table>
    @foreach($productFilters as $filter)
      @if(isset($productDetails['category_id']))
        <?php 
          $filterAvailable = ProductsFilter::filterAvailable($filter['id'],$productDetails['category_id']);
        ?>
        @if($filterAvailable=="Yes")
        <tr>
            <td>{{ $filter['filter_name'] }}</td>
            <td>
                @foreach($filter['filter_values'] as $value)
                   @if(!empty($productDetails[$filter['filter_column']]) && $value['filter_value']==$productDetails[$filter['filter_column']])
                     {{ ucwords($value['filter_value']) }}
                   @endif
                @endforeach
           </td>
        </tr>
        @endif
      @endif
    @endforeach
</table>
</div>
</div>
</div>
<!-- Specifications-Tab /- -->
<!-- Reviews-Tab -->
<div class="tab-pane fade" id="review">
<div class="review-whole-container">
<div class="row r-1 u-s-m-b-26 u-s-p-b-22">
<div class="col-lg-6 col-md-6">
    <div class="total-score-wrapper">
        <h6 class="review-h6">Average Rating</h6>
        <div class="circle-wrapper">
            <h1>4.5</h1>
        </div>
        <h6 class="review-h6">Based on 23 Reviews</h6>
    </div>
</div>
<div class="col-lg-6 col-md-6">
    <div class="total-star-meter">
        <div class="star-wrapper">
            <span>5 Stars</span>
            <div class="star">
                <span style='width:0'></span>
            </div>
            <span>(0)</span>
        </div>
        <div class="star-wrapper">
            <span>4 Stars</span>
            <div class="star">
                <span style='width:67px'></span>
            </div>
            <span>(23)</span>
        </div>
        <div class="star-wrapper">
            <span>3 Stars</span>
            <div class="star">
                <span style='width:0'></span>
            </div>
            <span>(0)</span>
        </div>
        <div class="star-wrapper">
            <span>2 Stars</span>
            <div class="star">
                <span style='width:0'></span>
            </div>
            <span>(0)</span>
        </div>
        <div class="star-wrapper">
            <span>1 Star</span>
            <div class="star">
                <span style='width:0'></span>
            </div>
            <span>(0)</span>
        </div>
    </div>
</div>
</div>
<div class="row r-2 u-s-m-b-26 u-s-p-b-22">
<div class="col-lg-12">
    <div class="your-rating-wrapper">
        <h6 class="review-h6">Your Review is matter.</h6>
        <h6 class="review-h6">Have you used this product before?</h6>
        <div class="star-wrapper u-s-m-b-8">
            <div class="star">
                <span id="your-stars" style='width:0'></span>
            </div>
            <label for="your-rating-value"></label>
            <input id="your-rating-value" type="text" class="text-field" placeholder="0.0">
            <span id="star-comment"></span>
        </div>
        <form>
            <label for="your-name">Name
                <span class="astk"> *</span>
            </label>
            <input id="your-name" type="text" class="text-field" placeholder="Your Name">
            <label for="your-email">Email
                <span class="astk"> *</span>
            </label>
            <input id="your-email" type="text" class="text-field" placeholder="Your Email">
            <label for="review-title">Review Title
                <span class="astk"> *</span>
            </label>
            <input id="review-title" type="text" class="text-field" placeholder="Review Title">
            <label for="review-text-area">Review
                <span class="astk"> *</span>
            </label>
            <textarea class="text-area u-s-m-b-8" id="review-text-area" placeholder="Review"></textarea>
            <button class="button button-outline-secondary">Submit Review</button>
        </form>
    </div>
</div>
</div>
<!-- Get-Reviews -->
<div class="get-reviews u-s-p-b-22">
<!-- Review-Options -->
<div class="review-options u-s-m-b-16">
    <div class="review-option-heading">
        <h6>Reviews
            <span> (15) </span>
        </h6>
    </div>
    <div class="review-option-box">
        <div class="select-box-wrapper">
            <label class="sr-only" for="review-sort">Review Sorter</label>
            <select class="select-box" id="review-sort">
                <option value="">Sort by: Best Rating</option>
                <option value="">Sort by: Worst Rating</option>
            </select>
        </div>
    </div>
</div>
<!-- Review-Options /- -->
<!-- All-Reviews -->
<div class="reviewers">
    <div class="review-data">
        <div class="reviewer-name-and-date">
            <h6 class="reviewer-name">John</h6>
            <h6 class="review-posted-date">10 May 2018</h6>
        </div>
        <div class="reviewer-stars-title-body">
            <div class="reviewer-stars">
                <div class="star">
                    <span style='width:67px'></span>
                </div>
                <span class="review-title">Good!</span>
            </div>
            <p class="review-body">
                Good Quality...!
            </p>
        </div>
    </div>
    <div class="review-data">
        <div class="reviewer-name-and-date">
            <h6 class="reviewer-name">Doe</h6>
            <h6 class="review-posted-date">10 June 2018</h6>
        </div>
        <div class="reviewer-stars-title-body">
            <div class="reviewer-stars">
                <div class="star">
                    <span style='width:67px'></span>
                </div>
                <span class="review-title">Well done!</span>
            </div>
            <p class="review-body">
                Cotton is good.
            </p>
        </div>
    </div>
    <div class="review-data">
        <div class="reviewer-name-and-date">
            <h6 class="reviewer-name">Tim</h6>
            <h6 class="review-posted-date">10 July 2018</h6>
        </div>
        <div class="reviewer-stars-title-body">
            <div class="reviewer-stars">
                <div class="star">
                    <span style='width:67px'></span>
                </div>
                <span class="review-title">Well done!</span>
            </div>
            <p class="review-body">
                Excellent condition
            </p>
        </div>
    </div>
    <div class="review-data">
        <div class="reviewer-name-and-date">
            <h6 class="reviewer-name">Johnny</h6>
            <h6 class="review-posted-date">10 March 2018</h6>
        </div>
        <div class="reviewer-stars-title-body">
            <div class="reviewer-stars">
                <div class="star">
                    <span style='width:67px'></span>
                </div>
                <span class="review-title">Bright!</span>
            </div>
            <p class="review-body">
                Cotton
            </p>
        </div>
    </div>
    <div class="review-data">
        <div class="reviewer-name-and-date">
            <h6 class="reviewer-name">Alexia C. Marshall</h6>
            <h6 class="review-posted-date">12 May 2018</h6>
        </div>
        <div class="reviewer-stars-title-body">
            <div class="reviewer-stars">
                <div class="star">
                    <span style='width:67px'></span>
                </div>
                <span class="review-title">Well done!</span>
            </div>
            <p class="review-body">
                Good polyester Cotton
            </p>
        </div>
    </div>
</div>
<!-- All-Reviews /- -->
<!-- Pagination-Review -->
<div class="pagination-review-area">
    <div class="pagination-review-number">
        <ul>
            <li style="display: none">
                <a href="single-product.html" title="Previous">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            <li class="active">
                <a href="single-product.html">1</a>
            </li>
            <li>
                <a href="single-product.html">2</a>
            </li>
            <li>
                <a href="single-product.html">3</a>
            </li>
            <li>
                <a href="single-product.html">...</a>
            </li>
            <li>
                <a href="single-product.html">10</a>
            </li>
            <li>
                <a href="single-product.html" title="Next">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- Pagination-Review /- -->
</div>
<!-- Get-Reviews /- -->
</div>
</div>
<!-- Reviews-Tab /- -->
</div>
</div>
</div>
</div>
<!-- Detail-Tabs /- -->
<!-- Different-Product-Section -->
<div class="detail-different-product-section u-s-p-t-80">
<!-- Similar-Products -->
<section class="section-maker">
<div class="container">
<div class="sec-maker-header text-center">
<h3 class="sec-maker-h3">Similar Products</h3>
</div>
<div class="slider-fouc">
<div class="products-slider owl-carousel" data-item="4">

@foreach($similarProducts as $product)
<div class="item">
    <div class="image-container">
    <a class="item-img-wrapper-link" href="{{ url('product/'.$product['id']) }}">
        <?php 
          $product_image_path = 'front/images/product_images/small/'.$product['product_image'];
        ?>
        @if(!empty($product['product_image']) && file_exists($product_image_path))
          <img class="img-fluid" src="{{ asset($product_image_path) }}" alt="Product">
        @else
          <img class="img-fluid" src="{{ asset('front/images/product_images/small/no-img.png') }}" alt="Product">
        @endif
    </a>
    <div class="item-action-behaviors">
        <a class="item-quick-look" data-toggle="modal" href="#quick-view">Quick Look</a>
        <a class="item-mail" href="javascript:void(0)">Mail</a>
        <a class="item-addwishlist" href="javascript:void(0)">Add to Wishlist</a>
        <a class="item-addCart" href="javascript:void(0)">Add to Cart</a>
    </div>
    </div>
    <div class="item-content">
    <div class="what-product-is">
       <ul class="bread-crumb">
            <li class="has-separator">
                <a href="shop-v1-root-category.html">{{ $product['product_code'] }}</a>
            </li>
            <li class="has-separator">
                <a href="listing.html">{{ $product['product_color'] }}</a>
            </li>
            <li>
                <a href="listing.html">{{ $product['brand']['name'] }}</a>
            </li>
          
        </ul>
        <h6 class="item-title">
            <a href="{{ url('product/'.$product['id']) }}">{{ $product['product_name'] }}</a>
        </h6>
        <!-- <div class="item-stars">
            <div class='star' title="0 out of 5 - based on 0 Reviews">
                <span style='width:0'></span>
            </div>
            <span>(0)</span>
        </div> -->
    </div>
    <?php 
        $getDiscountPrice = Product::getDiscountPrice($product['id']); 
    ?>
    @if($getDiscountPrice>0)
    <div class="price-template">
        <div class="item-new-price">
           Rs.{{ $getDiscountPrice }}
        </div>
        <div class="item-old-price">
            Rs.{{ $product['product_price'] }}
        </div>
    </div>
    @else
     <div class="price-template">
       <div class="item-old-price">
            $100.00
        </div>
    </div>
   @endif
    </div>
    <div class="tag new">
    <span>NEW</span>
    </div>
</div>
@endforeach


</div>
</div>
</div>
</section>
<!-- Similar-Products /- -->
<!-- Recently-View-Products  -->
<section class="section-maker">
<div class="container">
<div class="sec-maker-header text-center">
<h3 class="sec-maker-h3">Recently Viewed Products</h3>
</div>
<div class="slider-fouc">
<div class="products-slider owl-carousel" data-item="4">
    
 @foreach($recentlyViewedProducts as $product)
    <div class="item">
        <div class="image-container">
        <a class="item-img-wrapper-link" href="{{ url('product/'.$product['id']) }}">
            <?php 
              $product_image_path = 'front/images/product_images/small/'.$product['product_image'];
            ?>
            @if(!empty($product['product_image']) && file_exists($product_image_path))
              <img class="img-fluid" src="{{ asset($product_image_path) }}" alt="Product">
            @else
              <img class="img-fluid" src="{{ asset('front/images/product_images/small/no-img.png') }}" alt="Product">
            @endif
        </a>
        <div class="item-action-behaviors">
            <a class="item-quick-look" data-toggle="modal" href="#quick-view">Quick Look</a>
            <a class="item-mail" href="javascript:void(0)">Mail</a>
            <a class="item-addwishlist" href="javascript:void(0)">Add to Wishlist</a>
            <a class="item-addCart" href="javascript:void(0)">Add to Cart</a>
        </div>
        </div>
        <div class="item-content">
        <div class="what-product-is">
           <ul class="bread-crumb">
                <li class="has-separator">
                    <a href="shop-v1-root-category.html">{{ $product['product_code'] }}</a>
                </li>
                <li class="has-separator">
                    <a href="listing.html">{{ $product['product_color'] }}</a>
                </li>
                <li>
                    <a href="listing.html">{{ $product['brand']['name'] }}</a>
                </li>
              
            </ul>
            <h6 class="item-title">
                <a href="{{ url('product/'.$product['id']) }}">{{ $product['product_name'] }}</a>
            </h6>
            <!-- <div class="item-stars">
                <div class='star' title="0 out of 5 - based on 0 Reviews">
                    <span style='width:0'></span>
                </div>
                <span>(0)</span>
            </div> -->
        </div>
        <?php 
            $getDiscountPrice = Product::getDiscountPrice($product['id']); 
        ?>
        @if($getDiscountPrice>0)
        <div class="price-template">
            <div class="item-new-price">
               Rs.{{ $getDiscountPrice }}
            </div>
            <div class="item-old-price">
                Rs.{{ $product['product_price'] }}
            </div>
        </div>
        @else
         <div class="price-template">
           <div class="item-old-price">
                $100.00
            </div>
        </div>
       @endif
        </div>
        <div class="tag new">
        <span>NEW</span>
        </div>
    </div>
@endforeach


</div>
</div>
</div>
</section>
<!-- Recently-View-Products /- -->
</div>
<!-- Different-Product-Section /- -->
</div>
</div>
<!-- Single-Product-Full-Width-Page /- -->
<!-- Footer -->
    

@endsection