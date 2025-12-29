<div class="col-12 align-self-center hidden checkbox-actions-box p-b-15" id="refunds-checkbox-actions-container">
    <div class="x-buttons">
        @if(config('visibility.action_buttons_delete'))
        <button type="button" class="btn btn-sm btn-default waves-effect waves-dark confirm-action-danger"
            data-type="form" data-ajax-type="POST" data-form-id="refunds-checkbox-actions-container"
            data-confirm-title="{{ cleanLang(__('lang.delete_selected_items')) }}"
            data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}"
            data-url="{{ url('/refunds/delete') }}"> <!-- Need to implement bulk delete route if used -->
            <i class="sl-icon-trash"></i> {{ cleanLang(__('lang.delete')) }}
        </button>
        @endif
    </div>
</div>
