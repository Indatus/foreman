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
                ->addArgument('app-name', InputArgument::REQUIRED, "Name of your app")
                ->addArgument('template-file', InputArgument::REQUIRED, "Path to your template file")
                ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump intended actions for inspection.');
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    protected function fire()
    {
        $this->comment('Bang!', 'Nothing to do, please add some code', true);
    }

}
