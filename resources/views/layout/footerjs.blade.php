<!--module extension point-->
@stack('layout_footer_1')

<!--ALL THIRD PART JAVASCRIPTS-->
<script src="vendor/js/vendor.footer.js?v={{ config('system.versioning') }}"></script>


<!--nextloop.core.js-->
<script src="js/core/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="js/core/boot.js?v={{ config('system.versioning') }}"></script>

<!--EVENTS-->
<script src="js/core/events.js?v={{ config('system.versioning') }}"></script>

<!--CORE-->
<script src="js/core/app.js?v={{ config('system.versioning') }}"></script>

<!--module extension point-->
@stack('layout_footer_2')

<!--SEARCH-->
<script src="js/core/search.js?v={{ config('system.versioning') }}"></script>

<!--BILLING-->
<script src="js/core/billing.js?v={{ config('system.versioning') }}"></script>

<!--project page charts-->
@if(@config('visibility.projects_d3_vendor'))
<script src="vendor/js/d3/d3.min.js?v={{ config('system.versioning') }}"></script>
<script src="vendor/js/c3-master/c3.min.js?v={{ config('system.versioning') }}"></script>
@endif

<!--form builder-->
@if(@config('visibility.web_form_builder'))
<script src="vendor/js/formbuilder/form-builder.min.js?v={{ config('system.versioning') }}"></script>
<script src="js/webforms/webforms.js?v={{ config('system.versioning') }}"></script>
@endif

<!--export js (https://github.com/hhurz/tableExport.jquery.plugin)-->
<script src="js/core/export.js?v={{ config('system.versioning') }}"></script>
<script type="text/javascript"
    src="vendor/js/exportjs/libs/FileSaver/FileSaver.min.js?v={{ config('system.versioning') }}"></script>
<script type="text/javascript"
    src="vendor/js/exportjs/libs/js-xlsx/xlsx.core.min.js?v={{ config('system.versioning') }}"></script>
<script type="text/javascript" src="vendor/js/exportjs/tableExport.min.js?v={{ config('system.versioning') }}">
</script>

<!--printing-->
<script type="text/javascript" src="vendor/js/printthis/printthis.js?v={{ config('system.versioning') }}">
</script>

<!--table sorter-->
<script type="text/javascript"
    src="vendor/js/tablesorter/js/jquery.tablesorter.min.js?v={{ config('system.versioning') }}"></script>

<!--bootstrap-timepicker-->
<script type="text/javascript"
    src="vendor/js/bootstrap-timepicker/bootstrap-timepicker.js?v={{ config('system.versioning') }}">
</script>

<!--code mirror - css editor-->
<script type="text/javascript" src="js/codemirror/codemirror.min.js?v={{ config('system.versioning') }}">
</script>
<script type="text/javascript" src="js/codemirror/css.min.js?v={{ config('system.versioning') }}"></script>


<!--calendaerfull js [v6.1.13]-->
<script src="vendor/js/fullcalendar/index.global.min.js?v={{ config('system.versioning') }}"></script>
<!--IMPORTANT NOTES (June 2024) - any new JS libraries added here that are booted/initiated in boot.js should also be added to the landlord footerjs.blade.js, for saas-->

<!--[modules] js includes-->
{!! config('js.modules') !!}

<!--module extension point-->
@stack('layout_footer_3')

