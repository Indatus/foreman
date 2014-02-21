<?php namespace Foreman\Console;

/**
 * This file was copied from and inspired by a similar
 * file in the Laravel / Envoy package which is released
 * under the the MIT license.
 *
 * @see  https://github.com/laravel/envoy
 */

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Construction\Foundation;

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
                ->addArgument('template', InputArgument::REQUIRED, "Path to your Foreman template file")
                ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump intended actions for inspection.');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    protected function fire()
    {
        $dir = $this->argument('dir');
        $cmd = Foundation::getLaravelInstallCommand($dir);
        $foundation = new Foundation($dir, new Process($cmd));
        $foundation->install(function ($type, $buffer) {
            $this->comment("Installing Laravel", $buffer);
        });
    }
}