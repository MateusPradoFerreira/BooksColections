<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];

$nome_livro = mysqli_real_escape_string($conexaoSQL, $_POST['nome_livro']);
$descricao = mysqli_real_escape_string($conexaoSQL, $_POST['descricao']);
$imagem_livro = mysqli_real_escape_string($conexaoSQL, $_POST['imagem_livro']);

$data_atual = date('Y-m-d');

inserir_dados("insert into livros (nome_livro, imagem_livro, descricao, data_postagem, id_usuario_dono)
values ('$nome_livro', '$imagem_livro', '$descricao', '$data_atual', $session)");

header('Location: ../meus_livros.php');
