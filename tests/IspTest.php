<?php

use Mirahost\NetworkTools\Isp;

class IspTest extends PHPUnit_Framework_TestCase {

    public function testIsp()
    {
        $Isp = new Isp;
        $this->assertContains('ISP Address', $Isp->getIsp());
    }

}