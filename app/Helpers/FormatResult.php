<?php

namespace App\Helpers;

class FormatResult
{
    public function response(bool $success, $data = null, string $message = '', int $statusCode = 200)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];

        if (!$success) {
            $statusCode = $statusCode ?? 400;
        }

        if ($this->isApiRequest()) {
            return response()->json($response, $statusCode);
        }

        return [
            'status' => $success ? 'success' : 'error',
            'data' => $data,
            'message' => $message,
        ];
    }

    public function success($data = null, string $message = 'Operação realizada com sucesso', int $statusCode = 200)
    {
        return $this->response(true, $data, $message, $statusCode);
    }

    public function error(string $message = 'Erro ao processar a requisição', int $statusCode = 400)
    {
        return $this->response(false, null, $message, $statusCode);
    }

    private function isApiRequest()
    {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }

    public function view($view, $data = [])
    {
        extract($data); 
        ob_start();

        $viewPath = "/var/www/html/app/Views/{$view}.php"; 

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "View não encontrada: {$viewPath}";
        }

        return ob_get_clean();
    }
}
