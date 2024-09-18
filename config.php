<?php

require 'vendor/autoload.php';

// Verifica se o arquivo .env existe antes de tentar carregá-lo
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Verifica se a função env já foi definida para evitar redefinição
if (!function_exists('env')) {
    function env($key, $default = null) {
        return isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }
}

return [
    'mail' => [
        'host' => env('MAIL_HOST'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'port' => env('MAIL_PORT'),
    ],
];
