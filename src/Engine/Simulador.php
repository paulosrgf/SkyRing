<?php

namespace SkyRing\Engine;

use SkyRing\Personagens\Personagem;
use SkyRing\Personagens\Guerreiro;
use SkyRing\Personagens\Mago;
use SkyRing\Personagens\Necromante;
use SkyRing\Personagens\Paladino;
use SkyRing\Personagens\Monge;
use SkyRing\Personagens\Bruxa;
use SkyRing\Contratos\ConjuraHabilidadesInterface;

class Simulador
{
    private array $jogadores = [];
    private int $turno = 1;
    private array $logBatalha = [];

    public function iniciar(): void
    {
        // Garante a tela limpa assim que o jogo inicia no container
        system('clear');

        echo "===================================================\n";
        echo "       🌌 BEM-VINDO AO SKYRING ARENA 🌌         \n";
        echo "===================================================\n\n";

        // 1. Seleção de Personagens com opção de saída
        $this->jogadores[0] = $this->selecionarPersonagem("Jogador 1");
        system('clear'); 
        
        $this->jogadores[1] = $this->selecionarPersonagem("Jogador 2");
        system('clear'); 

        echo "===================================================\n";
        echo "⚔️  O COMBATE VAI COMEÇAR! ⚔️\n";
        echo "🔥 {$this->jogadores[0]->getNome()} ({$this->jogadores[0]->getTipo()}) VS {$this->jogadores[1]->getNome()} ({$this->jogadores[1]->getTipo()})\n";
        echo "===================================================\n\n";
        echo "Pressione ENTER para entrar na Arena...";
        fgets(STDIN);

        $indiceAtual = 0; 
        
        // 2. Loop Principal de Combate
        while ($this->jogadores[0]->estaVivo() && $this->jogadores[1]->estaVivo()) {
            system('clear');

            $jogadorAtual = $this->jogadores[$indiceAtual];
            $oponente = $this->jogadores[$indiceAtual === 0 ? 1 : 0];

            echo "---------------------------------------------------\n";
            echo "🔮 TURNO {$this->turno} | Vez de: {$jogadorAtual->getNome()} ({$jogadorAtual->getTipo()})\n";
            echo "---------------------------------------------------\n";
            
            $this->logBatalha[$this->turno] = [
                'personagem' => "{$jogadorAtual->getNome()} ({$jogadorAtual->getTipo()})",
                'acoes' => []
            ];

            $logStatus = $jogadorAtual->iniciarTurno();
            if (!empty($logStatus)) {
                echo "\n=== ⚠️ STATUS NEGATIVOS DO TURNO ===\n" . $logStatus . "====================================\n\n";
                
                $linhasStatus = explode("\n", trim($logStatus));
                foreach ($linhasStatus as $linha) {
                    $this->logBatalha[$this->turno]['acoes'][] = $linha;
                }

                if (!$jogadorAtual->estaVivo()) {
                    $this->logBatalha[$this->turno]['acoes'][] = "💀 {$jogadorAtual->getNome()} sucumbiu aos efeitos de status nocivos!";
                    echo "💀 {$jogadorAtual->getNome()} sucumbiu aos efeitos de status nocivos!\n";
                    echo "Pressione ENTER para continuar...";
                    fgets(STDIN);
                    break;
                }
            }

            $this->exibirStatus();

            $this->executarTurno($jogadorAtual, $oponente);

            if (!$oponente->estaVivo()) {
                $this->logBatalha[$this->turno]['acoes'][] = "💀 {$oponente->getNome()} foi derrotado em combate!";
                break;
            }

            $indiceAtual = $indiceAtual === 0 ? 1 : 0;
            $this->turno++;
            echo "\nPressione ENTER para passar o turno...";
            fgets(STDIN);
        }

        // 3. Resultado Final
        system('clear');
        $this->exibirResultadoFinal();
    }

