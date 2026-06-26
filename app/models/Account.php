<?php
/**
 * Account Model
 */

namespace App\Models;

use App\Core\Model;

class Account extends Model
{
    protected string $table = 'accounts';

    /**
     * Get account by ID
     */
    public static function findById(int $id): ?array
    {
        return self::where('id', $id)->first();
    }

    /**
     * Get accounts by user ID
     */
    public static function findByUserId(int $userId): array
    {
        return self::where('user_id', $userId)->get();
    }

    /**
     * Get account by account number
     */
    public static function findByAccountNumber(string $accountNumber): ?array
    {
        return self::where('account_number', $accountNumber)->first();
    }

    /**
     * Create new account
     */
    public static function create(array $data): int|false
    {
        return self::insert($data);
    }

    /**
     * Update account balance
     */
    public static function updateBalance(int $id, float $amount): bool
    {
        return self::where('id', $id)->update(['balance' => $amount]);
    }

    /**
     * Get account transactions
     */
    public function transactions(): array
    {
        return (new Transaction())->where('account_id', $this->id)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get owner
     */
    public function owner(): ?array
    {
        return User::findById($this->user_id);
    }
}
