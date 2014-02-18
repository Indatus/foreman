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

class RunCommand extends \Symfony\Component\Console\Command\Command
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

        $this->setName('build-cli')
                ->setDescription('Commission Forman to build a new CLI package')
                ->addArgument('name', InputArgument::REQUIRED)
                ->addOption('pretend', null, InputOption::VALUE_NONE, 'Dump Bash script for inspection.');
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

    

    /**
     * Deteremine if pretending and output should be dumped.
     *
     * @return bool
     */
    protected function pretending()
    {
        return $this->input->getOption('pretend');
    }
}
