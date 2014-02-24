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
            $this->getConfig(),
            $mFS,
            $mCmd
        );

        $this->assertEquals(
            json_decode($this->getComposerJson(), true),
            $composer->getComposerArray()
        );
    }



    public function testRequirePackages()
    {
        $appDir = '/path/to/app';
        $composerPath = $appDir.DIRECTORY_SEPARATOR.'composer.json';
        $require = $this->getConfig()['require'];

        $mFS = m::mock('Illuminate\Filesystem\Filesystem');
        $mFS->shouldReceive('get')
            ->once()
            ->with($composerPath)
            ->andReturn($this->getComposerJson());
   
        $mCmd = m::mock('Console\BuildCommand');
        $mCmd->shouldIgnoreMissing();

        foreach ($require as $package) {

            $pkg = $package['package'];
            $ver = $package['version'];

            $mCmd->shouldReceive('comment')
                ->once()
                ->with("Composer", "Require {$pkg} {$ver}");
        }
        

        $composer = new Composer(
            $appDir,
            $this->getConfig(),
            $mFS,
            $mCmd
        );
        $composer->requirePackages();

        $expectedPackages = [
            'laravel/framework'  => '4.1.*',
            'nesbot/Carbon'      => '*',
            'doctrine/inflector' => '1.0.*@dev'
        ];

        $this->assertEquals(
            $expectedPackages,
            $composer->getComposerArray()[Composer::REQUIRE_DEPENDENCIES]
        );

    }


    private function getConfig()
    {
        return [
            'require' => [
                ['package' => 'laravel/framework', 'version' => '4.1.*'],
                ['package' => 'nesbot/Carbon', 'version' => '*'],
                ['package' => 'doctrine/inflector', 'version' => '1.0.*@dev'],
            ],
            'require-dev' => [
                ['package' => 'mockery/mockery', 'version' => 'dev-master@dev'],
                ['package' => 'fzaninotto/faker', 'version' => '1.3.*'],
                ['package' => 'squizlabs/php_codesniffer', 'version' => '*'],
            ],
            'autoload' => [
                'classmap' => [
                    'app/commands',
                    'app/controllers',
                    'app/models',
                    'app/database/migrations',
                    'app/database/seeds',
                    'app/test/TestCase.php'
                ],
                'psr-0' => [
                    "Acme" => "app/lib"
                ],
                'psr-4' => []
            ]
        ];
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
