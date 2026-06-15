<?php

namespace SkyRing\Personagens;

abstract class Personagem 
{
    protected const DANO_MINIMO = 0;
    
    protected string $nome;
    protected string $tipo;
    protected int $vidaMax;
    protected int $vida;
    protected int $ataqueBase;
    protected int $defesaBase;
    protected int $defesaAtual;
    protected int $energiaMax;
    protected int $energia;

    // SISTEMA DE INVENTÁRIO (Requisito novo)
    protected array $inventario = [
        'pocao_vida' => 2,
        'pocao_mana' => 1
    ];

    // SISTEMA DE STATUS EXPANDIDO (Burn, Bleed, Poison)
    protected array $statusEfeitos = [];

    public function __construct(string $nome, string $tipo, int $vida, int $ataque, int $defesa, int $energia) 
    {
        $this->nome = $nome;
        $this->tipo = $tipo;
        $this->vidaMax = $vida;
        $this->vida = $vida;
        $this->ataqueBase = $ataque;
        $this->defesaBase = $defesa;
        $this->defesaAtual = $defesa;
        $this->energiaMax = $energia;
        $this->energia = $energia;
    }

    abstract public function atacar(Personagem $oponente): string;
    abstract public function defender(): string;
    
    // Atualizado para processar os 3 efeitos de status no início do turno
    public function iniciarTurno(): string
    {
        $this->defesaAtual = $this->defesaBase;
        $logEfeitos = "";

        // 1. 🔥 BURNING (Dano fixo moderado)
        if (!empty($this->statusEfeitos['burn'])) {
            $dano = 10;
            $this->receberDano($dano);
            $this->statusEfeitos['burn']--;
            $logEfeitos .= "🔥 {$this->nome} está queimando! Sofreu {$dano} de dano. ({$this->statusEfeitos['burn']} turnos restantes)\n";
        }

        // 2. 🩸 BLEEDING (Dano escala com a vida atual - sangramento severo)
        if (!empty($this->statusEfeitos['bleed'])) {
            $dano = 15;
            $this->receberDano($dano);
            $this->statusEfeitos['bleed']--;
            $logEfeitos .= "🩸 {$this->nome} está sangrando! Sofreu {$dano} de dano. ({$this->statusEfeitos['bleed']} turnos restantes)\n";
        }

        // 3. 🧪 POISON (Dano progressivo ou constante leve, mas dura muito)
        if (!empty($this->statusEfeitos['poison'])) {
            $dano = 7;
            $this->receberDano($dano);
            $this->statusEfeitos['poison']--;
            $logEfeitos .= "🧪 {$this->nome} está envenenado! Sofreu {$dano} de dano. ({$this->statusEfeitos['poison']} turnos restantes)\n";
        }

        return $logEfeitos;
    }

    // Ações de Inventário
    public function getInventario(): array { return $this->inventario; }

    public function usarItem(string $item): string
    {
        if (($this->inventario[$item] ?? 0) <= 0) {
            return "❌ Você não tem mais esse item no inventário!";
        }

        $this->inventario[$item]--;

        if ($item === 'pocao_vida') {
            $cura = (int)($this->vidaMax * 0.35); // Cura 35% da vida máxima
            $this->curar($cura);
            return "🧪 {$this->nome} tomou uma Poção de Vida e recuperou {$cura} de HP!";
        }

        if ($item === 'pocao_mana') {
            if ($this->energiaMax === 0) {
                return "💨 {$this->nome} usou uma poção de mana, mas não possui estamina/mana para recuperar!";
            }
            $recupera = 50;
            $this->energia = min($this->energiaMax, $this->energia + $recupera);
            return "🧪 {$this->nome} tomou uma Poção de Mana e recuperou {$recupera} de Energia!";
        }

        return "Item usado.";
    }

    public function aplicarStatus(string $efeito, int $duracao): void { $this->statusEfeitos[$efeito] = $duracao; }
    public function getNome(): string { return $this->nome; }
    public function getTipo(): string { return $this->tipo; }
    public function getVida(): int { return $this->vida; }
    public function getVidaMax(): int { return $this->vidaMax; }
    public function getEnergia(): int { return $this->energia; }
    public function getEnergiaMax(): int { return $this->energiaMax; }
    public function estaVivo(): bool { return $this->vida > 0; }

    public function receberDano(int $dano): void 
    {
        $this->vida -= $dano;
        if ($this->vida < 0) $this->vida = 0;
    }

    public function curar(int $quantidade): void
    {
        $this->vida = min($this->vidaMax, $this->vida + $quantidade);
    }
}