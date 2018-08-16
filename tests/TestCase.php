<?php
declare(strict_types=1);

namespace Tests\NatePage\ToolCraft;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Mock given class and set expectations using callable if provided.
     *
     * @param string $class
     * @param callable|null $setExpectations
     *
     * @return mixed
     */
    protected function mock(string $class, ?callable $setExpectations = null)
    {
        $mock = \Mockery::mock($class);

        $setExpectations($mock);

        return $mock;
    }
}
