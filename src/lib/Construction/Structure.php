<?php namespace Construction;

use Console\BuildCommand;
use Illuminate\Filesystem\Filesystem;
use Support\Path;

class Structure
{
    /**
     * Absolute path to the base application
     * directory
     * 
     * @var string
     */
    protected $appDir;

    /**
     * Multi-Dimension array of config
     * settings
     * 
     * @var array
     */
    protected $config;

    /**
     * Filesystem object to use for executing
     * various filesystem operations
     * 
     * @var Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * BuildCommand object used for writing out
     * status messages
     * 
     * @var Console\BuildCommand
     */
    protected $command;


    /**
     * Constructor to set all the various objects used 
     * in other instance methods
     * 
     * @param string       $appDir
     * @param array        $config
     * @param Filesystem   $fs    
     * @param BuildCommand $cmd   
     */
    public function __construct($appDir, array $config, Filesystem $fs, BuildCommand $cmd)
    {
        $this->appDir = $appDir;
        $this->config = $config;
        $this->filesystem = $fs;
        $this->command = $cmd;
    }


    /**
     * Function to copy files given in the template
     * configuration from the given source to the
     * given destination.
     * 
     * @return void
     */
    public function copy()
    {
        $key = str_replace(TemplateReader::STRUCTURE.'.', '', TemplateReader::STRUCTURE_COPY);

        $data = array_get($this->config, $key);

        foreach ($data as $cp) {

            $from = Path::absolute($cp['from'], $this->appDir);
            $to   = Path::absolute($cp['to'], $this->appDir);

            $this->command->comment("Copy", "from {$from} to {$to}");

            if (! $this->filesystem->exists(dirname($to))) {
                $this->filesystem->makeDirectory(
                    dirname($to),
                    0777,
                    true
                );
            }

            if ($this->filesystem->isDirectory($from) &&
                $this->filesystem->isDirectory($to)
            ) {

                $this->filesystem->copyDirectory($from, $to);

            } else {

                $this->filesystem->copy($from, $to);
            }
        }
    }


    public function move()
    {
        $key = str_replace(TemplateReader::STRUCTURE.'.', '', TemplateReader::STRUCTURE_MOVE);
    }


    public function delete()
    {
        $key = str_replace(TemplateReader::STRUCTURE.'.', '', TemplateReader::STRUCTURE_DEL);
    }


    public function touch()
    {
        $key = str_replace(TemplateReader::STRUCTURE.'.', '', TemplateReader::STRUCTURE_TOUCH);
    }


    public function mkdirs()
    {
        $key = str_replace(TemplateReader::STRUCTURE.'.', '', TemplateReader::STRUCTURE_MKDIR);
    }
}
