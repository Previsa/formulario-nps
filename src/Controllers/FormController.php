<?php

namespace Src\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Src\Models\NPSData;
use Src\Utils\Nps;
use Ramsey\Uuid\Uuid;

class FormController
{
    public function submit()
{
    // Captura o UUID enviado pelo formulário (via campo oculto) ou pela URL
    $uuid = $_POST['uuid'] ?? $_GET['uuid'] ?? null;

    // Verifica se o UUID foi capturado corretamente
    if (empty($uuid)) {
        echo "UUID inválido: não pode ser nulo ou vazio.";
        return;
    }

    // Instancia NPSData
    $npsData = new NPSData();

    // Captura as respostas do formulário
    $escala1 = filter_input(INPUT_POST, 'escala1', FILTER_SANITIZE_STRING) ?? null;
    $escala2 = filter_input(INPUT_POST, 'escala2', FILTER_SANITIZE_STRING) ?? null;
    $escala3 = filter_input(INPUT_POST, 'escala3', FILTER_SANITIZE_STRING) ?? null;
    $escala4 = filter_input(INPUT_POST, 'escala4', FILTER_SANITIZE_STRING) ?? null;
    $escala5 = filter_input(INPUT_POST, 'escala5', FILTER_SANITIZE_STRING) ?? null;
    $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING) ?? null;

    // Atualiza o JSON com as respostas do formulário
    $npsData->updateStoredUUID($uuid, [
        'escala1' => $escala1,
        'escala2' => $escala2,
        'escala3' => $escala3,
        'escala4' => $escala4,
        'escala5' => $escala5,
        'motivo' => $motivo
    ]);

    // Armazena as respostas na planilha
    $npsData->saveResponse([
        'uuid' => $uuid,
        'email' => $this->getEmailFromUuid($uuid),
        'escala1' => $escala1,
        'escala2' => $escala2,
        'escala3' => $escala3,
        'escala4' => $escala4,
        'escala5' => $escala5,
        'motivo' => $motivo
    ]);

    // Calcular e exibir o NPS
    $this->calculateAndDisplayNps();

