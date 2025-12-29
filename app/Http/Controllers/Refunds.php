<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\RefundErrorSourceRepository;
use App\Repositories\RefundPaymentModeRepository;
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
    protected $salessourcerepo;

    public function __construct(
        RefundRepository $refundrepo,
        RefundStatusRepository $statusrepo,
        RefundPaymentModeRepository $moderepo,
        UserRepository $userrepo,
        RefundErrorSourceRepository $errorsourcerepo,
        RefundSalesSourceRepository $salessourcerepo
    ) {
        parent::__construct();
        $this->middleware('auth');
        $this->refundrepo = $refundrepo;
        $this->statusrepo = $statusrepo;
        $this->moderepo = $moderepo;
        $this->userrepo = $userrepo;
        $this->errorsourcerepo = $errorsourcerepo;
        $this->salessourcerepo = $salessourcerepo;
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
                'selector' => '#refunds-table-body',
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
        $sales_sources = $this->salessourcerepo->search();

        $html = view('pages/refunds/components/modals/add-edit-inc', compact('page', 'statuses', 'payment_modes', 'users', 'error_sources', 'sales_sources'))->render();

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
            'selector' => '#refunds-table-body',
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
        $sales_sources = $this->salessourcerepo->search();

        $html = view('pages/refunds/components/modals/add-edit-inc', compact('page', 'refund', 'statuses', 'payment_modes', 'users', 'error_sources', 'sales_sources'))->render();

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
        $validator = Validator::make(request()->all(), [
            'refund_bill_no' => 'required',
            'refund_amount' => 'required|numeric',
            'refund_statusid' => 'required',
            'refund_payment_modeid' => 'required',
        ]);

        if ($validator->fails()) {
            abort(409, __('lang.fill_in_all_required_fields'));
        }

        if (!$this->refundrepo->update($id)) {
            abort(409, __('lang.request_could_not_be_completed'));
        }

        $refunds = $this->refundrepo->search();
        $html = view('pages/refunds/components/table/table', compact('refunds'))->render();

        $jsondata['dom_html'][] = [
            'selector' => '#refunds-table-body',
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
}
