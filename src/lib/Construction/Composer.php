<?php namespace Construction;

use Illuminate\Filesystem\Filesystem;
use Console\BuildCommand;
use Support\Path;

/**
 * Class for interacting with / adding to the composer.json
 * file of a Laravel application.  Requirements / actions will
 * come from the Foreman template file.
 */
class Composer
{

    /**
     * Filesystem object for interacting with the
     * underlying filesystem
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Command object used to send notifications back to 
     * the user via the CLI
     * 
     * @var Console\BuildCommand
     */
    protected $command;

    /**
     * Absolute path to the Laravel application
     * installation directory
     * 
     * @var string
     */
    protected $appDir;

    /**
     * Var to hold a multi-dimensional associative
     * array representing the composer.json configuration
     * directives
     * 
     * @var array
     */
    protected $composer;


    /**
     * Constructor to set our various class variables
     * and read in the composer.json file and convert to a 
     * multi-dimensional array for later operation
     * 
     * @param string       $appDir
     * @param Filesystem   $fs    
     * @param BuildCommand $cmd   
     */
    public function __construct($appDir, Filesystem $fs, BuildCommand $cmd)
    {
        $this->appDir = $appDir;
        $this->filesystem = $fs;
        $this->command = $cmd;

        $composerJson = Path::absolute('composer.json', $this->appDir);

        $this->command->comment("Foreman", "Reading composer.json from {$composerJson}");

        $this->composer = json_decode($this->filesystem->get($composerJson), true);
    }


    /**
     * Function to return the composer configuration
     * as multi-dimensional associative array
     * 
     * @return array
     */
    public function getComposerArray()
    {
        return $this->composer;
    }
}
