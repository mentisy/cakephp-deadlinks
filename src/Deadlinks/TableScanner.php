<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Deadlinks;

use Cake\ORM\Locator\LocatorInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Class TableScanner
 */
class TableScanner
{
    /**
     * Table locator, used to finding entities from the given tableName
     *
     * @var \Cake\ORM\Locator\LocatorInterface
     */
    protected LocatorInterface $tableLocator;

    /**
     * TableScanner constructor.
     *
     * @param string $tableName Table to scan
     * @param array<string> $fields Fields to scan
     * @return void
     */
    public function __construct(protected string $tableName, protected array $fields)
    {
        $this->tableLocator = TableRegistry::getTableLocator();
    }

    /**
     * Scan table for dead links, with the given fields
     *
     * @param string $tableName Table to scan
     * @param array<string> $fields Fields to scan
     * @return \Avolle\Deadlinks\Deadlinks\ResultSet
     */
    public static function scanTable(string $tableName, array $fields): ResultSet
    {
        return (new TableScanner($tableName, $fields))->scan();
    }

    /**
     * Scan table for dead links
     *
     * @return \Avolle\Deadlinks\Deadlinks\ResultSet
     */
    public function scan(): ResultSet
    {
        $model = $this->tableLocator->get($this->tableName);
        $primaryKey = $model->getPrimaryKey();
        $scanner = new UrlScanner();
        $resultSet = new ResultSet($this->tableName);

        foreach ($this->findEntities($model) as $entity) {
            foreach ($this->fields as $field) {
                $fieldValue = $entity->get($field);
                if (!empty($fieldValue) && $scanner->isDead($fieldValue)) {
                    $resultSet->add(new DeadLink($field, $fieldValue, $primaryKey, $entity->get($primaryKey)));
                }
            }
        }

        return $resultSet;
    }

    /**
     * Fields to select in the built query
     *
     * @param array|string $primaryKey Primary Key
     * @return array
     */
    protected function selectFields(array|string $primaryKey): array
    {
        return array_merge($this->fields, (array)$primaryKey);
    }

    /**
     * Find entities in the given model, selecting the fields to scan
     *
     * @param \Cake\ORM\Table $model Model
     * @return \Cake\ORM\Query\SelectQuery
     */
    protected function findEntities(Table $model): SelectQuery
    {
        $selectFields = $this->selectFields($model->getPrimaryKey());

        return $model->find()->select($selectFields);
    }
}
