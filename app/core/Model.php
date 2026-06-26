<?php
/**
 * Base Model Class
 * All models extend this class
 */

namespace App\Core;

use App\Core\Database;

abstract class Model
{
    protected string $table = '';
    protected array $attributes = [];
    protected array $fillable = [];
    protected array $hidden = [];

    /**
     * Constructor
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Get all records
     */
    public static function all(): array
    {
        $table = (new static())->table;
        return Database::query("SELECT * FROM `$table`") ?: [];
    }

    /**
     * Find by ID
     */
    public static function find(int $id): ?self
    {
        $instance = new static();
        $result = Database::queryOne(
            "SELECT * FROM `{$instance->table}` WHERE `id` = ?",
            [$id]
        );

        return $result ? new static($result) : null;
    }

    /**
     * Find by attribute
     */
    public static function findBy(string $column, mixed $value): ?self
    {
        $instance = new static();
        $result = Database::queryOne(
            "SELECT * FROM `{$instance->table}` WHERE `$column` = ?",
            [$value]
        );

        return $result ? new static($result) : null;
    }

    /**
     * Where clause
     */
    public static function where(string $column, mixed $value): array
    {
        $instance = new static();
        $results = Database::query(
            "SELECT * FROM `{$instance->table}` WHERE `$column` = ?",
            [$value]
        );

        return array_map(fn($row) => new static($row), $results ?: []);
    }

    /**
     * Create new record
     */
    public static function create(array $data): ?self
    {
        $instance = new static();
        $filtered = array_intersect_key($data, array_flip($instance->fillable));
        
        $id = Database::insert($instance->table, $filtered);
        
        return $id ? static::find($id) : null;
    }

    /**
     * Update record
     */
    public function update(array $data): bool
    {
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        
        if (Database::update($this->table, $filtered, ['id' => $this->id])) {
            $this->attributes = array_merge($this->attributes, $filtered);
            return true;
        }

        return false;
    }

    /**
     * Delete record
     */
    public function delete(): bool
    {
        return Database::delete($this->table, ['id' => $this->id]);
    }

    /**
     * Get attribute
     */
    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Set attribute
     */
    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Check if attribute exists
     */
    public function __isset(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        $array = $this->attributes;
        foreach ($this->hidden as $field) {
            unset($array[$field]);
        }
        return $array;
    }

    /**
     * Convert to JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}