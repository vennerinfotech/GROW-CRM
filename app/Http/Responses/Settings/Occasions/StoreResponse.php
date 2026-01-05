<?php

namespace App\Http\Responses\Settings\Occasions;

use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable
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

        // prepend content on top of list or show full table
        $html = view('pages.settings.sections.occasions.table.table', compact('occasions'))->render();
        $dom_html[] = [
            'selector' => '#occasions-table-wrapper',
            'action' => 'replace',
            'value' => $html,
        ];

        // close modal
        $dom_visibility[] = [
            'selector' => '#commonModal',
            'action' => 'close-modal',
        ];

        // notice
        $notification = [
            'type' => 'success',
            'value' => __('lang.request_has_been_completed'),
        ];

        // return
        return response()->json([
            'dom_html' => $dom_html,
            'dom_visibility' => $dom_visibility,
            'notification' => $notification,
        ]);
    }
}
