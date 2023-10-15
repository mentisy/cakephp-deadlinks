<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Command;

use Avolle\Deadlinks\Deadlinks\ResultSet;
use Avolle\Deadlinks\Deadlinks\TableScanner;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Log\LogTrait;
use Cake\Mailer\MailerAwareTrait;

/**
 * Scan command.
 */
class ScanCommand extends Command
{
    use LogTrait;
    use MailerAwareTrait;

    /**
     * Console Input/Output
     *
     * @var \Cake\Console\ConsoleIo $io The console io
     */
    protected ConsoleIo $io;

    /**
     * Command option parser.
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('terminal', [
            'help' => 'Output report in terminal',
            'short' => 't',
            'boolean' => true,
        ]);

        $parser->addOption('log', [
            'help' => 'Output report to log file',
            'short' => 'l',
            'boolean' => true,
        ]);

        $parser->addOption('mail', [
            'help' => 'Send report to mail',
            'short' => 'm',
            'boolean' => true,
        ]);

        return $parser;
    }

    /**
     * Scan for dead links and send results to the selected output
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->io = $io;

        $tables = Configure::read('Deadlinks.tables');

        $result = $this->scanTables($tables);

        // No dead links found in any table scans. Give feedback to user and exit
        $hasDeadlinks = collection($result)->filter(fn (ResultSet $resultSet) => !$resultSet->isEmpty());
        if ($hasDeadlinks->isEmpty()) {
            return $io->success('No links were found to be dead.');
        }

        // Output scan result to the desired channels
        if ($args->getOption('terminal')) {
            $this->outputTerminal($result);
        }
        if ($args->getOption('log')) {
            $this->outputLog($result);
        }
        if ($args->getOption('mail')) {
            $this->sendEmail($result);
        }

        return $io->success('Dead links have been output to the requested channels.');
    }

    /**
     * Scan the specified tables and return the results
     *
     * @param array $tables Tables to scan
     * @return array<\Avolle\Deadlinks\Deadlinks\ResultSet>
     */
    protected function scanTables(array $tables): array
    {
        $resultSets = [];
        foreach ($tables as $table => $config) {
            $scanner = new TableScanner($table, $config['fields']);
            $resultSets[$table] = $scanner->scan();
        }

        return $resultSets;
    }

    /**
     * Output results into the terminal
     *
     * @param array<\Avolle\Deadlinks\Deadlinks\ResultSet> $result Scanned results
     * @return void
     * @uses \Avolle\Deadlinks\Command\Helper\ScanOutputHelper
     */
    protected function outputTerminal(array $result): void
    {
        $this->io->helper('Avolle/Deadlinks.ScanOutput')->output($result);
    }

    /**
     * Output results into a log file
     *
     * @param array<\Avolle\Deadlinks\Deadlinks\ResultSet> $result Scanned results
     * @return void
     */
    protected function outputLog(array $result): void
    {
        $message = 'A scan of dead links was performed. ' . count($result) . ' tables were scanned.';
        $message .= "\n==========================================";

        foreach ($result as $resultSet) {
            $message .= "\n----- {$resultSet->getTableName()} -----\n";
            if ($resultSet->isEmpty()) {
                $message .= "No dead links found.\n";
                continue;
            }
            foreach ($resultSet->getResults() as $deadLink) {
                foreach ($deadLink->toArray() as $key => $value) {
                    $message .= sprintf("\n%s: %s", ucfirst($key), $value);
                }
                $message .= "\n";
            }
        }
        $this->log($message, 'info', ['scope' => 'deadlinks']);
    }

    /**
     * Output the results into an email
     *
     * @param array<\Avolle\Deadlinks\Deadlinks\ResultSet> $result Scanned results
     * @return array
     */
    protected function sendEmail(array $result): array
    {
        /** @var \Avolle\Deadlinks\Mailer\ScanResultMailer $mailer */
        $mailer = $this->getMailer('Avolle/Deadlinks.ScanResult');

        return $mailer->send('sendResult', [$result]);
    }
}
