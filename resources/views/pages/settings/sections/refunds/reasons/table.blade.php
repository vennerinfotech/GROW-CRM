<table class="table p-t-0 m-t-0 box-shadow-none sorting-none" id="refund-reasons-table">
    <thead>
        <tr>
            <th class="col_refundreason_title">Reason</th>
            <th class="col_refundreason_created text-right">Date Created</th>
            <th class="col_action_buttons w-px-100 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reasons as $reason)
        <tr class="toggle-table-row-hover" id="refund_reason_{{ $reason->refundreason_id }}">
            <td class="col_refundreason_title">
                {{ $reason->refundreason_title }}
            </td>
            <td class="col_refundreason_created text-right">
                {{ runtimeDate($reason->refundreason_created ?? \Carbon\Carbon::now()) }}
            </td>
            <td class="col_action_buttons text-right">
                <div class="list-table-action-dropdown">
                    <button type="button" title="Delete"
                        class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                        data-confirm-title="@lang('lang.delete_item')" data-confirm-text="@lang('lang.are_you_sure')"
                        data-ajax-type="DELETE" data-url="{{ url('settings/refunds/reasons/'.$reason->refundreason_id) }}">
                        <i class="sl-icon-trash"></i>
                    </button>
                    <button type="button" title="Edit"
                        class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#commonModal"
                        data-url="{{ url('settings/refunds/reasons/'.$reason->refundreason_id.'/edit') }}"
                        data-loading-target="commonModalBody" data-modal-title="Edit Reason"
                        data-action-url="{{ url('settings/refunds/reasons/'.$reason->refundreason_id) }}"
                        data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request"
                        data-action-ajax-loading-target="commonModalBody">
                        <i class="sl-icon-note"></i>
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
