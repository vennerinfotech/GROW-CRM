<!-- right-sidebar -->
<div class="right-sidebar" id="sidepanel-filter-refunds">
    <form>
        <div class="slimscrollright">
            <!--title-->
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_refunds')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-refunds"></i>
                </span>
            </div>
            <!--title-->
            <!--body-->
            <div class="r-panel-body">

                <!--bill no-->
                <div class="filter-block">
                    <div class="title">
                        Bill No
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control form-control-sm" name="filter_refund_bill_no"
                                    value="{{ request('filter_refund_bill_no') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!--bill no-->

                <!--date added-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.date_created')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="filter_refund_created_start"
                                    class="form-control form-control-sm pickadate" autocomplete="off"
                                    placeholder="{{ cleanLang(__('lang.start')) }}"
                                    value="{{ runtimeDatepickerDate(request('filter_refund_created_start')) }}">
                                <input class="mysql-date" type="hidden" id="filter_refund_created_start"
                                    name="filter_refund_created_start"
                                    value="{{ request('filter_refund_created_start') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="filter_refund_created_end"
                                    class="form-control form-control-sm pickadate" autocomplete="off"
                                    placeholder="{{ cleanLang(__('lang.end')) }}"
                                    value="{{ runtimeDatepickerDate(request('filter_refund_created_end')) }}">
                                <input class="mysql-date" type="hidden" id="filter_refund_created_end"
                                    name="filter_refund_created_end"
                                    value="{{ request('filter_refund_created_end') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!--date added-->

                <!--amount-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.amount')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" class="form-control form-control-sm"
                                    placeholder="{{ cleanLang(__('lang.minimum')) }}" name="filter_refund_amount_min"
                                    value="{{ request('filter_refund_amount_min') }}">
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control form-control-sm"
                                    placeholder="{{ cleanLang(__('lang.maximum')) }}" name="filter_refund_amount_max"
                                    value="{{ request('filter_refund_amount_max') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!--amount-->

                <!--status-->
                <div class="filter-block">
                    <div class="title">
                        {{ cleanLang(__('lang.status')) }}
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_refund_statusid" id="filter_refund_statusid"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @foreach($statuses as $status)
                                    <option value="{{ $status->refundstatus_id }}"
                                        {{ runtimePreselectedInArray($status->refundstatus_id, request('filter_refund_statusid') ?? []) }}>
                                        {{ $status->refundstatus_title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--status-->

                <!--payment mode-->
                <div class="filter-block">
                    <div class="title">
                        Mode of Payment
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_refund_payment_modeid" id="filter_refund_payment_modeid"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @foreach($payment_modes as $mode)
                                    <option value="{{ $mode->refundpaymentmode_id }}"
                                        {{ runtimePreselectedInArray($mode->refundpaymentmode_id, request('filter_refund_payment_modeid') ?? []) }}>
                                        {{ $mode->refundpaymentmode_title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--payment mode-->

                <!--error by-->
                <div class="filter-block">
                    <div class="title">
                        Error By
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_refund_error_sourceid" id="filter_refund_error_sourceid"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @foreach($error_sources as $error_source)
                                    <option value="{{ $error_source->refunderrorsource_id }}"
                                        {{ runtimePreselectedInArray($error_source->refunderrorsource_id, request('filter_refund_error_sourceid') ?? []) }}>
                                        {{ $error_source->refunderrorsource_title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--error by-->

                <!--sales by-->
                <div class="filter-block">
                    <div class="title">
                        Sales By
                    </div>
                    <div class="fields">
                        <div class="row">
                            <div class="col-md-12">
                                <select name="filter_refund_sales_sourceid" id="filter_refund_sales_sourceid"
                                    class="form-control form-control-sm select2-basic select2-multiple select2-tags select2-hidden-accessible"
                                    multiple="multiple" tabindex="-1" aria-hidden="true">
                                    @foreach($sales_sources as $sales_source)
                                    <option value="{{ $sales_source->refundsalessource_id }}"
                                        {{ runtimePreselectedInArray($sales_source->refundsalessource_id, request('filter_refund_sales_sourceid') ?? []) }}>
                                        {{ $sales_source->refundsalessource_title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--sales by-->

                <!--buttons-->
                <div class="buttons-block">
                    <a href="{{ url('/refunds') }}" class="btn btn-rounded-x btn-ignore-secondary">
                        {{ cleanLang(__('lang.reset')) }}
                    </a>
                    <input type="hidden" name="action" value="search">
                    <input type="hidden" name="source" value="{{ $page['source_for_filter_panels'] ?? '' }}">
                    <button type="submit" class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ url('/refunds/search') }}" data-type="form"
                        data-ajax-type="GET">{{ cleanLang(__('lang.apply_filter')) }}</button>
                </div>

            </div>
            <!--body-->
        </div>
    </form>
</div>
<!-- right-sidebar -->
