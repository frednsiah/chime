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

}