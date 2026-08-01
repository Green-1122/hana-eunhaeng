<?php
namespace App\Core;

class Controller
{
    protected function view(string $path, array $data = [])
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $path . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("View $viewFile not found");
        }
        include __DIR__ . '/../views/layouts/main.php';
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url);
        exit;
    }
}
