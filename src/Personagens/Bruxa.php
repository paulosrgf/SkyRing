<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\ConjuraHabilidadesInterface;

class Bruxa extends Personagem implements ConjuraHabilidadesInterface
{
    public function __construct(string $nome)
    {
        parent::__construct($nome, "Bruxa", 190, 24, 6, 110);
    }

    public function atacar(Personagem $oponente): string
    {
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) $dano = self::DANO_MINIMO;

        $oponente->receberDano($dano);
        return "🔮 {$this->nome} lançou uma rajada de energia sombria causando {$dano} de dano em {$oponente->getNome()}!";
    }

    public function defender(): string
    {
        $this->defesaAtual += 5;
        return "🔮 {$this->nome} flutuou brevemente envolta em morcegos ilusórios para se defender.";
    }

    public function getMenuHabilidades(): array
    {
        return [
            1 => ['nome' => 'Névoa Venenosa', 'custo' => 35, 'desc' => 'Dano inicial leve (10) e aplica envenenamento longo (Poison) por 4 turnos.'],
            2 => ['nome' => 'Praga de Sangue', 'custo' => 50, 'desc' => 'Causa dano destrutivo massivo ignorando metade da defesa do oponente.']
        ];
    }

    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string
    {
        if ($idHabilidade === 1) {
            $this->energia -= 35;
            $danoInicial = 10;
            $oponente->receberDano($danoInicial);
            $oponente->aplicarStatus('poison', 4);
            return "🧪 {$this->nome} conjurou [Névoa Venenosa]! Soprou um gás tóxico causando {$danoInicial} de dano e aplicando POISON por 4 turnos em {$oponente->getNome()}!";
        }

        if ($idHabilidade === 2) {
            $this->energia -= 50;
            $dano = (int)($this->ataqueBase * 1.8) - (int)($oponente->defesaAtual / 2);
            if ($dano < self::DANO_MINIMO) $dano = self::DANO_MINIMO;
            $oponente->receberDano($dano);
            return "💥 {$this->nome} conjurou [Praga de Sangue]! Uma explosão de runas vermelhas estraçalhou a guarda de {$oponente->getNome()} causando {$dano} de dano!";
        }

        return "❌ Habilidade desconhecida.";
    }
}