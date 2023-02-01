<?php

namespace App\Models;

use Exception;

class FileValidation {
    private array $inputFiles;
    private array $rules;
    private array $errors = [];
    private $allowedTypes = [
        'image' => [
            'jpg' => IMAGETYPE_JPEG,
            'jpeg' => IMAGETYPE_JPEG,
            'png' => IMAGETYPE_PNG
        ]
    ];

    public function __construct(array $files)
    {
        $this->inputFiles = $files;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validate(): void
    {
        foreach ($this->rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);

            if (!in_array('required', $fieldRules) && !$this->fieldExists($field)) {
                continue;
            }

            $this->validateField($field, $fieldRules);
        }
    }

    private function validateField(string $field, array $fieldRules): void
    {
        foreach ($fieldRules as $fieldRule) {
            $ruleSegments = explode(':', $fieldRule);

            $fieldRule = $ruleSegments[0];
            $satisfier = $ruleSegments[1] ?? null;

            if (!method_exists(FileValidation::class, $fieldRule)) {
                continue;
            }

            try {
                $this->{$fieldRule}($field, $satisfier);
            } catch (Exception $e) {
                $this->errors[$field][] = $e->getMessage();
            }
        }
    }

    private function fieldExists($field) {
        return isset($this->inputFiles[$field]) && $this->inputFiles[$field]['size'] > 0;
    }

    private function required($field): void
    {
        if (!$this->fieldExists($field)) {
            throw new Exception("The {$field} field must not be empty.");
        }
    }

    private function type($field, $satisfier): void
    {
        $allowedExtensions = array_keys($this->allowedTypes[$satisfier]);
        $extension = strtolower(pathinfo($this->inputFiles[$field]['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("The {$field} field must be of type {$satisfier}");
        }

        if ($satisfier === 'image') {
            $currentLocation = $this->inputFiles[$field]['tmp_name'];
            $detectedMimeType = exif_imagetype($currentLocation);
            $allowedMimeType = $this->allowedTypes[$satisfier][$extension];

            if ($detectedMimeType !== $allowedMimeType) {
                throw new Exception("The {$field} field must be of type {$satisfier}");
            }
        }
    }

    private function maxsize($field, $satisfier): void
    {
        if ($this->inputFiles[$field]['size'] > (int) $satisfier) {
            throw new Exception("The {$field} must not exceed {$satisfier} bytes.");
        }
    }
}
