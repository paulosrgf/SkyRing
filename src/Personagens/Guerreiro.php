<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\HabilidadeEspecialInterface;

class Guerreiro extends Personagem implements HabilidadeEspecialInterface
{
    private const CUSTO_ESPECIAL = 40;
    private const BONUS_DEFESA_TURNO = 8;

    public function __construct(string $nome)
    {
        // Balanceamento: Muita Vida (120), Defesa Alta (10), Ataque Moderado (15), Energia Inicial (60)
        parent::__construct($nome, "Guerreiro", 120, 15, 10, 60);
    }

    public function iniciarTurno(): void
    {
        // Regra do enunciado: Resetar bônus de defesa temporário no início do turno
        $this->defesaAtual = $this->defesaBase;
        
        // Regeneração parcial de energia por turno
        $this->energia = min($this->energiaMax, $this->energia + 5);
    }

    public function atacar(Personagem $oponente): string
    {
        // Cálculo de dano: Ataque - Defesa do Alvo
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) {
            $dano = self::DANO_MINIMO;
        }

        $oponente->receberDano($dano);

        return "{$this->nome} (Guerreiro) avançou com sua espada e causou {$dano} de dano em {$oponente->getNome()}!";
    }

    public function defender(): string
    {
        // Aumenta temporariamente a defesa
        $this->defesaAtual += self::BONUS_DEFESA_TURNO;
        return "{$this->nome} (Guerreiro) ergueu seu escudo pesado, aumentando sua defesa para este turno!";
    }

    // Cumprindo o contrato da Interface
    public function usarHabilidadeEspecial(Personagem $oponente): string
    {
        // Causar dano elevado furando a defesa (Regra de negócio customizada)
        $danoEspecial = $this->ataqueBase + 15; 
        
        $this->energia -= self::CUSTO_ESPECIAL;
        $oponente->receberDano($danoEspecial);

        return "🔥 {$this->nome} usou [Golpe Devastador]! Ignorou a armadura e cravou {$danoEspecial} de dano em {$oponente->getNome()}!";
    }

    public function getCustoEnergia(): int
    {
        return self::CUSTO_ESPECIAL;
    }
}