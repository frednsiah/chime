<?php
require_once './BellCounter.php';

class BellCounterTest extends PHPUnit\Framework\TestCase {
    public function setUp () {
        $this->counter = new BellCounter();
    }
    public function testRange1() {
        $this->assertEquals(5, $this->counter->countBells('2:00', '3:00'));
    }
    public function testRange2() {
        $this->assertEquals(5, $this->counter->countBells('14:00', '15:00'));
    }
    public function testRange3() {
        $this->assertEquals(3, $this->counter->countBells('14:23', '15:42'));
    }
    public function testRange4() {
        $this->assertEquals(24, $this->counter->countBells('23:00', '1:00'));
    }

    public function testRange5() {
        $this->assertEquals(0, $this->counter->countBells('2:04', '2:07'));
    }

    public function testRange6() {
        $this->assertEquals(156, $this->counter->countBells('2:07', '2:05'));
    }

    public function testRange7() {
        $this->assertEquals(158, $this->counter->countBells('02:00', '02:00'));
    }

    public function testRange8() {
        $this->assertEquals(156, $this->counter->countBells('3:07', '3:07'));
    }

}