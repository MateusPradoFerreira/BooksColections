<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];
$id_pedido = $_POST['recusar_troca'];

$query_pedidos = coletar_dados("select * from pedidos inner join livros on pedidos.id_livro = livros.id_livro where id_pedido = $id_pedido");
$dados_pedidos = mysqli_fetch_assoc($query_pedidos);

inserir_dados("insert into pedidos_recusados (id_usuario_dono, id_usuario_pedinte, motivo, nome_livro)
values (" . $dados_pedidos['id_usuario_dono'] . "," . $dados_pedidos['id_usuario_pedinte'] . ", 'Troca recusada pelo dono do livro' , '" . $dados_pedidos['nome_livro'] . "' )");

remover_dados("delete from pedidos where id_pedido = $id_pedido");

header('Location: ../trocas.php');
