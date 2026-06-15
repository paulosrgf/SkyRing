Markdown# SkyRing Arena v2

RPG de Combate por Turnos via Terminal desenvolvido em PHP moderno. O projeto aplica os pilares de Orientação a Objetos (POO), interfaces, polimorfismo e controle estrito de fluxo para simular batalhas táticas entre diferentes classes de personagens.

## Requisitos do Sistema

* PHP 8.1 ou superior instalado.
* Extensão `readline` (opcional, o sistema possui fallback para `fgets(STDIN)`).
* Terminal compatível com comandos POSIX (Linux/macOS) para execução correta da limpeza de tela (`system('clear')`).

## Instalação e Execução

1. Clone o repositório para o seu ambiente local:
   ```bash
   git clone [https://github.com/seu-usuario/skyring.git](https://github.com/seu-usuario/skyring.git)
   cd skyring
Certifique-se de que a estrutura de diretórios respeita a PSR-4 para o Autoloader. Caso utilize o Composer, configure o composer.json e execute:Bashcomposer install
Nota: Caso o projeto utilize um autoloader próprio, certifique-se de que o arquivo de entrada aponta corretamente para as classes mapeadas.Execute o jogo através do terminal:Bashphp Jogo.php
Arquitetura e Engenharia de SoftwareO ecossistema do simulador baseia-se em boas práticas de design de código e engenharia de software:Classe Abstrata (Personagem): Define a estrutura base de atributos protegidos (protected) para garantir o encapsulamento. Métodos essenciais como atacar() e defender() são definidos como abstratos, forçando a implementação individual nas classes filhas.Polimorfismo: O motor do jogo (Simulador) gerencia as ações referenciando a classe abstrata mãe. A resolução de qual habilidade ou ataque será executado ocorre em tempo de execução, dependendo da instância da classe ativa.Segregação de Interfaces: A conjuração de habilidades dinâmicas é isolada através da ConjuraHabilidadesInterface. Isso garante que apenas classes capazes de manipular grimórios/táticas implementem o contrato de código, mantendo a flexibilidade da Engine.Classes e Habilidades DisponíveisAbaixo estão descritas as classes implementadas no ecossistema do jogo, juntamente com seus respectivos kits de habilidades, custos e efeitos táticos.ClasseHabilidadeCusto de EnergiaDescrição / Efeito TáticoGuerreiroGolpe Esmagador25Desfere um ataque físico pesado baseado na força bruta, ignorando parte da defesa física do oponente.Brado de Guerra15Aumenta temporariamente o status de defesa base para os turnos seguintes.MagoProjétil Mágico30Canaliza dano mágico puro contra o alvo.Incinerar40Causa dano mágico contínuo, aplicando o status negativo de Burn (Queimadura) no início dos turnos do oponente.NecromanteDreno de Vida35Desfere dano do tipo Sombra ao oponente e converte uma porcentagem do dano causado em cura direta para si.Invocação Sombria45Invoca lacaios que causam dano físico e aplicam efeito de status negativo ao alvo.PaladinoJulgamento Sagrado30Golpe que escala com o ataque físico e aplica dano bônus baseado na energia atual do paladino.Luz Sagrada25Consome energia para converter em restauração direta de pontos de vida (HP).MongePalma de Ferro20Ataque focado que causa dano físico e tem chance de quebrar a postura de defesa do adversário.Fluxo de Chi15Restaura uma quantidade fixa de energia interna para o próximo turno.BruxaMaldição do Sangue35Lança uma penalidade mágica que drena vida gradativamente do alvo, aplicando o status de Bleed (Sangramento).Sifão de Alma40Reduz a energia do oponente e transfere parte do recurso para a Bruxa.Estrutura do InventárioTodo personagem inicia a partida com um inventário de consumíveis táticos. O uso de itens acessa o inventário de forma livre durante o turno do jogador e não consome a ação principal de ataque ou defesa.Poção de Vida: Recupera 35% do HP Máximo do personagem.Poção de Mana/Estamina: Restaura 50 pontos fixos de Energia.Histórico de Batalha (Logs)O motor do simulador conta com um sistema de telemetria de combate. Absolutamente todas as ações realizadas na partida (danos por turnos, consumo de itens, mitigações de defesa e modificadores de status) são gravadas em memória estruturada.Ao final da partida, após a determinação do vencedor e exibição do placar limpo de status, o sistema oferece um prompt de confirmação. Se aceito, o terminal realiza a renderização cronológica do log, detalhando a partida turno por turno para fins de auditoria de estratégia.
---

### 🚀 Comitando o README profissional no Git

Salve este conteúdo em um arquivo chamado `README.md` na raiz do seu projeto. Depois, use os comandos abaixo para fazer o commit e o push limpo para o seu GitHub:

```bash
git add README.md
git commit -m "docs: adiciona README.md profissional detalhando execucao, classes e arquitetura"
git push origin main