@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!-- summary row -->
    @include('pages.refunds.dashboard.widgets.summary-wrapper')

    <!-- charts row -->
    <div class="row">
        <!-- BY STATUS -->
        @include('pages.refunds.dashboard.widgets.by-status')

        <!-- BY MODE -->
        @include('pages.refunds.dashboard.widgets.by-mode')
    </div>

</div>
<!--main content -->
@endsection
