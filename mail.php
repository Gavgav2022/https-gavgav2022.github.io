<?php

    $data = json_decode(file_get_contents('php://input'), true);

    $errors = [];

    foreach ((array) $data as $field => $value) {
        if (!$value) {
            $errors[$field] = "Поле $field обов'язкове для заповнення";
        }
    }

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['errors' => $errors]);
    } else {
        http_response_code(200);
        echo json_encode($data);
    }

