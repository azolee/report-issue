<?php

namespace App;

use App\Traits\ValidatebleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Report extends Model
{
    use ValidatebleModel;

    protected $fillable = ['title', 'details', 'status', 'phone', 'email', 'user_id', 'data'];
    protected $casts = [
        'data' => 'array'
    ];

    public static function validationRules() {
        return [
            'id' => 'numeric',
            'title' => 'bail|required|max:255',
            'details' => 'required|string',
            'status' => 'in:new,processing,suspended,solved',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'data' => 'array',
            'user_id' => 'integer'
        ];
    }
    public static $statusValues = ['new', 'processing', 'suspended', 'solved'];
    public function photos(){
        return $this->hasMany(Photo::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function scopeForUser($query){
        $request = request();
        if($request->has('user_id')) {
            return $query->where('user_id', $request->user_id);
        }
        return $query;
    }
    public function scopeByStatus($query){
        $request = request();
        if($request->has('status')) {
            if( in_array($request->status, self::$statusValues) ) {
                return $query->where('status', $request->status);
            }
        }
        return $query;
    }
    public function setUserIdAttribute($user_id) {
        if( empty($user_id) && Auth::check() ) {
            $user_id = Auth::id();
        }
        $this->attributes['user_id'] = $user_id;
    }

}
