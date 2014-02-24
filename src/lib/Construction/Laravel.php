<?php namespace Construction;

use Console\BuildCommand;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class used for generating a Laravel installation
 */
class Laravel
{

    /**
     * App directory where Laravel
     * should be installed
     * 
     * @var string
     */
    protected $appDir;

    /**
     * ProcessBuilder used to run the
     * shell command to create the laravel
     * install
     * 
     * @var \Symfony\Component\Process\ProcessBuilder
     */
    protected $builder;

    /**
     * BuildCommand that called Laravel and 
     * requested install
     * 
     * @var BuildCommand
     */
    protected $command;


    /**
     * Constructor to set the objects we'll be using
     * to install Laravel with
     * 
     * @param string         $appDir 
     * @param ProcessBuilder $builder
     */
    public function __construct($appDir, ProcessBuilder $builder, BuildCommand $cmd)
    {
        $this->appDir = $appDir;
        $this->builder = $builder;
        $this->command = $cmd;
    }


    /**
     * Function to install Laravel
     * 
     * @return string directory where app was installed
     */
    public function install()
    {

        $this->command->comment("Foreman", "Installing fresh Laravel app");

        $this->builder->setPrefix('composer');
        $this->builder->setArguments([
            'create-project',
            'laravel/laravel',
            $this->appDir,
            '--prefer-dist'
        ]);

        $this->builder->getProcess()->run();

        $this->command->comment("Foreman", "Done, Laravel installed at: {$this->appDir}");

        return $this->appDir;
    }
}
