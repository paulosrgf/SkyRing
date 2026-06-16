<?php

namespace SkyRing\Contratos;

use SkyRing\Personagens\Personagem;

interface ConjuraHabilidadesInterface 
{
    public function getMenuHabilidades(): array;

    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string;
}