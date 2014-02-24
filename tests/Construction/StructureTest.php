<?php namespace Construction;

use Mockery as m;
use Illuminate\Filesystem\Filesystem as FS;
use Construction\Structure;
use Support\Path;

class StructureTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }


    public function testCopy()
    {

        $dir = '/path/to/app';

        $mFS = m::mock('Illuminate\Filesystem\Filesystem');
        $mCmd = m::mock('Console\BuildCommand');

        $config = $this->getConfig()['copy'];

        for ($i=0; $i < count($config); $i++) {

            $from = Path::absolute($config[$i]['from'], $dir);
            $to = Path::absolute($config[$i]['to'], $dir);

            $mCmd->shouldReceive('comment')->once()
                ->with("Copy", "from {$from} to {$to}");

            $mFS->shouldReceive('exists')->once()
                ->with(dirname($to))
                ->andReturn(false);

            $mFS->shouldReceive('makeDirectory')->once()
                ->with(dirname($to), 0777, true);


            if ($i < 2) {

                $mFS->shouldReceive('isDirectory')->once()
                    ->with($from)
                    ->andReturn(true);

                $mFS->shouldReceive('isDirectory')->once()
                    ->with($to)
                    ->andReturn(true);

                $mFS->shouldReceive('copyDirectory')->once()
                    ->with($from, $to);

            } else {

                $mFS->shouldReceive('isDirectory')->once()
                    ->with($from)
                    ->andReturn(false);

                $mFS->shouldReceive('isDirectory')
                    ->with($to)
                    ->andReturn(false);


                $mFS->shouldReceive('copy')->once()
                    ->with($from, $to);
            }
        }
        

        $structure = new Structure($dir, $this->getConfig(), $mFS, $mCmd);
        $structure->copy();
    }


    private function getConfig()
    {
        return [
            'copy' => [
                ['from' => '/path/one', 'to' => '/path/two'],
                ['from' => 'rel/path/three', 'to' => 'rel/path/four'],
                ['from' => '/path/five/file.txt', 'to' => '/path/six/file.txt']
            ],
            'move' => [
                ['from' => '/path/one', 'to' => '/path/two'],
                ['from' => '/path/three', 'to' => '/path/four'],
                ['from' => '/path/five', 'to' => '/path/six']
            ],
            'delete' => [
                '/foo/bar/file',
                '/biz/bang/file'
            ],
            'touch' => [
                '/touch/file/one',
                '/touch/file/two'
            ],
            'mkdir' => [
                '/path/to/dir/one',
                '/path/to/dir/two'
            ],
        ];
    }
}
