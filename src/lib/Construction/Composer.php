<?php namespace Construction;

use Illuminate\Filesystem\Filesystem;
use Console\BuildCommand;
use Support\Path;
use Construction\TemplateReader;

/**
 * Class for interacting with / adding to the composer.json
 * file of a Laravel application.  Requirements / actions will
 * come from the Foreman template file.
 */
class Composer
{

    /**
     * Constants corresponding to sections of the composer.json
     * config file
     */
    const NAME                        = 'name';
    const DESCRIPTION                 = 'description';
    const KEYWORDS                    = 'keywords';
    const LICENSE                     = 'license';
    const REQUIRE_DEPENDENCIES        = 'require';
    const REQUIRE_DEV_DEPENDENCIES    = 'require-dev';
    const AUTOLOAD_CLASSMAP           = 'autoload.classmap';
    const AUTOLOAD_PSR0               = 'autoload.psr-0';
    const AUTOLOAD_PSR4               = 'autoload.psr-4';
    const SCRIPTS_POST_INSTALL        = 'scripts.post-install-cmd';
    const SCRIPTS_POST_UPDATE         = 'scripts.post-update-cmd';
    const SCRIPTS_POST_CREATE_PROJECT = 'scripts.post-create-project-cmd';
    const CONFIG                      = 'config';
    const MINIMUM_STABILITY           = 'minimum-stability';

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
     * Multi-Dimension array of config
     * settings from the Foreman template
     * 
     * @var array
     */
    protected $config;

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
     * @param string       $appDir absolute path to application
     * @param array        $config Foreman template config
     * @param Filesystem   $fs     Filesystem object
     * @param BuildCommand $cmd    BuildCommand object
     */
    public function __construct($appDir, array $config, Filesystem $fs, BuildCommand $cmd)
    {
        $this->appDir = $appDir;
        $this->config = $config;
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


    // public function getComposerJson()
    // {
    //     return json_encode($this->composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    // }


    // public function writeComposerJson()
    // {
    //     (new Filesystem)->put('/Users/bwebb/Desktop/composer.json', $this->getComposerJson());
    // }


    public function requirePackages()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_REQ);

        $data = array_get($this->config, $key);
        $require = [];

        foreach ($data as $package) {
            $pkg = $package['package'];
            $ver = $package['version'];

            $this->command->comment("Composer", "Require: {$pkg} {$ver}");

            $require[$pkg] = $ver;
        }

        $this->composer[static::REQUIRE_DEPENDENCIES] = $require;
    }


    public function requireDevPackages()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_REQ_DEV);

        $data = array_get($this->config, $key);

        $require_dev = [];

        foreach ($data as $package) {
            $pkg = $package['package'];
            $ver = $package['version'];

            $this->command->comment("Composer", "Require Dev: {$pkg} {$ver}");

            $require_dev[$pkg] = $ver;
        }

        $this->composer[static::REQUIRE_DEV_DEPENDENCIES] = $require_dev;
    }


    public function autoloadClassmap()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_AUTOLOAD_CLS_MAP);

        $data = array_get($this->config, $key);
    }


    public function autoloadPsr0()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_AUTOLOAD_PSR0);

        $data = array_get($this->config, $key);
    }


    public function autoloadPsr4()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_AUTOLOAD_PSR4);

        $data = array_get($this->config, $key);
    }
}
