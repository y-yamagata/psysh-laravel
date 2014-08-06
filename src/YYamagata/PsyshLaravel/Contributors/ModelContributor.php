<?php namespace YYamagata\PsyshLaravel\Contributors;

use Illuminate\Filesystem\Filesystem;

class ModelContributor implements ContributorInterface {

    protected $list = array();
    protected $directory;
    protected $fs;

    public function __construct(array $config, Filesystem $fs)
    {
        $this->fs = $fs;
        if ($this->directory = array_get($config, 'model.path')) {
            $recursive = array_get($config, 'model.recursive');
            $this->list = $recursive ? $this->allFiles() : $this->files();
        }
    }

    protected function files()
    {
        $files = $this->fs->files($this->directory);
        return $this->filter($files);
    }

    protected function allFiles()
    {
        $objs = $this->fs->allFiles($this->directory);
        $files = array_map(function($obj) {
            return $obj->getPathname();
        }, $objs);

        return $this->filter($files);
    }

    protected function filter(array $files)
    {
        $files = array_filter($files, function ($file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            return $ext === 'php';
        });

        return array_map(function ($files) {
            return basename($files, '.php');
        }, $files);
    }

    public function getList()
    {
        return $this->list;
    }

}

