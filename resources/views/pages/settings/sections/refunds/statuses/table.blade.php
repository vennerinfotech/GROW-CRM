<table class="table p-t-0 m-t-0 box-shadow-none sorting-none" id="refund-statuses-table">
    <thead>
        <tr>
            <th class="col_refundstatus_title">Status</th>
            <th class="col_refundstatus_created text-right">Date Created</th>
            <th class="col_action_buttons w-px-100 text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($statuses as $status)
        <tr class="toggle-table-row-hover" id="refund_status_{{ $status->refundstatus_id }}">
            <td class="col_refundstatus_title">
                <span class="table-color-code" style="background-color: {{ $status->refundstatus_color }};"></span>
                {{ $status->refundstatus_title }}
                <!--default-->
                @if($status->refundstatus_system_default == 'yes')
                <span class="sl-icon-star text-warning p-l-5" data-toggle="tooltip"
                    title="@lang('lang.system_default')"></span>
                @endif
            </td>
            <td class="col_refundstatus_created text-right">
                {{ runtimeDate($status->refundstatus_created ?? \Carbon\Carbon::now()) }}
            </td>
            <td class="col_action_buttons text-right">
                <div class="list-table-action-dropdown">
                    <button type="button" title="Delete"
                        class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                        data-confirm-title="@lang('lang.delete_item')" data-confirm-text="@lang('lang.are_you_sure')"
                        data-ajax-type="DELETE" data-url="{{ url('settings/refunds/statuses/'.$status->refundstatus_id) }}">
                        <i class="sl-icon-trash"></i>
                    </button>
                    <button type="button" title="Edit"
                        class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                        data-toggle="modal" data-target="#commonModal"
                        data-url="{{ url('settings/refunds/statuses/'.$status->refundstatus_id.'/edit') }}"
                        data-loading-target="commonModalBody" data-modal-title="Edit Status"
                        data-action-url="{{ url('settings/refunds/statuses/'.$status->refundstatus_id) }}"
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
