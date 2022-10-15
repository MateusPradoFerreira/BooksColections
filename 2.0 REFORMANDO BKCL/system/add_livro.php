<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];

$nome_livro = mysqli_real_escape_string($conexaoSQL, $_POST['nome_livro']);
$descricao = mysqli_real_escape_string($conexaoSQL, $_POST['descricao']);

if ($_FILES['imagem_livro']['type'] == 'image/png') {
    $nome_arquivo = md5($_FILES['imagem_livro']['name'] . rand(1, 999)) . '.png';
} elseif ($_FILES['imagem_livro']['type'] == 'image/jpeg') {
    $nome_arquivo = md5($_FILES['imagem_livro']['name'] . rand(1, 999)) . '.jpg';
} elseif ($_FILES['imagem_livro']['type'] == 'image/webp') {
    $nome_arquivo = md5($_FILES['imagem_livro']['name'] . rand(1, 999)) . '.webp';
}

if ($_FILES['imagem_livro']['type'] != 'image/png' and $_FILES['imagem_livro']['type'] != 'image/jpeg' and $_FILES['imagem_livro']['type'] != 'image/webp') {
} else {
    if (isset($_FILES['imagem_livro'])) {
        move_uploaded_file($_FILES['imagem_livro']['tmp_name'], '../img_capas/' . $nome_arquivo);
    }

    $data_atual = date('Y-m-d');
    $arquivo = 'img_capas/' . $nome_arquivo;

    inserir_dados("insert into livros (nome_livro, imagem_livro, descricao, data_postagem, id_usuario_dono)
    values ('$nome_livro', '$arquivo', '$descricao', '$data_atual', $session)");
}

header('Location: ../meus_livros.php');
