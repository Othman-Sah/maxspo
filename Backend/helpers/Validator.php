<?php
/**
 * Input Validation Class
 */

class Validator {
    private $errors = [];

    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email invalide';
            return false;
        }
        return true;
    }

    public function validatePhone($phone) {
        // Remove spaces and dashes
        $phone = preg_replace('/[\s\-]/', '', $phone);
        if (!preg_match('/^(\+212|0)[67]\d{8}$/', $phone)) {
            $this->errors['phone'] = 'Téléphone invalide';
            return false;
        }
        return true;
    }

    public function validatePassword($password) {
        if (strlen($password) < 8) {
            $this->errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères';
            return false;
        }
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $this->errors['password'] = 'Le mot de passe doit contenir des majuscules et des chiffres';
            return false;
        }
        return true;
    }

    public function validateRequired($field, $value) {
        if (empty($value)) {
            $this->errors[$field] = ucfirst($field) . ' est requis';
            return false;
        }
        return true;
    }

    public function validateNumber($field, $value) {
        if (!is_numeric($value)) {
            $this->errors[$field] = $field . ' doit être un nombre';
            return false;
        }
        return true;
    }

    public function validateDate($field, $value) {
        $d = DateTime::createFromFormat('Y-m-d', $value);
        if (!$d || $d->format('Y-m-d') !== $value) {
            $this->errors[$field] = 'Date invalide';
            return false;
        }
        return true;
    }

    public function validateAge($age) {
        if (!is_numeric($age) || $age < 12 || $age > 120) {
            $this->errors['age'] = 'Âge invalide (12-120)';
            return false;
        }
        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return count($this->errors) > 0;
    }
}
?>
