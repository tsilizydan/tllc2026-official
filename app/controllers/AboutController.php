<?php
/**
 * TSILIZY LLC — About Controller
 */

namespace App\Controllers;

use Core\Controller;

class AboutController extends Controller
{
    /**
     * Display about page
     */
    public function index(): void
    {
        $this->view('public/about', [
            'page_title' => 'À Propos'
        ]);
    }
}
