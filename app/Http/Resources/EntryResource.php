<?php
/**
 * Created by VeHo.
 * Year: 2023-05-25
 */

namespace App\Http\Resources;

class EntryResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
