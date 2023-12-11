@extends('admin.layout.layout')
@section('content')


<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Products Management</h3>
  
        </div>
        <div class="col-12 col-xl-4">
         <div class="justify-content-end d-flex">
          <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
            <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
             <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
              <a class="dropdown-item" href="#">January - March</a>
              <a class="dropdown-item" href="#">March - June</a>
              <a class="dropdown-item" href="#">June - August</a>
              <a class="dropdown-item" href="#">August - November</a>
            </div>
          </div>
         </div>
        </div>
      </div>
    </div>
  </div>
  
   <div class="row">
        
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Add Attributes</h4>
               @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              @endif

               @if(Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Error: </strong> {{ Session::get('error_message') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif

                @if(Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success: </strong> {{ Session::get('success_message') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif

              <form class="forms-sample" action="{{ url('admin/add-edit-attributes/'.$product['id']) }}"  method="post">@csrf

               <div class="form-group">
                  <label for="product_name">Product Name</label>
                   &nbsp; {{ $product['product_name'] }}
               </div>

                <div class="form-group">
                  <label for="product_code">Product Code</label>
                  &nbsp; {{ $product['product_code'] }}
                </div>

                <div class="form-group">
                  <label for="product_color">Product Color</label>
                   &nbsp; {{ $product['product_color'] }}
                </div>

                <div class="form-group">
                  <label for="product_color">Product Price</label>
                   &nbsp; {{ $product['product_price'] }}
                </div>

                 <div class="form-group">
                 
                  @if(!empty($product['product_image']))
                    <img src="{{ url('front/images/product_images/small/'.$product['product_image']) }}">
                  @else
                     <img src="{{ url('front/images/product_images/small/no-img.png">
                  @endif   
                </div>

                <div class="form-group">
                    <div class="field_wrapper">
                      <div>
                          <input type="text" name="size[]" placeholder="Size" 
                          style="width:90px" required />
                          <input type="text" name="sku[]" placeholder="sku" 
                          style="width:90px" required />
                          <input type="text" name="price[]" placeholder="price" 
                          style="width:90px" required />
                          <input type="text" name="stock[]" placeholder="stock" 
                          style="width:90px" required />
                          <a href="javascript:void(0);" class="add_button" title="Add Attributes">Add</a>
                      </div>
                  </div>
                </div>
               
                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                <button class="btn btn-light">Cancel</button>
              </form>
            </div>
          </div>
        </div>

        <div class="table-responsive pt-3">
           <?php 
              $product['id'];
           ?>
          <h2>Product Attributes</h2>
          <form method="post" action="{{ url('admin/edit-attributes/'.$product['id'] ) }}"> @csrf
                     <table id="products" class="table table-bordered">
                        <thead>
                           <tr>
                              <th>
                                 ID
                              </th>
                               <th>
                                  Size
                               </th> 
                              <th> 
                                  Sku
                              </th>
                              <th>
                                  Price
                              </th>
                              <th>
                                  Stock
                              </th>
                              <th>
                                 Action
                              </th>
                         
                           </tr>
                        </thead>

                        <tbody>
                            
                          <?php 
                          $attributes = json_decode(json_encode($product['attributes']),true);
                            ?> 
                            @foreach($attributes as $attr)
                           <input type="text" style="display: none" name="attributeId[]" value="{{ $attr['id'] }}" >
                            <tr>
                              <td>{{$attr['id']  }}</td>
                              <td>{{$attr['size'] }}</td>
                              <td>{{$attr['sku']  }}</td>
                              <td>
                                <input type="number" name="price[]" value="{{ $attr['price'] }}" required>
                              </td>
                              <td>
                                 <input type="number" name="stock[]" value="{{ $attr['stock'] }}" required>
                              </td>
                               <td>
                                 @if($attr['status'])
                                  <a class="updateAttrStatus" id="attr-{{ $attr['id'] }}" attr_id="{{ $attr['id'] }}" href="javascript:void(0)">
                                  <i style="font-size:25px" class="mdi mdi-bookmark-check" status="Active"></i></a>
                                 @else
                                   <a class="updateAttrStatus" id="attr-{{ $attr['id'] }}" 
                                  attr_id="{{ $attr['id'] }}" href="javascript:void(0)"> <i style="font-size:25px" class="mdi mdi-bookmark-outline" status="InActive"></i></a>
                                  @endif
                              </td>
                            </tr>

                         
                            @endforeach
                           
                        </tbody>
                         
                           
                     </table>
                     <button type="submit" class="btn btn-primary">Update Attribute</button>
                      </form>
                  </div>
         </div>
</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
 @include('admin.layout.footer')

<!-- partial -->
</div>
@endsection