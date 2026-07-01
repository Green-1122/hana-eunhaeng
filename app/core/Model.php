<?php
/**
 * Base Model Class
 */

namespace App\Core;

use PDO;

class Model
{
    protected string $table = '';
    protected array $data = [];
    protected ?string $orderBy = null;
    protected ?int $limitValue = null;
    protected ?int $offsetValue = null;

    /**
     * Find by ID
     */
    public static function find(int $id): ?static
    {
        $query = new static();
        return $query->where('id', $id)->first();
    }

    /**
     * Get all records
     */
    public static function all(): array
    {
        return (new static())->get();
    }

    /**
     * Where clause
     */
    public function where(string $column, mixed $value): static
    {
        // Implement where logic
        return $this;
    }

    /**
     * Get first record
     */
    public function first(): ?array
    {
        $result = $this->get();
        return $result[0] ?? null;
    }

    /**
     * Get all records
     */
    public function get(): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        return Database::query($sql) ?? [];
    }

    /**
     * Order by
     */
    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orderBy = "$column $direction";
        return $this;
    }

    /**
     * Limit records
     */
    public function limit(int $value): static
    {
        $this->limitValue = $value;
        return $this;
    }

    /**
     * Offset records
     */
    public function offset(int $value): static
    {
        $this->offsetValue = $value;
        return $this;
    }

    /**
     * Insert data
     */
    public static function insert(array $data): int|false
    {
        return Database::insert((new static())->table, $data);
    }

    /**
     * Update data
     */
    public static function update(array $data, array $where): bool
    {
        return Database::update((new static())->table, $data, $where);
    }

    /**
     * Delete data
     */
    public static function delete(array $where): bool
    {
        return Database::delete((new static())->table, $where);
    }

    /**
     * Magic getter
     */
    public function __get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    /**
     * Magic setter
     */
    public function __set(string $name, mixed $value): void
    {
        $this->data[$name] = $value;
    }
}
