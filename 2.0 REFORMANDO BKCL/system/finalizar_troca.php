<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];
$id_trocas_andamento = $_GET['finalizar_troca'];

$dados_trocas_andamento = mysqli_fetch_assoc(coletar_dados("select * from trocas_andamento where id_trocas_andamento = $id_trocas_andamento"));

if ($dados_trocas_andamento['aceitos'] == 0 and $dados_trocas_andamento['aceitou_primeiro'] == 0) {
    alterar_dados("update trocas_andamento 
    set statu = 'Aguardando sua confirmação de troca', aceitou_primeiro = $session, aceitos = 1
    where id_trocas_andamento = $id_trocas_andamento");
} elseif ($dados_trocas_andamento['aceitos'] == 1 and $dados_trocas_andamento['aceitou_primeiro'] == $session) {
} elseif ($dados_trocas_andamento['aceitos'] == 1 and $dados_trocas_andamento['aceitou_primeiro'] != $session) {
    $dados_ta = mysqli_fetch_assoc(coletar_dados("select * from trocas_andamento where id_trocas_andamento = $id_trocas_andamento"));

    $dados_p1 = mysqli_fetch_assoc(coletar_dados("select * from pedidos inner join livros on pedidos.id_livro = livros.id_livro 
    where id_pedido = " . $dados_ta['id_pedido_01']));
    $nome_livro_01 = $dados_p1['nome_livro'];
    $id_user_01 = $dados_p1['id_usuario_dono'];

    $dados_p2 = mysqli_fetch_assoc(coletar_dados("select * from pedidos inner join livros on pedidos.id_livro = livros.id_livro 
    where id_pedido = " . $dados_ta['id_pedido_02']));
    $nome_livro_02 = $dados_p2['nome_livro'];
    $id_user_02 = $dados_p2['id_usuario_dono'];

    alterar_dados("update livros set id_usuario_dono = " . $dados_p1['id_usuario_pedinte'] . " where id_livro = " . $dados_p1['id_livro']);
    alterar_dados("update livros set id_usuario_dono = " . $dados_p2['id_usuario_pedinte'] . " where id_livro = " . $dados_p2['id_livro']);

    remover_dados("delete from pedidos where id_pedido = " . $dados_ta['id_pedido_01']);
    remover_dados("delete from pedidos where id_pedido = " . $dados_ta['id_pedido_02']);

    $data_atual = date('Y-m-d');

    inserir_dados("insert into trocas_finalizadas ( nome_livro_01, id_user_01, nome_livro_02, id_user_02, data_finalizacao )
    values (" . '"' . $nome_livro_01 . '" ,' . $id_user_01 . ', "' . $nome_livro_02 . '" , ' . $id_user_02 . ", '$data_atual' )");

    remover_dados("delete from trocas_andamento where id_trocas_andamento = $id_trocas_andamento");

    header('Location: ../meus_livros.php');
}

header('Location: ../trocas.php');
