<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Deadlinks;

/**
 * Class ResultSet
 */
class ResultSet
{
    /**
     * Results
     *
     * @var array<\Avolle\Deadlinks\Deadlinks\DeadLink>
     */
    protected array $results = [];

    /**
     * Table that was scanned
     *
     * @var string
     */
    protected string $tableName = '';

    /**
     * ResultSet constructor.
     *
     * @param string $tableName Table that was scanned
     */
    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * Get the results
     *
     * @return array<\Avolle\Deadlinks\Deadlinks\DeadLink>
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Add a new dead link instance to the results
     *
     * @param \Avolle\Deadlinks\Deadlinks\DeadLink $deadLink Dead link instance
     * @return void
     */
    public function add(DeadLink $deadLink): void
    {
        $this->results[] = $deadLink;
    }

    /**
     * Count how many dead links are in the result
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->results);
    }

    /**
     * Whether the scanned result is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->results);
    }

    /**
     * Get the table that was scanned
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }
}
