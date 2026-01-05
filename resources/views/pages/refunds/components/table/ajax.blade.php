@foreach($refunds as $refund)
<tr id="refund_{{ $refund->refund_id }}">
    <td class="refunds_col_checkbox checkitem" id="refunds_col_checkbox_{{ $refund->refund_id }}">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-refunds-{{ $refund->refund_id }}" name="ids[{{ $refund->refund_id }}]"
                class="listcheckbox listcheckbox-refunds filled-in chk-col-light-blue"
                data-actions-container-class="refunds-checkbox-actions-container">
            <label for="listcheckbox-refunds-{{ $refund->refund_id }}"></label>
        </span>
    </td>
    <td>
        {{ $refund->refund_id }}
    </td>
    <td>
        {{ $refund->refund_bill_no }}
    </td>
    <td>
        {{ runtimeMoneyFormat($refund->refund_amount) }}
    </td>
    <td>
        {{ $refund->refundpaymentmode_title ?? '---' }}
    </td>
    <td>
        <span title="{{ $refund->refund_reason ?? '' }}">{{ \Illuminate\Support\Str::limit($refund->refund_reason ?? '', 20) }}</span>
    </td>
    <td>
        {{ $refund->refund_courier }} / {{ $refund->refund_docket_no }}
    </td>
    <td>
        <span class="label {{ bootstrapColors($refund->refundstatus_color ?? 'default', 'label') }}">
            {{ $refund->refundstatus_title ?? '---' }}
        </span>
    </td>
    <td>
        {{ $refund->refunderrorsource_title ?? '---' }}
    </td>
    <td>
        {{ $refund->refundsalessource_title ?? '---' }}
    </td>
    
    <td class="refunds_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <button type="button" title="{{ cleanLang(__('lang.delete')) }}"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="{{ cleanLang(__('lang.delete_item')) }}"
                data-confirm-text="{{ cleanLang(__('lang.are_you_sure')) }}" data-ajax-type="DELETE"
                data-url="{{ url('/') }}/refunds/{{ $refund->refund_id }}">
                <i class="sl-icon-trash"></i>
            </button>
            
            <!--edit-->
            <button type="button" title="{{ cleanLang(__('lang.edit')) }}"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="{{ urlResource('/refunds/'.$refund->refund_id.'/edit') }}"
                data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.edit_item')) }}"
                data-action-url="{{ urlResource('/refunds/'.$refund->refund_id) }}" data-action-method="PUT"
                data-action-ajax-class="js-ajax-ux-request" data-action-ajax-loading-target="refunds-td-container">
                <i class="sl-icon-note"></i>
            </button>
        </span>
    </td>
</tr>
@endforeach
