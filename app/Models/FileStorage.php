<?php

namespace App\Models;

use Exception;
use App\Helpers\Str;

class FileStorage {
    private array $file;
    private string $extension;
    private string $currentLocation;
    private string $generatedName;

    public function __construct(array $file)
    {
        $this->file = $file;
        $this->extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $this->currentLocation = $this->file['tmp_name'];
        $this->generatedName = Str::token() . '.' . $this->extension;
    }

    public function getGeneratedName(): string
    {
        return $this->generatedName;
    }

    public function saveIn(string $folder): void
    {
        $destination = "{$folder}/{$this->generatedName}";

        if (!move_uploaded_file($this->currentLocation, $destination)) {
            throw new Exception('We encountered an error uploading the file.');
        }
    }

    public static function delete(string $path): bool
    {
        return unlink(ltrim($path, DIRECTORY_SEPARATOR));
    }
}
