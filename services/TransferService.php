<?php
namespace App\Services;

use App\Core\Database;
use App\Models\Account;
use App\Models\Transaction;

class TransferService
{
    public function transfer(int $fromAccountId, int $toAccountId, float $amount, string $description, int $initiatorUserId): void
    {
        if ($fromAccountId === $toAccountId) {
            throw new \Exception('Cannot transfer to the same account');
        }

        if ($amount <= 0) {
            throw new \Exception('Invalid amount');
        }

        $pdo = Database::getInstance();

        // Start DB transaction
        $pdo->beginTransaction();
        try {
            // Lock rows for update to avoid race conditions (SELECT ... FOR UPDATE)
            $stmt1 = $pdo->prepare('SELECT * FROM accounts WHERE id = :id FOR UPDATE');
            $stmt1->execute(['id' => $fromAccountId]);
            $from = $stmt1->fetch();

            $stmt2 = $pdo->prepare('SELECT * FROM accounts WHERE id = :id FOR UPDATE');
            $stmt2->execute(['id' => $toAccountId]);
            $to = $stmt2->fetch();

            if (!$from || !$to) {
                throw new \Exception('Account not found');
            }

            // Basic permission check: initiator must own the from account (admin use case aside)
            if ((int)$from['user_id'] !== (int)$initiatorUserId) {
                throw new \Exception('Unauthorized: you do not own the source account');
            }

            // Ensure sufficient funds
            $fromBalance = (float)$from['balance'];
            $toBalance = (float)$to['balance'];
            if ($fromBalance < $amount) {
                throw new \Exception('Insufficient funds');
            }

            $newFrom = $fromBalance - $amount;
            $newTo = $toBalance + $amount;

            // Update balances
            $upd = $pdo->prepare('UPDATE accounts SET balance = :balance WHERE id = :id');
            $upd->execute(['balance' => number_format($newFrom, 2, '.', ''), 'id' => $fromAccountId]);
            $upd->execute(['balance' => number_format($newTo, 2, '.', ''), 'id' => $toAccountId]);

            // Insert transaction entries (double-entry style)
            $txnModel = new Transaction();
            $meta = [
                'transfer' => true,
                'from_account' => $fromAccountId,
                'to_account' => $toAccountId,
                'initiator' => $initiatorUserId,
            ];

            // Debit (from)
            $txnModel->create([
                'account_id' => $fromAccountId,
                'type' => 'debit',
                'amount' => $amount,
                'description' => $description,
                'meta' => $meta,
            ]);

            // Credit (to)
            $txnModel->create([
                'account_id' => $toAccountId,
                'type' => 'credit',
                'amount' => $amount,
                'description' => $description,
                'meta' => $meta,
            ]);

            // Audit log
            $audit = $pdo->prepare('INSERT INTO audit_logs (user_id, action, ip, user_agent, created_at) VALUES (:user_id, :action, :ip, :ua, NOW())');
            $audit->execute([
                'user_id' => $initiatorUserId,
                'action' => "transfer: $fromAccountId -> $toAccountId amount=$amount",
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]);

            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
