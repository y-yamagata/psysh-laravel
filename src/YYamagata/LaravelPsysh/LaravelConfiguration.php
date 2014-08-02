<?php namespace YYamagata\LaravelPsysh;

use Psy\Shell;
use Psy\Configuration;
use Psy\ExecutionLoop\ForkingLoop;
use YYamagata\LaravelPsysh\Complementers\ComplementerInterface;
use YYamagata\LaravelPsysh\Complementers\Complementer;
use YYamagata\LaravelPsysh\Complementers\DummyComplementer;
use YYamagata\LaravelPsysh\ExecutionLoop\LaravelLoop;

class LaravelConfiguration extends Configuration {

    private $complementer;
    private $newContributors = array();

    public function loadConfig(array $options)
    {
        parent::loadConfig($options);

        foreach (array('complementer') as $option) {
            if (isset($options[$option])) {
                $method = 'set'.ucfirst($option);
                $this->$method($options[$option]);
            }
        }

        foreach (array('contributors') as $option) {
            if (isset($options[$option])) {
                $method = 'add'.ucfirst($option);
                $this->$method($options[$option]);
            }
        }
    }

    public function addContributors(array $contributors)
    {
        $this->newContributors = array_merge($this->newContributors, $contributors);
        if (isset($this->shell)) {
            $this->doAddContributors();
        }
    }

    public function doAddContributors()
    {
        if (!empty($this->newContributors)) {
            $this->shell->addContributors($this->newContributors);
            $this->newContributors = array();
        }
    }

    public function setShell(Shell $shell)
    {
        parent::setShell($shell);

        $this->doAddContributors();
    }

    public function getLoop()
    {
        $loop = parent::getLoop();
        if ($loop instanceof ForkingLoop) {
            $loop = new LaravelLoop($this);
            $this->setLoop($loop);
        }

        return $loop;
    }

    public function setComplementer($complementer)
    {
        if ($complementer && !$complementer instanceof ComplementerInterface) {
            throw new \InvalidArgumentException('Unexpected complementer instance.');
        }

        $this->complementer = $complementer;
    }

    public function getComplementer()
    {
        if (!isset($this->complementer)) {
            $this->complementer = $this->useReadline() ? new Complementer() : new DummyComplementer();
        }

        return $this->complementer;
    }

}
