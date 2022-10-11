<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];

$id_pedido_01 = $_POST['cancelar_troca_nn'];

$query_pedidos_01 = coletar_dados("select * from pedidos inner join livros on pedidos.id_livro = livros.id_livro where id_pedido = $id_pedido_01");
$dados_pedidos_01 = mysqli_fetch_assoc($query_pedidos_01);

inserir_dados("insert into pedidos_recusados (id_usuario_dono, id_usuario_pedinte, motivo, nome_livro)
values (" . $dados_pedidos_01['id_usuario_dono'] . "," . $dados_pedidos_01['id_usuario_pedinte'] . ", 'Cancelamento durante o processo de troca' , " . '"' . $dados_pedidos_01['nome_livro'] . ' " )');

remover_dados("delete from pedidos where id_pedido = $id_pedido_01");



$id_pedido_02 = $_POST['cancelar_troca_nn2'];

$query_pedidos_02 = coletar_dados("select * from pedidos inner join livros on pedidos.id_livro = livros.id_livro where id_pedido = $id_pedido_02");
$dados_pedidos_02 = mysqli_fetch_assoc($query_pedidos_02);

inserir_dados("insert into pedidos_recusados (id_usuario_dono, id_usuario_pedinte, motivo, nome_livro)
values (" . $dados_pedidos_02['id_usuario_dono'] . "," . $dados_pedidos_02['id_usuario_pedinte'] . ", 'Cancelamento durante o processo de troca' , '" . $dados_pedidos_02['nome_livro'] . "' )");

remover_dados("delete from pedidos where id_pedido = $id_pedido_02");




header('Location: ../trocas.php');
