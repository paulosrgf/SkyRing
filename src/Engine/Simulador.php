<?php

namespace SkyRing\Engine;

use SkyRing\Personagens\Personagem;
use SkyRing\Personagens\Guerreiro;
use SkyRing\Personagens\Mago;

class Simulador
{
    private array $jogadores = [];
    private int $turno = 1;

    public function iniciar(): void
    {
        echo "=========================================\n";
        echo "    🌌 BEM-VINDO AO SKYRING ARENA 🌌     \n";
        echo "=========================================\n\n";

        // 1. Seleção de Personagens (Requisito do Enunciado)
        $this->jogadores[0] = $this->selecionarPersonagem("Jogador 1");
        $this->jogadores[1] = $this->selecionarPersonagem("Jogador 2");

        echo "\n--- O COMBATE VAI COMEÇAR ---\n";
        echo "{$this->jogadores[0]->getNome()} VS {$this->jogadores[1]->getNome()}\n";
        echo "=========================================\n\n";

        // 2. Loop Principal de Combate (Até alguém morrer)
        $indiceAtual = 0; // Começa com o Jogador 1
        
        while ($this->jogadores[0]->estaVivo() && $this->jogadores[1]->estaVivo()) {
            $jogadorAtual = $this->jogadores[$indiceAtual];
            $oponente = $this->jogadores[$indiceAtual === 0 ? 1 : 0];

            echo "-----------------------------------------\n";
            echo "TURNO {$this->turno} - Vez de: {$jogadorAtual->getNome()} ({$jogadorAtual->getTipo()})\n";
            echo "-----------------------------------------\n";
            
            // Prepara o personagem para o turno (reseta defesa, regenera mana)
            $jogadorAtual->iniciarTurno();

            // Exibe status da tela de combate (Requisito do Enunciado)
            $this->exibirStatus();

            // Executa a ação do jogador
            $this->executarTurno($jogadorAtual, $oponente);

            // Alterna o jogador para o próximo turno
            $indiceAtual = $indiceAtual === 0 ? 1 : 0;
            $this->turno++;
            echo "\nPressione ENTER para continuar...";
            fgets(STDIN);
        }

        // 3. Condição de Vitória e Resumo Final
        $this->exibirResultadoFinal();
    }

    private function selecionarPersonagem(string $nomeJogador): Personagem
    {
        while (true) {
            echo "{$nomeJogador}, escolha seu combatente:\n";
            echo "1. Guerreiro (Alta Defesa e Vida, Especial focado em quebra de armadura)\n";
            echo "2. Mago (Alto Ataque e Mana, Especial de dano massivo)\n";
            echo "Escolha (1-2): ";
            
            $entrada = trim(fgets(STDIN));

            echo "Digite o nome customizado do seu personagem: ";
            $nomeCustomizado = trim(fgets(STDIN));
            if (empty($nomeCustomizado)) {
                $nomeCustomizado = ($entrada == 1) ? "Valente" : "Eldrin";
            }

            if ($entrada === '1') {
                return new Guerreiro($nomeCustomizado);
            } elseif ($entrada === '2') {
                return new Mago($nomeCustomizado);
            }

            echo "\n❌ Opção inválida! Tente novamente.\n\n";
        }
    }

    private function exibirStatus(): void
    {
        foreach ($this->jogadores as $p) {
            $barraVida = str_repeat("■", max(0, ceil($p->getVida() / 10)));
            echo "• {$p->getNome()} ({$p->getTipo()}) -> HP: {$p->getVida()} [{$barraVida}] | Energia: {$p->getEnergia()}\n";
        }
        echo "-----------------------------------------\n";
    }

    private function executarTurno(Personagem $ativo, Personagem $oponente): void
    {
        while (true) {
            echo "AÇÕES DISPONÍVEIS:\n";
            echo "1. Atacar\n";
            echo "2. Defender\n";
            echo "3. Usar Habilidade Especial (Custo: " . ($ativo instanceof \SkyRing\Contratos\HabilidadeEspecialInterface ? $ativo->getCustoEnergia() : 0) . " de Energia)\n";
            echo "Escolha uma ação: ";

            $acao = trim(fgets(STDIN));
            echo "\n";

            if ($acao === '1') {
                echo $ativo->atacar($oponente) . "\n";
                break;
            } elseif ($acao === '2') {
                echo $ativo->defender() . "\n";
                break;
            } elseif ($acao === '3') {
                // Tratamento de Exceção simples para energia (Exigência do enunciado)
                if ($ativo instanceof \SkyRing\Contratos\HabilidadeEspecialInterface) {
                    if ($ativo->getEnergia() < $ativo->getCustoEnergia()) {
                        echo "❌ Energia insuficiente para usar o Especial! Escolha outra ação.\n\n";
                        continue;
                    }
                    echo $ativo->usarHabilidadeEspecial($oponente) . "\n";
                    break;
                }
            }

            echo "❌ Ação inválida! Selecione uma opção correta.\n\n";
        }
    }

    private function exibirResultadoFinal(): void
    {
        $vencedor = $this->jogadores[0]->estaVivo() ? $this->jogadores[0] : $this->jogadores[1];
        
        echo "\n=========================================\n";
        echo "💥 FIM DE JOGO! O COMBATE TERMINOU! 💥\n";
        echo "=========================================\n";
        echo "🏆 VENCEDOR: {$vencedor->getNome()} ({$vencedor->getTipo()})\n";
        echo "⏱️ Duração da partida: " . ($this->turno - 1) . " turnos.\n";
        echo "❤️ HP Restante do vencedor: {$vencedor->getVida()}\n";
        echo "=========================================\n";
    }
}