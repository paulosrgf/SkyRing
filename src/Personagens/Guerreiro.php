<?php

namespace SkyRing\Personagens;

use SkyRing\Contratos\ConjuraHabilidadesInterface;

class Guerreiro extends Personagem implements ConjuraHabilidadesInterface
{
    public function __construct(string $nome)
    {
        // Buff no Ataque (22) e mantida a Vida Alta (250)
        parent::__construct($nome, "Guerreiro", 250, 22, 11, 70);
    }

    public function atacar(Personagem $oponente): string
    {
        $dano = $this->ataqueBase - $oponente->defesaAtual;
        if ($dano < self::DANO_MINIMO) $dano = self::DANO_MINIMO;

        $oponente->receberDano($dano);
        return "⚔️ {$this->nome} (Guerreiro) avançou com sua espada e causou {$dano} de dano em {$oponente->getNome()}!";
    }

    public function defender(): string
    {
        $this->defesaAtual += 8;
        return "🛡️ {$this->nome} (Guerreiro) ergueu seu escudo pesado, aumentando sua defesa para este turno!";
    }

    public function getMenuHabilidades(): array
    {
        return [
            1 => ['nome' => 'Golpe Devastador', 'custo' => 35, 'desc' => 'Ataque brutal (Ataque + 20) que ignora completamente a armadura (defesa) do alvo.'],
            2 => ['nome' => 'Grito de Batalha', 'custo' => 25, 'desc' => 'Intimida o oponente causando sangramento (Bleed) por 2 turnos devido à pressão física.']
        ];
    }

    public function conjurarHabilidade(int $idHabilidade, Personagem $oponente): string
    {
        if ($idHabilidade === 1) {
            $this->energia -= 35;
            // Dano bufado para +20 ignorando armadura
            $danoEspecial = $this->ataqueBase + 20; 
            $oponente->receberDano($danoEspecial);

            return "🔥 {$this->nome} usou [Golpe Devastador]! Ignorou a armadura e cravou {$danoEspecial} de dano direto em {$oponente->getNome()}!";
        }

        if ($idHabilidade === 2) {
            $this->energia -= 25;
            $danoInicial = 5;
            $oponente->receberDano($danoInicial);
            $oponente->aplicarStatus('bleed', 2);

            return "📢 {$this->nome} soltou um [Grito de Batalha] ensurdecedor! Causou {$danoInicial} de dano pelo impacto e deixou {$oponente->getNome()} atordoado e sangrando (Bleed) por 2 turnos!";
        }

        return "❌ Habilidade desconhecida.";
    }
}