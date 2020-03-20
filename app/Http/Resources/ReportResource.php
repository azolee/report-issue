<?php

namespace App\Http\Resources;

use App\Traits\RelatableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    use RelatableResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = [
            'id' => $this->id,
            'title' => $this->title,
            'details' => $this->details,
            'status' => $this->status,
        ];
        if( auth()->user()->can('update', $this->resource) ) {
            $return['phone'] = $this->phone;
            $return['email'] = $this->email;
        }
        $this->prefix = 'report';
        $return = $this->relationships(
            $request,
            $return,
            [
                'user'=>[UserResource::class, 'user', 'reports'],
                'photos'=>[PhotoResource::class, 'photos']
            ]
        );
        $return['created'] = $this->created_at->toDateTimeString();
        return $return;
    }
}
