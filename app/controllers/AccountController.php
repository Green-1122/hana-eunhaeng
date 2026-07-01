<?php
/**
 * Account Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Middleware\AuthMiddleware;

class AccountController extends Controller
{
    /**
     * List accounts
     */
    public function list()
    {
        AuthMiddleware::requireAuth();

        $userId = AuthMiddleware::getUserId();
        $accounts = Account::findByUserId($userId);

        $this->view('accounts/list', ['accounts' => $accounts]);
    }

    /**
     * View account details
     */
    public function view()
    {
        AuthMiddleware::requireAuth();

        $accountId = $_GET['id'] ?? null;
        if (!$accountId) {
            http_response_code(404);
            exit;
        }

        $account = Account::findById($accountId);
        if (!$account || $account['user_id'] !== AuthMiddleware::getUserId()) {
            http_response_code(403);
            exit;
        }

        $transactions = Transaction::findByAccountId($accountId, 50);

        $data = [
            'account' => $account,
            'transactions' => $transactions,
            'transactionCount' => count($transactions),
        ];

        $this->view('accounts/view', $data);
    }

    /**
     * Create new account
     */
    public function create()
    {
        AuthMiddleware::requireAuth();
        $this->view('accounts/create');
    }

    /**
     * Store new account
     */
    public function store()
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        $userId = AuthMiddleware::getUserId();
        $accountNumber = 'ACC' . strtoupper(uniqid());

        $data = [
            'user_id' => $userId,
            'account_number' => $accountNumber,
            'account_type' => $_POST['account_type'] ?? 'checking',
            'account_name' => $_POST['account_name'] ?? 'New Account',
            'balance' => 0,
            'available_balance' => 0,
            'status' => 'active',
            'opening_date' => date('Y-m-d'),
        ];

        $accountId = Account::create($data);

        if ($accountId) {
            $_SESSION['success'] = 'Account created successfully!';
            header('Location: /accounts/view?id=' . $accountId);
        } else {
            $_SESSION['errors'] = ['form' => 'Failed to create account.'];
            header('Location: /accounts/create');
        }
        exit;
    }
}
