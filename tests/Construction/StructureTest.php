<?php namespace Construction;

use Mockery as m;
use Illuminate\Filesystem\Filesystem as FS;
use Construction\Structure;
use Support\Path;
use org\bovigo\vfs\vfsStream;

class StructureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var  vfsStreamDirectory
     */
    private $root;


    /**
     * set up test environmemt
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('touchDir');
    }


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



    public function testMove()
    {

        $dir = '/path/to/app';

        $mFS = m::mock('Illuminate\Filesystem\Filesystem');
        $mCmd = m::mock('Console\BuildCommand');

        $config = $this->getConfig()['move'];

        for ($i=0; $i < count($config); $i++) {

            $from = Path::absolute($config[$i]['from'], $dir);
            $to = Path::absolute($config[$i]['to'], $dir);

            $mCmd->shouldReceive('comment')->once()
                ->with("Move", "from {$from} to {$to}");

            $mFS->shouldReceive('move')->once()
                ->with($from, $to);

        }//end for

        $structure = new Structure($dir, $this->getConfig(), $mFS, $mCmd);
        $structure->move();
    }



    public function testDelete()
    {

        $dir = '/path/to/app';

        $mFS = m::mock('Illuminate\Filesystem\Filesystem');
        $mCmd = m::mock('Console\BuildCommand');

        $config = $this->getConfig()['delete'];

        for ($i=0; $i < count($config); $i++) {

            $del = $config[$i];

            $mCmd->shouldReceive('comment')->once()
                ->with("Delete", $del);

            if ($i == 0) {

                $mFS->shouldReceive('isDirectory')->once()
                    ->with($del)
                    ->andReturn(true);

                $mFS->shouldReceive('deleteDirectory')->once()
                    ->with($del);

            } else {

                $mFS->shouldReceive('isDirectory')->once()
                    ->with($del)
                    ->andReturn(false);

                $mFS->shouldReceive('delete')->once()
                    ->with($del);
            }

        }//end for

        $structure = new Structure($dir, $this->getConfig(), $mFS, $mCmd);
        $structure->delete();
    }



    public function testTouch()
    {
        $dir  = '/path/to/app';
        $mFS  = m::mock('Illuminate\Filesystem\Filesystem');
        $mCmd = m::mock('Console\BuildCommand');

        $touches = [
            vfsStream::url('touchDir'). DIRECTORY_SEPARATOR .'one.txt',
            vfsStream::url('touchDir'). DIRECTORY_SEPARATOR .'two.txt'
        ];

        $config = $this->getConfig();
        $config['touch'] = $touches;

        foreach ($touches as $touchFile) {
            $mCmd->shouldReceive('comment')->once()
                ->with("Touch", $touchFile);
        }

        $this->assertFalse($this->root->hasChild('one.txt'));
        $this->assertFalse($this->root->hasChild('two.txt'));

        $structure = new Structure($dir, $config, $mFS, $mCmd);
        $structure->touch();

        $this->assertTrue($this->root->hasChild('one.txt'));
        $this->assertTrue($this->root->hasChild('two.txt'));
    }



    public function testMakeDirectories()
    {
        $appDir  = '/path/to/app';
        $mFS  = m::mock('Illuminate\Filesystem\Filesystem');
        $mCmd = m::mock('Console\BuildCommand');

        foreach ($this->getConfig()['mkdir'] as $dir) {

            $mCmd->shouldReceive('comment')->once()
                ->with("Make Directory", $dir);

            $mFS->shouldReceive('makeDirectory')->once()
                ->with(Path::absolute($dir, $appDir), 0777, true);
        }

        $structure = new Structure($dir, $this->getConfig(), $mFS, $mCmd);
        $structure->mkdirs();
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
                ['from' => 'rel/path/three', 'to' => 'rel/path/four'],
                ['from' => '/path/five/file.txt', 'to' => '/path/six/file.txt']
            ],
            'delete' => [
                '/foo/bar/dir',
                '/biz/bang/file.txt'
            ],
            'touch' => [
                '/touch/file/one.txt',
                '/touch/file/two.txt'
            ],
            'mkdir' => [
                '/path/to/dir/one',
                '/path/to/dir/two'
            ],
        ];
    }
}
