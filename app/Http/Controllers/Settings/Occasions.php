<?php

/**
 * --------------------------------------------------------------------------------
 * This controller manages all the business logic for occasions settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 * ----------------------------------------------------------------------------------
 */

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Occasions\CreateResponse;
use App\Http\Responses\Settings\Occasions\DestroyResponse;
use App\Http\Responses\Settings\Occasions\EditResponse;
use App\Http\Responses\Settings\Occasions\IndexResponse;
use App\Http\Responses\Settings\Occasions\StoreResponse;
use App\Http\Responses\Settings\Occasions\UpdateResponse;
use App\Repositories\LeadOccasionsRepository;
use Illuminate\Http\Request;
use Validator;

class Occasions extends Controller
{
    /**
     * The occasions repository instance.
     */
    protected $occasionsrepo;

    public function __construct(LeadOccasionsRepository $occasionsrepo)
    {
        // parent
        parent::__construct();

        // authenticated
        $this->middleware('auth');

        // settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->occasionsrepo = $occasionsrepo;
    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // crumbs, page data & stats
        $page = $this->pageSettings();

        $occasions = $this->occasionsrepo->search();

        // reponse payload
        $payload = [
            'page' => $page,
            'occasions' => $occasions,
        ];

        // show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // page settings
        $page = $this->pageSettings('create');

        // reponse payload
        $payload = [
            'page' => $page,
        ];

        // show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // custom error messages
        $messages = [];

        // validate
        $validator = Validator::make(request()->all(), [
            'leadoccasions_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\LeadOccasion::where('leadoccasions_title', $value)
                            ->exists()) {
                        return $fail(__('lang.occasion_already_exists'));
                    }
                }
            ],
        ], $messages);

        // errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        // create the occasion
        if (!$leadoccasions_id = $this->occasionsrepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        // get the occasion object (friendly for rendering in blade template)
        $occasions = $this->occasionsrepo->search();

        // reponse payload
        $payload = [
            'occasions' => $occasions,
        ];

        // process reponse
        return new StoreResponse($payload);
    }

    /**
     * Show the form for editing the specified resource.
     * @url baseusr/items/1/edit
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // page settings
        $page = $this->pageSettings('edit');

        // client leadoccasions
        $leadoccasions = $this->occasionsrepo->search($id);

        // not found
        if (!$leadoccasion = $leadoccasions->first()) {
            abort(409, __('lang.error_loading_item'));
        }

        // reponse payload
        $payload = [
            'page' => $page,
            'occasion' => $leadoccasion,
        ];

        // response
        return new EditResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // custom error messages
        $messages = [];

        // validate
        $validator = Validator::make(request()->all(), [
            'leadoccasions_title' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (\App\Models\LeadOccasion::where('leadoccasions_title', $value)
                            ->where('leadoccasions_id', '!=', request()->route('occasion'))
                            ->exists()) {
                        return $fail(__('lang.occasion_already_exists'));
                    }
                }
            ],
        ], $messages);

        // errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        // update the resource
        if (!$this->occasionsrepo->update($id)) {
            abort(409);
        }

        // get the category object (friendly for rendering in blade template)
        $occasions = $this->occasionsrepo->search();

        // reponse payload
        $payload = [
            'occasions' => $occasions,
        ];

        // process reponse
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified resource from storage.
     * @url baseusr/occasions/1
     * @param int $id resource id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get the occasion
        if (!$occasions = $this->occasionsrepo->search($id)) {
            abort(409);
        }

        // remove the occasion
        $occasions->first()->delete();

        // reponse payload
        $payload = [
            'occasion_id' => $id,
        ];

        // process reponse
        return new DestroyResponse($payload);
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = [])
    {
        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.leads'),
                __('lang.lead_occasions'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => __('lang.settings'),
            'heading' => __('lang.settings'),
        ];

        // default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.add_lead_occasion'),
            'add_modal_create_url' => url('settings/occasions/create'),
            'add_modal_action_url' => url('settings/occasions'),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        config([
            // visibility - add project buttton
            'visibility.list_page_actions_add_button' => true,
        ]);

        return $page;
    }
}
