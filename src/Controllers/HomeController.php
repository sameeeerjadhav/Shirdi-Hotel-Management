<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller {
    public function index() {
        return $this->view('home/index', [
            'title' => 'CHNMS - Centralized Hotel Network Management System'
        ]);
    }

    public function registerHotelForm() {
        return $this->view('home/register_hotel', [
            'title' => 'Register Your Hotel - CHNMS'
        ]);
    }

    public function registerHotelSubmit() {
        // Here we would normally validate and insert into the `hotels` and `users` tables, 
        // handle file uploads (e.g. trade license, GST cert), and create the Hotel Admin user.
        // For demonstration, we'll just mock the success and redirect.

        $_SESSION['success'] = "Hotel registration submitted successfully. Please wait for Super Admin approval.";
        $this->redirect('/');
    }
}
