<?php namespace Construction;

use Mockery as m;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Construction\Laravel;

class LaravelTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }


    public function testInstallLaravel()
    {
        $appDir = '/path/to/install/dir';

        //mock the process
        $mProcess = m::mock('Symfony\Component\Process\Process');
        $mProcess->shouldIgnoreMissing();
        $mProcess->shouldReceive('run')->once();

        //mock the builder to create the process
        $mBuilder = m::mock('Symfony\Component\Process\ProcessBuilder');
        $mBuilder->shouldIgnoreMissing();
        $mBuilder->shouldReceive('setPrefix')
            ->once()
            ->with('composer')
            ->shouldReceive('setArguments')
            ->once()
            ->with(
                [
                    'create-project',
                    'laravel/laravel',
                    $appDir,
                    '--prefer-dist'
                ]
            )
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($mProcess);

        $mCmd = m::mock('Console\BuildCommand');
        $mCmd->shouldReceive('comment')
            ->with("Foreman", "Installing fresh Laravel app")
            ->shouldReceive('comment')
            ->with("Foreman", "Done, Laravel installed at: {$appDir}");

        //test the class and inject the mocks
        $installedTo = (new Laravel($appDir, $mBuilder, $mCmd))->install();

        $this->assertEquals($appDir, $installedTo);
    }
}
