# SkyRing Arena v2

RPG de Combate por Turnos via Terminal desenvolvido em PHP moderno. O projeto aplica os pilares de Orientação a Objetos (POO), interfaces, polimorfismo e controle estrito de fluxo para simular batalhas táticas entre diferentes classes de personagens.

## Requisitos do Sistema

* PHP 8.1 ou superior instalado.
* Extensão `readline` (opcional, o sistema possui fallback para `fgets(STDIN)`).
* Terminal compatível com comandos POSIX (Linux/macOS) para execução correta da limpeza de tela (`system('clear')`).

## Instalação e Execução

1. Clone o repositório para o seu ambiente local:

   ```bash
   git clone https://github.com/seu-usuario/skyring.git
   cd skyring
   ```

2. Certifique-se de que a estrutura de diretórios respeita a PSR-4 para o Autoloader. Caso utilize o Composer, configure o `composer.json` e execute:

   ```bash
   composer install
   ```

   > **Nota:** Caso o projeto utilize um autoloader próprio, certifique-se de que o arquivo de entrada aponta corretamente para as classes mapeadas.

3. Execute o jogo através do terminal:

   ```bash
   php Jogo.php
   ```

## Arquitetura e Engenharia de Software

O ecossistema do simulador baseia-se em boas práticas de design de código e engenharia de software:

* **Classe Abstrata (Personagem):** Define a estrutura base de atributos protegidos (`protected`) para garantir o encapsulamento. Métodos essenciais como `atacar()` e `defender()` são definidos como abstratos, forçando a implementação individual nas classes filhas.
* **Polimorfismo:** O motor do jogo (`Simulador`) gerencia as ações referenciando a classe abstrata mãe. A resolução de qual habilidade ou ataque será executado ocorre em tempo de execução, dependendo da instância da classe ativa.
* **Segregação de Interfaces:** A conjuração de habilidades dinâmicas é isolada através da `ConjuraHabilidadesInterface`. Isso garante que apenas classes capazes de manipular grimórios/táticas implementem o contrato de código, mantendo a flexibilidade da Engine.

## Classes e Habilidades Disponíveis

Abaixo estão descritas as classes implementadas no ecossistema do jogo, juntamente com seus respectivos kits de habilidades, custos e efeitos táticos.

| Classe | Habilidade | Custo de Energia | Descrição / Efeito Tático |
|---|---|---|---|
| **Guerreiro** | Golpe Esmagador | 25 | Desfere um ataque físico pesado baseado na força bruta, ignorando parte da defesa física do oponente. |
| | Brado de Guerra | 15 | Aumenta temporariamente o status de defesa base para os turnos seguintes. |
| **Mago** | Projétil Mágico | 30 | Canaliza dano mágico puro contra o alvo. |
| | Incinerar | 40 | Causa dano mágico contínuo, aplicando o status negativo de Burn (Queimadura) no início dos turnos do oponente. |
| **Necromante** | Dreno de Vida | 35 | Desfere dano do tipo Sombra ao oponente e converte uma porcentagem do dano causado em cura direta para si. |
| | Invocação Sombria | 45 | Invoca lacaios que causam dano físico e aplicam efeito de status negativo ao alvo. |
| **Paladino** | Julgamento Sagrado | 30 | Golpe que escala com o ataque físico e aplica dano bônus baseado na energia atual do paladino. |
| | Luz Sagrada | 25 | Consome energia para converter em restauração direta de pontos de vida (HP). |
| **Monge** | Palma de Ferro | 20 | Ataque focado que causa dano físico e tem chance de quebrar a postura de defesa do adversário. |
| | Fluxo de Chi | 15 | Restaura uma quantidade fixa de energia interna para o próximo turno. |
| **Bruxa** | Maldição do Sangue | 35 | Lança uma penalidade mágica que drena vida gradativamente do alvo, aplicando o status de Bleed (Sangramento). |
| | Sifão de Alma | 40 | Reduz a energia do oponente e transfere parte do recurso para a Bruxa. |

## Estrutura do Inventário

Todo personagem inicia a partida com um inventário de consumíveis táticos. O uso de itens acessa o inventário de forma livre durante o turno do jogador e não consome a ação principal de ataque ou defesa.

* **Poção de Vida:** Recupera 35% do HP Máximo do personagem.
* **Poção de Mana/Estamina:** Restaura 50 pontos fixos de Energia.

## Histórico de Batalha (Logs)

O motor do simulador conta com um sistema de telemetria de combate. Absolutamente todas as ações realizadas na partida (danos por turnos, consumo de itens, mitigações de defesa e modificadores de status) são gravadas em memória estruturada.

Ao final da partida, após a determinação do vencedor e exibição do placar limpo de status, o sistema oferece um prompt de confirmação. Se aceito, o terminal realiza a renderização cronológica do log, detalhando a partida turno por turno para fins de auditoria de estratégia.
