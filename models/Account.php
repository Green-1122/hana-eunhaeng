<?php
namespace App\Models;

use App\Core\Model;

class Account extends Model
{
    protected string $table = 'accounts';

    public function findByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function adjustBalance(int $id, float $newBalance): bool
    {
        $stmt = $this->db->prepare("UPDATE accounts SET balance = :balance WHERE id = :id");
        return $stmt->execute(['balance' => number_format($newBalance, 2, '.', ''), 'id' => $id]);
    }
}
