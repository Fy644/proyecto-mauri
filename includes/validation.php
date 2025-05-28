<?php
// Validation helper functions

// Validate integer fields (int(11))
function validateInt($value, $field_name) {
    if (!is_numeric($value)) {
        return "El campo $field_name debe ser un número.";
    }
    $value = intval($value);
    if ($value > 2147483647 || $value < -2147483648) {
        return "El campo $field_name debe estar entre -2,147,483,648 y 2,147,483,647.";
    }
    return null;
}

// Validate varchar fields
function validateVarchar($value, $field_name, $max_length) {
    if (strlen($value) > $max_length) {
        return "El campo $field_name no puede tener más de $max_length caracteres.";
    }
    return null;
}

// Validate text fields
function validateText($value, $field_name) {
    if (strlen($value) > 65535) {
        return "El campo $field_name no puede tener más de 65,535 caracteres.";
    }
    return null;
}

// Validate phone numbers (bigint(20))
function validatePhone($value) {
    if (!is_numeric($value)) {
        return "El número de teléfono debe contener solo dígitos.";
    }
    if (strlen($value) !== 10) {
        return "El número de teléfono debe tener exactamente 10 dígitos.";
    }
    return null;
}

// Validate card numbers (bigint(20))
function validateCardNumber($value) {
    if (!is_numeric($value)) {
        return "El número de tarjeta debe contener solo dígitos.";
    }
    if (strlen($value) > 20) {
        return "El número de tarjeta no puede tener más de 20 dígitos.";
    }
    return null;
}

// Validate email
function validateEmail($value) {
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return "El correo electrónico no es válido.";
    }
    if (strlen($value) > 255) {
        return "El correo electrónico no puede tener más de 255 caracteres.";
    }
    return null;
}

// Validate date
function validateDate($value) {
    if (!strtotime($value)) {
        return "La fecha no es válida.";
    }
    return null;
}

// Validate datetime
function validateDateTime($value) {
    if (!strtotime($value)) {
        return "La fecha y hora no son válidas.";
    }
    return null;
}

// Validate boolean/tinyint
function validateBoolean($value) {
    if ($value !== '0' && $value !== '1' && $value !== 0 && $value !== 1) {
        return "El valor debe ser 0 o 1.";
    }
    return null;
}

// Validate RFC (13 characters)
function validateRFC($value) {
    if (strlen($value) > 13) {
        return "El RFC no puede tener más de 13 caracteres.";
    }
    return null;
}

// Validate PIN (4 digits)
function validatePIN($value) {
    if (!is_numeric($value) || strlen($value) !== 4) {
        return "El PIN debe ser un número de 4 dígitos.";
    }
    return null;
}

// Validate price (float)
function validatePrice($value) {
    if (!is_numeric($value)) {
        return "El precio debe ser un número.";
    }
    if ($value < 0) {
        return "El precio no puede ser negativo.";
    }
    return null;
}

// Validate months (int)
function validateMonths($value) {
    if (!is_numeric($value)) {
        return "El número de meses debe ser un número.";
    }
    $value = intval($value);
    if ($value < 0 || $value > 2147483647) {
        return "El número de meses debe estar entre 0 y 2,147,483,647.";
    }
    return null;
}
?> 