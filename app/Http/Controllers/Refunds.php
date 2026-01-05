<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\RefundCourierRepository;
use App\Repositories\RefundErrorSourceRepository;
use App\Repositories\RefundPaymentModeRepository;
use App\Repositories\RefundReasonRepository;
use App\Repositories\RefundRepository;
use App\Repositories\RefundSalesSourceRepository;
use App\Repositories\RefundStatusRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Validator;

class Refunds extends Controller
{
    protected $refundrepo;
    protected $statusrepo;
    protected $moderepo;
    protected $userrepo;
    protected $errorsourcerepo;
    //
    protected $salessourcerepo;
    protected $reasonrepo;
    protected $courierrepo;

    public function __construct(
        RefundRepository $refundrepo,
        RefundStatusRepository $statusrepo,
        RefundPaymentModeRepository $moderepo,
        UserRepository $userrepo,
        RefundErrorSourceRepository $errorsourcerepo,
        RefundSalesSourceRepository $salessourcerepo,
        RefundReasonRepository $reasonrepo,
        RefundCourierRepository $courierrepo
    ) {
        parent::__construct();
        $this->middleware('auth');
        $this->refundrepo = $refundrepo;
        $this->statusrepo = $statusrepo;
        $this->moderepo = $moderepo;
        $this->userrepo = $userrepo;
        $this->errorsourcerepo = $errorsourcerepo;
        //
        $this->salessourcerepo = $salessourcerepo;
        $this->reasonrepo = $reasonrepo;
        $this->courierrepo = $courierrepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $page = $this->pageSettings('dashboard');

        $stats = [
            'today' => $this->refundrepo->getStatistics('today'),
            'month' => $this->refundrepo->getStatistics('month'),
            'all' => $this->refundrepo->getStatistics('all'),
        ];

        $by_status = $this->refundrepo->groupByStatus();
        $by_mode = $this->refundrepo->groupByPaymentMode();

        return view('pages/refunds/dashboard/wrapper', compact('page', 'stats', 'by_status', 'by_mode'));
    }

