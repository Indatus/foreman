<?php namespace Console;

/**
 * This file was copied from and inspired by a similar
 * file in the Laravel / Envoy package which is released
 * under the the MIT license.
 *
 * @see  https://github.com/laravel/envoy
 */

use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;
use Construction\Laravel;
use Construction\TemplateReader;
use Construction\Structure;
use Construction\Composer;
use Support\Path;

/**
 * CLI command used to build a new Laravel app
 * and customize it with directives in a provided
 * Foreman template
 */
class BuildCommand extends \Symfony\Component\Console\Command\Command
{

    use Command;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this->setName('build')
                ->setDescription('Commission Forman to build a new Laravel app for you')
                ->addArgument('dir', InputArgument::REQUIRED, "Directory where your app should be installed")
                ->addArgument('template', InputArgument::REQUIRED, "Path to your Foreman template file");
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    protected function fire()
    {

        $appDir = $this->argument('dir');

        //exit is non-absolute path to app directory was given
        if (! preg_match(Path::ABSOLUTE_PATTERN, $appDir)) {
            $this->comment("Error", "App directory must be an absolute path", true);
            exit;
        }


        //install a fresh Laravel app
        (new Laravel(
            $appDir,
            new ProcessBuilder,
            $this
        ))->install();


        //get the template
        $template = new TemplateReader(
            $this->argument('template'),
            new Filesystem,
            $this
        );


        //process structural portions of the config
        $structure = new Structure(
            $appDir,
            $template->getConfigSection(TemplateReader::STRUCTURE),
            new Filesystem,
            $this
        );
        $structure->copy();
        $structure->move();
        $structure->delete();
        $structure->touch();
        $structure->mkdirs();


        //process composer portions of the config
        $composer = new Composer(
            $appDir,
            $template->getConfigSection(TemplateReader::COMPOSER),
            new Filesystem,
            $this
        );
        $composer->requirePackages();
        $composer->requireDevPackages();
        $composer->autoloadClassmap();
        $composer->autoloadPsr0();
        $composer->autoloadPsr4();
        $composer->writeComposerJson();
    }
}
