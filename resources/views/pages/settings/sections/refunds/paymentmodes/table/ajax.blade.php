@foreach($payment_modes as $mode)
<tr class="view-poll-answer" id="refundpaymentmode_{{ $mode->refundpaymentmode_id }}">
    <td class="col_refundpaymentmode_title">
        {{ $mode->refundpaymentmode_title }}
        <!--default-->
        @if($mode->refundpaymentmode_system_default == 'yes')
        <span class="sl-icon-star text-warning p-l-5" data-toggle="tooltip"
            title="{{ cleanLang(__('lang.system_default')) }}"></span>
        @endif
    </td>
    <td class="col_refundpaymentmode_created">
        {{ runtimeDate($mode->refundpaymentmode_created) }}
    </td>
    <td class="col_refundpaymentmode_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            @if($mode->refundpaymentmode_system_default != 'yes')
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/settings/refunds/payment-modes/{{ $mode->refundpaymentmode_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            @else
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-default btn-circle btn-sm disabled" disabled>
                <i class="sl-icon-trash"></i>
            </button>
            @endif
            <!--edit-->
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/settings/refunds/payment-modes/'.$mode->refundpaymentmode_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_item')) }}"
                data-action-url="{{ urlResource('/settings/refunds/payment-modes/'.$mode->refundpaymentmode_id) }}"
                data-action-method="PUT" data-action-ajax-class="js-ajax-ux-request"
                data-action-ajax-loading-target="refund-paymentmodes-td-container">
                <i class="sl-icon-note"></i>
            </button>
        </span>
    </td>
</tr>
@endforeach
