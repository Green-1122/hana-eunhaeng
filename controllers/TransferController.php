<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Account;
use App\Helpers\Csrf;
use App\Middleware\Auth;
use App\Services\TransferService;

class TransferController extends Controller
{
    public function __construct()
    {
        Auth::require();
    }

    public function createForm()
    {
        $userId = $_SESSION['user_id'];
        $accountModel = new Account();
        $accounts = $accountModel->findByUserId((int)$userId);

        $this->view('transfers/create', [
            'accounts' => $accounts,
            'csrf' => Csrf::token(),
        ]);
    }

    public function create()
    {
        $userId = $_SESSION['user_id'];
        $token = $_POST['_csrf'] ?? '';
        if (!Csrf::validate($token)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            exit;
        }

        $from = isset($_POST['from_account']) ? (int)$_POST['from_account'] : 0;
        $to = isset($_POST['to_account']) ? (int)$_POST['to_account'] : 0;
        $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.0;
        $description = trim($_POST['description'] ?? 'Transfer');

        // basic validation
        if ($from <= 0 || $to <= 0 || $amount <= 0) {
            $_SESSION['flash_error'] = 'Invalid transfer parameters';
            $this->redirect('/transfers/create');
        }

        $transferService = new TransferService();
        try {
            $transferService->transfer($from, $to, $amount, $description, (int)$userId);
            $_SESSION['flash_success'] = 'Transfer completed successfully';
            $this->redirect('/accounts');
        } catch (\Exception $e) {
            // In production, log $e->getMessage()
            $_SESSION['flash_error'] = 'Transfer failed: ' . $e->getMessage();
            $this->redirect('/transfers/create');
        }
    }
}
