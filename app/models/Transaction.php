<?php
/**
 * Transaction Model
 */

namespace App\Models;

use App\Core\Model;

class Transaction extends Model
{
    protected string $table = 'transactions';

    /**
     * Get transaction by ID
     */
    public static function findById(int $id): ?array
    {
        return self::where('id', $id)->first();
    }

    /**
     * Get transactions by account ID
     */
    public static function findByAccountId(int $accountId, int $limit = 50): array
    {
        return self::where('account_id', $accountId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get transactions by user ID
     */
    public static function findByUserId(int $userId, int $limit = 50): array
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Create transaction
     */
    public static function create(array $data): int|false
    {
        return self::insert($data);
    }

    /**
     * Get transaction status
     */
    public function getStatus(): string
    {
        return $this->status ?? 'pending';
    }

    /**
     * Mark as completed
     */
    public static function markCompleted(int $id): bool
    {
        return self::where('id', $id)->update(['status' => 'completed', 'completed_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Mark as failed
     */
    public static function markFailed(int $id, string $reason = ''): bool
    {
        return self::where('id', $id)->update(['status' => 'failed', 'failure_reason' => $reason]);
    }
}