    private function selecionarPersonagem(string $nomeJogador): Personagem
    {
        while (true) {
            echo "➔ {$nomeJogador}, escolha a classe do seu combatente:\n";
            echo "  1. Guerreiro   |  2. Mago       |  3. Necromante\n";
            echo "  4. Paladino    |  5. Monge      |  6. Bruxa\n";
            echo "  0. Sair do Jogo\n";
            echo "Escolha (0-6): ";
            
            $entrada = fgets(STDIN);

            // Captura interrupções de terminal ou Ctrl+D para não quebrar o container
            if ($entrada === false || feof(STDIN)) {
                system('clear');
                echo "🚪 Conexão com o terminal encerrada. Saindo do SkyRing...\n";
                exit(0);
            }

            $entrada = trim($entrada);

            if ($entrada === '0') {
                system('clear');
                echo "👋 Saindo da Arena... Até a próxima batalha!\n";
                exit(0);
            }

            // Se o input estiver correto, prossegue para o nome customizado
            if (in_array($entrada, ['1', '2', '3', '4', '5', '6'])) {
                echo "Digite o nome customizado do seu personagem: ";
                $nomeCustomizado = trim(fgets(STDIN));
                if (empty($nomeCustomizado)) {
                    $nomeCustomizado = "Heroi_" . rand(100, 999);
                }

                switch ($entrada) {
                    case '1': return new Guerreiro($nomeCustomizado);
                    case '2': return new Mago($nomeCustomizado);
                    case '3': return new Necromante($nomeCustomizado);
                    case '4': return new Paladino($nomeCustomizado);
                    case '5': return new Monge($nomeCustomizado);
                    case '6': return new Bruxa($nomeCustomizado);
                }
            }

            system('clear');
            echo "❌ Opção inválida! Selecione um número válido de 0 a 6.\n\n";
        }
    }

    private function exibirStatus(): void
    {
        foreach ($this->jogadores as $p) {
            $porcentagemVida = max(0, ceil(($p->getVida() / $p->getVidaMax()) * 10));
            $barraVida = str_repeat("■", $porcentagemVida) . str_repeat("□", 10 - $porcentagemVida);
            
            echo "• {$p->getNome()} ({$p->getTipo()})\n";
            echo "  [{$barraVida}] HP: {$p->getVida()}/{$p->getVidaMax()} | Energia: {$p->getEnergia()}/{$p->getEnergiaMax()}\n";
        }
        echo "---------------------------------------------------\n";
    }

    private function executarTurno(Personagem $ativo, Personagem $oponente): void
    {
        $fezAcaoPrincipal = false;

        while (!$fezAcaoPrincipal) {
            echo "SUA VEZ! ESCOLHA UMA AÇÃO:\n";
            echo "1. Atacar (Básico)\n";
            echo "2. Defender (Aumentar Guarda)\n";
            echo "3. Conjurar Magias/Táticas (Habilidades)\n";
            echo "4. Abrir Inventário (Poções)\n";
            echo "Escolha: ";

            $escolha = trim(fgets(STDIN));
            echo "\n";

            switch ($escolha) {
                case '1':
                    system('clear');
                    echo "---------------------------------------------------\n";
                    echo "⚔️ RELATÓRIO DE COMBATE\n";
                    echo "---------------------------------------------------\n";
                    
                    $resultadoAtaque = $ativo->atacar($oponente);
                    echo $resultadoAtaque . "\n";
                    
                    $this->logBatalha[$this->turno]['acoes'][] = "⚔️ " . $resultadoAtaque;
                    $fezAcaoPrincipal = true;
                    break;

                case '2':
                    system('clear');
                    echo "---------------------------------------------------\n";
                    echo "🛡️ RELATÓRIO DE DEFESA\n";
                    echo "---------------------------------------------------\n";
                    
                    $resultadoDefesa = $ativo->defender();
                    echo $resultadoDefesa . "\n";
                    
                    $this->logBatalha[$this->turno]['acoes'][] = "🛡️ " . $resultadoDefesa;
                    $fezAcaoPrincipal = true;
                    break;

                case '3':
                    if ($ativo instanceof ConjuraHabilidadesInterface) {
                        $conjurou = $this->gerenciarMenuHabilidades($ativo, $oponente);
                        if ($conjurou) {
                            $fezAcaoPrincipal = true;
                        } else {
                            system('clear');
                            echo "---------------------------------------------------\n";
                            echo "🔮 TURNO {$this->turno} | Vez de: {$ativo->getNome()} ({$ativo->getTipo()})\n";
                            echo "---------------------------------------------------\n";
                            $this->exibirStatus();
                        }
                    } else {
                        echo "❌ Este personagem não possui habilidades mágicas ou táticas.\n\n";
                    }
                    break;

                case '4':
                    $this->gerenciarInventario($ativo);
                    system('clear');
                    echo "---------------------------------------------------\n";
                    echo "🔮 TURNO {$this->turno} | Vez de: {$ativo->getNome()} ({$ativo->getTipo()})\n";
                    echo "---------------------------------------------------\n";
                    $this->exibirStatus();
                    break;

                default:
                    echo "❌ Opção inválida! Selecione uma ação correta.\n\n";
                    break;
            }
        }
    }

