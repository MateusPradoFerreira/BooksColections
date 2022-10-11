<?php
include('conection.php');
session_start();

$id_pedido =$_POST['cancelar_pedido'];

remover_dados("delete from pedidos where id_pedido = $id_pedido");

if (isset($_POST['cancelar_pedido_redire'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: ../trocas.php');
}