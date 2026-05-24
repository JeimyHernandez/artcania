<?php
class Validation {
    private array $errors = [];

    public function check(array $data, array $rules): bool {
        $this->errors = [];
        foreach ($rules as $field => $ruleStr) {
            $value = $data[$field] ?? null;
            foreach (explode('|', $ruleStr) as $rule) {
                $this->applyRule($field, $value, $rule, $data);
            }
        }
        return empty($this->errors);
    }

    private function applyRule(string $field, $value, string $rule, array $data): void {
        [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
        $label = ucfirst($field);
        switch ($name) {
            case 'required':
                if (empty($value) && $value !== '0') $this->errors[$field][] = "$label es requerido.";
                break;
            case 'min':
                if (strlen((string)$value) < (int)$param) $this->errors[$field][] = "$label debe tener al menos $param caracteres.";
                break;
            case 'max':
                if (strlen((string)$value) > (int)$param) $this->errors[$field][] = "$label debe tener máximo $param caracteres.";
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) $this->errors[$field][] = "$label no es válido.";
                break;
            case 'same':
                if ($value !== ($data[$param] ?? null)) $this->errors[$field][] = "$label no coincide con $param.";
                break;
            case 'numeric':
                if (!is_numeric($value)) $this->errors[$field][] = "$label debe ser numérico.";
                break;
            case 'regex':
                if (!preg_match($param, (string)$value)) $this->errors[$field][] = "$label no tiene el formato correcto.";
                break;
            case 'in':
                $opts = explode(',', $param);
                if (!in_array($value, $opts)) $this->errors[$field][] = "$label no es una opción válida.";
                break;
        }
    }

    public function errors(): array { return $this->errors; }
    public function firstError(string $field): string { return $this->errors[$field][0] ?? ''; }
}
