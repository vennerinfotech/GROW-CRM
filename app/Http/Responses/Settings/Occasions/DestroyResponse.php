<?php

namespace App\Http\Responses\Settings\Occasions;

use Illuminate\Contracts\Support\Responsable;

class DestroyResponse implements Responsable
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

        // hide the row
        $dom_visibility[] = [
            'selector' => '#occasion_' . $occasion_id,
            'action' => 'slideup-slow-remove',
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
            'dom_visibility' => $dom_visibility,
            'notification' => $notification,
        ]);
    }
}
