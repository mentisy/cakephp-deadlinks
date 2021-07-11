<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test\TestCase\Deadlinks;

use Avolle\Deadlinks\Deadlinks\DeadLink;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultSetTest
 */
class DeadLinkTest extends TestCase
{
    /**
     * Test DeadLink
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\DeadLink
     */
    public function testResultSet()
    {
        $deadLink = new DeadLink('link', 'https://localhost/a-link', 'id', 5);
        $this->assertEquals('link', $deadLink->getField());
        $this->assertEquals('https://localhost/a-link', $deadLink->getValue());
        $this->assertEquals('id', $deadLink->getPrimaryKey());
        $this->assertEquals(5, $deadLink->getPrimaryKeyValue());
    }

    /**
     * Test toArray method
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\DeadLink::toArray()
     */
    public function testToArray()
    {
        $deadLink = new DeadLink('link', 'https://localhost/a-link', 'id', 5);
        $actual = $deadLink->toArray();
        $this->assertEquals('link', $actual['field']);
        $this->assertEquals('https://localhost/a-link', $actual['value']);
        $this->assertEquals('id', $actual['primaryKey']);
        $this->assertEquals(5, $actual['primaryKeyValue']);
    }
}
