<?php

// Puxa o sistema de carregamento automático de arquivos
require_once __DIR__ . '/autoload.php';

use SkyRing\Engine\Simulador;

// Instancia a engine e inicia o SkyRing
$jogo = new Simulador();
$jogo->iniciar();