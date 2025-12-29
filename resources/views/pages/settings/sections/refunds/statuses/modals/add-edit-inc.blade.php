<div class="row">
    <div class="col-lg-12">
        <!--title-->
        <div class="form-group row">
            <label class="col-12 text-left control-label col-form-label required">Status Title</label>
            <div class="col-12">
                <input type="text" class="form-control form-control-sm" id="refundstatus_title"
                    name="refundstatus_title" value="{{ $status->refundstatus_title ?? '' }}">
            </div>
        </div>

        <!--color-->
        <div class="form-group row">
            <label class="col-12 text-left control-label col-form-label required">Color</label>
            <div class="col-12">
                <input type="color" class="form-control form-control-sm form-control-color" name="refundstatus_color"
                    value="{{ $status->refundstatus_color ?? '#cccccc' }}">
            </div>
        </div>
    </div>
</div>
