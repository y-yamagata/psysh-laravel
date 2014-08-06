<?php namespace YYamagata\PsyshLaravel;

use Psy\Shell;
use Psy\ExecutionLoop\ForkingLoop;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Foundation\Application;
use YYamagata\PsyshLaravel\Contributors\FunctionContributor;

class LaravelShell extends Shell {

    private $useForkingLoop;

    private $app;
    private $complementer;

    public function __construct(LaravelConfiguration $config, Application $app)
    {
        parent::__construct($config);

        $this->app = $app;
        $this->complementer = $config->getComplementer();
        $this->useForkingLoop = $config->getLoop() instanceof ForkingLoop;

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

    public function afterLoop()
    {
        parent::afterLoop();

        if ($this->useForkingLoop) {
            $connections = $this->app['db']->getConnections();
            foreach (array_keys($connections) as $name) {
                $this->app['db']->disconnect($name);
            }
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

