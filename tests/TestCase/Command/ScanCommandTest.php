<?php
declare(strict_types=1);

namespace Command;

use Avolle\Deadlinks\Exception\MissingConfigException;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\EmailTrait;
use Cake\TestSuite\TestCase;
use DateTimeImmutable;

/**
 * Class ScanCommandTest
 *
 * @uses \Avolle\Deadlinks\Command\ScanCommand::execute()
 */
class ScanCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected array $fixtures = [
        'plugin.Avolle/Deadlinks.Files',
        'plugin.Avolle/Deadlinks.Links',
        'plugin.Avolle/Deadlinks.Resources',
    ];

    /**
     * Test buildOptionParser method
     * Assert help messages
     *
     * @return void
     * @uses \Avolle\Deadlinks\Command\ScanCommand::buildOptionParser()
     */
    public function testBuildOptionParser(): void
    {
        $this->exec('scan -h');
        $this->assertOutputContains('--log, -l       Output report to log file');
        $this->assertOutputContains('--mail, -m      Send report to mail');
        $this->assertOutputContains('--terminal, -t  Output report in terminal');
    }

    /**
     * Test execute
     * The config file detailing which Models and fields to scan does not exist.
     * Assert MissingConfigException
     *
     * @return void
     */
    public function testExecuteMissingConfigFile(): void
    {
        // First rename the valid deadlinks.php file, so that the command thinks it does not exist
        rename(CONFIG . 'deadlinks.php', CONFIG . 'deadlinks.php.tmp');
        $this->expectException(MissingConfigException::class);
        $this->expectExceptionMessage('Configuration key `Deadlinks` was not found. Please add a `deadlinks.php` '
            . 'file to your config folder.');
        try {
            $this->exec('scan');
        } catch (MissingConfigException $ex) {
            // Rename deadlinks config file back to a valid file, so it will be useful for further tests
            rename(CONFIG . 'deadlinks.php.tmp', CONFIG . 'deadlinks.php');
            // Re-throw exception to the assertions work
            throw $ex;
        }
    }

    /**
     * Test execute method
     * No links fail, so assert success message
     *
     * @return void
     * @uses \Avolle\Deadlinks\Command\ScanCommand::execute()
     */
    public function testExecuteNoFails(): void
    {
        rename(CONFIG . 'deadlinks.php', CONFIG . 'deadlinks.php.tmp');
        rename(CONFIG . 'deadlinks_empty.php', CONFIG . 'deadlinks.php');
        $this->exec('scan -t');
        rename(CONFIG . 'deadlinks.php', CONFIG . 'deadlinks_empty.php');
        rename(CONFIG . 'deadlinks.php.tmp', CONFIG . 'deadlinks.php');
        $this->assertOutputContains('No links were found to be dead.');
    }

    /**
     * Test execute method
     * Output to terminal
     *
     * @return void
     * @uses \Avolle\Deadlinks\Command\ScanCommand::execute()
     */
    public function testExecuteTerminal(): void
    {
        $this->exec('scan --terminal');
        $this->assertOutputContains('Dead links have been output to the requested channels.');
        // Tables with dead links should be present
        $this->assertOutputContains('<info>Files</info>');
        $this->assertOutputContains('<info>Resources</info>');
        // Tables without dead links should not be present
        $this->assertOutputNotContains('<info>Links</info>');

        // Table header
        $this->assertOutputContainsRow([
            '<info>Primary Key</info>',
            '<info>Primary Key Value</info>',
            '<info>Field</info>',
            '<info>Dead Link</info>',
        ]);

        // Files should have the following dead links
        $this->assertOutputContainsRow(['id', '1', 'linkTwo', 'https://a-dead-link-here.no']);
        $this->assertOutputContainsRow(['id', '2', 'linkOne', 'https://a-dead-link-here.no']);
        $this->assertOutputContainsRow(['id', '2', 'linkTwo', 'https://a-dead-link-here-too.no']);
        // Resources should have the following dead links
        $this->assertOutputContainsRow(['id', '1', 'link', 'https://a-dead-link-here.no']);

        // These links exist in the table, but are alive, so should NEVER be present in any output
        $this->assertOutputNotContains('https://google.com');
        $this->assertOutputNotContains('https://google.co');
    }

    /**
     * Test execute method
     * Output to terminal
     *
     * @return void
     * @uses \Avolle\Deadlinks\Command\ScanCommand::execute()
     */
    public function testExecuteLog(): void
    {
        // Remove the existing debug file, so that we have a fresh file to insert the results into
        if (file_exists(LOGS . 'deadlinks.log')) {
            unlink(LOGS . 'deadlinks.log');
        }
        $this->exec('scan --log');
        // Replace the placeholder for datetime with the current datetime
        $currentDateTime = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $expected = file_get_contents(TEST_FILES . 'deadlinks_static.log');
        $expected = str_replace('__DATETIME__PLACEHOLDER__', $currentDateTime, $expected);

        // Assert the expected result matches the actual result
        $this->assertTextEquals($expected, file_get_contents(LOGS . 'deadlinks.log'));
    }

    /**
     * Test execute method
     * Send email
     *
     * @return void
     * @uses \Avolle\Deadlinks\Command\ScanCommand::execute()
     */
    public function testExecuteMail(): void
    {
        $this->exec('scan --mail');
        $this->assertMailSentTo('cakephp-plugins@avolle.com');
        $this->assertMailSubjectContains('Dead Links Report - 2021-07-10');
        // Intro
        $this->assertMailContains('Dead Links Scan Results run 2021-07-10 23:11');
        $this->assertMailContains('A total of 3 tables were scanned.');
        // Tables scanned
        $this->assertMailContains('Files');
        $this->assertMailContains('Links');
        $this->assertMailContains('Resources');
        // First dead link of the Resources table
        $this->assertMailContains('PrimaryKey: id');
        $this->assertMailContains('PrimaryKeyValue: 1');
        $this->assertMailContains('Field: link');
        $this->assertMailContains('Value: https://a-dead-link-here.no');
        // Further dead links
        $this->assertMailContains('PrimaryKeyValue: 1');
        $this->assertMailContains('PrimaryKeyValue: 2');
        // No dead links in the Links table
        $this->assertMailContainsText("----- Links -----\nNo dead links found");
    }
}