    /**
     * Export refunds to CSV
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        // get all records (no pagination)
        $refunds = $this->refundrepo->search('all');

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=refunds_' . date('Y-m-d') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = [
            'ID',
            'Bill No',
            'Amount',
            'Status',
            'Payment Mode',
            'Reason',
            'Courier',
            'Docket No',
            'Error Source',
            'Sales Source',
            'Created By',
            'Date Created'
        ];

        $callback = function () use ($refunds, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($refunds as $refund) {
                fputcsv($file, [
                    $refund->refund_id,
                    $refund->refund_bill_no,
                    $refund->refund_amount,
                    $refund->refundstatus_title ?? '',
                    $refund->refundpaymentmode_title ?? '',
                    $refund->refund_reasonid,  // You might want to join reasons table
                    $refund->refund_courierid,  // You might want to join couriers table
                    $refund->refund_docket_no,
                    $refund->refunderrorsource_title ?? '',
                    $refund->refundsalessource_title ?? '',
                    ($refund->creator_first_name ?? '') . ' ' . ($refund->creator_last_name ?? ''),
                    $refund->refund_created
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = $this->pageSettings('index');

        // Action buttons
        config(['visibility.list_page_actions_add_button' => true]);

        $refunds = $this->refundrepo->search();

        // If ajax request (pagination/sorting)
        if (request()->ajax()) {
            $html = view('pages/refunds/components/table/table', compact('refunds'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#refunds-view-wrapper',
                'action' => 'replace',
                'value' => $html
            ];
            return response()->json($jsondata);
        }

        $statuses = $this->statusrepo->search();
        $payment_modes = $this->moderepo->search();
        $users = $this->userrepo->getTeamMembers();
        $error_sources = $this->errorsourcerepo->search();
        $sales_sources = $this->salessourcerepo->search();

        return view('pages/refunds/wrapper', compact('page', 'refunds', 'statuses', 'payment_modes', 'users', 'error_sources', 'sales_sources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = $this->pageSettings('create');

        $statuses = $this->statusrepo->search();
        $payment_modes = $this->moderepo->search();
        $users = $this->userrepo->getTeamMembers();
        $error_sources = $this->errorsourcerepo->search();
        $error_sources = $this->errorsourcerepo->search();
        $sales_sources = $this->salessourcerepo->search();
        $reasons = $this->reasonrepo->search();
        $couriers = $this->courierrepo->search();

        $html = view('pages/refunds/components/modals/add-edit-inc', compact('page', 'statuses', 'payment_modes', 'users', 'error_sources', 'sales_sources', 'reasons', 'couriers'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['postrun_functions'][] = ['value' => 'NXCommonSelectorInput'];

        return response()->json($jsondata);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'refund_bill_no' => 'required',
            'refund_amount' => 'required|numeric',
            'refund_statusid' => 'required',
            'refund_payment_modeid' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        if (!$this->refundrepo->create()) {
            abort(409, __('lang.request_could_not_be_completed'));
        }

        $refunds = $this->refundrepo->search();
        $html = view('pages/refunds/components/table/table', compact('refunds'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refunds-view-wrapper',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = $this->pageSettings('edit');

        if (!$refund = \App\Models\Refund::find($id)) {
            abort(409, __('lang.item_not_found'));
        }

        $statuses = $this->statusrepo->search();
        $payment_modes = $this->moderepo->search();
        $users = $this->userrepo->getTeamMembers();
        $error_sources = $this->errorsourcerepo->search();
        $error_sources = $this->errorsourcerepo->search();
        $sales_sources = $this->salessourcerepo->search();
        $reasons = $this->reasonrepo->search();
        $couriers = $this->courierrepo->search();

        $html = view('pages/refunds/components/modals/add-edit-inc', compact('page', 'refund', 'statuses', 'payment_modes', 'users', 'error_sources', 'sales_sources', 'reasons', 'couriers'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['postrun_functions'][] = ['value' => 'NXCommonSelectorInput'];

        return response()->json($jsondata);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // Get current refund to check status transitions
        $refund = \App\Models\Refund::find($id);
        if (!$refund) {
            abort(409, __('lang.item_not_found'));
        }

        $current_status = $refund->refund_statusid;
        $new_status = request('refund_statusid');

        // Constraint: Authorized (2) -> Initial (1)
        if ($current_status == 2 && $new_status == 1) {
            abort(409, 'Cannot revert status from Authorized to Initial');
        }

        // Constraint: Complete (3) -> Authorized (2)
        if ($current_status == 3 && $new_status == 2) {
            abort(409, 'Cannot revert status from Complete to Authorized');
        }

        // Constraint: Rejected (5) -> Authorized (2) or Completed (3)
        if ($current_status == 5 && ($new_status == 2 || $new_status == 3)) {
            abort(409, 'Cannot change status from Rejected to Authorized or Completed');
        }

        $rules = [
            'refund_bill_no' => 'required',
            'refund_amount' => 'required|numeric',
            'refund_statusid' => 'required',
        ];

        // Conditional Validation: Initial (1) or Completed (3) - Payment Mode required
        // Authorized (2) does NOT show payment mode, so don't require it?
        // But if it's new, it will be null. Is that okay?
        // We will require it for 1 and 3.
        if ($new_status == 1 || $new_status == 3) {
            $rules['refund_payment_modeid'] = 'required';
        }

        // Conditional Validation: Authorized
        if ($new_status == 2) {
            $rules['refund_authorized_date'] = 'required';
            // Description/Image no longer required for Authorized
        }

        // Conditional Validation: Completed
        if ($new_status == 3) {
            $rules['refund_authorized_date'] = 'required';  // Usually assumes authorized date exists
            $rules['refund_payment_date'] = 'required';  // Payment date check
            $rules['refund_authorized_description'] = 'required';  // Note

            // Valid image required if not already present
            if (empty($refund->refund_image)) {
                $rules['refund_image'] = 'required';
            }
        }

        // Conditional Validation: Rejected
        if ($new_status == 5) {
            $rules['refund_rejected_reason'] = 'required';
        }

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= $message . '<br>';
            }
            abort(409, $messages);
        }

        if (!$this->refundrepo->update($id)) {
            abort(409, __('lang.request_could_not_be_completed'));
        }

        $refunds = $this->refundrepo->search();
        $html = view('pages/refunds/components/table/table', compact('refunds'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refunds-view-wrapper',
            'action' => 'replace',
            'value' => $html
        ];
        $jsondata['dom_visibility'][] = ['selector' => '#commonModal', 'action' => 'close-modal'];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$refund = \App\Models\Refund::find($id)) {
            abort(409);
        }

        $refund->delete();

        $jsondata['dom_visibility'][] = [
            'selector' => '#refund_' . $id,
            'action' => 'slideup-slow-remove',
        ];
        $jsondata['notification'] = ['type' => 'success', 'value' => __('lang.request_has_been_completed')];

        return response()->json($jsondata);
    }

    private function pageSettings($section = '', $data = [])
    {
        $page = [
            'crumbs' => ['Refunds'],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'refunds',
            'meta_title' => 'Refund Reports',
            'heading' => 'Refund Reports',
            'mainmenu_refunds' => 'active',
        ];

        if ($section == 'index') {
            $page['heading'] = 'Refund Report';
            $filters = request('filter_refund_statusid');
            if (is_array($filters)) {
                if (in_array(1, $filters))
                    $page['submenu_refunds_initial'] = 'active';
                if (in_array(2, $filters))
                    $page['submenu_refunds_authorized'] = 'active';
                if (in_array(3, $filters))
                    $page['submenu_refunds_completed'] = 'active';
                if (in_array(5, $filters))
                    $page['submenu_refunds_rejected'] = 'active';
            }
        }

        if ($section == 'dashboard') {
            $page['heading'] = 'Refunds Dashboard';
            $page['mainmenu_refunds'] = 'active';
            $page['submenu_refunds_dashboard'] = 'active';
        }

        if ($section == 'create') {
            $page['add_modal_title'] = 'Add New Refund';
            $page['add_modal_action_url'] = url('refunds');
            $page['add_modal_action_method'] = 'POST';
        }

        if ($section == 'edit') {
            $page['add_modal_title'] = 'Edit Refund';
            $page['add_modal_action_url'] = url('refunds/' . request()->route('refund'));
            $page['add_modal_action_method'] = 'PUT';
        }

        return $page;
    }

    public function uploadImage()
    {
        if (request()->hasFile('file')) {
            $path = request()->file('file')->store('files', 'public');
            return response()->json([
                'status' => 'success',
                'filename' => basename($path),
                'url' => url('storage/files/' . basename($path))
            ]);
        }
        return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
    }
}
