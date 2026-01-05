<?php

/**
 * --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for lead occasions
 *
 * @package    Grow CRM
 * @author     NextLoop
 * ----------------------------------------------------------------------------------
 */

namespace App\Repositories;

use App\Models\LeadOccasion;
use Log;

class LeadOccasionsRepository
{
    /**
     * The lead occasions repository instance.
     */
    protected $occasions;

    /**
     * Inject dependecies
     */
    public function __construct(LeadOccasion $occasions)
    {
        $this->occasions = $occasions;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object lead occasion collection
     */
    public function search($id = '')
    {
        $occasions = $this->occasions->newQuery();

        // joins
        $occasions->leftJoin('users', 'users.id', '=', 'leads_occasions.leadoccasions_creatorid');

        // all client fields
        $occasions->selectRaw('*');

        if (is_numeric($id)) {
            $occasions->where('leadoccasions_id', $id);
        }

        // default sorting
        $occasions->orderBy('leadoccasions_title', 'desc');

        // Get the results and return them.
        return $occasions->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Create a new record
     * @param array $data payload data
     * @return mixed int|bool
     */
    public function create()
    {
        // save new user
        $leadoccasion = new $this->occasions;

        // data
        $leadoccasion->leadoccasions_title = request('leadoccasions_title');
        $leadoccasion->leadoccasions_creatorid = auth()->id();

        // save and return id
        if ($leadoccasion->save()) {
            return $leadoccasion->leadoccasions_id;
        } else {
            Log::error('validation error - invalid params', ['process' => '[LeadOccasionRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id)
    {
        // get the record
        if (!$leadoccasion = $this->occasions->find($id)) {
            return false;
        }

        // general
        $leadoccasion->leadoccasions_title = request('leadoccasions_title');

        // save
        if ($leadoccasion->save()) {
            return $leadoccasion->leadoccasions_id;
        } else {
            Log::error('record could not be updated - database error', ['process' => '[LeadOccasionRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}
