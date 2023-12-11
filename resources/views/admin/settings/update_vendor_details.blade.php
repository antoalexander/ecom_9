@extends('admin.layout.layout')
@section('content')

<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">
        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Settings</h3>
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
  
  @if($slug=="personal")
   <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Vendor Details</h4>
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

                  <form class="forms-sample" action="{{ url('admin/update-vendor-details/personal') }}" method="post" name="updateAdminDetailsForm" id="updateAdminDetailsForm" enctype="multipart/form-data">@csrf
                    <div class="form-group">
                      <label for="exampleInputUsername1">Vendor Username/Email</label>
                      <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                    </div>
           
                    <div class="form-group">
                      <label for="vendor_name">Name</label>
                      <input type="text" class="form-control"  placeholder="Enter Name" name="vendor_name" id="vendor_name" value ="{{ Auth::guard('admin')->user()->name }}" >
                    </div>
                   
                   <div class="form-group">
                      <label for="vendor_address">Address</label>
                      <input type="text" class="form-control"  placeholder="Enter Address" name="vendor_address" id="vendor_address" value ="{{ $vendorDetails['address'] }}">
                    </div>

                   <div class="form-group">
                      <label for="vendor_city">City</label>
                      <input type="text" class="form-control"  placeholder="City" name="vendor_city" id="vendor_city" value ="{{ $vendorDetails['city'] }}" >
                    </div>

                    <div class="form-group">
                      <label for="State">State</label>
                      <input type="text" class="form-control"  placeholder="State" name="vendor_state" id="vendor_state" value ="{{ $vendorDetails['state'] }}" >
                    </div>

                    <div class="form-group">
                      <label for="vendor_country">Counry</label>
                     <!--  <input type="text" class="form-control"  placeholder="Enter Country" name="vendor_country" id="vendor_country" value ="{{ $vendorDetails['country'] }}" > -->
                       <select class="form-control" id="vendor_country" name="vendor_country" style="color: #495057">
                         <option value="">Select Country</option>
                         @foreach($countries as $country)
                            <option value="{{ $country['country_name'] }}"
                             @if($country['country_name']==$vendorDetails['country']) selected @endif>
                              {{ $country['country_name'] }}
                            </option>
                         @endforeach
                       </select>
                    </div>

                   <div class="form-group">
                      <label for="vendor_pincode">Pin Code</label>
                      <input type="text" class="form-control"  placeholder="Enter Pincode" name="vendor_pincode" id="vendor_pincode" value ="{{ $vendorDetails['pincode'] }}" >
                    </div>

                  <div class="form-group">
                      <label for="admin_mobile">Mobile </label>
                      <input type="text" class="form-control" id="vendor_mobile" name="vendor_mobile" placeholder="Enter 10 Digit Mobile No." value ="{{ $vendorDetails['mobile'] }}" 
                      maxlength="10" minlength="10" >
                    </div>

                     <div class="form-group">
                      <label for="vendor_image">Photo</label>
                      <input type="file" class="form-control" id="vendor_image" name="vendor_image" >
                      @if(!empty(Auth::guard('admin')->user()->image))
                        <a target="_blank" href="{{ url('admin/images/photos/'.Auth::guard('admin')->user()->image) }}">View Image</a>
                        <input type="hidden" name="current_vendor_image" value="{{ Auth::guard('admin')->user()->image }}">
                      @endif
               
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
           
          </div>
  @elseif($slug=="business")
   <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Business Information</h4>
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

                  <form class="forms-sample" action="{{ url('admin/update-vendor-details/business') }}" method="post" name="updateAdminDetailsForm" id="updateAdminDetailsForm" enctype="multipart/form-data">@csrf
                    <div class="form-group">
                      <label for="exampleInputUsername1">Shop Username/Email</label>
                      <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                    </div>
           
                    <div class="form-group">
                      <label for="shop_name">Shop Name</label>
                      <input type="text" class="form-control"  placeholder="Enter Shop Name" name="shop_name" id="shop_name" @if(isset($vendorDetails['shop_name'])) value ="{{ $vendorDetails['shop_name'] }}" @endif>
                    </div>
                   
                   <div class="form-group">
                      <label for="shop_address">Shop Address</label>
                      <input type="text" class="form-control"  placeholder="Enter Shop Address" name="shop_address" id="shop_address" @if(isset($vendorDetails['shop_address'])) value ="{{ $vendorDetails['shop_address'] }}" @endif>
                    </div>

                   <div class="form-group">
                      <label for="shop_city">Shop City</label>
                      <input type="text" class="form-control"  placeholder="Shop City" name="shop_city" id="shop_city" @if(isset($vendorDetails['shop_city']))  
                      value="{{ $vendorDetails['shop_city'] }}" @endif>
                    </div>

                    <div class="form-group">
                      <label for="State">Shop State</label>
                      <input type="text" class="form-control"  placeholder="Shop State" name="shop_state" id="shop_state" @if(isset($vendorDetails['shop_city']))
                      value="{{ $vendorDetails['shop_city'] }}" @endif>
                    </div>



                    <div class="form-group">
                      <label for="shop_country">Shop Country</label>
                      <select class="form-control" id="shop_country" name="shop_country" style="color: #495057">
                         <option value="">Select Country</option>
                         @foreach($countries as $country)
                            <option  value="{{ $country['country_name'] }}"
                             @if(isset($vendorDetails['shop_country']) && $country['country_name']==$vendorDetails['shop_country'])
                             selected @endif>
                              {{ $country['country_name'] }}
                            </option>
                         @endforeach
                       </select>
                    </div>



                   <div class="form-group">
                      <label for="shop_pincode">Shop Pin Code</label>
                      <input type="text" class="form-control"  placeholder="Enter Shop Pincode" name="shop_pincode" id="shop_pincode"  @if(isset($vendorDetails['shop_pincode']))
                      value="{{ $vendorDetails['shop_pincode'] }}" @endif>
                    </div>

                   <div class="form-group">
                      <label for="admin_mobile">Shop Mobile </label>
                      <input type="text" class="form-control" id="shop_mobile" name="shop_mobile" placeholder="Enter 10 Digit Mobile No." @if(isset($vendorDetails['shop_mobile'])) value ="{{ $vendorDetails['shop_mobile'] }}"
                      maxlength="10" minlength="10" @endif>
                    </div>

                       <div class="form-group">
                      <label for="business_license_number">Business License Number</label>
                      <input type="text" class="form-control"  placeholder="Business License Number" name="business_license_number" id="business_license_number" 
                      @if(isset($vendorDetails['business_license_number']))
                        value="{{ $vendorDetails['business_license_number'] }}"
                      @endif>

                    </div>

                    <div class="form-group">
                      <label for="gst_number">Shop Gst Number</label>
                      <input type="text" class="form-control"  placeholder="Gst Number" name="gst_number" id="gst_number"
                      @if(isset($vendorDetails['business_license_number']))
                       value="{{ $vendorDetails['business_license_number'] }}"
                      @endif>
                    </div>

                   <div class="form-group">
                      <label for="pan_number">Shop Pan Number</label>
                      <input type="text" class="form-control"  placeholder="Pan Number" name="pan_number" id="pan_number"
                      @if(isset($vendorDetails['pan_number']))
                       value="{{ $vendorDetails['pan_number'] }}"
                      @endif>
                    </div>

                    <div class="form-group">
                      <label for="address">Shop Address Proof</label>
                      <select class="form-control" name="address_proof" id="address_proof">
                        <option value="Passpost" @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof']=="Passport") selected @endif>Passpost</option>
                        <option value="Voting Card"  @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof']=="Voting Card") selected @endif>Voting Card</option>
                        <option value="PAN"  @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof']=="PAN") selected @endif>PAN</option>
                        <option value="Driving License"  @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof']=="Driving License") selected @endif>Driving License</option>
                        <option value="Aadhar Card"  @if(isset($vendorDetails['address_proof']) && $vendorDetails['address_proof']=="Aadhar Card") selected @endif>Aadhar Card</option>
                      </select>
                    </div>

                     <div class="form-group">
                      <label for="address_proof_image">Shop Address Proof Image</label>
                      <input type="file" class="form-control" id="address_proof_image" name="address_proof_image" >
                      @if(!empty(Auth::guard('admin')->user()->image))
                        <a target="_blank" href="{{ url('admin/images/proofs/'.$vendorDetails['address_proof_image'] ) }}">View Image</a>
                        <input type="hidden" name="current_address_proof" value="{{ $vendorDetails['address_proof_image'] }}">
                      @endif
               
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
           
          </div>
  @elseif($slug=="bank")
     
    <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Bank Information</h4>
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

                  <form class="forms-sample" action="{{ url('admin/update-vendor-details/bank') }}" method="post" name="updateAdminDetailsForm" id="updateAdminDetailsForm" enctype="multipart/form-data">@csrf
                    <div class="form-group">
                      <label for="exampleInputUsername1">Shop Username/Email</label>
                      <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                    </div>
           
                    <div class="form-group">
                      <label for="account_holder_name">Account holder Name</label>
                      <input type="text" class="form-control"  placeholder="Account holder Name" name="account_holder_name" id="account_holder_name"
                      @if(isset($vendorDetails['account_holder_name'])) 
                      value="{{ $vendorDetails['account_holder_name'] }}"
                      @endif>
                    </div>
                   
                   <div class="form-group">
                      <label for="bank_name">Bank name</label>
                      <input type="text" class="form-control"  placeholder="Bank Name" name="bank_name" id="bank_name"
                      @if(isset($vendorDetails['bank_name'])) 
                       value="{{ $vendorDetails['bank_name'] }}"
                      @endif>
                    </div>

                   <div class="form-group">
                      <label for="account_number">Account number</label>
                      <input type="text" class="form-control"  placeholder="account_number City" name="account_number" id="account_number"
                       @if(isset($vendorDetails['account_number'])) 
                        value="{{ $vendorDetails['account_number'] }}"
                       @endif>
                    </div>

                    <div class="form-group">
                      <label for="bank_ifsc_code">Bank Ifsc code</label>
                      <input type="text" class="form-control"  placeholder="Shop State" name="bank_ifsc_code" id="bank_ifsc_code"
                      @if(isset($vendorDetails['bank_ifsc_code'])) 
                        value="{{ $vendorDetails['bank_ifsc_code'] }}"
                      @endif>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button type="reset" class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
           
          </div>

  @endif
</div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
 @include('admin.layout.footer')

<!-- partial -->
</div>
@endsection