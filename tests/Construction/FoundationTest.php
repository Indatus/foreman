<?php namespace Construction;

use Mockery as m;
use Symfony\Component\Process\Process;

class FoundationTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }
}
