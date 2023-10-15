<?php
declare(strict_types=1);

namespace Avolle\Deadlinks\Deadlinks;

/**
 * Class DeadLink
 *
 * @package Deadlinks\Deadlinks
 */
class DeadLink
{
    /**
     * DeadLink constructor.
     *
     * @param string $field Field of scanned link
     * @param mixed $value Value of scannede link
     * @param array<string>|string $primaryKey Primary key of row with link scanned
     * @param array<int>|array<string>|string|int $primaryKeyValue Primary key value of row with link scanned
     */
    public function __construct(
        protected string $field,
        protected mixed $value,
        protected array|string $primaryKey,
        protected array|int|string $primaryKeyValue,
    ) {
    }

    /**
     * Get field that was scanned
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Get field value that was scanned
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get primary key field name of row that was scanned
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Get primary key value of row that was scanned
     *
     * @return array<int>|array<string>|string|int
     */
    public function getPrimaryKeyValue(): array|int|string
    {
        return $this->primaryKeyValue;
    }

    /**
     * Convert dead link class properties to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'value' => $this->value,
            'primaryKey' => $this->primaryKey,
            'primaryKeyValue' => $this->primaryKeyValue,
        ];
    }
}
