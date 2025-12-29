@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        @include('misc.heading-crumbs')
        <!--Page Title & Bread Crumbs -->


        <!-- action buttons -->
        @include('pages.refunds.components.misc.list-page-actions')
        <!-- action buttons -->

    </div>
    <!--page heading-->

    <!-- page content -->
    <div class="row">
        <div class="col-12" id="refunds-layout-wrapper">
           
            <!--refunds table-->
            @include('pages.refunds.components.table.wrapper')
        
        <!--filter-->
        @include('pages.refunds.components.misc.filter')
        <!--filter-->
            <!--refunds table-->

        </div>
    </div>
    <!--page content -->

</div>
<!--main content -->
@endsection
