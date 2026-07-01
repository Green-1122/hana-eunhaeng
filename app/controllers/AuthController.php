<?php
/**
 * Auth Controller - User authentication and registration
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\SecurityHelper;
use App\Helpers\ValidationHelper;
use App\Middleware\AuthMiddleware;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function login()
    {
        AuthMiddleware::requireGuest();
        $this->view('auth/login');
    }

    /**
     * Handle login submission
     */
    public function loginSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // Verify CSRF token
        if (!SecurityHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            exit;
        }

        // Validate input
        $validator = new ValidationHelper($_POST);
        $validator->required('email', 'Email is required')
                  ->email('email', 'Please enter a valid email')
                  ->required('password', 'Password is required');

        if (!$validator->passes()) {
            $_SESSION['errors'] = $validator->errors();
            header('Location: /auth/login');
            exit;
        }

        $email = SecurityHelper::sanitize($_POST['email']);
        $password = $_POST['password'];

        $user = User::findByEmail($email);

        if (!$user || !SecurityHelper::verifyPassword($password, $user['password_hash'])) {
            $_SESSION['errors'] = ['email' => 'Invalid email or password'];
            header('Location: /auth/login');
            exit;
        }

        if ($user['status'] !== 'active') {
            $_SESSION['errors'] = ['email' => 'Account is not active'];
            header('Location: /auth/login');
            exit;
        }

        // Set session
        AuthMiddleware::setUser($user);
        User::query("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$user['id']]);

        $_SESSION['success'] = 'Welcome back, ' . $user['first_name'] . '!';
        header('Location: /dashboard');
        exit;
    }

    /**
     * Show registration page
     */
    public function register()
    {
        AuthMiddleware::requireGuest();
        $this->view('auth/register');
    }

    /**
     * Handle registration submission
     */
    public function registerSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // Verify CSRF token
        if (!SecurityHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            exit;
        }

        // Validate input
        $validator = new ValidationHelper($_POST);
        $validator->required('first_name', 'First name is required')
                  ->required('last_name', 'Last name is required')
                  ->required('email', 'Email is required')
                  ->email('email', 'Please enter a valid email')
                  ->unique('email', 'users', 'email', 'Email already registered')
                  ->required('password', 'Password is required')
                  ->minLength('password', 8, 'Password must be at least 8 characters')
                  ->required('phone', 'Phone number is required');

        if (!$validator->passes()) {
            $_SESSION['errors'] = $validator->errors();
            header('Location: /auth/register');
            exit;
        }

        $data = [
            'first_name' => SecurityHelper::sanitize($_POST['first_name']),
            'last_name' => SecurityHelper::sanitize($_POST['last_name']),
            'email' => SecurityHelper::sanitize($_POST['email']),
            'phone' => SecurityHelper::sanitize($_POST['phone']),
            'password_hash' => SecurityHelper::hashPassword($_POST['password']),
            'status' => 'pending',
        ];

        $userId = User::create($data);

        if ($userId) {
            $_SESSION['success'] = 'Registration successful! Please verify your email.';
            header('Location: /auth/login');
        } else {
            $_SESSION['errors'] = ['form' => 'Registration failed. Please try again.'];
            header('Location: /auth/register');
        }
        exit;
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        AuthMiddleware::logout();
        $_SESSION['success'] = 'You have been logged out successfully.';
        header('Location: /');
        exit;
    }

    /**
     * Show forgot password page
     */
    public function forgotPassword()
    {
        AuthMiddleware::requireGuest();
        $this->view('auth/forgot-password');
    }

    /**
     * Handle forgot password submission
     */
    public function forgotPasswordSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        $validator = new ValidationHelper($_POST);
        $validator->required('email', 'Email is required')
                  ->email('email', 'Please enter a valid email');

        if (!$validator->passes()) {
            $_SESSION['errors'] = $validator->errors();
            header('Location: /auth/forgot-password');
            exit;
        }

        $email = SecurityHelper::sanitize($_POST['email']);
        $user = User::findByEmail($email);

        if ($user) {
            // Generate reset token
            $resetToken = SecurityHelper::generateToken();
            // TODO: Save token to database with expiration
            // TODO: Send email with reset link
        }

        $_SESSION['success'] = 'If an account exists with that email, you will receive a password reset link.';
        header('Location: /auth/login');
        exit;
    }
}
