<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];
$id_livro = $_POST['remover_livro'];

$query_pedidos = coletar_dados("select * from pedidos inner join livros on pedidos.id_livro = livros.id_livro where pedidos.id_livro = $id_livro");

while ($dados_pedidos = mysqli_fetch_assoc($query_pedidos)) {
    inserir_dados("insert into pedidos_recusados (id_usuario_dono, id_usuario_pedinte, motivo, nome_livro)
    values (" . $dados_pedidos['id_usuario_dono'] . "," . $dados_pedidos['id_usuario_pedinte'] . ", 'O livro foi retirado do acervo de seu dono' , '" . $dados_pedidos['nome_livro'] ."' )");
    $id_pedido = $dados_pedidos['id_pedido'];
    remover_dados("delete from pedidos where id_pedido = $id_pedido");
    remover_dados("delete from trocas_andamento where id_pedido_01 = $id_pedido or id_pedido_02 = $id_pedido");
}

remover_dados("delete from livros where id_livro = $id_livro");

header('Location: ../meus_livros.php');
