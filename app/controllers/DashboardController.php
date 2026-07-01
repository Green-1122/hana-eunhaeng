<?php
/**
 * Dashboard Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Middleware\AuthMiddleware;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index()
    {
        AuthMiddleware::requireAuth();

        $userId = AuthMiddleware::getUserId();
        $user = User::findById($userId);
        $accounts = Account::findByUserId($userId);
        $recentTransactions = Transaction::findByUserId($userId, 10);

        // Calculate totals
        $totalBalance = array_sum(array_column($accounts, 'balance'));
        $totalSpent = 0;
        foreach ($recentTransactions as $transaction) {
            if ($transaction['transaction_type'] === 'withdrawal' || $transaction['transaction_type'] === 'transfer') {
                $totalSpent += $transaction['amount'];
            }
        }

        $data = [
            'user' => $user,
            'accounts' => $accounts,
            'recentTransactions' => $recentTransactions,
            'totalBalance' => $totalBalance,
            'totalSpent' => $totalSpent,
            'accountCount' => count($accounts),
        ];

        $this->view('dashboard/index', $data);
    }

    /**
     * Show profile
     */
    public function profile()
    {
        AuthMiddleware::requireAuth();

        $userId = AuthMiddleware::getUserId();
        $user = User::findById($userId);

        $this->view('dashboard/profile', ['user' => $user]);
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        $userId = AuthMiddleware::getUserId();
        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'state' => $_POST['state'] ?? '',
            'zip_code' => $_POST['zip_code'] ?? '',
        ];

        User::updateUser($userId, $data);

        $_SESSION['success'] = 'Profile updated successfully!';
        header('Location: /dashboard/profile');
        exit;
    }

    /**
     * Show settings
     */
    public function settings()
    {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::getUserId();
        $user = User::findById($userId);

        $this->view('dashboard/settings', ['user' => $user]);
    }
}
