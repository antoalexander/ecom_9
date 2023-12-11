@extends('front.layout.layout')
@section('content')

<!-- Page Introduction Wrapper -->
<div class="page-style-a">
<div class="container">
<div class="page-intro">
<h2>Account</h2>
<ul class="bread-crumb">
<li class="has-separator">
    <i class="ion ion-md-home"></i>
    <a href="index.html">Home</a>
</li>
<li class="is-marked">
    <a href="account.html">Account</a>
</li>
</ul>
</div>
</div>
</div>
<!-- Page Introduction Wrapper /- -->
<!-- Account-Page -->
<div class="page-account u-s-p-t-80">
<div class="container">

@if(Session::has('success_message'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	  <strong>Success: </strong> {{ Session::get('success_message') }}
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
@if($errors->any())
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
	  <strong>Error: </strong>  <?php echo implode('', $errors->all('<div>:message</div>')) ?>
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
@endif
<div class="row">
<!-- Login -->

<div class="col-lg-6">
<div class="login-wrapper">
    <h2 class="account-h2 u-s-m-b-20">Login</h2>
    <h6 class="account-h6 u-s-m-b-30">Welcome back! Sign in to your account.</h6>
    <form class="pt-3" action="{{ url('admin/login')}}" method="post">@csrf
        <div class="u-s-m-b-30">
            <label for="vendor-email">Email
                <span class="astk">*</span>
            </label>
            <input type="email"  class="text-field" name="email" id="vendor-email" placeholder="Vendor Email">
        </div>
        <div class="u-s-m-b-30">
            <label for="vendor-password">Password
                <span class="astk">*</span>
            </label>
            <input type="password" class="text-field" name="password" id="vendor-password"
            placeholder="Password">
        </div>
        
        <div class="m-b-45">
            <button class="button button-outline-secondary w-100">Login</button>
        </div>
    </form>
</div>
</div>
<!-- Login /- -->
<!-- Register -->
<div class="col-lg-6">
<div class="reg-wrapper">
    <h2 class="account-h2 u-s-m-b-20">Register</h2>
    <h6 class="account-h6 u-s-m-b-30">Registering for this site allows you to access your order status and history.</h6>
    <form  id="vendorForm" action="{{ url('/vendor/register') }}" method="post">@csrf
        
        <div class="u-s-m-b-30">
            <label for="vendorname">Name
                <span class="astk">*</span>
            </label>
            <input type="text" id="vendorname" class="text-field" placeholder="Vendor Name" name="name">
        </div>

        <div class="u-s-m-b-30">
            <label for="vendormobile">Mobile
                <span class="astk">*</span>
            </label>
            <input type="text" id="vendormobile" class="text-field" placeholder="Mobile" name="mobile">
        </div>

        <div class="u-s-m-b-30">
            <label for="vendoremail">Email
                <span class="astk">*</span>
            </label>
            <input type="email" id="vendoremail" class="text-field" name="email" placeholder="Vendor Email">
        </div>
        <div class="u-s-m-b-30">
            <label for="vendorpassword">Password
                <span class="astk">*</span>
            </label>
            <input type="password" id="vendorpassword" class="text-field" name="password" placeholder="Vendor Password">
        </div>
        <div class="u-s-m-b-30">
            <input type="checkbox" class="check-box" id="accept" name="accept" value="on">
            <label class="label-text no-color" for="accept">I’ve read and accept the
                <a href="terms-and-conditions.html" class="u-c-brand">terms & conditions</a>
            </label>
        </div>
        <div class="u-s-m-b-45">
            <button class="button button-primary w-100">Register</button>
        </div>
    </form>
</div>
</div>
<!-- Register /- -->
</div>
</div>
</div>
<!-- Account-Page /- -->

@endsection