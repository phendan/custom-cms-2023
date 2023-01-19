<?php

class FormValidation {
    private array $formInput;
    private array $rules;
    private array $errors = [];

    public function __construct(array $formInput)
    {
        $this->formInput = $formInput;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function validate(): void
    {
        foreach ($this->rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);

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
        foreach ($fieldRules as $fieldRule) {
            $ruleSegments = explode(':', $fieldRule);
            $fieldRule = $ruleSegments[0];

            if (isset($ruleSegments[1])) {
                $satisfier = $ruleSegments[1];
            } else {
                $satisfier = null;
            }

            if (!method_exists(FormValidation::class, $fieldRule)) {
                continue;
            }

            try {
                $this->{$fieldRule}($field, $satisfier);
            } catch (Exception $e) {
                $this->errors[$field][] = $e->getMessage();
            }
        }
    }

    private function required(string $field) {
        if (!isset($this->formInput[$field]) || empty($this->formInput[$field])) {
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
            throw new Exception("");
        }
    }
}
