<?php namespace YYamagata\PsyshLaravel;

use Psy\Shell;
use Psy\Configuration;
use Psy\ExecutionLoop\ForkingLoop;
use YYamagata\PsyshLaravel\Complementers\ComplementerInterface;
use YYamagata\PsyshLaravel\Complementers\Complementer;
use YYamagata\PsyshLaravel\Complementers\DummyComplementer;
use YYamagata\PsyshLaravel\ExecutionLoop\LaravelLoop;

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
