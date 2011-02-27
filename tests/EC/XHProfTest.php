<?php

require_once 'EC/XHProf.php';


class EC_XHProfTest extends PHPUnit_Framework_TestCase
{
    protected $_time = null;
    protected $_nameSpace = __CLASS__;
    protected $_testDirectory = '/tmp/xhprof_unit_test';

    public function setUp()
    {
        $this->_time = time();
        mkdir($this->_testDirectory);
    }
    public function tearDown()
    {
        $this->_time = null;
        exec("rm -rf {$this->_testDirectory}");
    }

    public function testGetRunsClearRuns()
    {
        $file1 = '/tmp/xhprof_unit_test/RUN1.' . $this->_nameSpace;
        $file2 = '/tmp/xhprof_unit_test/RUN2.someothervhost';
        $file3 = '/tmp/xhprof_unit_test/RUN3.' . 'media-' . $this->_nameSpace;
        touch($file1);
        touch($file2);
        touch($file3);
        $xhprof = new EC_XHProf($this->_nameSpace, $this->_testDirectory);
        $runs = $xhprof->getRuns();
        $run1Time = strtotime($runs['RUN1']);
        $run2Time = strtotime($runs['RUN3']);
        $this->assertTrue(($run1Time >= $this->_time) && ($run1Time <= ($this->_time + 1)));
        $this->assertTrue(($run2Time >= $this->_time) && ($run2Time <= ($this->_time + 1)));
        $this->assertTrue(count($runs) == 2);
        $xhprof->clear();
        $clear = $xhprof->getRuns();
        $this->assertTrue(empty($clear));
    }

    public function testGetRunsFailNoDirectory()
    {
        $xhprof = new EC_XHProf($this->_nameSpace, '/foobarnothere');
        $runs = $xhprof->getRuns();
        $this->assertTrue(empty($runs));
    }
}
