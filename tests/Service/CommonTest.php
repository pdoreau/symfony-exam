<?php

namespace App\Tests\Service;

use App\Service\Common;
use PHPUnit\Framework\TestCase;

class CommonTest extends TestCase
{
    public function testBoo()
    {
        $this->assertEquals([], Common::boo([]));
        $this->assertEquals(['foo'], Common::boo(['foo']));
        $this->assertEquals(['foo', 'bar'], Common::boo(['foo', 'bar']));
        $this->assertEquals(['foo', 'bar', 'baz'], Common::boo(['foo', 'bar', 'baz']));
    }

    public function testFoo()
    {
        $this->assertEquals(['foo', 'bar', 'k-b' => 'baz'], Common::foo(['foo', 'bar'], ['k' => 'k-b', 'v' => 'baz']));
        $this->assertEquals(['foo', 'k-b' => 'baz'], Common::foo(['foo', 'k-b' => 'bar'], ['k' => 'k-b', 'v' => 'baz']));
    }

    public function testBar()
    {
        $this->assertTrue(Common::bar(['foo', 'bar' => 'baz'], [0, 'bar']));
        $this->assertTrue(Common::bar(['foo', 'bar' => 'baz'], ['bar', 0]));
        $this->assertFalse(Common::bar(['foo', 'bar' => 'baz'], [1, 'bar']));
        $this->assertFalse(Common::bar(['foo', 'bar' => 'baz'], [0, 'baz']));
    }
}
