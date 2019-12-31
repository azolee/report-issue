<?php
/**
 * Created by PhpStorm.
 * User: Zoli
 * Date: 06/05/2019
 * Time: 18:19
 */

namespace App\Traits;
use Illuminate\Database\Eloquent\Collection;

trait RelatableResource
{
    protected $prefix = null;

    public function relationships($request, $return, $relationships){
        $excludes = [];
        if($request->has('exclude')){
            $excludes = explode(',', $request->exclude);
        }
        if($request->has('with')){
            if(!$this->prefix) {
                $class_name =  join('', array_slice(explode('\\', __CLASS__), -1));
                $this->prefix = strtolower( $class_name );
            }
            $this->prefix .= ".";
            $with = explode(',', $request->with);
            foreach($relationships as $key => $relationship){
                if(in_array($this->prefix.$key, $with) && !in_array($this->prefix.$key, $excludes)){
                    if(isset($relationship[2])){
                        $request->request->add(['exclude' => $relationship[2]]);
                    }
                    $modelRelationship = $this->{$relationship[1]};
                    if( $modelRelationship instanceof Collection) {
                        $return[$key] = $relationship[0]::collection($modelRelationship);
                    } else {
                        $return[$key] = new $relationship[0]($modelRelationship);
                    }
                }
            }
        }
        return $return;
    }

}