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
        $this->comment('Bang!', 'Nothing to do, please add some code', true);
    }
}
