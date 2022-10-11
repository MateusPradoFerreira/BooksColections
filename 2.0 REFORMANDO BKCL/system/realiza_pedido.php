<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];
$id_livro = $_POST['realiza_pedido'];

$id_usuario_dono = mysqli_fetch_assoc(coletar_dados("select id_usuario_dono from livros where id_livro = $id_livro"));

inserir_dados("insert into pedidos (id_usuario_dono, id_usuario_pedinte, id_livro, stat)
values (" . $id_usuario_dono['id_usuario_dono'] . " , $session, $id_livro, 'PENDENTE')");

if (isset($_POST['realiza_pedido_redire'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: ../trocas.php');
}
