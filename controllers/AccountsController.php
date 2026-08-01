<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Middleware\Auth;

class AccountsController extends Controller
{
    public function __construct()
    {
        Auth::require();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $accountModel = new Account();
        $accounts = $accountModel->findByUserId((int)$userId);

        $this->view('accounts/index', [
            'accounts' => $accounts
        ]);
    }

    public function show()
    {
        $userId = $_SESSION['user_id'];
        $accountId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $accountModel = new Account();
        $account = $accountModel->find((int)$accountId);

        if (!$account || (int)$account['user_id'] !== (int)$userId) {
            http_response_code(404);
            echo 'Account not found or access denied';
            exit;
        }

        $transactionModel = new Transaction();
        $transactions = $transactionModel->getByAccountId((int)$accountId, 50);

        $this->view('accounts/show', [
            'account' => $account,
            'transactions' => $transactions
        ]);
    }
}
