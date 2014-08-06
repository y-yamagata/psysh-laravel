<?php namespace YYamagata\PsyshLaravel\Contributors;

class EloquentContributor implements ContributorInterface {

    protected $list = array();

    public function __construct()
    {
        $this->list = array_merge(
            $this->getModelMethods(),
            $this->getBuilderMethods(),
            $this->getQueryMethods()
        );
    }

    protected function getModelMethods()
    {
        return $this->getMethods('Illuminate\\Database\\Eloquent\\Model');
    }

    protected function getBuilderMethods()
    {
        return $this->getMethods('Illuminate\\Database\\Eloquent\\Builder');
    }

    protected function getQueryMethods()
    {
        return $this->getMethods('Illuminate\\Database\\Query\\Builder');
    }

    protected function getMethods($className)
    {
        $reflector = new \ReflectionClass($className);
        $methods = $reflector->getMethods(\ReflectionMethod::IS_PUBLIC);
        return array_map(function ($method) {
            return $method->name;
        }, $methods);
    }

    public function getList()
    {
        return $this->list;
    }

}

