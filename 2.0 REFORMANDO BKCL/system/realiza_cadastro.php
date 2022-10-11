<?php
include('conection.php');
session_start();

if (empty($_POST['email']) || empty($_POST['senha'])) {
    header('Location: ../login.php');
    exit();
}

$nome_usuario = mysqli_real_escape_string($conexaoSQL, $_POST['nome_usuario']);
$email = mysqli_real_escape_string($conexaoSQL, $_POST['email']);
$senha = mysqli_real_escape_string($conexaoSQL, $_POST['senha']);
$telefone = mysqli_real_escape_string($conexaoSQL, $_POST['telefone']);

inserir_dados("insert into usuarios (nome_usuario, senha, email, telefone)
values ('$nome_usuario', md5('$senha'), '$email', '$telefone');");

header('Location: ../login.php');
