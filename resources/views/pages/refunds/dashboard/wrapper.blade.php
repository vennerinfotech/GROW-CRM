@extends('layout.wrapper') @section('content')
<!-- main content -->
<div class="container-fluid">

    <!-- summary row -->
    @include('pages.leads.components.misc.list-pages-stats', ['stats' => $payload['stats'] ?? []])

    <!-- refunds table -->
    @include('pages.refunds.components.table.wrapper')

    <!-- filter -->
    @include('pages.refunds.components.misc.filter')

</div>
<!--main content -->
@endsection
