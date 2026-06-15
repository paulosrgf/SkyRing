<?php

namespace SkyRing\Personagens;

abstract class Personagem 
{
    // Constantes obrigatórias pelo requisito técnico nº 7
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

    // Métodos abstratos que o Polimorfismo exige que cada classe filha implemente do seu jeito
    abstract public function atacar(Personagem $oponente): string;
    abstract public function defender(): string;
    abstract public function iniciarTurno(): void; // Útil para regenerar mana e resetar defesa

    // Getters e Setters encapsulados
    public function getNome(): string { return $this->nome; }
    public function getTipo(): string { return $this->tipo; }
    public function getVida(): int { return $this->vida; }
    public function getEnergia(): int { return $this->energia; }
    
    public function estaVivo(): bool { return $this->vida > 0; }

    public function receberDano(int $dano): void 
    {
        $this->vida -= $dano;
        if ($this->vida < 0) {
            $this->vida = 0;
        }
    }
}