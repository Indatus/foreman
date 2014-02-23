<?php namespace Support;

use Support\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{

    public function testRemoveTrailingSlash()
    {
        $this->assertEquals(
            '/path/to/dir',
            Path::removeTrailingSlash('/path/to/dir/')
        );

        $this->assertEquals(
            'C:\\path\\to\\dir',
            Path::removeTrailingSlash('C:\\path\\to\\dir\\')
        );
    }


    public function testGetAbsolutePath()
    {
        $baseDir = '/path/to/app';
        $relPath1 = 'another/given/path';
        $relPath2 = 'file';

        $this->assertEquals(
            $baseDir.DIRECTORY_SEPARATOR.$relPath1,
            Path::absolute($relPath1, $baseDir)
        );

        $this->assertEquals(
            $baseDir.DIRECTORY_SEPARATOR.$relPath2,
            Path::absolute($relPath2, $baseDir)
        );

        $this->assertEquals(
            '/file',
            Path::absolute('/file', $baseDir)
        );

        $this->assertEquals(
            '\file',
            Path::absolute('\file', $baseDir)
        );

        $this->assertEquals(
            'c:\file',
            Path::absolute('c:\file', $baseDir)
        );
    }
}
