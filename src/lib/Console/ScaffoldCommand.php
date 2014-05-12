<?php namespace Console;

/**
 * This file was copied from and inspired by a similar
 * file in the Laravel / Envoy package which is released
 * under the the MIT license.
 *
 * @see  https://github.com/laravel/envoy
 */

use Symfony\Component\Console\Input\InputArgument;
use Scaffolding\TemplateWriter;
use Illuminate\Filesystem\Filesystem;

/**
 * CLI command used for generating a scaffolded
 * Foreman template
 */
class ScaffoldCommand extends \Symfony\Component\Console\Command\Command
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

        $this->setName('scaffold')
                ->setDescription('Generate a scaffolded Foreman template file')
                ->addArgument('file', InputArgument::REQUIRED, "Location to write the scaffold file");
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    protected function fire()
    {
        $file = $this->argument('file');
        $tw = new TemplateWriter($file, new Filesystem);
        $output = $tw->write();

        $this->comment("Success", "Template written to {$output}");
    }
}
