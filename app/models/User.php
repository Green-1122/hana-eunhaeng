<?php
/**
 * User Model
 */

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    /**
     * Get user by email
     */
    public static function findByEmail(string $email): ?array
    {
        return self::where('email', $email)->first();
    }

    /**
     * Get user by ID
     */
    public static function findById(int $id): ?array
    {
        return self::where('id', $id)->first();
    }

    /**
     * Create new user
     */
    public static function create(array $data): int|false
    {
        return self::insert($data);
    }

    /**
     * Update user
     */
    public static function updateUser(int $id, array $data): bool
    {
        return self::where('id', $id)->update($data);
    }

    /**
     * Get user accounts
     */
    public function accounts(): array
    {
        return (new Account())->where('user_id', $this->id)->get();
    }

    /**
     * Get user transactions
     */
    public function transactions(): array
    {
        return (new Transaction())->where('user_id', $this->id)->get();
    }

    /**
     * Verify email
     */
    public static function verifyEmail(int $id): bool
    {
        return self::where('id', $id)->update(['email_verified_at' => date('Y-m-d H:i:s')]);
    }
}
