<?php

namespace App\Requests;

use Exception;

class CreatePropertyRequest
{
    private $data;
    private $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validated(): array
    {
        $this->validate();
        if (!empty($this->errors)) {
            $_SESSION['errors'] = $this->errors;
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
        return $this->data;
    }

    private function validate(): void
    {
        if (empty($this->data['name'])) {
            $this->errors[] = 'O campo nome é obrigatório.';
        }

        if (empty($this->data['address'])) {
            $this->errors[] = 'O campo endereço é obrigatório.';
        }

        if (empty($this->data['image'])) {
            $this->errors[] = 'A imagem é obrigatória.';
        }

        if (isset($this->data['image']) && !in_array(pathinfo($this->data['image']['name'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
            $this->errors[] = 'A imagem deve ser nos formatos jpg, jpeg ou png.';
        }
    }
}
