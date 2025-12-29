<div class="table-responsive" id="refund-statuses-table-wrapper">
    @if (@count($statuses) > 0)
    <table id="refund-statuses-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10"
        data-type="form" data-form-id="refund-statuses-table-wrapper" data-ajax-type="POST"
        data-url="{{ url('settings/refunds/statuses/update-positions') }}">
        <thead>
            <tr>
                <th class="col_refundstatus_title">{{ cleanLang(__('lang.title')) }}</th>
                <th class="col_refundstatus_created">{{ cleanLang(__('lang.created')) }}</th>
                <th class="col_refundstatus_action text-right"><a href="javascript:void(0)">{{ cleanLang(__('lang.action')) }}</a></th>
            </tr>
        </thead>
        <tbody id="refund-statuses-td-container">
            <!--ajax content here-->
            @include('pages.settings.sections.refunds.statuses.table.ajax')
            <!--ajax content here-->
        </tbody>
    </table>
    @endif
    @if (@count($statuses) == 0)
    <!--nothing found-->
    @include('notifications.no-results-found')
    <!--nothing found-->
    @endif
</div>
