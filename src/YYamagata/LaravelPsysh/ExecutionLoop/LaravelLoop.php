<?php namespace YYamagata\LaravelPsysh\ExecutionLoop;

use Psy\ExecutionLoop\ForkingLoop;

class LaravelLoop extends ForkingLoop {

    private $shell;

    public function run(Shell $shell)
    {
        $this->shell = $shell;
        parent::run($shell);
    }

    public function beforeLoop()
    {
        parent::beforeLoop();
        register_shutdown_function(array($this->shell, 'handleShutdown'));
    }

}

