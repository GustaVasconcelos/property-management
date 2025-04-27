<?php

namespace App\Helpers;

use Exception;

class ImageUploadHelper
{
    private $uploadDirectory;

    public function __construct(string $uploadDirectory = 'uploads/properties/')
    {
        $this->uploadDirectory = $uploadDirectory;

        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0777, true);
        }
    }

    public function uploadImage(array $file, string $oldImagePath = null): string
    {
        if ($oldImagePath && file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro no envio da imagem.");
        }

        $fileName = $this->generateFileName($file['name']);
        $destination = $this->uploadDirectory . $fileName;

        if (!getimagesize($file['tmp_name'])) {
            throw new Exception("O arquivo enviado não é uma imagem válida.");
        }

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Erro ao mover o arquivo para o diretório de destino.");
        }

        return $destination; 
    }

    private function generateFileName(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid('property_', true) . '.' . $extension;
    }
}
