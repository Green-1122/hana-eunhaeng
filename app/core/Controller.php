<?php
/**
 * Base Controller Class
 * All controllers extend this class
 */

namespace App\Core;

abstract class Controller
{
    protected array $data = [];
    protected string $layout = 'app';

    /**
     * Render view
     */
    protected function render(string $view, array $data = []): void
    {
        $this->data = array_merge($this->data, $data);
        extract($this->data);

        // Start output buffering
        ob_start();
        include APP_PATH . "/views/$view.php";
        $content = ob_get_clean();

        // Render layout
        include APP_PATH . "/views/layouts/{$this->layout}.php";
    }

    /**
     * Send JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect
     */
    protected function redirect(string $url, array $params = []): void
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        header("Location: $url");
        exit;
    }

    /**
     * Set data for view
     */
    protected function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get authenticated user ID
     */
    protected function getUserId(): int|null
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get request parameter
     */
    protected function getParam(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * Get POST parameter
     */
    protected function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET parameter
     */
    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Abort with error
     */
    protected function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        $this->render('errors/' . $code, ['message' => $message]);
        exit;
    }

    /**
     * Flash message to session
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        
        if (!$token || !isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }
}