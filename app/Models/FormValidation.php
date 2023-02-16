<?php

namespace App\Models;

use App\Models\Database;
use Exception;
use App\Helpers\Session;

class FormValidation {
    private array $rules;
    private array $errors = [];
    private array $messages = [];

    public function __construct(private array $formInput, private Database $db)
    {}

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    public function validate(): void
    {
        try {
            $this->csrfToken();
        } catch (Exception $e) {
            $this->errors['root'][] = $e->getMessage();
            return;
        }

        foreach ($this->rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);

            // If field doesn't exist, don't check it
            if (!in_array('required', $fieldRules) && !$this->fieldExists($field)) {
                continue;
            }

            $this->validateField($field, $fieldRules);
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateField(string $field, array $fieldRules)
    {
        // Sort required to the front
        usort($fieldRules, function ($firstRule, $secondRule) {
            if ($firstRule === 'required') {
                return -1;
            }

            return 1;
        });

        foreach ($fieldRules as $fieldRule) {
            $ruleSegments = explode(':', $fieldRule);
            $fieldRule = $ruleSegments[0];

            // if (isset($ruleSegments[1])) {
            //     $satisfier = $ruleSegments[1];
            // } else {
            //     $satisfier = null;
            // }

            // Ternary operator
            // $satisfier = isset($ruleSegments[1]) ? $ruleSegments[1] : null;

            // Null-coalescing operator
            $satisfier = $ruleSegments[1] ?? null;

            if (!method_exists(FormValidation::class, $fieldRule)) {
                continue;
            }

            try {
                $this->{$fieldRule}($field, $satisfier);
            } catch (Exception $e) {
                $message = $this->messages["{$field}.{$fieldRule}"] ?? $e->getMessage();
                $this->errors[$field][] = $message;

                if ($fieldRule === 'required') break;
            }
        }
    }

    private function fieldExists(string $field): bool
    {
        return isset($this->formInput[$field]) && !empty($this->formInput[$field]);
    }

    private function csrfToken()
    {
        if (!isset($this->formInput['csrfToken']) || Session::get('csrfToken') !== $this->formInput['csrfToken']) {
            throw new Exception("The form request could not be validated.");
        }
    }

    private function required(string $field) {
        if (!$this->fieldExists($field)) {
            throw new Exception("The {$field} field is required.");
        }
    }

    private function min(string $field, string $satisfier)
    {
        if (strlen($this->formInput[$field]) < (int) $satisfier) {
            throw new Exception("The {$field} must be at least {$satisfier} characters.");
        }
    }

    private function max(string $field, string $satisfier)
    {
        if (strlen($this->formInput[$field]) > (int) $satisfier) {
            throw new Exception("The {$field} must not be more than {$satisfier} characters.");
        }
    }

    private function email(string $field)
    {
        if (!filter_var($this->formInput[$field], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("The {$field} field must be a valid email address.");
        }
    }

    private function matches(string $field, string $satisfier)
    {
        if ($this->formInput[$field] !== $this->formInput[$satisfier]) {
            throw new Exception("The {$field} field must match the {$satisfier} field.");
        }
    }

    private function available(string $field, string $satisfier)
    {
        $sql = "SELECT 1 FROM `{$satisfier}` WHERE `{$field}` = :field";

        $this->db->query($sql, [ 'field' => $this->formInput[$field] ]);

        if ($this->db->count() > 0) {
            throw new Exception("The {$field} is already taken.");
        }
    }
}
