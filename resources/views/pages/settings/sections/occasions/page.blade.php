@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!-- action buttons -->
@include('pages.settings.sections.occasions.misc.list-page-actions')
<!-- action buttons -->

<!--settings-->
<div class="settings-page">
    <div class="row">
        <div class="col-12">
            <!--table-->
            @include('pages.settings.sections.occasions.table.table')
        </div>
    </div>
</div>
@endsection
