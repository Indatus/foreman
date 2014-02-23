<?php namespace Scaffolding;

use Mockery as m;
use Illuminate\Filesystem\Filesystem as FS;

class TemplateWriterTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }


    public function testPathGetter()
    {
        $path = '/path/to/file';
        $tw = new TemplateWriter($path, new FS);
        $this->assertEquals(
            $path,
            $tw->getPath()
        );

        $path = '/path/to/file/';
        $tw = new TemplateWriter($path, new FS);
        $this->assertEquals(
            '/path/to/file',
            $tw->getPath()
        );
    }


    public function testGetStructure()
    {
        $tw = new TemplateWriter('/some/path', new FS);
        $this->assertEquals(
            $this->getStructureArray(),
            $tw->getStructureArray()
        );
    }


    public function testGetStructureJson()
    {
        $tw = new TemplateWriter('/some/path', new FS);
        $this->assertJsonStringEqualsJsonString(
            json_encode($this->getStructureArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            $tw->getStructureJson()
        );
    }


    public function testWriteOutTemplateFileGivenADirectory()
    {
        $path = '/some/path';
        $file = '/some/path/template.json';

        $fsMock = m::mock('Illuminate\Filesystem\Filesystem');

        //call isDir on a dir
        $fsMock->shouldReceive('isDirectory')
            ->once()
            ->with($path)
            ->andReturn(true);

        //should write to default file at given path
        $fsMock->shouldReceive('put')
            ->once()
            ->with(
                $path.'/foreman-tpl.json',
                json_encode($this->getStructureArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

        //call isDir on a file
        $fsMock->shouldReceive('isDirectory')
            ->once()
            ->with($file)
            ->andReturn(false);

        //should write to given file
        $fsMock->shouldReceive('put')
            ->once()
            ->with(
                $file,
                json_encode($this->getStructureArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

        $tw = new TemplateWriter($path, $fsMock);
        $pathWritten = $tw->write();
        $this->assertEquals(
            $path.'/foreman-tpl.json',
            $pathWritten
        );

        $tw = new TemplateWriter($file, $fsMock);
        $fileWritten = $tw->write();
        $this->assertEquals(
            $file,
            $fileWritten
        );
    }


    /**
     * Test helper to get the assumed / default
     * structure for a template
     * 
     * @return array
     */
    private function getStructureArray()
    {
        return [
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
    }
}
