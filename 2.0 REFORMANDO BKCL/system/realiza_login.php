<?php
include('conection.php');
session_start();

if (empty($_POST['email']) || empty($_POST['senha'])) {
    header('Location: ../login.php');
    exit();
}

$email = mysqli_real_escape_string($conexaoSQL, $_POST['email']);
$senha = mysqli_real_escape_string($conexaoSQL, $_POST['senha']);

$query = coletar_dados("select * from usuarios where email = '$email' and senha = md5('$senha')");

if (mysqli_num_rows($query) == 1) {
    $_SESSION['usuario'] = mysqli_fetch_assoc($query)['id_user'];
    header('Location: ../feed.php');
    exit();
} else {
    $_SESSION['nao_autenticado'] = true;
    header('Location: ../login.php');
    exit();
}
