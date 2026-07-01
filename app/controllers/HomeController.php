<?php
/**
 * Home Controller - Landing page
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;

class HomeController extends Controller
{
    /**
     * Show home page
     */
    public function index()
    {
        // If user is logged in, redirect to dashboard
        if (AuthMiddleware::isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }

        $this->view('home/index');
    }
}
