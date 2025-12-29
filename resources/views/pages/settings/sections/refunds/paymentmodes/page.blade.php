@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
<div class="settings-page">
    <!--action buttons-->
    <div class="main-pages-actions-container settings-actions-container-top text-right p-b-15">
        <button type="button"
            class="btn btn-primary btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal"
            data-url="{{ url('settings/refunds/payment-modes/create') }}" data-loading-target="commonModalBody"
            data-modal-title="Add New Payment Mode" data-action-url="{{ url('settings/refunds/payment-modes') }}"
            data-action-method="POST" data-action-ajax-class="js-ajax-ux-request"
            data-action-ajax-loading-target="commonModalBody">
            <i class="ti-plus"></i>
        </button>
    </div>
    <!--action buttons-->

    <!--table-->
    <div class="table-responsive" id="refund-paymentmodes-table">
        @include('pages.settings.sections.refunds.paymentmodes.table')
    </div>
    <!--table-->
</div>
<!--settings-->
@endsection
