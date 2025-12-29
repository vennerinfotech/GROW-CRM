<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-7 p-b-9 align-self-center text-right {{ $page['list_page_actions_size'] ?? '' }} {{ $page['list_page_actions_container_class'] ?? '' }}"
    id="list-page-actions-container">
    <div id="list-page-actions">
        <!--ADD NEW ITEM-->
        <button type="button"
            class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ url('settings/refunds/payment-modes/create') }}"
            data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.add_new_item')) }}"
            data-action-url="{{ url('settings/refunds/payment-modes') }}"
            data-action-method="POST"
            data-action-ajax-loading-target="commonModalBody">
            <i class="ti-plus"></i>
        </button>
    </div>
</div>
