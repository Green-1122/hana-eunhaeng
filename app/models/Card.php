<?php
/**
 * Card Model
 */

namespace App\Models;

use App\Core\Model;

class Card extends Model
{
    protected string $table = 'cards';

    /**
     * Get card by ID
     */
    public static function findById(int $id): ?array
    {
        return self::where('id', $id)->first();
    }

    /**
     * Get cards by user ID
     */
    public static function findByUserId(int $userId): array
    {
        return self::where('user_id', $userId)->get();
    }

    /**
     * Create card
     */
    public static function create(array $data): int|false
    {
        return self::insert($data);
    }

    /**
     * Update card status
     */
    public static function updateStatus(int $id, string $status): bool
    {
        return self::where('id', $id)->update(['status' => $status]);
    }

    /**
     * Lock card
     */
    public static function lock(int $id): bool
    {
        return self::updateStatus($id, 'locked');
    }

    /**
     * Unlock card
     */
    public static function unlock(int $id): bool
    {
        return self::updateStatus($id, 'active');
    }
}
