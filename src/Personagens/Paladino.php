<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\ConjuraHabilidadesInterface;

class Paladino extends Personagem implements ConjuraHabilidadesInterface
{
    public function __construct(string $nome)
    {
        // Buff no Ataque (24) e mantida a Defesa/Vida excelentes
        parent::__construct($nome, "Paladino", 260, 24, 14, 80);
    }

    public function atacar(Personagem $oponente): string
    {
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) $dano = self::DANO_MINIMO;

        $oponente->receberDano($dano);
        return "⚔️ {$this->nome} golpeou vigorosamente com seu Martelo da Luz causando {$dano} de dano em {$oponente->getNome()}!";
    }

    public function defender(): string
    {
        $this->defesaAtual += 10;
        return "🛡️ {$this->nome} ajoelhou-se em prece atrás do escudo, aumentando drasticamente sua defesa!";
    }

    public function getMenuHabilidades(): array
    {
        return [
            1 => ['nome' => 'Luz Sagrada', 'custo' => 45, 'desc' => 'Invoca milagre que cura 60 de HP do próprio Paladino.'],
            2 => ['nome' => 'Julgamento Retributivo', 'custo' => 50, 'desc' => 'A mecânica da virada! Causa 15 de dano base + 35% de toda a vida que o Paladino perdeu na partida.']
        ];
    }

    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string
    {
        if ($idHabilidade === 1) {
            $this->energia -= 45;
            $cura = 60;
            $this->curar($cura);
            return "✨ {$this->nome} canalizou a [Luz Sagrada]! Um feixe dourado desceu dos céus recuperando {$cura} de HP!";
        }

        if ($idHabilidade === 2) {
            $this->energia -= 50;
            $vidaPerdida = $this->vidaMax - $this->vida;
            // Buff pesado: 35% do HP perdido vira dano puro
            $danoBonus = (int)($vidaPerdida * 0.35); 
            $danoTotal = 15 + $danoBonus;
            
            $oponente->receberDano($danoTotal);
            return "⚖️ {$this->nome} decretou [Julgamento Retributivo]! Descarregou a fúria de suas feridas causando {$danoTotal} de dano em {$oponente->getNome()}!";
        }

        return "❌ Habilidade desconhecida.";
    }
}