    private function gerenciarMenuHabilidades(Personagem $ativo, Personagem $oponente): bool
    {
        $habilidades = $ativo->getMenuHabilidades();
        
        while (true) {
            system('clear');
            echo "---------------------------------------------------\n";
            echo "🔮 TURNO {$this->turno} | Vez de: {$ativo->getNome()} ({$ativo->getTipo()})\n";
            echo "---------------------------------------------------\n";
            $this->exibirStatus();

            echo "=== 📜 GRIMÓRIO / HABILIDADES ===\n";
            foreach ($habilidades as $id => $info) {
                echo "{$id}. {$info['nome']} (Custo: {$info['custo']} Energia)\n";
                echo "   └─ Descrição: {$info['desc']}\n";
            }
            echo "0. Voltar ao menu de ações\n";
            echo "Escolha a habilidade para conjurar: ";

            $idEscolhido = (int)trim(fgets(STDIN));
            echo "\n";

            if ($idEscolhido === 0) {
                return false; 
            }

            if (isset($habilidades[$idEscolhido])) {
                $custo = $habilidades[$idEscolhido]['custo'];
                
                if ($ativo->getEnergia() < $custo) {
                    echo "❌ Energia/Mana insuficiente! Você precisa de {$custo} mas tem apenas {$ativo->getEnergia()}.\n";
                    echo "Pressione ENTER para tentar novamente...";
                    fgets(STDIN);
                    continue;
                }

                system('clear');
                echo "---------------------------------------------------\n";
                echo "📜 RELATÓRIO DE CONJURAÇÃO\n";
                echo "---------------------------------------------------\n";
                
                $resultadoHabilidade = $ativo->conjurarHabilidade($idEscolhido, $oponente);
                echo $resultadoHabilidade . "\n";
                
                $this->logBatalha[$this->turno]['acoes'][] = "📜 " . $resultadoHabilidade;
                return true; 
            }

            echo "❌ Código de habilidade inválido!\n";
            echo "Pressione ENTER para tentar novamente...";
            fgets(STDIN);
        }
    }

