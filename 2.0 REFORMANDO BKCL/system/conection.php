<?php

// =================================================================
// CONEXÂO
// =================================================================

$hostname = 'localhost';
$BD = 'bookscolection';
$user = 'root';
$senha = 'root';

$conexaoSQL = mysqli_connect($hostname, $user, $senha, $BD);

// =================================================================
// COLETA DE DADOS
// =================================================================

function coletar_dados($sql) {
    global $conexaoSQL;
    return mysqli_query($conexaoSQL, $sql);
}

function inserir_dados($sql) {
    global $conexaoSQL;
    mysqli_query($conexaoSQL, $sql);
}

function remover_dados($sql) {
    global $conexaoSQL;
    mysqli_query($conexaoSQL, $sql);
}

function alterar_dados($sql) {
    global $conexaoSQL;
    mysqli_query($conexaoSQL, $sql);
}


?>