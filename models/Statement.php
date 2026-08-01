<?php
namespace App\Models;

use App\Core\Model;

class Statement extends Model
{
    protected string $table = 'statements';

    public function createRecord(int $userId, int $accountId, string $from, string $to): int
    {
        $stmt = $this->db->prepare("INSERT INTO statements (user_id, account_id, period_from, period_to, status, created_at) VALUES (:user_id, :account_id, :pfrom, :pto, 'pending', NOW())");
        $stmt->execute([
            'user_id' => $userId,
            'account_id' => $accountId,
            'pfrom' => $from,
            'pto' => $to,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateFile(int $id, string $filename, string $filehash, string $expiresAt): bool
    {
        $stmt = $this->db->prepare("UPDATE statements SET file_path = :fp, file_hash = :fh, status = 'ready', expires_at = :exp, updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['fp' => $filename, 'fh' => $filehash, 'exp' => $expiresAt, 'id' => $id]);
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM statements WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM statements WHERE user_id = :uid ORDER BY created_at DESC LIMIT 50");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }
}
