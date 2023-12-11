@extends('front.layout.layout')
@section('content')

<div class="page-style-a">
    <div class="container">
         <div class="page-intro">
            <h2>{{ $cmsPageDetails['title'] }}</h2>
            <ul class="bread-crumb">
                 <li class="has-separator">
                     <i class="ion ion-md-home"></i>
                     <a href="index.html">Home</a>
                 </li> 
                 <li class="is-marked">
                    <a href="#">{{ $cmsPageDetails['title'] }}</a>
                 </li> 
            </ul> 
        </div>        
     </div> 
</div>

<div class="page-cart u-s-p-t-80">
    <div class="container">
         <div class="row">
            <div class="col-lg-12" align="center">
               <p>{{ $cmsPageDetails['description'] }}</p> 
            </div>
         </div>
    </div>
</div>

@endsection
