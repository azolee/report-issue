<?php

namespace App\Http\Resources;

use App\Traits\RelatableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'level' => $this->level,
            'created' => $this->created_at->toDateTimeString(),
        ];
        $this->prefix = 'user';
        $return = $this->relationships(
            $request,
            $return,
            [
                'reports'=>[ReportResource::class, 'reports', 'user'],
            ]
        );

        return $return;
    }
}
