<?php

namespace Src\Models;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Ramsey\Uuid\Uuid;
use Src\Utils\Nps;

class NPSData
{
    private $file;
    private $uuidFile;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../data/nps_response.xlsx'; // Caminho da planilha Excel
        $this->uuidFile = __DIR__ . '/../../data/uuids.json'; // Arquivo JSON para rastrear UUIDs

        // Verifica se o arquivo de UUIDs existe; caso contrário, cria-o
        if (!file_exists($this->uuidFile)) {
            file_put_contents($this->uuidFile, json_encode([]));
        }

        // Verifica se o arquivo da planilha existe; caso contrário, cria a planilha com os cabeçalhos
        if (!file_exists($this->file)) {
            $this->createSpreadsheet();
        } else {
            // Verifica se as tabelas necessárias existem; caso contrário, cria
            $this->checkAndCreateNPSCountTable();
            $this->checkAndCreateNPSSummaryTable();
            $this->checkAndCreateUUIDEmailMappingTable();
        }
    }

    private function createSpreadsheet()
    {
        $spreadsheet = new Spreadsheet();

        // Remove a aba padrão "Worksheet"
        $spreadsheet->removeSheetByIndex(0);

        // Cria a aba NPSCount
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('NPSCount');

        // Definindo os cabeçalhos da tabela NPSCount
        $sheet->setCellValue('A1', 'UUID');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Indicação');
        $sheet->setCellValue('D1', 'Contábil');
        $sheet->setCellValue('E1', 'Fiscal');
        $sheet->setCellValue('F1', 'Pessoal');
        $sheet->setCellValue('G1', 'Gerente de Sucesso');
        $sheet->setCellValue('H1', 'Motivo das notas');
        $sheet->setCellValue('I1', 'Data e Hora da resposta');

        // Salvando a planilha com a tabela NPSCount
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->file);

        // Cria as tabelas necessárias
        $this->checkAndCreateNPSSummaryTable();
        $this->checkAndCreateUUIDEmailMappingTable();
    }

    private function checkAndCreateHeaders($sheet)
    {
        $headers = [
            'A1' => 'UUID',
            'B1' => 'Email',
            'C1' => 'Indicação',
            'D1' => 'Contábil',
            'E1' => 'Fiscal',
            'F1' => 'Pessoal',
            'G1' => 'Gerente de Sucesso',
            'H1' => 'Motivo das notas',
            'I1' => 'Data e Hora da resposta'
        ];

        foreach ($headers as $cell => $header) {
            if ($sheet->getCell($cell)->getValue() !== $header) {
                $sheet->setCellValue($cell, $header);
            }
        }
    }

    private function checkAndCreateNPSCountTable()
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->file);

        // Verifica se a aba NPSCount já existe
        $sheet = $spreadsheet->getSheetByName('NPSCount');
        if (!$sheet) {
            // Cria a nova aba NPSCount
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('NPSCount');

            // Define os cabeçalhos da tabela NPSCount
            $sheet->setCellValue('A1', 'UUID');
            $sheet->setCellValue('B1', 'Email');
            $sheet->setCellValue('C1', 'Indicação');
            $sheet->setCellValue('D1', 'Contábil');
            $sheet->setCellValue('E1', 'Fiscal');
            $sheet->setCellValue('F1', 'Pessoal');
            $sheet->setCellValue('G1', 'Gerente de Sucesso');
            $sheet->setCellValue('H1', 'Motivo das notas');
            $sheet->setCellValue('I1', 'Data e Hora da resposta');

            // Salva as alterações na planilha
            $writer = new Xlsx($spreadsheet);
            $writer->save($this->file);
        }
    }

    private function checkAndCreateNPSSummaryTable()
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->file);

        // Verifica se a aba NPSSummary já existe
        $sheet = $spreadsheet->getSheetByName('NPSSummary');
        if (!$sheet) {
            // Cria a nova aba NPSSummary
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('NPSSummary');

            // Define os cabeçalhos da tabela NPSSummary
            $sheet->setCellValue('A1', 'NPS Total');
            $sheet->setCellValue('B1', 'Zona de Classificação');
            $sheet->setCellValue('C1', 'Promotores');
            $sheet->setCellValue('D1', 'Detratores');
            $sheet->setCellValue('E1', 'Neutros');

            // Salva as alterações na planilha
            $writer = new Xlsx($spreadsheet);
            $writer->save($this->file);
        }
    }

    private function checkAndCreateUUIDEmailMappingTable()
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->file);

        // Verifica se a aba UUIDEmailMapping já existe
        $sheet = $spreadsheet->getSheetByName('UUIDEmailMapping');
        if (!$sheet) {
            // Cria a nova aba UUIDEmailMapping
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('UUIDEmailMapping');

            // Define os cabeçalhos da tabela UUIDEmailMapping
            $sheet->setCellValue('A1', 'UUID');
            $sheet->setCellValue('B1', 'Email');
            $sheet->setCellValue('C1', 'Data e Hora do Envio');

            // Salva as alterações na planilha
            $writer = new Xlsx($spreadsheet);
            $writer->save($this->file);
        }
    }

    public function getStoredUUIDs()
    {
        $content = file_get_contents($this->uuidFile);
        return json_decode($content, true);
    }

    public function storeUUID($uuid, $email, $data)
{
    // Carrega os dados atuais do arquivo JSON
    $uuids = $this->getStoredUUIDs();

    // Certifique-se de que o UUID não seja nulo ou vazio
    if (!empty($uuid)) {
        // Armazena os dados sob a chave do UUID
        $uuids[$uuid] = [
            'email' => $email,
            'data' => []
        ];
    } else {
        error_log("UUID inválido: não pode ser nulo ou vazio.");
    }

    // Salva de volta no arquivo JSON
    file_put_contents($this->uuidFile, json_encode($uuids, JSON_PRETTY_PRINT));
}


    public function saveResponse($data)
{
    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load($this->file);
    $sheet = $spreadsheet->getSheetByName('NPSCount');

    // Verifica se os cabeçalhos existem e os cria se necessário
    $this->checkAndCreateHeaders($sheet);

    // Usa o UUID fornecido em vez de gerar um novo
    $uuid = $data['uuid'];


    // Armazena o UUID e email no arquivo JSON
    $this->storeUUID($uuid, $data['email'], $data);

    // Encontra a próxima linha vazia
    $row = $sheet->getHighestRow() + 1;

    // Armazena os dados na planilha NPSCount
    $sheet->setCellValue("A{$row}", $uuid);
    $sheet->setCellValue("B{$row}", $data['email']);
    $sheet->setCellValue("C{$row}", $data['escala1']);
    $sheet->setCellValue("D{$row}", $data['escala2']);
    $sheet->setCellValue("E{$row}", $data['escala3']);
    $sheet->setCellValue("F{$row}", $data['escala4']);
    $sheet->setCellValue("G{$row}", $data['escala5']);
    $sheet->setCellValue("H{$row}", $data['motivo']);
    $sheet->setCellValue("I{$row}", date('Y-m-d H:i:s'));

    // Salva as alterações na planilha
    $writer = new Xlsx($spreadsheet);
    $writer->save($this->file);

    // Atualiza a tabela NPSSummary
    $this->updateNPSSummaryTable();
}

    public function saveUuidMapping($uuid, $email)
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->file);
        $sheet = $spreadsheet->getSheetByName('UUIDEmailMapping');

        // Encontra a próxima linha vazia na tabela UUIDEmailMapping
        $row = $sheet->getHighestRow() + 1;

        // Armazena o UUID, email e a data/hora de envio na tabela UUIDEmailMapping
        $sheet->setCellValue("A{$row}", $uuid);
        $sheet->setCellValue("B{$row}", $email);
        $sheet->setCellValue("C{$row}", date('Y-m-d H:i:s')); // Armazena a data e hora de envio

        // Salva as alterações na planilha
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->file);
    }

    public function updateStoredUUID($uuid, $data)
{
    $uuids = $this->getStoredUUIDs();

    if (isset($uuids[$uuid])) {
        // Atualiza o campo 'data' com as respostas do formulário
        $uuids[$uuid]['data'] = $data;

        file_put_contents($this->uuidFile, json_encode($uuids, JSON_PRETTY_PRINT));
    }
}


    private function updateNPSSummaryTable()
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->file);
        $sheet = $spreadsheet->getSheetByName('NPSSummary');

        // Calcular NPS, quantidade de promotores, detratores e neutros
        $responses = $this->getResponses();
        $promoters = count(array_filter($responses, fn($score) => $score >= 9));
        $detractors = count(array_filter($responses, fn($score) => $score <= 6));
        $neutrals = count($responses) - ($promoters + $detractors);
        $npsTotal = (($promoters - $detractors) / count($responses)) * 100;

        $nps = new Nps();
        $npsClassification = $nps->classifyResponse($npsTotal);

        // Armazena os dados na tabela NPSSummary
        $sheet->setCellValue("A2", $npsTotal);
        $sheet->setCellValue("B2", $npsClassification);
        $sheet->setCellValue("C2", $promoters);
        $sheet->setCellValue("D2", $detractors);
        $sheet->setCellValue("E2", $neutrals);

        // Salva as alterações na planilha
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->file);
    }

    public function getResponses()
    {
        // Carrega a planilha existente
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->file);
        $sheet = $spreadsheet->getSheetByName('NPSCount');

        $responses = [];
        $rows = $sheet->toArray();

        // Ignora a primeira linha (cabeçalhos) e coleta os dados
        for ($i = 1; $i < count($rows); $i++) {
            $responses[] = array_slice($rows[$i], 2, 5); // Pega as colunas de C a G
        }

        return $responses;
    }

    public function getEmailByUuid($uuid)
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($this->file);
        $sheet = $spreadsheet->getSheetByName('UUIDEmailMapping');
        $rows = $sheet->toArray();

        foreach ($rows as $row) {
            if ($row[0] === $uuid) {
                return $row[1]; // Retorna o email correspondente ao UUID
            }
        }

        return null; // Retorna null se o UUID não for encontrado
    }
}
