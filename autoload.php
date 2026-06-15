<?php

spl_autoload_register(function ($classe) {
    // Define o prefixo do namespace do projeto
    $prefixo = 'SkyRing\\';
    // Direitório base onde os arquivos estão
    $diretorioBase = __DIR__ . '/src/';

    // Verifica se a classe usa o prefixo do nosso projeto
    $len = strlen($prefixo);
    if (strncmp($prefixo, $classe, $len) !== 0) {
        return; // Se não usar, deixa para outro autoloader (se houver)
    }

    // Pega a parte relativa da classe (ex: Personagens\Mago)
    $classeRelativa = substr($classe, $len);

    // Substitui as barras invertidas do namespace pelas barras de diretório do Linux
    // E adiciona a extensão .php
    $arquivo = $diretorioBase . str_replace('\\', '/', $classeRelativa) . '.php';

    // Se o arquivo existir, inclui ele
    if (file_exists($arquivo)) {
        require_once $arquivo;
    }
});