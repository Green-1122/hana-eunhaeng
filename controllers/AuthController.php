<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\Csrf;

class AuthController extends Controller
{
    public function loginForm()
    {
        $this->view('auth/login', [
            'csrf' => Csrf::token(),
        ]);
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $token = $_POST['_csrf'] ?? '';

        if (!Csrf::validate($token)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            exit;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            // In production, add rate limiting + logging
            $_SESSION['flash_error'] = 'Invalid credentials';
            $this->redirect('/login');
        }

        // Auth success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];

        $this->redirect('/');
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}
