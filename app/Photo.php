<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['url', 'data', 'report_id'];
    protected $casts = [
        'data' => 'array'
    ];

    public static function validationRules() {
        return [
            'id' => 'numeric',
            'url' => 'bail|required|max:255',
            'data' => 'array',
            'report_id' => 'required|integer'
        ];
    }

    public function report(){
        return $this->belongsTo(Report::class);
    }
}
