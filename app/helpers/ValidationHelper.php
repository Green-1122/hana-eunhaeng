<?php
/**
 * Validation Helper
 */

namespace App\Helpers;

class ValidationHelper
{
    private array $errors = [];
    private array $data = [];

    /**
     * Constructor
     */
    public function __construct(array $data = [])
    {
        $this->data = $data ?: $_REQUEST;
    }

    /**
     * Required field
     */
    public function required(string $field, string $message = null): self
    {
        if (empty($this->data[$field])) {
            $this->errors[$field] = $message ?? "$field is required";
        }
        return $this;
    }

    /**
     * Email validation
     */
    public function email(string $field, string $message = null): self
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "$field must be a valid email";
        }
        return $this;
    }

    /**
     * Min length
     */
    public function minLength(string $field, int $length, string $message = null): self
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "$field must be at least $length characters";
        }
        return $this;
    }

    /**
     * Max length
     */
    public function maxLength(string $field, int $length, string $message = null): self
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "$field must not exceed $length characters";
        }
        return $this;
    }

    /**
     * Numeric validation
     */
    public function numeric(string $field, string $message = null): self
    {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "$field must be numeric";
        }
        return $this;
    }

    /**
     * Unique validation
     */
    public function unique(string $field, string $table, string $column = null, string $message = null): self
    {
        $col = $column ?? $field;
        $count = \App\Core\Database::count($table, [$col => $this->data[$field]]);
        
        if ($count > 0) {
            $this->errors[$field] = $message ?? "$field already exists";
        }
        return $this;
    }

    /**
     * Check if validation passes
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get errors
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get first error
     */
    public function firstError(): ?string
    {
        return reset($this->errors) ?: null;
    }
}
