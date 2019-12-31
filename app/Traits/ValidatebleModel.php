<?php
/**
 * Created by PhpStorm.
 * User: Zoli
 * Date: 27/11/2019
 * Time: 15:40
 */

namespace App\Traits;


trait ValidatebleModel
{
    abstract protected static function validationRules();

    public static function getValidationRules(){
        return static::validationRules();
    }
}