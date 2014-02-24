<?php namespace Construction;

use Construction\Composer;
use Mockery as m;

class ComposerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }



    public function testReadComposerJson()
    {
        $appDir = '/path/to/app';
        $composerPath = $appDir.DIRECTORY_SEPARATOR.'composer.json';

        $mFS = m::mock('Illuminate\Filesystem\Filesystem');
        $mFS->shouldReceive('get')
            ->once()
            ->with($composerPath)
            ->andReturn($this->getComposerJson());

        $mCmd = m::mock('Console\BuildCommand');
        $mCmd->shouldReceive('comment')
            ->once()
            ->with("Foreman", "Reading composer.json from {$composerPath}");

        $composer = new Composer(
            $appDir,
            $mFS,
            $mCmd
        );

        $this->assertEquals(
            json_decode($this->getComposerJson(), true),
            $composer->getComposerArray()
        );
    }


    private function getComposerJson()
    {
        return $json = <<<JSON
{
    "name": "laravel/laravel",
    "description": "The Laravel Framework.",
    "keywords": ["framework", "laravel"],
    "license": "MIT",
    "require": {
        "laravel/framework": "4.1.*"
    },
    "autoload": {
        "classmap": [
            "app/commands",
            "app/controllers",
            "app/models",
            "app/database/migrations",
            "app/database/seeds",
            "app/tests/TestCase.php"
        ]
    },
    "scripts": {
        "post-install-cmd": [
            "php artisan clear-compiled",
            "php artisan optimize"
        ],
        "post-update-cmd": [
            "php artisan clear-compiled",
            "php artisan optimize"
        ],
        "post-create-project-cmd": [
            "php artisan key:generate"
        ]
    },
    "config": {
        "preferred-install": "dist"
    },
    "minimum-stability": "stable"
}
JSON;
    }
}