    $this->renderSuccess();
}

    private function calculateAndDisplayNps()
    {
        $npsData = new NPSData();
        $responses = $npsData->getResponses();

        // Combina todas as escalas em um único array para calcular o NPS
        $allScores = array_merge(...array_map(function($response) {
            return [
                $response['escala1'] ?? null,
                $response['escala2'] ?? null,
                $response['escala3'] ?? null,
                $response['escala4'] ?? null,
                $response['escala5'] ?? null
            ];
        }, $responses));

        $nps = new Nps();
        $npsScore = $nps->calculateNps($allScores);
        $npsClassification = $nps->classifyResponse($npsScore);
    }

    public function scheduleEmails()
{
    $startDate = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING) ?? null;
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING) ?? null;

    if ($action === 'Agendar Disparos') {
        $this->scheduleEmailJobs($startDate);
        echo "Disparos de e-mails agendados a partir de $startDate.";
    } elseif ($action === 'Interromper Disparos') {
        $this->cancelEmailJobs();
        echo "Disparos de e-mails interrompidos.";
    }
}


    private function scheduleEmailJobs($startDate)
{
    $emailsConfig = include(__DIR__ . '/../../config/emails.php');
    $emails = $emailsConfig['emails'];

    $startTime = strtotime($startDate);
    if (time() < $startTime) {
        sleep($startTime - time());  // Aguarda até a hora de início
    }

    foreach ($emails as $index => $email) {
        $this->sendEmailTo($email);
        error_log("E-mail enviado para $email. (Email $index de " . count($emails) . ")");

        // Verifica se é o último e-mail
        if ($index < count($emails) - 1) {
            sleep(10);  // Aguarda 20 segundos antes de enviar o próximo e-mail
        } else {
            error_log("Todos os e-mails foram enviados.");
        }
    }
}


    private function sendEmailTo($email)
    {
        $config = include(__DIR__ . '/../../config.php');
    
        // Gera um UUID para o email (gerado uma única vez)
        $uuid = Uuid::uuid4()->toString();
    
        // Armazena o UUID e o email na planilha e no JSON
        $this->storeUuidEmailMapping($uuid, $email);
    
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $config['mail']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['mail']['username'];
            $mail->Password = $config['mail']['password'];
            $mail->SMTPSecure = $config['mail']['encryption'] ?? 'tls';
            $mail->Port = $config['mail']['port'];
            $mail->CharSet = 'UTF-8'; // Garantindo que o charset seja UTF-8
    
            $mail->setFrom($config['mail']['username'], 'NPS Form');
            $mail->addAddress($email);
    
            $bccEmail = 'virginiaalcantara@previsa.com.br';
            $mail->addBCC($bccEmail);
    
            $mail->isHTML(true);
            $mail->Subject = 'Sua opinião importa! Participe da nossa pesquisa de satisfação';
    
            // Adiciona o UUID como parâmetro na URL
            $mail->Body = '
            <p>Olá, Cliente!</p>
            <p>Esperamos que você esteja bem!</p>
            <p>Queremos te convidar para participar de uma rápida pesquisa de satisfação. Prometemos que vai levar apenas alguns minutos. E, claro, a sua opinião é fundamental para que possamos continuar evoluindo e adaptando nossos serviços às suas necessidades.</p>
            <p>Não se preocupe, é tudo bem tranquilo e direto ao ponto!</p>
            <p>Basta clicar no link abaixo e compartilhar suas impressões com a gente.</p>
            <p><a href="https://www.previsacontabilidade.com.br/formulario-nps/?uuid=' . urlencode($uuid) . '">LINK DA PESQUISA</a></p>
            <p>Desde já, agradecemos por sua ajuda!</p>
            <p>Abraços,</p>
            <p><img src="https://raw.githubusercontent.com/Previsa/images-novero/main/Thiago-Vitor%201.png" alt="Assinatura" style="width:300px;"></p>
        ';
    
            if($mail->send()) {
                error_log("E-mail enviado com sucesso para $email com UUID $uuid.");
            } else {
                error_log("Falha ao enviar e-mail para $email. Erro: {$mail->ErrorInfo}");
            }
        } catch (Exception $e) {
            error_log("Message could not be sent to $email. Mailer Error: {$mail->ErrorInfo}");
        }
    }
    
    private function storeUuidEmailMapping($uuid, $email)
{
    $npsData = new NPSData();

    // Definindo o fuso horário para America/Sao_Paulo
    date_default_timezone_set('America/Sao_Paulo');

    // Cria o array $data com apenas o UUID e o email no armazenamento inicial
    $data = [
        'uuid' => $uuid,
        'email' => $email,
    ];

    // Salva o mapeamento na planilha e no JSON
    $npsData->saveUuidMapping($uuid, $email);
    $npsData->storeUUID($uuid, $email, $data); 
}

    
    private function getEmailFromUuid($uuid)
    {
        $npsData = new NPSData();
        return $npsData->getEmailByUuid($uuid); // Chama o método getEmailByUuid no NPSData
    }

    private function isJobCanceled($email, $file)
    {
        if (file_exists($file)) {
            $handle = fopen($file, 'r');
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($data[0] === $email && $data[2] === 'canceled') {
                    fclose($handle);
                    return true;
                }
            }
            fclose($handle);
        }
        return false;
    }

    private function cancelEmailJobs()
    {
        $file = __DIR__ . '/../../email_jobs.csv';
        $updatedJobs = [];

        if (file_exists($file)) {
            $handle = fopen($file, 'r');
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($data[2] === 'active') { // Verifica se o status é 'active'
                    $data[2] = 'canceled'; // Muda o status para 'canceled'
                }
                $updatedJobs[] = $data;
            }
            fclose($handle);

            // Escreve as alterações de volta ao arquivo
            $handle = fopen($file, 'w');
            foreach ($updatedJobs as $job) {
                fputcsv($handle, $job);
            }
            fclose($handle);

            echo "Todos os agendamentos de e-mails foram cancelados.";
        } else {
            echo "Nenhum agendamento de e-mail encontrado.";
        }
    }

    private function saveToFile($data)
    {
        $file = 'data.csv';
        $handle = fopen($file, 'a');
        fputcsv($handle, $data);
        fclose($handle);
    }

    private function renderSuccess()
    {
        include __DIR__ . '/../Views/success.php';
    }

    public function renderForm()
    {
        // Captura o UUID da URL, se existir
        $uuid = $_GET['uuid'] ?? '';

        // Passa o UUID como parâmetro para o formulário
        include __DIR__ . '/../Views/form_closed.php';
    }

    public function showConfigurationForm()
    {
        include __DIR__ . '/../Views/configuration.php';
    }
}
