<?php
/**
 * Card Controller
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Card;
use App\Models\Account;
use App\Middleware\AuthMiddleware;
use App\Helpers\SecurityHelper;

class CardController extends Controller
{
    /**
     * List user cards
     */
    public function list()
    {
        AuthMiddleware::requireAuth();

        $userId = AuthMiddleware::getUserId();
        $cards = Card::findByUserId($userId);

        $this->view('cards/list', ['cards' => $cards]);
    }

    /**
     * View card details
     */
    public function view()
    {
        AuthMiddleware::requireAuth();

        $cardId = $_GET['id'] ?? null;
        $card = Card::findById($cardId);

        if (!$card || $card['user_id'] !== AuthMiddleware::getUserId()) {
            http_response_code(403);
            exit;
        }

        $this->view('cards/view', ['card' => $card]);
    }

    /**
     * Show card request form
     */
    public function request()
    {
        AuthMiddleware::requireAuth();
        $userId = AuthMiddleware::getUserId();
        $accounts = Account::findByUserId($userId);

        $this->view('cards/request', ['accounts' => $accounts]);
    }

    /**
     * Process card request
     */
    public function requestSubmit()
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        $userId = AuthMiddleware::getUserId();
        $accountId = $_POST['account_id'] ?? null;
        $cardType = $_POST['card_type'] ?? 'debit';

        $account = Account::findById($accountId);
        if (!$account || $account['user_id'] !== $userId) {
            http_response_code(403);
            exit;
        }

        // Generate card number (for testing only - in production use payment processor)
        $cardNumber = '4' . str_pad(rand(0, 999999999999), 15, '0', STR_PAD_LEFT);
        $expiryMonth = date('m');
        $expiryYear = date('Y') + 5;

        $data = [
            'user_id' => $userId,
            'account_id' => $accountId,
            'card_number' => $cardNumber,
            'card_type' => $cardType,
            'card_brand' => 'visa',
            'holder_name' => $_POST['holder_name'] ?? '',
            'expiry_month' => $expiryMonth,
            'expiry_year' => $expiryYear,
            'status' => 'active',
            'daily_limit' => 1000,
            'issued_date' => date('Y-m-d'),
        ];

        $cardId = Card::create($data);

        if ($cardId) {
            $_SESSION['success'] = 'Card requested successfully! Your card will arrive in 7-10 business days.';
            header('Location: /cards/list');
        } else {
            $_SESSION['errors'] = ['form' => 'Failed to request card.'];
            header('Location: /cards/request');
        }
        exit;
    }

    /**
     * Lock card
     */
    public function lock()
    {
        AuthMiddleware::requireAuth();

        $cardId = $_POST['card_id'] ?? null;
        $card = Card::findById($cardId);

        if (!$card || $card['user_id'] !== AuthMiddleware::getUserId()) {
            http_response_code(403);
            exit;
        }

        if (Card::lock($cardId)) {
            $this->json(['success' => true, 'message' => 'Card locked successfully']);
        } else {
            http_response_code(500);
            $this->json(['success' => false, 'message' => 'Failed to lock card'], 500);
        }
    }

    /**
     * Unlock card
     */
    public function unlock()
    {
        AuthMiddleware::requireAuth();

        $cardId = $_POST['card_id'] ?? null;
        $card = Card::findById($cardId);

        if (!$card || $card['user_id'] !== AuthMiddleware::getUserId()) {
            http_response_code(403);
            exit;
        }

        if (Card::unlock($cardId)) {
            $this->json(['success' => true, 'message' => 'Card unlocked successfully']);
        } else {
            http_response_code(500);
            $this->json(['success' => false, 'message' => 'Failed to unlock card'], 500);
        }
    }
}
