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
     * Field that was scanned
     *
     * @var string
     */
    protected string $field = "";

    /**
     * Value (URL) that was scanned
     *
     * @var string
     */
    protected string $value = "";

    /**
     * Primary key of the dead link row
     *
     * @var string|string[]
     */
    protected $primaryKey = "";

    /**
     * Primary key value of the dead link row
     *
     * @var string|string[]|int|int[]
     */
    protected $primaryKeyValue;

    /**
     * DeadLink constructor.
     *
     * @param string $field Field of scanned link
     * @param mixed $value Value of scannede link
     * @param string|string[] $primaryKey Primary key of row with link scanned
     * @param string|string[]|int|int[] $primaryKeyValue Primary key value of row with link scanned
     */
    public function __construct(string $field, $value, $primaryKey, $primaryKeyValue)
    {
        $this->field = $field;
        $this->value = $value;
        $this->primaryKey = $primaryKey;
        $this->primaryKeyValue = $primaryKeyValue;
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
     * @return int|int[]|string|string[]
     */
    public function getPrimaryKeyValue()
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
