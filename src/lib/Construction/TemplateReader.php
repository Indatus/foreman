<?php namespace Construction;

use Console\BuildCommand;
use Illuminate\Filesystem\Filesystem;

/**
 * Class for reading a Foreman template and 
 * getting certain bits of data from it
 */
class TemplateReader
{

    /**
     * Constants corresponding to sections of the Foreman
     * Template file
     */
    const STRUCTURE                 = 'structure';
    const STRUCTURE_COPY            = 'structure.copy';
    const STRUCTURE_MOVE            = 'structure.move';
    const STRUCTURE_DEL             = 'structure.delete';
    const STRUCTURE_TOUCH           = 'structure.touch';
    const STRUCTURE_MKDIR           = 'structure.mkdir';
    const COMPOSER                  = 'composer';
    const COMPOSER_REQ              = 'composer.require';
    const COMPOSER_REQ_DEV          = 'composer.require-dev';
    const COMPOSER_AUTOLOAD_CLS_MAP = 'composer.autoload.classmap';
    const COMPOSER_AUTOLOAD_PSR0    = 'composer.autoload.psr-0';
    const COMPOSER_AUTOLOAD_PSR4    = 'composer.autoload.psr-4';

    /**
     * The parsed configuration in 
     * nested associative array format
     * 
     * @var array
     */
    protected $config;

    /**
     * Filesystem object for interacting
     * with the filesystem
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * BuildCommand for rendering messages
     * back to the cli
     * 
     * @var Console\BuildCommand
     */
    protected $command;


    /**
     * Constructor to set member vars, and read in the 
     * given config file as json, then convert it to an
     * associative array
     * 
     * @param string       $tpl
     * @param Filesystem   $fs 
     * @param BuildCommand $cmd
     */
    public function __construct($tpl, Filesystem $fs, BuildCommand $cmd)
    {
        $this->command = $cmd;
        $this->filesystem = $fs;

        $this->command->comment("Foreman", "Reading template from {$tpl}");
        $this->config = json_decode($this->filesystem->get($tpl), true);
    }


    /**
     * Function to return the full
     * parsed config
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }


    /**
     * Function to return a portion of
     * the parsed config
     * 
     * @param  string $section array dot notation of the section
     * @return mixed
     */
    public function getConfigSection($section)
    {
        return array_get($this->getConfig(), $section);
    }
}
