<?php namespace Construction;

use Mockery as m;
use Illuminate\Filesystem\Filesystem as FS;
use Construction\TemplateReader;

class TemplateReaderTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }



    public function testReadTemplate()
    {
        $tpl = '/path/to/template.json';
        $mFS = m::mock('Illuminate\Filesystem\Filesystem');
        $mFS->shouldReceive('get')
            ->once()
            ->with($tpl)
            ->andReturn($this->getTemplateJson());

        $mCmd = m::mock('Console\BuildCommand');
        $mCmd->shouldReceive('comment')
            ->once()
            ->with("Foreman", "Reading template from {$tpl}");

        $reader = new TemplateReader(
            $tpl,
            $mFS,
            $mCmd
        );

        $this->assertEquals(
            json_decode($this->getTemplateJson(), true),
            $reader->getConfig()
        );
    }



    public function testReadTemplateSection()
    {
        $tpl = '/path/to/template.json';
        $mFS = m::mock('Illuminate\Filesystem\Filesystem');
        $mFS->shouldReceive('get')
            ->once()
            ->with($tpl)
            ->andReturn($this->getTemplateJson());

        $mCmd = m::mock('Console\BuildCommand');
        $mCmd->shouldIgnoreMissing();

        $reader = new TemplateReader(
            $tpl,
            $mFS,
            $mCmd
        );

        $result = $reader->getConfigSection(TemplateReader::COMPOSER_REQ);
        $section = json_decode($this->getTemplateJson(), true)['composer']['require'];

        $this->assertEquals(
            $section,
            $result
        );
    }



    private function getTemplateJson()
    {
        $config = [
            'structure' => [
                'copy' => [
                    ['from' => '', 'to' => '']
                ],
                'move' => [
                    ['from' => '', 'to' => '']
                ],
                'delete' => [],
                'touch' => [],
                'mkdir' => [],
            ],
            'composer' => [
                'require' => [
                    ['package' => 'laravel/framework', 'version' => '4.1.*']
                ],
                'require-dev' => [
                    ['package' => '', 'version' => '']
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
                    'psr-0' => [],
                    'psr-4' => []
                ]
            ]
        ];
        return json_encode(
            $config,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
