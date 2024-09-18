<?php

namespace Src\Utils;

class Nps
{
    /**
     * Calcula o NPS (Net Promoter Score) com base nos valores fornecidos.
     *
     * @param array $scores - Array de notas recebidas (entre 0 e 10).
     * @return float - O valor do NPS calculado.
     */
    public function calculateNps($scores)
    {
        $total_responses = count($scores);
        if ($total_responses == 0) {
            return 0;
        }

        // Contagem dos Promoters (nota 9 ou 10)
        $promoters = count(array_filter($scores, function ($score) {
            return $score >= 9;
        }));

        // Contagem dos Detractors (nota 0 a 6)
        $detractors = count(array_filter($scores, function ($score) {
            return $score <= 6;
        }));

        // Cálculo do NPS
        $nps_score = (($promoters - $detractors) / $total_responses) * 100;

        return $nps_score;
    }

    /**
     * Classifica o NPS em uma das zonas de qualidade.
     *
     * @param float $nps_score - O valor do NPS calculado.
     * @return string - A classificação correspondente ao NPS.
     */
    public function classifyResponse($nps_score)
    {
        if ($nps_score >= 91) {
            return "Zona de Encantamento";
        } elseif ($nps_score >= 76) {
            return "Zona de Excelência";
        } elseif ($nps_score >= 51) {
            return "Zona de Qualidade";
        } elseif ($nps_score >= 1) {
            return "Zona de Aperfeiçoamento";
        } else {
            return "Zona Crítica";
        }
    }
}
