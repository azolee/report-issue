<?php

namespace App;

use App\Traits\ValidatebleModel;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, ValidatebleModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    public const LEVEL_USER = "user";
    public const LEVEL_MAINTAINER = "maintainer";
    public const LEVEL_ADMIN = "admin";
    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=200&d=mm';
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Get the user's reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return void
     */
    public function setLevelAttribute($value)
    {
        if( !$value ) {
            $value = self::LEVEL_USER;
        }
        $this->attributes['level'] = $value;
    }

    /**
     *
     * @return boolean
     */
    protected function isLevel( $level = self::LEVEL_USER) {
        return $this->level === $level;
    }

    /**
     *
     * @return boolean
     *
     */
    public function isUser(){
        return $this->isLevel();
    }

    /**
     *
     * @return boolean
     *
     */
    public function isMaintainer(){
        return $this->isLevel(self::LEVEL_MAINTAINER);
    }

    /**
     *
     * @return boolean
     *
     */
    public function isAdmin(){
        return $this->isLevel(self::LEVEL_ADMIN);
    }

    public static function getValidationRules($except = "") {
        return static::validationRules($except);
    }
    public static function validationRules($except = "") {
        if( $except!="" ){
            $except = ",".$except;
        }
        return [
            'id' => 'numeric|exists:users',
            'email' => 'bail|required|email|unique:users,email'.$except.'|max:255',
            'name' => 'required|min:5|max:255',
            'level' => 'in:user,maintainer,admin',
            'password' => 'required_if:id,|string|min:5|max:255',
            'password_again' => 'same:password',
        ];
    }
}
