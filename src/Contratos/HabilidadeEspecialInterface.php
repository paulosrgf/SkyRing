<?php

namespace SkyRing\Contratos;

use SkyRing\Personagens\Personagem;

interface HabilidadeEspecialInterface 
{
    /**
     * Executa a habilidade especial do personagem sobre o oponente.
     * Deve retornar uma string descrevendo o que aconteceu.
     */
    public function usarHabilidadeEspecial(Personagem $oponente): string;
    
    /**
     * Retorna o custo de energia/mana para usar a habilidade.
     */
    public function getCustoEnergia(): int;
}