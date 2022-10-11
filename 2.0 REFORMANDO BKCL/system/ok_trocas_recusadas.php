<?php
include('conection.php');
session_start();

$id_pedido_recusado = $_POST['ok_trocas_recusadas'];

remover_dados("delete from pedidos_recusados where id_pedido_recusado = $id_pedido_recusado");

header('Location: ../perfil_pessoal.php');
