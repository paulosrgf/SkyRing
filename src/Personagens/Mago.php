<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\ConjuraHabilidadesInterface;

class Mago extends Personagem implements ConjuraHabilidadesInterface
{
    public function __construct(string $nome)
    {
        parent::__construct($nome, "Mago", 160, 26, 5, 140);
    }

    public function atacar(Personagem $oponente): string
    {
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) $dano = self::DANO_MINIMO;

        $oponente->receberDano($dano);
        return "🔮 {$this->nome} (Mago) lançou um projétil de energia mágica causando {$dano} de dano em {$oponente->getNome()}!";
    }

    public function defender(): string
    {
        $this->defesaAtual += 4;
        return "🔮 {$this->nome} (Mago) conjurou uma barreira mágica fina para se proteger!";
    }

    public function getMenuHabilidades(): array
    {
        return [
            1 => ['nome' => 'Explosão Arcana', 'custo' => 50, 'desc' => 'Dano massivo equivalente ao dobro do ataque base (ignora bônus de turno).'],
            2 => ['nome' => 'Bola de Fogo', 'custo' => 40, 'desc' => 'Causa 20 de dano inicial e incendeia o alvo (Burn) por 3 turnos.']
        ];
    }

    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string
    {
        if ($idHabilidade === 1) {
            $this->energia -= 50;
            $danoEspecial = $this->ataqueBase * 2; 
            $oponente->receberDano($danoEspecial);

            return "⚡ {$this->nome} conjurou uma [Explosão Arcana] avassaladora! O céu estremeceu e causou {$danoEspecial} de dano em {$oponente->getNome()}!";
        }

        if ($idHabilidade === 2) {
            $this->energia -= 40;
            $danoInicial = 20;
            $oponente->receberDano($danoInicial);
            // Aplica Burn (Queimadura) por 3 turnos
            $oponente->aplicarStatus('burn', 3);

            return "🔥 {$this->nome} lançou uma [Bola de Fogo] rugiente! Causou {$danoInicial} de dano de impacto e INCENDIOU {$oponente->getNome()} por 3 turnos!";
        }

        return "❌ Habilidade desconhecida.";
    }
}