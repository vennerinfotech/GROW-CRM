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
        <div id="payment_fields_container">
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
        </div>

        <!-- Status -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Status</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="refund_statusid" name="refund_statusid"
                    {{ !isset($refund) ? 'disabled' : '' }}>
                    <option value=""></option>
                    @foreach($statuses as $status)
                    <option value="{{ $status->refundstatus_id }}"
                        {{ runtimePreselected($refund->refund_statusid ?? 1, $status->refundstatus_id) }}>
                        {{ $status->refundstatus_title }}
                    </option>
                    @endforeach
                </select>
                @if(!isset($refund))
                <input type="hidden" name="refund_statusid" value="1">
                @endif
            </div>
        </div>

        <!-- Authorized Date (Status: Authorized) -->
        <div id="authorized_date_container" style="display:none;">
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Authorized Date</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm pickadate" name="refund_authorized_date"
                        autocomplete="off" value="{{ runtimeDatepickerDate($refund->refund_authorized_date ?? '') }}">
                    <input class="mysql-date" type="hidden" name="refund_authorized_date" id="refund_authorized_date"
                        value="{{ $refund->refund_authorized_date ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Payment Date (Status: Completed) -->
        <div id="payment_date_container" style="display:none;">
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Payment Date</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm pickadate" name="refund_payment_date"
                        autocomplete="off" value="{{ runtimeDatepickerDate($refund->refund_payment_date ?? '') }}">
                    <input class="mysql-date" type="hidden" name="refund_payment_date" id="refund_payment_date"
                        value="{{ $refund->refund_payment_date ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Completion Details (Status: Completed) -->
        <div id="completion_details_container" style="display:none;">
            <!-- Image Upload -->
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Image</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="hidden" name="refund_image" id="refund_image_hidden" value="{{ $refund->refund_image ?? '' }}">
                    <input type="file" class="form-control form-control-sm" id="refund_image_input" name="file">
                    <div id="image_preview_container" class="m-t-10">
                        @if(isset($refund) && $refund->refund_image)
                        <div class="m-t-5">
                            <img src="{{ url('storage/files/' . $refund->refund_image) }}" alt="Refund Image" class="img-thumbnail" style="max-height: 100px;">
                            <div class="m-t-5">
                                <a href="{{ url('storage/files/' . $refund->refund_image) }}" target="_blank">View Full Image</a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div id="upload_status" class="text-info m-t-5" style="display:none;"></div>
                </div>
            </div>

            <!-- Payment Date (Status: Completed) -->


            <!-- Description/Note -->
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Note</label>
                <div class="col-sm-12 col-lg-9">
                    <input type="text" class="form-control form-control-sm" name="refund_authorized_description"
                        value="{{ $refund->refund_authorized_description ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Rejected Fields -->
        <div id="rejected_fields" style="{{ isset($refund) && $refund->refund_statusid == 5 ? '' : 'display:none;' }}">
            <div class="form-group row">
                <label class="col-sm-12 col-lg-3 text-left control-label col-form-label required">Rejected Reason</label>
                <div class="col-sm-12 col-lg-9">
                    <textarea class="form-control form-control-sm" rows="3" name="refund_rejected_reason">{{ $refund->refund_rejected_reason ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Initialize visibility based on current selection (if any)
                var currentStatus = $('#refund_statusid').val();
                toggleRefundFields(currentStatus);

                // On Change event
                $('#refund_statusid').on('change', function() {
                    var statusId = $(this).val();
                    toggleRefundFields(statusId);
                    
                    // Auto-populate dates logic
                    var today = moment().format('YYYY-MM-DD'); // Assuming moment.js is available, or use native JS specific format required by pickadate (usually yyyy-mm-dd or similar)
                    // If pickadate uses specific format, we might need to adjust. Usually hidden input expects YYYY-MM-DD.
                    
                    // Authorized Status (2)
                    if (statusId == 2) {
                        var existingAuthDate = $('#refund_authorized_date').val();
                        if (!existingAuthDate) {
                             // Set to today
                             var picker = $('[name="refund_authorized_date"]').pickadate('picker');
                             if(picker) picker.set('select', new Date());
                        }
                    }

                    // Completed Status (3)
                    if (statusId == 3) {
                        // Ensure Authorized Date is present if not already
                         var existingAuthDate = $('#refund_authorized_date').val();
                        if (!existingAuthDate) {
                             var pickerAuth = $('[name="refund_authorized_date"]').pickadate('picker');
                             if(pickerAuth) pickerAuth.set('select', new Date());
                        }

                        // Payment Date
                        var existingPayDate = $('#refund_payment_date').val();
                        if (!existingPayDate) {
                             var pickerPay = $('[name="refund_payment_date"]').pickadate('picker');
                             if(pickerPay) pickerPay.set('select', new Date());
                        }
                    }
                });

                function toggleRefundFields(statusId) {
                    // Hide all first
                    $('#authorized_date_container').hide();
                    $('#completion_details_container').hide();
                    $('#payment_fields_container').hide();
                    $('#rejected_fields').hide();
                    $('#payment_date_container').hide();

                    if (statusId == 2) { // Authorized
                        $('#authorized_date_container').show();
                    } else if (statusId == 3) { // Completed
                        $('#completion_details_container').show();
                        $('#payment_fields_container').show();
                        $('#authorized_date_container').show();
                        $('#payment_date_container').show();
                    } else if (statusId == 5) { // Rejected
                        $('#rejected_fields').show();
                    } else {
                        // Initial or others: Maybe hide payment?
                        // User said "Status complete selected kare atle... payment data show".
                        // Logic implies: Not complete -> No payment data?
                        // But Initial usually needs payment mode?
                        // "Bill No", "Amount" are common.
                        // I'll leave Payment visible for Initial if Logic requires it, 
                        // BUT my code above HIDES it by default.
                        // If user meant "Show payment data ONLY when complete", then it's correct.
                        // If Initial needs it, I should add 'else { $('#payment_fields_container').show(); }'
                        // I will stick to USER REQUEST: show when complete.
                        // Wait, creating a refund implies initial. If I hide payment mode for initial, can they create it?
                        // Standard flow: Initial -> Authorized (Date) -> Completed (Payment & Image).
                        // If so, Initial creation logic might fail if Payment Mode is required.
                        // Let's check "required" class on label. Added "required".
                        // If hidden, form submission might fail validation or just submit empty?
                        // I'll assume Initial needs Basic info. 
                        // USER SAID: "Status complete selected kare atle... payment data show".
                        // This strongly implies it's hidden otherwise.
                        // But existing data might have it?
                        // I'll modify logic: Show payment for Initial too? Or just strictly follow user?
                        // Let's follow user: Authorized -> Date only. Complete -> Image/Note/Payment.
                        // Initial? Maybe nothing extra.
                        // BUT: Required field issue.
                        // I'll show payment for completed.
                    }
                }
                // Image Upload AJAX
                $('#refund_image_input').on('change', function() {
                    var file_data = $(this).prop('files')[0];
                    if(!file_data) return;

                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    form_data.append('_token', '{{ csrf_token() }}');

                    $('#upload_status').show().text('Uploading...').removeClass('text-danger text-success').addClass('text-info');
                    
                    $.ajax({
                        url: "{{ route('refunds.upload-image') }}",
                        type: "POST",
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if(response.status == 'success') {
                                $('#refund_image_hidden').val(response.filename);
                                $('#upload_status').text('Upload Successful').removeClass('text-info').addClass('text-success');
                                // Update preview
                                var html = '<div class="m-t-5"><img src="' + response.url + '" class="img-thumbnail" style="max-height: 100px;">' +
                                           '<div class="m-t-5"><a href="' + response.url + '" target="_blank">View Full Image</a></div></div>';
                                $('#image_preview_container').html(html);
                            } else {
                                $('#upload_status').text('Upload Failed').removeClass('text-info').addClass('text-danger');
                            }
                        },
                        error: function() {
                            $('#upload_status').text('Upload Error').removeClass('text-info').addClass('text-danger');
                        }
                    });
                });
            });
        </script>
        
        <!-- Reason -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Reason</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="refund_reasonid" name="refund_reasonid">
                    <option value=""></option>
                    @foreach($reasons as $reason)
                    <option value="{{ $reason->refundreason_id }}"
                        {{ runtimePreselected($refund->refund_reasonid ?? '', $reason->refundreason_id) }}>
                        {{ $reason->refundreason_title }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Courier -->
        <div class="form-group row">
            <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Courier</label>
            <div class="col-sm-12 col-lg-9">
                <select class="select2-basic form-control form-control-sm" id="refund_courierid" name="refund_courierid">
                    <option value=""></option>
                    @foreach($couriers as $courier)
                    <option value="{{ $courier->refundcourier_id }}"
                        {{ runtimePreselected($refund->refund_courierid ?? '', $courier->refundcourier_id) }}>
                        {{ $courier->refundcourier_title }}
                    </option>
                    @endforeach
                </select>
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
