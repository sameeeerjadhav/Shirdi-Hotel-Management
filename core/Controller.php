<?php

namespace Core;

class Controller {
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . '/../src/Views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            
            require __DIR__ . '/../src/Views/layout.php';
        } else {
            die("View $view not found.");
        }
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function redirect($url) {
        if (strpos($url, '/') === 0) {
            $url = BASE_URL . $url;
        }
        header("Location: $url");
        exit();
    }
}
