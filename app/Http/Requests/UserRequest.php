<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        $user = Auth::user();
        $request = app('request');

        if(!$request->has('id')) {
            return true;
        }

        if($user->isAdmin()) {
            return true;
        }

        if($user->id == $request->id) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $except = "";
        $request = request();
        if($request->has('id')){
            $except = $request->id;
        }
        return User::getValidationRules($except);
    }
}
