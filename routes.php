<?php

use Src\Controllers\FormController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryParams = [];
parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryParams);

// Instancia o controlador
$controller = new FormController();

// Gerenciamento de rotas
if ($uri === '/formulario-nps/' && isset($queryParams['uuid'])) {
    $controller->renderForm();
} elseif ($uri === '/formulario-nps/configure') {
    $controller->showConfigurationForm();
} elseif ($uri === '/formulario-nps/schedule_emails' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->scheduleEmails();
} elseif ($uri === '/formulario-nps/index.php' && isset($queryParams['uuid']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trata o envio do formulário e redireciona corretamente
    $controller->submit();
} elseif ($uri === '/formulario-nps/submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->submit();
} else {
    // Redireciona para 404 ou uma página padrão de erro
    http_response_code(404);
    echo "Página não encontrada.";
}
