<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\ConjuraHabilidadesInterface;

class Necromante extends Personagem implements ConjuraHabilidadesInterface
{
    public function __construct(string $nome)
    {
        parent::__construct($nome, "Necromante", 180, 22, 8, 150);
    }

    public function atacar(Personagem $oponente): string
    {
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) $dano = self::DANO_MINIMO;

        $oponente->receberDano($dano);
        $this->curar(4); // Drena um pouco de vida no básico
        return "💀 {$this->nome} usou [Toque Sombrio]! Causou {$dano} de dano em {$oponente->getNome()} e sugou 4 de HP para si!";
    }

    public function defender(): string
    {
        $this->defesaAtual += 6;
        return "💀 {$this->nome} se envolveu em uma névoa de almas penadas para mitigar danos.";
    }

    public function getMenuHabilidades(): array
    {
        return [
            1 => ['nome' => 'Rito do Sangue', 'custo' => 40, 'desc' => 'Causa 15 de dano direto e faz o alvo sangrar (Bleed) por 3 turnos.'],
            2 => ['nome' => 'Decomposição Macabra', 'custo' => 55, 'desc' => 'Dano moderado (25) e corrói a armadura, reduzindo a defesa base do alvo em 4 pontos permanentemente.']
        ];
    }

    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string
    {
        if ($idHabilidade === 1) {
            $this->energia -= 40;
            $danoDireto = 15;
            $oponente->receberDano($danoDireto);
            $oponente->aplicarStatus('bleed', 3);
            return "🩸 {$this->nome} realizou um [Rito do Sangue]! Cortou o ar causando {$danoDireto} de dano e deixou {$oponente->getNome()} sofrendo de BLEED por 3 turnos!";
        }

        if ($idHabilidade === 2) {
            $this->energia -= 55;
            $dano = 25;
            $oponente->receberDano($dano);
            $oponente->defesaBase = max(0, $oponente->defesaBase - 4);
            $oponente->defesaAtual = max(0, $oponente->defesaAtual - 4);
            
            return "🪰 {$this->nome} conjurou [Decomposição Macabra]! Nuvens de moscas da peste causaram {$dano} de dano e apodreceram a armadura de {$oponente->getNome()}, reduzindo sua defesa permanentemente!";
        }

        return "❌ Habilidade desconhecida.";
    }
}