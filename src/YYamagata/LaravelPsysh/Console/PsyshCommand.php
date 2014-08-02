<?php namespace YYamagata\LaravelPsysh\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use YYamagata\LaravelPsysh\LaravelShell;
use YYamagata\LaravelPsysh\LaravelConfiguration;
use YYamagata\LaravelPsysh\Presenters\EloquentPresenter;
use YYamagata\LaravelPsysh\Contributors\EloquentContributor;
use YYamagata\LaravelPsysh\Contributors\CommandContributor;
use YYamagata\LaravelPsysh\Contributors\ModelContributor;

class PsyshCommand extends Command {

    /**
     * Available commands.
     *
     * @var array
     */
    private static $AVAILABLE_COMMANDS = array(
        'clear-compiled', 'down', 'dump-autoload', 'env', 'optimize',
        'routes', 'up', 'workbench', 'asset:publish', 'auth:clear-reminders',
        'auth:reminders-controller', 'auth:reminders-table', 'cache:clear',
        'command:make', 'config:publish', 'controller:make', 'key:generate',
        'migrate:make', 'migrate:publish', 'session:table', 'view:publish',
    );

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'psysh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'lunch psysh.';

    /**
     * Shell.
     *
     * @var YYamagata\LaravelPsysh\LaravelShell
     */
    protected $shell;

    /**
     * Laravel application.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Config.
     *
     * @var array
     */
    protected $config = array();

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($app)
	{
		parent::__construct();

        $this->app = $app;
        $this->config = $app['config']['laravel-psysh::config'];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        restore_error_handler();
        restore_exception_handler();
        $this->app->error(function() { return ''; });

        if ($configFile = $this->option('config')) {
            array_set($this->config, 'configuration.configFile', $configFile);
        }

        $configuration = new LaravelConfiguration($this->config['configuration']);
        $configuration->addPresenters($this->presenters());

        $this->shell = new LaravelShell($configuration);
        $this->shell->setIncludes($this->argument('include'));
        $this->shell->addCommands($this->commands());
        $this->shell->addContributors($this->contributors());
        $this->shell->run();
	}

    /**
     * @return array
     */
    protected function presenters()
    {
        return array(
            new EloquentPresenter,
        );
    }

    /**
     * @return array
     */
    protected function commands()
    {
        $commands = array();
        foreach ($this->app['artisan']->all() as $command) {
            if (in_array($command->getName(), static::$AVAILABLE_COMMANDS)) {
                $commands[] = $command;
            }
        }

        return $commands;
    }

    /**
     * @return array
     */
    protected function contributors()
    {
        return array(
            new EloquentContributor,
            new CommandContributor($this->shell),
            new ModelContributor($this->config['contributor'], $this->app['files']),
        );
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('include', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Include.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('config', 'c', InputOption::VALUE_REQUIRED, 'Use an alternate PsySH config file location.', null),
		);
	}

}
