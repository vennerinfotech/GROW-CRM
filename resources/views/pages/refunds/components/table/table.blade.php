<div class="card count-{{ @count($refunds ?? []) }}" id="refunds-view-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper">
            @if (@count($refunds ?? []) > 0)
            <table id="refunds-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        <th class="list-checkbox-wrapper">
                            <!--list checkbox-->
                            <span class="list-checkboxes display-inline-block w-px-20">
                                <input type="checkbox" id="listcheckbox-refunds" name="listcheckbox-refunds"
                                    class="listcheckbox-all filled-in chk-col-light-blue"
                                    data-actions-container-class="refunds-checkbox-actions-container"
                                    data-children-checkbox-class="listcheckbox-refunds">
                                <label for="listcheckbox-refunds"></label>
                            </span>
                        </th>
                        <th>@lang('lang.id')</th>
                        <th>Bill No</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Reason</th>
                        <th>Courier / Docket</th>
                        <th>Status</th>
                        <th>Error By</th>
                        <th>Sales By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="refunds-table-body">
                    <!--ajax content here-->
                    @include('pages.refunds.components.table.ajax')
                    <!--ajax content here-->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20">
                            <!--load more button-->
                            @include('misc.load-more-button')
                            <!--load more button-->
                        </td>
                    </tr>
                </tfoot>
            </table>
            @endif 
            
            @if (@count($refunds ?? []) == 0)
            <!--nothing found-->
            @include('notifications.no-results-found')
            <!--nothing found-->
            @endif
        </div>
    </div>
</div>