    private function gerenciarInventario(Personagem $ativo): void
    {
        while (true) {
            system('clear');
            echo "---------------------------------------------------\n";
            echo "🔮 TURNO {$this->turno} | Vez de: {$ativo->getNome()} ({$ativo->getTipo()})\n";
            echo "---------------------------------------------------\n";
            $this->exibirStatus();

            $inv = $ativo->getInventario();
            echo "=== 🎒 INVENTÁRIO DE " . strtoupper($ativo->getNome()) . " ===\n";
            echo "1. Poção de Vida (Recupera 35% HP Máx) - Qtd: [{$inv['pocao_vida']}]\n";
            echo "2. Poção de Mana/Estamina (+50 Energia) - Qtd: [{$inv['pocao_mana']}]\n";
            echo "0. Fechar Inventário e Voltar\n";
            echo "Selecione o item para usar: ";

            $itemEscolhido = trim(fgets(STDIN));
            echo "\n";

            if ($itemEscolhido === '0') {
                return; 
            }

            if ($itemEscolhido === '1') {
                if ($inv['pocao_vida'] <= 0) {
                    echo "❌ Você não tem Poções de Vida restantes!\n";
                    echo "Pressione ENTER para continuar...";
                    fgets(STDIN);
                    continue;
                }
                system('clear');
                echo "---------------------------------------------------\n";
                echo "🎒 USO DE ITEM\n";
                echo "---------------------------------------------------\n";
                
                $resultadoItem = $ativo->usarItem('pocao_vida');
                echo $resultadoItem . "\n";
                
                $this->logBatalha[$this->turno]['acoes'][] = "🎒 " . $resultadoItem;
                
                echo "\nPressione ENTER para voltar às suas ações principais...";
                fgets(STDIN);
                return; 
            }

            if ($itemEscolhido === '2') {
                if ($inv['pocao_mana'] <= 0) {
                    echo "❌ Você não tem Poções de Mana restantes!\n";
                    echo "Pressione ENTER para continuar...";
                    fgets(STDIN);
                    continue;
                }
                system('clear');
                echo "---------------------------------------------------\n";
                echo "🎒 USO DE ITEM\n";
                echo "---------------------------------------------------\n";
                
                $resultadoItem = $ativo->usarItem('pocao_mana');
                echo $resultadoItem . "\n";
                
                $this->logBatalha[$this->turno]['acoes'][] = "🎒 " . $resultadoItem;
                
                echo "\nPressione ENTER para voltar às suas ações principais...";
                fgets(STDIN);
                return; 
            }

            echo "❌ Opção inválida!\n";
            echo "Pressione ENTER para tentar novamente...";
            fgets(STDIN);
        }
    }

    private function exibirResultadoFinal(): void
    {
        $vencedor = $this->jogadores[0]->estaVivo() ? $this->jogadores[0] : $this->jogadores[1];
        
        echo "\n===================================================\n";
        echo "💥 FIM DE JOGO! O COMBATE FOI CONCLUÍDO! 💥\n";
        echo "===================================================\n";
        echo "🏆 VENCEDOR INCONTESTÁVEL: {$vencedor->getNome()} ({$vencedor->getTipo()})\n";
        echo "⏱️  A arena resistiu por: " . ($this->turno) . " turnos de pura estratégia.\n";
        echo "❤️ HP Restante do campeão: {$vencedor->getVida()}/{$vencedor->getVidaMax()}\n";
        echo "===================================================\n\n";
        
        echo "🤔 Deseja visualizar o histórico detalhado da batalha? (S/N): ";
        $resposta = strtoupper(trim(fgets(STDIN)));
        
        if ($resposta === 'S') {
            system('clear');
            echo "===================================================\n";
            echo "📜 HISTÓRICO COMPLETO DA BATALHA (LOGS) 📜\n";
            echo "===================================================\n";
            
            foreach ($this->logBatalha as $numTurno => $dadosTurno) {
                echo "📌 TURNO {$numTurno} | Agente: {$dadosTurno['personagem']}\n";
                if (empty($dadosTurno['acoes'])) {
                    echo "   └─ Nenhuma ação registrada ou turno interrompido.\n";
                } else {
                    foreach ($dadosTurno['acoes'] as $acao) {
                        echo "   └─ {$acao}\n";
                    }
                }
                echo "---------------------------------------------------\n";
            }
            echo "===================================================\n";
            echo "Fim do relatório do SkyRing Arena. Obrigado por jogar!\n";
            echo "===================================================\n";
        } else {
            echo "\n✨ Arena fechada com sucesso. Até a próxima batalha!\n";
        }
    }
}