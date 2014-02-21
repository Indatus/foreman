<?php namespace Construction;

use Symfony\Component\Process\Process;

class Foundation
{

    protected $appDir;

    protected $process;


    public function __construct($appDir, Process $process)
    {
        $this->appDir = $appDir;
        $this->process = $process;
    }


    public static function getLaravelInstallCommand($appDir = '')
    {
        return "composer create-project laravel/laravel {$appDir} --prefer-dist";
    }


    public function install(callable $callback = null)
    {
        $this->process->start($callback);
    }
}
