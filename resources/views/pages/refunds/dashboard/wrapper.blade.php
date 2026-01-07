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

    <!--stats panel-->
    <div class="stats-wrapper" id="tasks-stats-wrapper">
        @include('pages.leads.components.misc.list-pages-stats', ['stats' => $payload['stats'] ?? []])
    </div>
    <!--stats panel-->

    <!-- page content -->
    <div class="row kanban-wrapper">
        <div class="col-12" id="refunds-layout-wrapper">

            <!-- refunds table -->
            @include('pages.refunds.components.table.wrapper')

            <!-- filter -->
            @include('pages.refunds.components.misc.filter')

        </div>
    </div>
    <!--page content -->

</div>
<!--main content -->
@endsection
