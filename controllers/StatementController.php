<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Statement;
use App\Models\Account;
use App\Helpers\Csrf;
use App\Middleware\Auth;
use App\Services\PdfService;
use App\Services\MailService;
use App\Services\JobQueue;

class StatementController extends Controller
{
    public function __construct()
    {
        Auth::require();
    }

    public function index()
    {
        $userId = (int)$_SESSION['user_id'];
        $accountModel = new Account();
        $accounts = $accountModel->findByUserId($userId);

        $this->view('statements/index', [
            'accounts' => $accounts,
            'csrf' => Csrf::token(),
        ]);
    }

    public function generate()
    {
        $userId = (int)$_SESSION['user_id'];
        $token = $_POST['_csrf'] ?? '';
        if (!Csrf::validate($token)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            exit;
        }

        $accountId = isset($_POST['account_id']) ? (int)$_POST['account_id'] : 0;
        $from = $_POST['from_date'] ?? null;
        $to = $_POST['to_date'] ?? null;
        $email = isset($_POST['email_me']) ? true : false;

        if ($accountId <= 0 || !$from || !$to) {
            $_SESSION['flash_error'] = 'Please select account and date range';
            $this->redirect('/statements');
        }

        $statementModel = new Statement();
        $recordId = $statementModel->createRecord($userId, $accountId, $from, $to);

        // Create a job for background processing (DB-backed)
        $jobPayload = [
            'type' => 'generate_statement',
            'statement_id' => $recordId,
            'email' => $email
        ];

        $queue = new JobQueue();
        $queue->push($jobPayload);

        $_SESSION['flash_success'] = 'Statement generation queued. You will be notified when ready.';
        $this->redirect('/statements');
    }

    public function download()
    {
        $userId = (int)($_SESSION['user_id'] ?? 0);
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $token = $_GET['t'] ?? '';

        if ($id <= 0 || !$token) {
            http_response_code(400);
            echo 'Invalid request';
            exit;
        }

        $statementModel = new Statement();
        $stmt = $statementModel->findById($id);
        if (!$stmt) {
            http_response_code(404);
            echo 'Not found';
            exit;
        }

        // Verify ownership
        if ((int)$stmt['user_id'] !== $userId) {
            http_response_code(403);
            echo 'Access denied';
            exit;
        }

        // Validate signed token and expiry
        $expected = hash_hmac('sha256', $id . '|' . $stmt['expires_at'], getenv('APP_KEY') ?: '');
        if (!hash_equals($expected, $token) || strtotime($stmt['expires_at']) < time()) {
            http_response_code(403);
            echo 'Link expired or invalid';
            exit;
        }

        $path = __DIR__ . '/../../public/uploads/statements/' . $stmt['file_path'];
        if (!file_exists($path)) {
            http_response_code(404);
            echo 'File missing';
            exit;
        }

        // Stream file securely
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="statement-' . $id . '.pdf"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    // Endpoint to immediately email (not used when queued)
    public function email()
    {
        $userId = (int)$_SESSION['user_id'];
        $token = $_POST['_csrf'] ?? '';
        if (!Csrf::validate($token)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            exit;
        }

        $statementId = isset($_POST['statement_id']) ? (int)$_POST['statement_id'] : 0;
        if ($statementId <= 0) {
            $_SESSION['flash_error'] = 'Invalid statement';
            $this->redirect('/statements');
        }

        $statementModel = new Statement();
        $stmt = $statementModel->findById($statementId);
        if (!$stmt || (int)$stmt['user_id'] !== $userId) {
            $_SESSION['flash_error'] = 'Statement not found or access denied';
            $this->redirect('/statements');
        }

        $mail = new MailService();
        $mail->sendStatementEmail($userId, $stmt);

        $_SESSION['flash_success'] = 'Email sent';
        $this->redirect('/statements');
    }
}
