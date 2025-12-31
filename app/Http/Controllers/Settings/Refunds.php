<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Repositories\RefundCourierRepository;
use App\Repositories\RefundPaymentModeRepository;
use App\Repositories\RefundReasonRepository;
use App\Repositories\RefundStatusRepository;
use Illuminate\Http\Request;
use Validator;

class Refunds extends Controller
{
    protected $statusrepo;
    protected $moderepo;
    protected $reasonrepo;
    protected $courierrepo;

    public function __construct(
        RefundStatusRepository $statusrepo,
        RefundPaymentModeRepository $moderepo,
        RefundReasonRepository $reasonrepo,
        RefundCourierRepository $courierrepo
    ) {
        parent::__construct();
        $this->middleware('auth');
        $this->statusrepo = $statusrepo;
        $this->moderepo = $moderepo;
        $this->reasonrepo = $reasonrepo;
        $this->courierrepo = $courierrepo;
    }

    /**
     * Display general settings or redirect to statuses
     */
    public function index()
    {
        // For now, default to statuses view
        return $this->statuses();
    }

    // ============================================================================================
    // STATUSES
    // ============================================================================================

    public function statuses()
    {
        $page = $this->pageSettings('statuses');
        $statuses = $this->statusrepo->search();

        $html = view('pages/settings/sections/refunds/statuses/page', compact('page', 'statuses'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#settings-wrapper',
            'action' => 'replace',
            'value' => $html
        ];

        // Menu highlighting
        $jsondata = array_merge($jsondata, $this->updateMenu('settings-menu-refunds-statuses'));

        return response()->json($jsondata);
    }

    // ... (keep create/store/edit/update/destroy methods as is)

    // ============================================================================================
    // PAYMENT MODES
    // ============================================================================================

    public function paymentModes()
    {
        $page = $this->pageSettings('payment_modes');
        $modes = $this->moderepo->search();

        $html = view('pages/settings/sections/refunds/paymentmodes/page', compact('page', 'modes'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#settings-wrapper',
            'action' => 'replace',
            'value' => $html
        ];

        // Menu highlighting
        $jsondata = array_merge($jsondata, $this->updateMenu('settings-menu-refunds-paymentmodes'));

        return response()->json($jsondata);
    }

    // ...

    /**
     * Update menu highlighting
     */
    private function updateMenu($active_id)
    {
        $jsondata['dom_classes'][] = [
            'selector' => '.settings-menu-link',
            'action' => 'remove',
            'value' => 'active',
        ];
        $jsondata['dom_classes'][] = [
            'selector' => '#' . $active_id,
            'action' => 'add',
            'value' => 'active',
        ];

        return $jsondata;
    }

    public function createStatus()
    {
        $page = $this->pageSettings('create_status');
        $html = view('pages/settings/sections/refunds/statuses/modals/add-edit-inc', compact('page'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['postrun_functions'][] = ['value' => 'NXCommonSelectorInput'];

        return response()->json($jsondata);
    }

    public function storeStatus()
    {
        $validator = Validator::make(request()->all(), [
            'refundstatus_title' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        // Get next position
        $last = \App\Models\RefundStatus::orderBy('refundstatus_position', 'desc')->first();
        $position = $last ? $last->refundstatus_position + 1 : 1;

        if (!$id = $this->statusrepo->create($position)) {
            abort(409, __('lang.request_could_not_be_completed'));
        }

        $statuses = $this->statusrepo->search();
        $html = view('pages/settings/sections/refunds/statuses/table', compact('statuses'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-statuses-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function editStatus($id)
    {
        $page = $this->pageSettings('edit_status');
        $page['section'] = 'edit';

        $status = \App\Models\RefundStatus::find($id);
        $html = view('pages/settings/sections/refunds/statuses/modals/add-edit-inc', compact('page', 'status'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['postrun_functions'][] = ['value' => 'NXCommonSelectorInput'];

        return response()->json($jsondata);
    }

    public function updateStatus($id)
    {
        if (!$this->statusrepo->update($id)) {
            abort(409);
        }

        $statuses = $this->statusrepo->search();
        $html = view('pages/settings/sections/refunds/statuses/table', compact('statuses'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-statuses-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function destroyStatus($id)
    {
        $status = \App\Models\RefundStatus::find($id);
        if ($status->refundstatus_system_default == 'yes') {
            abort(409, __('lang.cannot_delete_system_default_item'));
        }

        // check usage
        if ($status->refunds()->exists()) {
            abort(409, __('lang.item_is_in_use'));
        }

        $status->delete();

        $jsondata['dom_visibility'][] = [
            'selector' => '#refund_status_' . $id,
            'action' => 'slideup-slow-remove',
        ];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    // ============================================================================================
    // PAYMENT MODES
    // ============================================================================================

    //

    public function createPaymentMode()
    {
        $page = $this->pageSettings('create_mode');
        $html = view('pages/settings/sections/refunds/paymentmodes/modals/add-edit-inc', compact('page'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function storePaymentMode()
    {
        $validator = Validator::make(request()->all(), [
            'refundpaymentmode_title' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        if (!$id = $this->moderepo->create()) {
            abort(409, __('lang.request_could_not_be_completed'));
        }

        $modes = $this->moderepo->search();
        $html = view('pages/settings/sections/refunds/paymentmodes/table', compact('modes'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-paymentmodes-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function editPaymentMode($id)
    {
        $page = $this->pageSettings('edit_mode');
        $page['section'] = 'edit';

        $mode = \App\Models\RefundPaymentMode::find($id);
        $html = view('pages/settings/sections/refunds/paymentmodes/modals/add-edit-inc', compact('page', 'mode'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function updatePaymentMode($id)
    {
        if (!$this->moderepo->update($id)) {
            abort(409);
        }

        $modes = $this->moderepo->search();
        $html = view('pages/settings/sections/refunds/paymentmodes/table', compact('modes'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-paymentmodes-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function destroyPaymentMode($id)
    {
        $mode = \App\Models\RefundPaymentMode::find($id);
        if ($mode->refundpaymentmode_system_default == 'yes') {
            abort(409, __('lang.cannot_delete_system_default_item'));
        }

        if ($mode->refunds()->exists()) {
            abort(409, __('lang.item_is_in_use'));
        }

        $mode->delete();

        $jsondata['dom_visibility'][] = [
            'selector' => '#refund_paymentmode_' . $id,
            'action' => 'slideup-slow-remove',
        ];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    // ============================================================================================
    // ERROR SOURCES
    // ============================================================================================

    public function errorSources()
    {
        $page = $this->pageSettings('error_sources');
        $sources = \App\Models\RefundErrorSource::orderBy('refunderrorsource_id', 'desc')->get();

        $html = view('pages/settings/sections/refunds/errorsources/page', compact('page', 'sources'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#settings-wrapper',
            'action' => 'replace',
            'value' => $html
        ];

        // Menu highlighting
        $jsondata = array_merge($jsondata, $this->updateMenu('settings-menu-refunds-errorsources'));

        return response()->json($jsondata);
    }

    public function createErrorSource()
    {
        $page = $this->pageSettings('create_error_source');
        $html = view('pages/settings/sections/refunds/errorsources/modals/add-edit-inc', compact('page'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function storeErrorSource()
    {
        $validator = Validator::make(request()->all(), [
            'refunderrorsource_title' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        $source = new \App\Models\RefundErrorSource();
        $source->refunderrorsource_title = request('refunderrorsource_title');
        $source->save();

        $sources = \App\Models\RefundErrorSource::orderBy('refunderrorsource_id', 'desc')->get();
        $html = view('pages/settings/sections/refunds/errorsources/table', compact('sources'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-errorsources-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function editErrorSource($id)
    {
        $page = $this->pageSettings('edit_error_source');
        $page['section'] = 'edit';

        $source = \App\Models\RefundErrorSource::find($id);
        $html = view('pages/settings/sections/refunds/errorsources/modals/add-edit-inc', compact('page', 'source'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function updateErrorSource($id)
    {
        $source = \App\Models\RefundErrorSource::find($id);
        $source->refunderrorsource_title = request('refunderrorsource_title');
        $source->save();

        $sources = \App\Models\RefundErrorSource::orderBy('refunderrorsource_id', 'desc')->get();
        $html = view('pages/settings/sections/refunds/errorsources/table', compact('sources'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-errorsources-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function destroyErrorSource($id)
    {
        $source = \App\Models\RefundErrorSource::find($id);

        if ($source->refunds()->exists()) {
            abort(409, __('lang.item_is_in_use'));
        }

        $source->delete();

        $jsondata['dom_visibility'][] = [
            'selector' => '#refund_errorsource_' . $id,
            'action' => 'slideup-slow-remove',
        ];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    // ============================================================================================
    // SALES SOURCES
    // ============================================================================================

    public function salesSources()
    {
        $page = $this->pageSettings('sales_sources');
        $sources = \App\Models\RefundSalesSource::orderBy('refundsalessource_id', 'desc')->get();

        $html = view('pages/settings/sections/refunds/salessources/page', compact('page', 'sources'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#settings-wrapper',
            'action' => 'replace',
            'value' => $html
        ];

        // Menu highlighting
        $jsondata = array_merge($jsondata, $this->updateMenu('settings-menu-refunds-salessources'));

        return response()->json($jsondata);
    }

    public function createSalesSource()
    {
        $page = $this->pageSettings('create_sales_source');
        $html = view('pages/settings/sections/refunds/salessources/modals/add-edit-inc', compact('page'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function storeSalesSource()
    {
        $validator = Validator::make(request()->all(), [
            'refundsalessource_title' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        $source = new \App\Models\RefundSalesSource();
        $source->refundsalessource_title = request('refundsalessource_title');
        $source->save();

        $sources = \App\Models\RefundSalesSource::orderBy('refundsalessource_id', 'desc')->get();
        $html = view('pages/settings/sections/refunds/salessources/table', compact('sources'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-salessources-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function editSalesSource($id)
    {
        $page = $this->pageSettings('edit_sales_source');
        $page['section'] = 'edit';

        $source = \App\Models\RefundSalesSource::find($id);
        $html = view('pages/settings/sections/refunds/salessources/modals/add-edit-inc', compact('page', 'source'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function updateSalesSource($id)
    {
        $source = \App\Models\RefundSalesSource::find($id);
        $source->refundsalessource_title = request('refundsalessource_title');
        $source->save();

        $sources = \App\Models\RefundSalesSource::orderBy('refundsalessource_id', 'desc')->get();
        $html = view('pages/settings/sections/refunds/salessources/table', compact('sources'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-salessources-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function destroySalesSource($id)
    {
        $source = \App\Models\RefundSalesSource::find($id);

        if ($source->refunds()->exists()) {
            abort(409, __('lang.item_is_in_use'));
        }

        $source->delete();

        $jsondata['dom_visibility'][] = [
            'selector' => '#refund_salessource_' . $id,
            'action' => 'slideup-slow-remove',
        ];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    private function pageSettings($section = '', $data = [])
    {
        $page = [
            'crumbs' => [__('lang.settings'), 'Refunds'],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => 'Refund Settings',
            'heading' => 'Refund Settings',
        ];

        if ($section == 'statuses') {
            $page['heading'] = 'Refund Statuses';
            $page['add_modal_title'] = 'Add New Status';
            $page['add_modal_create_url'] = url('settings/refunds/statuses/create');
            $page['add_modal_action_url'] = url('settings/refunds/statuses/create');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'create_status') {
            $page['add_modal_title'] = 'Add New Status';
            $page['add_modal_action_url'] = url('settings/refunds/statuses');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'edit_status') {
            $page['add_modal_title'] = 'Edit Status';
            $page['add_modal_action_url'] = url('settings/refunds/statuses/' . request()->route('id'));
            $page['add_modal_action_method'] = 'PUT';
        }

        if ($section == 'payment_modes') {
            $page['heading'] = 'Payment Modes';
            $page['add_modal_title'] = 'Add New Payment Mode';
            $page['add_modal_create_url'] = url('settings/refunds/payment-modes/create');
            $page['add_modal_action_url'] = url('settings/refunds/payment-modes/create');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'create_mode') {
            $page['add_modal_title'] = 'Add New Payment Mode';
            $page['add_modal_action_url'] = url('settings/refunds/payment-modes');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'edit_mode') {
            $page['add_modal_title'] = 'Edit Payment Mode';
            $page['add_modal_action_url'] = url('settings/refunds/payment-modes/' . request()->route('id'));
            $page['add_modal_action_method'] = 'PUT';
        }

        if ($section == 'error_sources') {
            $page['heading'] = 'Error Sources';
            $page['add_modal_title'] = 'Add New Error Source';
            $page['add_modal_create_url'] = url('settings/refunds/error-sources/create');
            $page['add_modal_action_url'] = url('settings/refunds/error-sources/create');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'create_error_source') {
            $page['add_modal_title'] = 'Add New Error Source';
            $page['add_modal_action_url'] = url('settings/refunds/error-sources');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'edit_error_source') {
            $page['add_modal_title'] = 'Edit Error Source';
            $page['add_modal_action_url'] = url('settings/refunds/error-sources/' . request()->route('id'));
            $page['add_modal_action_method'] = 'PUT';
        }

        if ($section == 'sales_sources') {
            $page['heading'] = 'Sales Sources';
            $page['add_modal_title'] = 'Add New Sales Source';
            $page['add_modal_create_url'] = url('settings/refunds/sales-sources/create');
            $page['add_modal_action_url'] = url('settings/refunds/sales-sources/create');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'create_sales_source') {
            $page['add_modal_title'] = 'Add New Sales Source';
            $page['add_modal_action_url'] = url('settings/refunds/sales-sources');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'edit_sales_source') {
            $page['add_modal_title'] = 'Edit Sales Source';
            $page['add_modal_action_url'] = url('settings/refunds/sales-sources/' . request()->route('id'));
            $page['add_modal_action_method'] = 'PUT';
        }

        if ($section == 'reasons') {
            $page['heading'] = 'Refund Reasons';
            $page['add_modal_title'] = 'Add New Reason';
            $page['add_modal_create_url'] = url('settings/refunds/reasons/create');
            $page['add_modal_action_url'] = url('settings/refunds/reasons/create');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'create_reason') {
            $page['add_modal_title'] = 'Add New Reason';
            $page['add_modal_action_url'] = url('settings/refunds/reasons');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'edit_reason') {
            $page['add_modal_title'] = 'Edit Reason';
            $page['add_modal_action_url'] = url('settings/refunds/reasons/' . request()->route('id'));
            $page['add_modal_action_method'] = 'PUT';
        }

        if ($section == 'couriers') {
            $page['heading'] = 'Couriers';
            $page['add_modal_title'] = 'Add New Courier';
            $page['add_modal_create_url'] = url('settings/refunds/couriers/create');
            $page['add_modal_action_url'] = url('settings/refunds/couriers/create');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'create_courier') {
            $page['add_modal_title'] = 'Add New Courier';
            $page['add_modal_action_url'] = url('settings/refunds/couriers');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'edit_courier') {
            $page['add_modal_title'] = 'Edit Courier';
            $page['add_modal_action_url'] = url('settings/refunds/couriers/' . request()->route('id'));
            $page['add_modal_action_method'] = 'PUT';
        }

        return $page;
    }

    //

    public function reasons()
    {
        $page = $this->pageSettings('reasons');
        $reasons = $this->reasonrepo->search();

        $html = view('pages/settings/sections/refunds/reasons/page', compact('page', 'reasons'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#settings-wrapper',
            'action' => 'replace',
            'value' => $html
        ];

        // Menu highlighting
        $jsondata = array_merge($jsondata, $this->updateMenu('settings-menu-refunds-reasons'));

        return response()->json($jsondata);
    }

    public function createReason()
    {
        $page = $this->pageSettings('create_reason');
        $html = view('pages/settings/sections/refunds/reasons/modals/add-edit-inc', compact('page'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function storeReason()
    {
        $validator = Validator::make(request()->all(), [
            'refundreason_title' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        if (!$this->reasonrepo->create()) {
            abort(409, __('lang.request_could_not_be_completed'));
        }

        $reasons = $this->reasonrepo->search();
        $html = view('pages/settings/sections/refunds/reasons/table', compact('reasons'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-reasons-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function editReason($id)
    {
        $page = $this->pageSettings('edit_reason');
        $page['section'] = 'edit';

        $reason = \App\Models\RefundReason::find($id);
        $html = view('pages/settings/sections/refunds/reasons/modals/add-edit-inc', compact('page', 'reason'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function updateReason($id)
    {
        if (!$this->reasonrepo->update($id)) {
            abort(409);
        }

        $reasons = $this->reasonrepo->search();
        $html = view('pages/settings/sections/refunds/reasons/table', compact('reasons'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-reasons-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function destroyReason($id)
    {
        $reason = \App\Models\RefundReason::find($id);

        if ($reason->refunds()->exists()) {
            abort(409, __('lang.item_is_in_use'));
        }

        $reason->delete();

        $jsondata['dom_visibility'][] = [
            'selector' => '#refund_reason_' . $id,
            'action' => 'slideup-slow-remove',
        ];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    // ============================================================================================
    // COURIERS
    // ============================================================================================

    public function couriers()
    {
        $page = $this->pageSettings('couriers');
        $couriers = $this->courierrepo->search();

        $html = view('pages/settings/sections/refunds/couriers/page', compact('page', 'couriers'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#settings-wrapper',
            'action' => 'replace',
            'value' => $html
        ];

        // Menu highlighting
        $jsondata = array_merge($jsondata, $this->updateMenu('settings-menu-refunds-couriers'));

        return response()->json($jsondata);
    }

    public function createCourier()
    {
        $page = $this->pageSettings('create_courier');
        $html = view('pages/settings/sections/refunds/couriers/modals/add-edit-inc', compact('page'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function storeCourier()
    {
        $validator = Validator::make(request()->all(), [
            'refundcourier_title' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        if (!$this->courierrepo->create()) {
            abort(409, __('lang.request_could_not_be_completed'));
        }

        $couriers = $this->courierrepo->search();
        $html = view('pages/settings/sections/refunds/couriers/table', compact('couriers'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-couriers-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function editCourier($id)
    {
        $page = $this->pageSettings('edit_courier');
        $page['section'] = 'edit';

        $courier = \App\Models\RefundCourier::find($id);
        $html = view('pages/settings/sections/refunds/couriers/modals/add-edit-inc', compact('page', 'courier'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];

        return response()->json($jsondata);
    }

    public function updateCourier($id)
    {
        if (!$this->courierrepo->update($id)) {
            abort(409);
        }

        $couriers = $this->courierrepo->search();
        $html = view('pages/settings/sections/refunds/couriers/table', compact('couriers'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refund-couriers-table',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    public function destroyCourier($id)
    {
        $courier = \App\Models\RefundCourier::find($id);

        if ($courier->refunds()->exists()) {
            abort(409, __('lang.item_is_in_use'));
        }

        $courier->delete();

        $jsondata['dom_visibility'][] = [
            'selector' => '#refund_courier_' . $id,
            'action' => 'slideup-slow-remove',
        ];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    //
}
