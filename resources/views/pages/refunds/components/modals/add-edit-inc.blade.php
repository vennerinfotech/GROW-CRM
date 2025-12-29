<div class="row">
    <div class="col-lg-12">
        
        <!-- Bill No -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Bill No</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="refund_bill_no" name="refund_bill_no"
                    value="{{ $refund->refund_bill_no ?? '' }}">
            </div>
        </div>

        <!-- Amount -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Amount</label>
            <div class="col-sm-12 col-lg-9">
                <input type="number" class="form-control form-control-sm" id="refund_amount" name="refund_amount"
                    value="{{ $refund->refund_amount ?? '' }}" step="0.01">
            </div>
        </div>

        <!-- Mode of Payment -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Mode of Payment</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="refund_payment_modeid"
                    name="refund_payment_modeid">
                    <option value=""></option>
                    @foreach($payment_modes as $mode)
                    <option value="{{ $mode->refundpaymentmode_id }}"
                        {{ runtimePreselected($refund->refund_payment_modeid ?? '', $mode->refundpaymentmode_id) }}>
                        {{ $mode->refundpaymentmode_title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Status -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Status</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="refund_statusid" name="refund_statusid">
                    <option value=""></option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->refundstatus_id }}"
                        {{ runtimePreselected($refund->refund_statusid ?? '', $status->refundstatus_id) }}>
                        {{ $status->refundstatus_title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Reason -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Reason</label>
            <div class="col-sm-12 col-lg-9">
                <textarea class="form-control form-control-sm tinymce-textarea" rows="5" name="refund_reason"
                    id="refund_reason">{{ $refund->refund_reason ?? '' }}</textarea>
            </div>
        </div>
        
        <!-- Courier -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Courier</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="refund_courier" name="refund_courier"
                    value="{{ $refund->refund_courier ?? '' }}">
            </div>
        </div>

        <!-- Docket No -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Docket No</label>
            <div class="col-sm-12 col-lg-9">
                <input type="text" class="form-control form-control-sm" id="refund_docket_no" name="refund_docket_no"
                    value="{{ $refund->refund_docket_no ?? '' }}">
            </div>
        </div>

        <!-- Error By -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Error By</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="refund_error_sourceid"
                    name="refund_error_sourceid">
                    <option value=""></option>
                    @foreach($error_sources as $error_source)
                    <option value="{{ $error_source->refunderrorsource_id }}"
                        {{ runtimePreselected($refund->refund_error_sourceid ?? '', $error_source->refunderrorsource_id) }}>
                        {{ $error_source->refunderrorsource_title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Sales By -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Sales By</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="refund_sales_sourceid"
                    name="refund_sales_sourceid">
                    <option value=""></option>
                    @foreach($sales_sources as $sales_source)
                    <option value="{{ $sales_source->refundsalessource_id }}"
                        {{ runtimePreselected($refund->refund_sales_sourceid ?? '', $sales_source->refundsalessource_id) }}>
                        {{ $sales_source->refundsalessource_title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
</div>
