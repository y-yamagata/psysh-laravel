<?php namespace YYamagata\PsyshLaravel\Presenters;

use Psy\Presenter\ObjectPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class EloquentPresenter extends ObjectPresenter {

    public function canPresent($value)
    {
        return $value instanceof Model || $value instanceof Collection;
    }

    protected function getProperties($value, \ReflectionClass $class)
    {
        return $value->toArray();
    }

}
