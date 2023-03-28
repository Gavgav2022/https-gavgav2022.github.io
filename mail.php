<?php

    $data = json_decode(file_get_contents('php://input'), true);

    $adminEmail = 'dimagysev9595@gmail.com';
    $errors = [];
    $message = '';
    $subject = 'Feedback form from site';


    foreach ((array) $data as $field => $value) {
        if (!$value) {
            $errors[$field] = "Поле $field обов'язкове для заповнення";
        } else {
            $message .= sprintf('<p><b>%s:</b> %s</p>', $field, $value);
        }
    }

    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
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