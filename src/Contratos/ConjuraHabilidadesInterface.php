<?php

namespace SkyRing\Contratos;

use SkyRing\Personagens\Personagem;

interface ConjuraHabilidadesInterface 
{
    /**
     * Retorna um array com o menu de habilidades disponíveis desta classe.
     * Exemplo: [1 => ['nome' => 'Bola de Fogo', 'custo' => 30], 2 => ...]
     */
    public function getMenuHabilidades(): array;

    /**
     * Conjura uma habilidade específica baseada na escolha do jogador.
     * Deve retornar a string com a narrativa do que aconteceu para o terminal.
     */
    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string;
}