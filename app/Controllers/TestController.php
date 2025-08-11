<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestController extends Controller {
    
    public function index() {
        // This route should be protected by CuentaFilter
        echo "<h1>Test Controller</h1>";
        echo "<p>If you can see this, you have access to this account.</p>";
        echo "<p>Your cuenta_id is: " . session('session_data.cuenta_id') . "</p>";
        echo "<p>Your rol_id is: " . session('session_data.rol_id') . "</p>";
    }
    
    public function public() {
        // This route should be accessible without CuentaFilter
        echo "<h1>Public Test Page</h1>";
        echo "<p>This page is accessible without cuenta_id restrictions.</p>";
        if (session()->has('session_data')) {
            echo "<p>Your cuenta_id is: " . session('session_data.cuenta_id') . "</p>";
            echo "<p>Your rol_id is: " . session('session_data.rol_id') . "</p>";
        } else {
            echo "<p>You are not logged in.</p>";
        }
    }
}