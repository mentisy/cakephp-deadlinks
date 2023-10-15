<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Test\TestCase\Deadlinks;

use Avolle\Deadlinks\Deadlinks\DeadLink;
use Avolle\Deadlinks\Deadlinks\ResultSet;
use Avolle\Deadlinks\Deadlinks\TableScanner;
use Cake\TestSuite\TestCase;

/**
 * Class TableScannerTest
 *
 * @uses \Avolle\Deadlinks\Test\Fixture\FilesFixture
 * @uses \Avolle\Deadlinks\Test\Fixture\LinksFixture
 * @uses \Avolle\Deadlinks\Test\Fixture\ResourcesFixture
 */
class TableScannerTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array<string> Fixtures
     */
    protected array $fixtures = [
        'plugin.Avolle/Deadlinks.Files',
        'plugin.Avolle/Deadlinks.Links',
        'plugin.Avolle/Deadlinks.Resources',
    ];

    /**
     * Test scan method
     * Testing the Files Fixture
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\TableScanner::scan()
     */
    public function testScanFilesWithDeadLinks(): void
    {
        $table = 'Files';
        $fields = ['linkOne', 'linkTwo'];
        $scanner = new TableScanner($table, $fields);
        $actual = $scanner->scan();
        // Is of ResultSet instance
        $this->assertInstanceOf(ResultSet::class, $actual);
        // Result in an array and contains one result
        $this->assertIsArray($actual->getResults());
        $this->assertCount(3, $actual->getResults());
        // Key of ResultSet array is a DeadLink class
        $this->assertInstanceOf(DeadLink::class, $actual->getResults()[0]);
        // Create the expected result to compare against actual
        $expected = new ResultSet($table);
        // First Dead Link
        $deadLink = new DeadLink('linkTwo', 'https://a-dead-link-here.no', 'id', 1);
        $expected->add($deadLink);
        // Second Dead Link
        $deadLink = new DeadLink('linkOne', 'https://a-dead-link-here.no', 'id', 2);
        $expected->add($deadLink);
        // Third Dead Link
        $deadLink = new DeadLink('linkTwo', 'https://a-dead-link-here-too.no', 'id', 2);
        $expected->add($deadLink);
        // Perform equality test
        $this->assertEquals($actual, $expected);
    }

    /**
     * Test scan method
     * Testing the Links Fixture
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\TableScanner::scan()
     */
    public function testScanLinksWithNoDeadLinks(): void
    {
        $table = 'Links';
        $fields = ['link'];
        $scanner = new TableScanner($table, $fields);
        $actual = $scanner->scan();
        // Is of ResultSet instance
        $this->assertInstanceOf(ResultSet::class, $actual);
        // Result in an array and is empty (as no links are dead)
        $this->assertIsArray($actual->getResults());
        $this->assertEmpty($actual->getResults());
    }

    /**
     * Test scan method
     * Testing the Resources Fixture
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\TableScanner::scan()
     */
    public function testScanResourcesWithOnlyOneDeadLink(): void
    {
        $table = 'Resources';
        $fields = ['link'];
        $scanner = new TableScanner($table, $fields);
        $actual = $scanner->scan();
        // Is of ResultSet instance
        $this->assertInstanceOf(ResultSet::class, $actual);
        // Result in an array and contains one result
        $this->assertIsArray($actual->getResults());
        $this->assertCount(1, $actual->getResults());
        // Key of ResultSet array is a DeadLink class
        $this->assertInstanceOf(DeadLink::class, $actual->getResults()[0]);
        // Create the expected result to compare against actual
        $expected = new ResultSet($table);
        $deadLink = new DeadLink('link', 'https://a-dead-link-here.no', 'id', 1);
        $expected->add($deadLink);
        // Perform equality test
        $this->assertEquals($actual, $expected);
    }

    /**
     * Test scanTable method
     *
     * @return void
     * @uses \Avolle\Deadlinks\Deadlinks\TableScanner::scanTable()
     */
    public function testStaticScanTables(): void
    {
        $table = 'Links';
        $fields = ['link'];
        $actual = TableScanner::scanTable($table, $fields);
        // Is of ResultSet instance
        $this->assertInstanceOf(ResultSet::class, $actual);
        // Result in an array and is empty (as no links are dead)
        $this->assertIsArray($actual->getResults());
        $this->assertEmpty($actual->getResults());
    }
}
