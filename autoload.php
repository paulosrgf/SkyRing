<?php

spl_autoload_register(function ($classe) {
    $prefixo = 'SkyRing\\';
    $diretorioBase = __DIR__ . '/src/';

    // Verifica se a classe usa o prefixo do projeto
    $len = strlen($prefixo);
    if (strncmp($prefixo, $classe, $len) !== 0) {
        return;
    }

    $classeRelativa = substr($classe, $len);

    // Substitui as barras invertidas do namespace pelas barras de diretório do Linux
    $arquivo = $diretorioBase . str_replace('\\', '/', $classeRelativa) . '.php';

    if (file_exists($arquivo)) {
        require_once $arquivo;
    }
});