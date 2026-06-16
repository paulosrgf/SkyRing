<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\ConjuraHabilidadesInterface;

class Monge extends Personagem implements ConjuraHabilidadesInterface
{
    public function __construct(string $nome)
    {
        parent::__construct($nome, "Monge", 210, 21, 9, 90);
    }

    public function atacar(Personagem $oponente): string
    {
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) $dano = self::DANO_MINIMO;

        $oponente->receberDano($dano);
        return "👊 {$this->nome} desferiu uma Palma de Ferro certeira causando {$dano} de dano em {$oponente->getNome()}!";
    }

    public function defender(): string
    {
        $this->defesaAtual += 7;
        return "🧘 {$this->nome} entrou em postura de fluxo etéreo, desviando elegantemente de golpes.";
    }

    public function getMenuHabilidades(): array
    {
        return [
            1 => ['nome' => 'Combo dos Cem Punhos', 'custo' => 30, 'desc' => 'Sequência veloz que causa 30 de dano e queima 15 de energia do oponente.'],
            2 => ['nome' => 'Chute do Vento Espiritual', 'custo' => 40, 'desc' => 'Dano moderado (20) e remove IMEDIATAMENTE todos os efeitos negativos ativos no Monge.']
        ];
    }

    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string
    {
        if ($idHabilidade === 1) {
            $this->energia -= 30;
            $dano = 30;
            $oponente->receberDano($dano);
            
            // Queima de energia/mana do alvo
            $oponente->energia = max(0, $oponente->energia - 15);
            
            return "⚡ {$this->nome} canalizou o Chi no [Combo dos Cem Punhos]! Uma enxurrada de socos causou {$dano} de dano e drenou 15 de Energia de {$oponente->getNome()}!";
        }

        if ($idHabilidade === 2) {
            $this->energia -= 40;
            $dano = 20;
            $oponente->receberDano($dano);
            
            // Limpa os efeitos negativos limpando o array de status do Monge
            $this->statusEfeitos = [];
            
            return "🍃 {$this->nome} rotacionou no [Chute do Vento Espiritual]! Causou {$dano} de dano e a lufada de vento purificou seu corpo, removendo todos os efeitos de status negativos!";
        }

        return "❌ Habilidade desconhecida.";
    }
}