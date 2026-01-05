<div class="row">
    <div class="col-lg-12">
        <div class="form-group row">
            <label class="col-12 text-left control-label col-form-label required">{{ cleanLang(__('lang.occasion_name')) }}*</label>
            <div class="col-12">
                <input type="text" class="form-control form-control-sm" id="leadoccasions_title" name="leadoccasions_title"
                    value="{{ $occasion->leadoccasions_title ?? '' }}">
            </div>
        </div>
    </div>
</div>
