<?php declare(strict_types = 1);

namespace App\Booking\Validation; 

trait FormValidatorTrait
{
    abstract public static function getValidationRules(): array;

    /**
     * Validate a given form field
     *
     * @return bool
     */ 
    private static function validateField(array $field, mixed $requestParam, array &$errors): bool
    {
        try {
            if ($field['required'] && empty($requestParam)) {
                throw new \Exception('This field is required.');
            }

            $value = $requestParam;

            if (!empty($field['type'])) {
                switch ($field['type']) {
                    case 'integer':
                        if (!filter_var((int)$value, FILTER_VALIDATE_INT)) {
                            throw new \Exception('This field must be an integer.');
                        }
                        break;
                    case 'number':
                        if (!filter_var((int)$value, FILTER_VALIDATE_INT) && !filter_var((float)$value, FILTER_VALIDATE_FLOAT)) {
                            throw new \Exception('This field must be an integer or float.');
                        }
                        break;
                    case 'float':
                        if (!filter_var((float)$value, FILTER_VALIDATE_FLOAT)) {
                            throw new \Exception('This field must be a float.');
                        }
                        break;
                    case 'dateString':
                        if (date('Y-m-d', strtotime(trim($value))) !== $value) {
                            throw new \Exception('This field must be a valid date.');
                        }
                        if (!empty($field['mustBeInFuture'])) {
                            $format = 'Y-m-d';
                            $now = date_create_from_format($format, date($format));
                            $date = date_create_from_format($format, trim($value));
                            if ($date < $now) {
                                throw new \Exception('The date must be in the future.');
                            }
                        }
                        break;
                    // case 'timeString':
                    //     $format = 'H:i';
                    //     try {
                    //         $activityDate = date_create_from_format($format, trim($value));
                    //     } catch (\Exception $e) {
                    //         throw new \Exception('This field must be a valid time.');
                    //     }
                    //     break;
                    // case 'email':
                    //     if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    //         throw new \Exception("Email '$value' is invalid.");
                    //     }
                    //    break;
                }
            }

            if (!empty($field['maxLength']) && strlen(trim($value)) > $field['maxLength']) {
                throw new \Exception('Up to ' . $field['maxLength'] . ' characters allowed.');
            }

            if (!empty($field['setLength']) && strlen(trim($value)) !== $field['setLength']) {
                throw new \Exception('Must be ' . $field['setLength'] . ' characters long.');
            }

            // if (!empty($field['mustMatch']) && !empty($_POST[$field['mustMatch']]) && trim($value) !== trim($_POST[$field['mustMatch']])) {
            //     throw new \Exception('Passwords must match.');
            // }

            return true;
        } catch(\Exception $e) {
            if (!empty($field['error']) && !empty($field['errorMsg'])) {
                $errors[$field['error']] = $field['errorMsg'];
            } else if (!empty($field['name'])) {
                $errors[$field['name']] = $e->getMessage();
            }
            return false;
        }
    }

    /**
     * Validate form fields.
     * If the argument is null, all fields are required.
     *
     * @return bool
     */ 
    public static function validateForm(array $requestParams, ?array $fieldsToValidate = null): array|true
    {
        // print_r($requestParams);
        // die;
        $rules = self::getValidationRules();
        $isValid = true;
        $errors = [];

        if ($fieldsToValidate === null) {
            foreach ($rules as $field) {
                // echo $requestParams[$field['name']] . '<br>';
                // print_r($field);
        // die;
                if (!self::validateField($field, $requestParams[$field['name']], $errors)) {
                    $isValid = false;
                }
            }

            return $isValid ? $isValid : $errors;
        }

        foreach ($fieldsToValidate as $field) {
            if (!empty($field)) {
                if (!self::validateField($rules[$field], $requestParams[$field['name']], $errors)) {
                    $isValid = false;
                }
            }
        }

        return $isValid ? $isValid : $errors;
    }
}