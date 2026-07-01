<?php
/**
 * Transaction Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Transfer;
use App\Middleware\AuthMiddleware;
use App\Helpers\ValidationHelper;

class TransactionController extends Controller
{
    /**
     * Show transaction history
     */
    public function history()
    {
        AuthMiddleware::requireAuth();

        $userId = AuthMiddleware::getUserId();
        $accountId = $_GET['account_id'] ?? null;
        
        if ($accountId) {
            $account = Account::findById($accountId);
            if (!$account || $account['user_id'] !== $userId) {
                http_response_code(403);
                exit;
            }
            $transactions = Transaction::findByAccountId($accountId, 100);
        } else {
            $transactions = Transaction::findByUserId($userId, 100);
        }

        $this->view('transactions/history', [
            'transactions' => $transactions,
            'accountId' => $accountId,
        ]);
    }

    /**
     * View transaction details
     */
    public function view()
    {
        AuthMiddleware::requireAuth();

        $transactionId = $_GET['id'] ?? null;
        $transaction = Transaction::findById($transactionId);

        if (!$transaction || $transaction['user_id'] !== AuthMiddleware::getUserId()) {
            http_response_code(403);
            exit;
        }

        $this->view('transactions/view', ['transaction' => $transaction]);
    }

    /**
     * Show transfer page
     */
    public function transfer()
    {
        AuthMiddleware::requireAuth();

        $userId = AuthMiddleware::getUserId();
        $accounts = Account::findByUserId($userId);

        $this->view('transactions/transfer', ['accounts' => $accounts]);
    }

    /**
     * Process transfer
     */
    public function processTransfer()
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        $validator = new ValidationHelper($_POST);
        $validator->required('from_account', 'From account is required')
                  ->required('to_account', 'To account is required')
                  ->required('amount', 'Amount is required')
                  ->numeric('amount', 'Amount must be numeric');

        if (!$validator->passes()) {
            $_SESSION['errors'] = $validator->errors();
            header('Location: /transactions/transfer');
            exit;
        }

        $userId = AuthMiddleware::getUserId();
        $fromAccountId = (int) $_POST['from_account'];
        $toAccountId = (int) $_POST['to_account'];
        $amount = (float) $_POST['amount'];

        $fromAccount = Account::findById($fromAccountId);
        $toAccount = Account::findById($toAccountId);

        // Validate accounts
        if (!$fromAccount || $fromAccount['user_id'] !== $userId) {
            http_response_code(403);
            exit;
        }

        if (!$toAccount) {
            $_SESSION['errors'] = ['to_account' => 'Recipient account not found.'];
            header('Location: /transactions/transfer');
            exit;
        }

        if ($amount > $fromAccount['available_balance']) {
            $_SESSION['errors'] = ['amount' => 'Insufficient funds.'];
            header('Location: /transactions/transfer');
            exit;
        }

        // Process transfer
        try {
            \App\Core\Database::beginTransaction();

            // Deduct from source
            Account::updateBalance($fromAccountId, $fromAccount['balance'] - $amount);

            // Add to destination
            Account::updateBalance($toAccountId, $toAccount['balance'] + $amount);

            // Create transaction records
            Transaction::create([
                'user_id' => $userId,
                'account_id' => $fromAccountId,
                'transaction_type' => 'transfer',
                'amount' => $amount,
                'description' => 'Transfer to ' . $toAccount['account_number'],
                'status' => 'completed',
                'balance_after' => $fromAccount['balance'] - $amount,
                'recipient_account_id' => $toAccountId,
                'reference_number' => 'TXN' . uniqid(),
            ]);

            \App\Core\Database::commit();

            $_SESSION['success'] = 'Transfer completed successfully!';
            header('Location: /dashboard');
        } catch (\Exception $e) {
            \App\Core\Database::rollback();
            $_SESSION['errors'] = ['form' => 'Transfer failed: ' . $e->getMessage()];
            header('Location: /transactions/transfer');
        }
        exit;
    }
}
