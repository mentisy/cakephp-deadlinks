<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Command\Helper;

use Avolle\Deadlinks\Deadlinks\ResultSet;
use Cake\Console\Helper;

/**
 * Class ScanOutputHelper
 */
class ScanOutputHelper extends Helper
{
    /**
     * Output scan results
     *
     * @param array $args Output args
     * @return void
     */
    public function output(array $args): void
    {
        $this->_io->out("The following tables have dead links: ");
        foreach ($args as $tableResult) {
            $this->outputTable($tableResult);
        }
    }

    /**
     * Output a scanned table's results
     *
     * @param \Avolle\Deadlinks\Deadlinks\ResultSet $tableResult Table Result
     * @return void
     */
    protected function outputTable(ResultSet $tableResult): void
    {
        if ($tableResult->isEmpty()) {
            return;
        }
        $this->_io->info($tableResult->getTableName());

        $results = $tableResult->getResults();
        $rows = $this->createHeaders();

        foreach ($results as $result) {
            $rows[] = [
                $result->getPrimaryKey(),
                (string)$result->getPrimaryKeyValue(),
                $result->getField(),
                $result->getValue(),
            ];
        }
        $this->_io->helper('table')->output($rows);
        $this->_io->out();
    }

    /**
     * Add headers to table output
     *
     * @return string[][]
     */
    protected function createHeaders(): array
    {
        return [
            ['Primary Key', 'Primary Key Value', 'Field', 'Dead Link'],
        ];
    }
}
