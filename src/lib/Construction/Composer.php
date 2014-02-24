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


    /**
     * Function to parse the stored composer directives array
     * into JSON
     * 
     * @return string
     */
    public function getComposerJson()
    {
        return json_encode($this->composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }


    /**
     * Function to write out the built up composer.json
     * file based on the directives parsed from the Foreman
     * template file
     * 
     * @return void
     */
    public function writeComposerJson()
    {
        $composerJson = Path::absolute('composer.json', $this->appDir);

        $this->command->comment('Composer', "Writing composer file to {$composerJson}");

        $this->filesystem->put($composerJson, $this->getComposerJson());
    }


    /**
     * Function to process the Foreman template section related
     * to 'require' and make the appropriate updates to the composer
     * directives held in memory.
     * 
     * @return void
     */
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

        array_set($this->composer, static::REQUIRE_DEPENDENCIES, $require);
    }


    /**
     * Function to process the Foreman template section related
     * to 'require-dev' and make the appropriate updates to the composer
     * directives held in memory.
     * 
     * @return void
     */
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

        //if the require-dev key exists already just write to it
        if (array_key_exists(static::REQUIRE_DEV_DEPENDENCIES, $this->composer)) {

            array_set($this->composer, static::REQUIRE_DEV_DEPENDENCIES, $require_dev);

        //if not we'll add it but to make it clean we'll add it right behind the require key
        } else {

            $offset = array_search(static::REQUIRE_DEPENDENCIES, array_keys($this->composer)) + 1;

            $require_dev_block = [static::REQUIRE_DEV_DEPENDENCIES => $require_dev];

            $this->composer = array_slice($this->composer, 0, $offset, true)
                + $require_dev_block
                + array_slice($this->composer, $offset, null, true);
        }
    }


    /**
     * Function to process the Foreman template section related
     * to 'autoload > classmap' and make the appropriate updates to the composer
     * directives held in memory.
     * 
     * @return void
     */
    public function autoloadClassmap()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_AUTOLOAD_CLS_MAP);

        $data = array_get($this->config, $key);

        foreach ($data as $entry) {
            $this->command->comment('Composer', "Autoload Classmap adding: {$entry}");
        }

        array_set($this->composer, static::AUTOLOAD_CLASSMAP, $data);
    }


    /**
     * Function to process the Foreman template section related
     * to 'autoload > psr-0' and make the appropriate updates to the composer
     * directives held in memory.
     * 
     * @return void
     */
    public function autoloadPsr0()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_AUTOLOAD_PSR0);

        $data = array_get($this->config, $key);

        $psr0 = [];

        foreach ($data as $name => $value) {

            $this->command->comment("Composer", "Adding PSR0 entry {$name} => {$value}");

            $psr0[$name] = $value;
        }

        array_set($this->composer, static::AUTOLOAD_PSR0, $psr0);
    }


    /**
     * Function to process the Foreman template section related
     * to 'autoload > psr-4' and make the appropriate updates to the composer
     * directives held in memory.
     * 
     * @return void
     */
    public function autoloadPsr4()
    {
        $key = str_replace(TemplateReader::COMPOSER.'.', '', TemplateReader::COMPOSER_AUTOLOAD_PSR4);

        $data = array_get($this->config, $key);

        $psr4 = [];

        foreach ($data as $name => $value) {

            $this->command->comment("Composer", "Adding PSR4 entry {$name} => {$value}");

            $psr4[$name] = $value;
        }

        array_set($this->composer, static::AUTOLOAD_PSR4, $psr4);
    }
}
