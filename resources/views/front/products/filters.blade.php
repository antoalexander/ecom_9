<?php 
  use App\Models\ProductsFilter;

  
  $productFilters = ProductsFilter::productFilters();

  // dd($productFilters);

?>

<!-- Shop-Left-Side-Bar-Wrapper -->
<div class="col-lg-3 col-md-3 col-sm-12">
<!-- Fetch-Categories-from-Root-Category  -->
<div class="fetch-categories">
    <h3 class="title-name">Browse Categories</h3>
    <!-- Level 1 -->
    <h3 class="fetch-mark-category">
        <a href="listing.html">T-Shirts
            <span class="total-fetch-items">(5)</span>
        </a>
    </h3>
    <ul>
        <li>
            <a href="shop-v3-sub-sub-category.html">Casual T-Shirts
                <span class="total-fetch-items">(3)</span>
            </a>
        </li>
        <li>
            <a href="listing.html">Formal T-Shirts
                <span class="total-fetch-items">(2)</span>
            </a>
        </li>
    </ul>
    <!-- //end Level 1 -->
    <!-- Level 2 -->
    <h3 class="fetch-mark-category">
        <a href="listing.html">Shirts
            <span class="total-fetch-items">(5)</span>
        </a>
    </h3>
    <ul>
        <li>
            <a href="shop-v3-sub-sub-category.html">Casual Shirts
                <span class="total-fetch-items">(3)</span>
            </a>
        </li>
        <li>
            <a href="listing.html">Formal Shirts
                <span class="total-fetch-items">(2)</span>
            </a>
        </li>
    </ul>
    <!-- //end Level 2 -->
</div>
<!-- Fetch-Categories-from-Root-Category  /- -->
<!-- Filters -->
<!-- Filter-Size -->
<?php $getSizes = ProductsFilter::getSizes($url); ?>
<div class="facet-filter-associates">
    <h3 class="title-name">Size</h3>
    <form class="facet-form" action="#" method="post">
        <div class="associate-wrapper">
            
            @foreach($getSizes as $key => $size)
                <input type="checkbox" class="check-box size" name="size[]" id="size{{ $key }}"
                value="{{ $size }}">
                <label class="label-text" for="size{{ $key }}">{{ $size }}
                    <!-- <span class="total-fetch-items">(2)</span> -->
                </label>
            @endforeach
            
        </div>
    </form>
</div>
<!-- Filter-Size -->
<!-- Filter-Color -->
 
<?php $getColors = ProductsFilter::getColors($url); ?>
<div class="facet-filter-associates">
    <h3 class="title-name">Color</h3>
    <form class="facet-form" action="#" method="post">
        <div class="associate-wrapper">
            
            @foreach($getColors as $key => $color)
                <input type="checkbox" class="check-box color" name="color[]" id="color{{ $key }}"
                value="{{ $color }}">
                <label class="label-text" for="color{{ $key }}">{{ $color }}</label>
            @endforeach
            
        </div>
    </form>
</div>
<!-- Filter-Color /- -->

  <!-- Filter-Brand -->
<?php $getBrands = ProductsFilter::getBrands($url); ?>
<div class="facet-filter-associates">
    <h3 class="title-name">Brand</h3>
    <form class="facet-form" action="#" method="post">
        <div class="associate-wrapper">
           
           @foreach($getBrands as $key => $brand)
            <input type="checkbox" class="check-box brand" name="brand[]" id="brand{{ $key }}" 
            value="{{ $brand['id'] }}">
            <label class="label-text" for="brand{{ $key }}">{{ $brand['name'] }}</label>
           @endforeach
        </div>
    </form>
</div>
<!-- Filter-Brand /- -->

<!-- Filter-Brand -->
<div class="facet-filter-associates">
    <h3 class="title-name">Price</h3>
    <form class="facet-form" action="#" method="post">
        <div class="associate-wrapper">
           
           <?php $prices = array('0-1000','1001-2000','2001-5000','5001-10000','10001-100000'); ?>
           
           @foreach($prices as $key => $price)
          

            <input type="checkbox" class="check-box price" name="price[]" id="price{{ $key }}"
                value="{{ $price }}">
                <label class="label-text" for="price{{ $key }}">Rs. {{ $price }}</label>
           @endforeach

        </div>
    </form>
</div>
            <!-- Filter-Brand /- -->

<!-- Filter -->
@foreach($productFilters as $filter)
<?php 
   $filterAvailable = ProductsFilter::filterAvailable($filter['id'],$categoryDetails['categoryDetails']['id']);
?>
@if($filterAvailable=="Yes")
  @if(count($filter['filter_values'])>0)
    <div class="facet-filter-associates">
        <h3 class="title-name">{{ $filter['filter_name'] }}</h3>
        <form class="facet-form" action="#" method="post">
            <div class="associate-wrapper">
               
               @foreach($filter['filter_values'] as $value)
                <input type="checkbox" class="check-box {{ $filter['filter_column'] }}"
                id="{{ $value['filter_value'] }}" name="{{ $filter['filter_column'] }}[]"
                value="{{ $value['filter_value'] }}">
                <label class="label-text" for="{{ $value['filter_value'] }}">{{ ucwords($value['filter_value']) }}
                   <!--  <span class="total-fetch-items">(0)</span> -->
                </label>
               @endforeach
              
            </div>
        </form>
    </div>
  @endif
@endif
@endforeach
<!-- Filter /- -->

</div>
<!-- Shop-Left-Side-Bar-Wrapper /- -->