<?php

namespace App\Http\Responses\Settings\Occasions;

use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable
{
    protected $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view for occasions
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

        $html = view('pages.settings.sections.occasions.page', compact('page', 'occasions'))->render();

        $jsondata['dom_html'][] = array(
            'selector' => '#settings-wrapper',
            'action' => 'replace',
            'value' => $html
        );

        // POSTRUN FUNCTIONS------
        $jsondata['postrun_functions'][] = [
            'value' => 'NXSettingsOccasions',
        ];

        // left menu activate
        if (request('url_type') == 'dynamic') {
            $jsondata['dom_attributes'][] = [
                'selector' => '#settings-menu-leads',
                'attr' => 'aria-expanded',
                'value' => false,
            ];
            $jsondata['dom_action'][] = [
                'selector' => '#settings-menu-leads',
                'action' => 'trigger',
                'value' => 'click',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#settings-menu-leads-occasions',
                'action' => 'add',
                'value' => 'active',
            ];
        }

        // ajax response
        return response()->json($jsondata);
    }
}
