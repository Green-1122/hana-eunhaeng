<?php
namespace App\Models;

use App\Core\Model;

class Transaction extends Model
{
    protected string $table = 'transactions';

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO transactions (account_id, type, amount, description, meta, created_at) VALUES (:account_id, :type, :amount, :description, :meta, NOW())");
        $stmt->execute([
            'account_id' => $data['account_id'],
            'type' => $data['type'],
            'amount' => number_format($data['amount'], 2, '.', ''),
            'description' => $data['description'] ?? null,
            'meta' => isset($data['meta']) ? json_encode($data['meta']) : null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function getByAccountId(int $accountId, int $limit = 50): array
    {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE account_id = :account_id ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':account_id', $accountId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
