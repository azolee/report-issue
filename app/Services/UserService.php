<?php
/**
 * Created by PhpStorm.
 * User: Zoli
 * Date: 28/11/2019
 * Time: 15:06
 */

namespace App\Services;

use App\User;
use Illuminate\Http\Request;

class UserService
{
    public function create(Request $request) {
        return User::create( $request->only(['name', 'password', 'email', 'level']) );
    }
    public function update(Request $request, User $user) {
        return $user->update( $request->only(['name', 'password', 'email', 'level']) );
    }
    public function delete(User $user){
        return $user->delete();
    }
}