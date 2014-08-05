<?php namespace YYamagata\LaravelPsysh;

use Psy\Shell;
use Psy\ExecutionLoop\ForkingLoop;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YYamagata\LaravelPsysh\Contributors\FunctionContributor;

class LaravelShell extends Shell {

    private $complementer;

    public function __construct(LaravelConfiguration $config = null)
    {
        $config = $config ?: new LaravelConfiguration;
        parent::__construct($config);

        $this->complementer = $config->getComplementer();

        foreach ($this->getDefaultContributors() as $contributor) {
            $this->contribute($contributor);
        }
    }

    public function contribute($contributor)
    {
        $this->complementer->add($contributor);
    }

    public function addContributors(array $contributors)
    {
        foreach ($contributors as $contributor) {
            $this->contribute($contributor);
        }
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->complementer->register();

        parent::doRun($input, $output);
    }

    protected function getDefaultContributors()
    {
        return array(
            new FunctionContributor(),
        );
    }

}

