<?php
/**
 * Base Controller Class
 */

namespace App\Core;

class Controller
{
    protected array $data = [];

    /**
     * Render view
     */
    protected function view(string $view, array $data = []): void
    {
        $this->data = $data;
        
        // Extract data into variables
        extract($this->data);
        
        // Build file path
        $file = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($file)) {
            http_response_code(404);
            echo "View not found: $view";
            return;
        }
        
        // Start output buffering
        ob_start();
        require $file;
        $content = ob_get_clean();
        
        // Load layout
        $layoutFile = __DIR__ . '/../views/layout.php';
        require $layoutFile;
    }

    /**
     * Return JSON response
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
    protected function redirect(string $path): void
    {
        header("Location: $path");
        exit;
    }
}
