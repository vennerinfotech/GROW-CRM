<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-7 p-b-9 align-self-center text-right {{ $page['list_page_actions_size'] ?? '' }} {{ $page['list_page_actions_container_class'] ?? '' }}"
    id="list-page-actions-container">
    <div id="list-page-actions">
        
        <!--FILTER-->
        <button type="button" data-toggle="tooltip" title="{{ cleanLang(__('lang.filter')) }}"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-side-panel"
            data-target="sidepanel-filter-refunds">
            <i class="mdi mdi-filter-outline"></i>
        </button>

        <!--ADD NEW ITEM-->
        @if(config('visibility.list_page_actions_add_button'))
        <button type="button"
            class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ url('refunds/create') }}"
            data-loading-target="commonModalBody" data-modal-title="@lang('lang.add_new_refund')"
            data-action-url="{{ url('refunds') }}" data-action-method="POST"
            data-action-ajax-class="js-ajax-ux-request" data-modal-size="modal-lg"
            data-action-ajax-loading-target="commonModalBody" data-save-button-class="" data-project-progress="0">
            <i class="ti-plus"></i>
        </button>
        @endif
    </div>
</div>
