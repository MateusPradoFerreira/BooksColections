<?php
include('conection.php');
session_start();

$session = $_SESSION['usuario'];

$id_pedido_original = $_POST['id_pedido_original'];

$id_livro = $_POST['pedir_em_troca'];
$id_dono_do_livro = $_POST['id_dono_do_livro'];

$query_pedidos = coletar_dados("select * from pedidos where id_livro = $id_livro and id_usuario_pedinte = $session");

if (mysqli_num_rows($query_pedidos) > 0) {
    $id_pedido_existente = mysqli_fetch_assoc($query_pedidos)['id_pedido'];
    alterar_dados("update pedidos set stat = 'EM ANDAMENTO' where id_pedido = $id_pedido_existente");
    $id_pedido_secundario = $id_pedido_existente;
} else {
    inserir_dados("insert into pedidos (id_usuario_dono, id_usuario_pedinte, id_livro, stat) 
    values (" . $id_dono_do_livro . " , $session, $id_livro, 'EM ANDAMENTO')");

    $id_pedido_secundario = mysqli_fetch_assoc(coletar_dados("select id_pedido from pedidos 
    where id_usuario_dono = $id_dono_do_livro and id_usuario_pedinte = $session and id_livro = $id_livro"))['id_pedido'];
}

alterar_dados("update pedidos set stat = 'EM ANDAMENTO' where id_pedido = $id_pedido_original");
inserir_dados("insert into trocas_andamento (id_pedido_01, id_pedido_02, aceitos, aceitou_primeiro, statu) values ($id_pedido_original, $id_pedido_secundario, 0, 0, 'Aguardando confirmações')");




$query_pedidos_livro_pp = coletar_dados("select * from pedidos where id_pedido = $id_pedido_original");
$dados_pedidos_livro_pp = mysqli_fetch_assoc($query_pedidos_livro_pp);

$query_pedidos_livro_01 = coletar_dados("select * from pedidos  
inner join livros on pedidos.id_livro = livros.id_livro  
where pedidos.id_livro = " . $dados_pedidos_livro_pp['id_livro'] . " and id_usuario_pedinte != " . $dados_pedidos_livro_pp['id_usuario_pedinte']);

while ($dados_pedidos_livro_01 = mysqli_fetch_assoc($query_pedidos_livro_01)) {
    inserir_dados("insert into pedidos_recusados (id_usuario_dono, id_usuario_pedinte, motivo, nome_livro)
values (" . $dados_pedidos_livro_01['id_usuario_dono'] . "," . $dados_pedidos_livro_01['id_usuario_pedinte'] . ", 
'A troca desse livro iniciada com outro usuario' , '" . $dados_pedidos_livro_01['nome_livro'] . "' )");

    remover_dados("delete from pedidos where id_pedido = " . $dados_pedidos_livro_01['id_pedido']);
}



$query_pedidos_livro_02 = coletar_dados("select * from pedidos  
inner join livros on pedidos.id_livro = livros.id_livro  
where pedidos.id_livro = $id_livro and id_usuario_pedinte != $session");

while ($dados_pedidos_livro_02 = mysqli_fetch_assoc($query_pedidos_livro_02)) {
    inserir_dados("insert into pedidos_recusados (id_usuario_dono, id_usuario_pedinte, motivo, nome_livro)
values (" . $dados_pedidos_livro_02['id_usuario_dono'] . "," . $dados_pedidos_livro_02['id_usuario_pedinte'] . ", 
'A troca desse livro iniciada com outro usuario' , '" . $dados_pedidos_livro_02['nome_livro'] . "' )");

    remover_dados("delete from pedidos where id_pedido = " . $dados_pedidos_livro_02['id_pedido']);
}


header('Location: ../trocas.php');
