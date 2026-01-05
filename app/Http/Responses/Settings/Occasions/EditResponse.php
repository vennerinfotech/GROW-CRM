<?php

namespace App\Http\Responses\Settings\Occasions;

use Illuminate\Contracts\Support\Responsable;

class EditResponse implements Responsable
{
    protected $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        // set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        // render the form
        $html = view('pages.settings.sections.occasions.modals.add-edit-inc', compact('page', 'occasion'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        );

        // show modal footer
        $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        // POSTRUN FUNCTIONS------
        $jsondata['postrun_functions'][] = [
            'value' => 'NXSettingsOccasionsCreate',
        ];

        // ajax response
        return response()->json($jsondata);
    }
}
