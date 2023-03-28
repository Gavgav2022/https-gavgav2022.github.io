<?php

    $data = json_decode(file_get_contents('php://input'), true);

    $adminEmail = 'smctest2023@gmail.com';
    $errors = [];
    $message = '';
    $subject = 'Feedback form from site Smart-Clean';


    foreach ((array) $data as $field => $value) {
        if (!$value) {
            $errors[$field] = "Поле $field обов'язкове для заповнення";
        } else {
            $message .= sprintf( '%s: %s, ', $field, $value);
        }
    }

    $headers = [
        'MIME-Version: 1.0',
        "Content-Type: text/html; charset=utf-8",
        'To: '. 'Admin <' . $adminEmail . '>',
        'From: ' . '<' . $data['email'] . '>',
    ];

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['errors' => $errors]);
    } else {
        try {
            mail(
                $adminEmail,
                $subject,
                $message,
                implode('\r\n', $headers)
            );
        } catch (\Throwable $exception) {
            throw new \Exception('Error!');
        }
        http_response_code(200);
        echo json_encode($data);
    }

