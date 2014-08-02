<?php namespace YYamagata\LaravelPsysh\Contributors;

class FunctionContributor implements ContributorInterface {

    protected $list = array();

    public function __construct()
    {
        foreach (get_loaded_extensions() as $module) {
            $funcs = get_extension_funcs($module);
            if (!$funcs) {
                continue;
            }
            foreach ($funcs as $f) {
                $this->list[] = $f;
            }
        }
    }

    public function getList()
    {
        return $this->list;
    }

}

