<?php


namespace App\Models\Concerns;



use Illuminate\Support\Str;

trait UsesUuid
{

    protected static function bootUsesUuid()
    {
        static::creating(function ($model){
            if(!$model->getKey()){
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    //Disable auto incrementing id column
    public function getIncrementing()
    {
        return false;
    }

    //Set id to be stored as string
    public function getKeyType()
    {
        return 'string';
    }

}
