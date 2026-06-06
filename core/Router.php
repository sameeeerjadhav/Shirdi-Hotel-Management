<?php

namespace Core;

class Router {
    private array $routes = [];

    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute($method, $uri, $action) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function dispatch($method, $uri) {
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                // Convert route URI to a regex pattern
                // Replace {param} with regex capture group
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route['uri']);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    // Extract parameters
                    $params = [];
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }

                    $action = $route['action'];

                    if (is_callable($action)) {
                        return call_user_func_array($action, $params);
                    }

                    if (is_array($action)) {
                        $controller = new $action[0]();
                        $method = $action[1];
                        return call_user_func_array([$controller, $method], $params);
                    }
                }
            }
        }

        $this->abort(404);
    }

    protected function abort($code = 404) {
        http_response_code($code);
        echo "404 Not Found";
        exit();
    }
}
