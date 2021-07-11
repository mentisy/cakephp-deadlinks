<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test\TestCase\Deadlinks;

use Avolle\Deadlinks\Deadlinks\DeadLink;
use Avolle\Deadlinks\Deadlinks\ResultSet;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultSetTest
 */
class ResultSetTest extends TestCase
{
    /**
     * Test resultSet
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\ResultSet
     */
    public function testResultSet()
    {
        $resultSet = new ResultSet('Links');
        $this->assertEquals(0, $resultSet->count());
        $this->assertTrue($resultSet->isEmpty());
        $this->assertEmpty($resultSet->getResults());

        $resultSet->add(new DeadLink('link', 'A link', 'id', 1));
        $resultSet->add(new DeadLink('linkTwo', 'A link 2', 'id', 2));

        $this->assertEquals(2, $resultSet->count());
        $this->assertFalse($resultSet->isEmpty());
        $this->assertNotEmpty($resultSet->getResults());
        $this->assertInstanceOf(DeadLink::class, $resultSet->getResults()[0]);
        $expected = ['link', 'A link', 'id', 1];
        $actual = [
            $resultSet->getResults()[0]->getField(),
            $resultSet->getResults()[0]->getValue(),
            $resultSet->getResults()[0]->getPrimaryKey(),
            $resultSet->getResults()[0]->getPrimaryKeyValue(),
        ];
        $this->assertEquals($expected, $actual);
        $expectedTwo = ['linkTwo', 'A link 2', 'id', 2];
        $actualTwo = [
            $resultSet->getResults()[1]->getField(),
            $resultSet->getResults()[1]->getValue(),
            $resultSet->getResults()[1]->getPrimaryKey(),
            $resultSet->getResults()[1]->getPrimaryKeyValue(),
        ];
        $this->assertEquals($expectedTwo, $actualTwo);
    }
}
