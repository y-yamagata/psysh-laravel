<?php namespace YYamagata\PsyshLaravel\Contributors;

use Symfony\Component\Console\Application;

class CommandContributor implements ContributorInterface {

    protected $shell;

    public function __construct(Application $shell)
    {
        $this->shell = $shell;
    }

    public function getList()
    {
        $list = array();
        foreach ($this->shell->all() as $command) {
            $list[] = $command->getName();
        }

        return $list;
    }

}

