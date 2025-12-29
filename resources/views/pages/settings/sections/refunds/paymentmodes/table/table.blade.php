<div class="table-responsive" id="refund-paymentmodes-table-wrapper">
    @if (@count($payment_modes) > 0)
    <table id="refund-paymentmodes-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
        <thead>
            <tr>
                <th class="col_refundpaymentmode_title">{{ cleanLang(__('lang.title')) }}</th>
                <th class="col_refundpaymentmode_created">{{ cleanLang(__('lang.created')) }}</th>
                <th class="col_refundpaymentmode_action text-right"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
            </tr>
        </thead>
        <tbody id="refund-paymentmodes-td-container">
            <!--ajax content here-->
            @include('pages.settings.sections.refunds.paymentmodes.table.ajax')
            <!--ajax content here-->
        </tbody>
    </table>
    @endif
    @if (@count($payment_modes) == 0)
    <!--nothing found-->
    @include('notifications.no-results-found')
    <!--nothing found-->
    @endif
</div>
