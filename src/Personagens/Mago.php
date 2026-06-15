<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\HabilidadeEspecialInterface;

class Mago extends Personagem implements HabilidadeEspecialInterface
{
    private const CUSTO_ESPECIAL = 50;
    private const BONUS_DEFESA_TURNO = 4;

    public function __construct(string $nome)
    {
        // Balanceamento: Pouca Vida (80), Defesa Baixa (4), Ataque Alto (25),Muita Energia (100)
        parent::__construct($nome, "Mago", 80, 25, 4, 100);
    }

    public function iniciarTurno(): void
    {
        $this->defesaAtual = $this->defesaBase;
        // Mago regenera mais mana por turno que o guerreiro
        $this->energia = min($this->energiaMax, $this->energia + 15);
    }

    public function atacar(Personagem $oponente): string
    {
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) {
            $dano = self::DANO_MINIMO;
        }

        $oponente->receberDano($dano);

        return "{$this->nome} (Mago) lançou um projétil de energia mágica causando {$dano} de dano em {$oponente->getNome()}!";
    }

    public function defender(): string
    {
        $this->defesaAtual += self::BONUS_DEFESA_TURNO;
        return "{$this->nome} (Mago) conjurou uma barreira mágica fina para se proteger!";
    }

    // Cumprindo o contrato da Interface
    public function usarHabilidadeEspecial(Personagem $oponente): string
    {
        $danoEspecial = $this->ataqueBase * 2; // Dano massivo dobrado
        
        $this->energia -= self::CUSTO_ESPECIAL;
        $oponente->receberDano($danoEspecial);

        return "⚡ {$this->nome} conjurou uma [Explosão Arcana] avassaladora! O céu estremeceu e causou {$danoEspecial} de dano em {$oponente->getNome()}!";
    }

    public function getCustoEnergia(): int
    {
        return self::CUSTO_ESPECIAL;
    }
}