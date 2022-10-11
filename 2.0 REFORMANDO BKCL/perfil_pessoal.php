<?php
include('system/verifica_session.php');
include('system/conection.php');

$session = $_SESSION['usuario'];

// Dados dos livros

$editado = 0;

if (isset($_POST['edd_perfil'])) {
    $nome_usuario = $_POST['nome_usuario'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    alterar_dados("update usuarios set nome_usuario = '$nome_usuario', email = '$email', telefone = '$telefone' 
    where id_user = $session");

    $editado = 1;
}

$query_trocas_finalizadas = coletar_dados("select * from trocas_finalizadas 
where id_user_02 = $session or id_user_01 = $session ");

// Dados dos pedidos recusados

$query_trocas_recusadas = coletar_dados("select * from pedidos_recusados 
inner join usuarios on pedidos_recusados.id_usuario_pedinte = usuarios.id_user 
where pedidos_recusados.id_usuario_pedinte = $session 
order by nome_livro");

$query_livros = coletar_dados("select * from livros 
inner join usuarios on livros.id_usuario_dono = usuarios.id_user 
where livros.id_usuario_dono = $session 
order by nome_livro");

$dados_perfil = mysqli_fetch_assoc(coletar_dados("select * from usuarios where id_user = $session"));
$numero_livros = mysqli_fetch_assoc(coletar_dados("select count(id_livro) as quant from livros where id_usuario_dono = $session"));

$foto_perfil = substr(mysqli_fetch_assoc(coletar_dados("select nome_usuario from usuarios where id_user = $session"))['nome_usuario'], 0, 1);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BooksColections</title>
    <link rel="stylesheet" href="scss/styles.css">
</head>

<body class="body">
    <header class="l-header">
        <img src="img/logo branca.png" alt="" class="c-logo">

        <nav>
            <ul class="c-menu">
                <a href="feed.php">
                    <li>Home</li>
                </a>
                <a href="trocas.php">
                    <li>Trocas</li>
                </a>
                <a href="meus_livros.php">
                    <li>Meus Livros</li>
                </a>
                <a style="margin-left: 30px" href="system/logout.php">
                    <li style="padding: 0;">Sair</li>
                </a>
                <a href="perfil_pessoal.php">
                    <li>
                        <?php
                        if (mysqli_num_rows(coletar_dados("select * from pedidos_recusados where id_usuario_pedinte = $session")) > 0) {
                            echo '<div class="ponto" style="background-color: red; width: 10px; height: 10px; position: absolute; margin-left: 37px;margin-top: 3px; border-radius: 50%"></div>';
                        }
                        ?>
                        <div class="c-foto_perfil">
                            <?php echo $foto_perfil; ?>
                        </div>
                    </li>
                </a>
            </ul>
        </nav>
    </header>

    <div class="l-section">
        <h1 class="c-title">
            Minhas Informações
        </h1>

        <?php
        if ($editado == 1) {
            echo '<p style="padding: 5px 20px;
            background-color: #689f38;
            color: white;
            font-size: 13px;
            width: 300px;
            border-radius: 100px;
            font-weight: 500; margin-bottom: 30px"> Perfil editado com sucesso </p>';
        }
        ?>

        <div>

            <div class="c-foto_perfil" style="background-color: #9c27b0; color: #fff; width: 150px; height: 150px; font-size: 70px; float: left; margin-right: 40px">
                <?php echo $foto_perfil; ?>
            </div>

            <p style="margin-bottom: 15px"><span style="color: #8e24aa; font-weight: 500">Nome:</span> <?= $dados_perfil['nome_usuario'] ?></p>
            <p style="margin-bottom: 15px"><span style="color: #8e24aa; font-weight: 500">Email:</span> <?= $dados_perfil['email'] ?></p>
            <p style="margin-bottom: 15px"><span style="color: #8e24aa; font-weight: 500">Telefone:</span> <?= $dados_perfil['telefone'] ?></p>
            <p style="margin-bottom: 15px"><span style="color: #8e24aa; font-weight: 500">N° livros:</span> <?= $numero_livros['quant'] ?></p>
        </div>

        <br>

        <button onclick="edd_perfil()" class="bnt" style="width: 250px;">Editar perfil</button>

        <br><br>

        <div class="l-modal-add-livro" id="modal_perfil_edd">
            <div class="c-modal-add-box">
                <h1 class="c-title">
                    Editar Perfil
                </h1>

                <form action="" method="post" class="l-login-form__form" style="display: flex; justify-content: space-between;flex-wrap: wrap;">
                    <p>Nome Completo</p>
                    <input style="width: 100%" value="<?= $dados_perfil['nome_usuario'] ?>" type="text" name="nome_usuario" maxlength="40" required>

                    <p>Email</p>
                    <input style="width: 100%" value="<?= $dados_perfil['email'] ?>" type="email" name="email" maxlength="100" required>

                    <p>Telefone</p>
                    <input style="width: 100%" value="<?= $dados_perfil['telefone'] ?>" type="text" name="telefone" maxlength="15" required>

                    <button type="submit" name="edd_perfil" class="bnt">Confirmar</button>
                    <br>
                </form>

                <button style="width: 250px;
                    margin-top: 10px;
                    padding: 10px;
                    border-radius: 100px;
                    border: none;
                    background-color: var(--color-palette-red);
                    color: white;
                    cursor: pointer; 
                    -webkit-box-shadow: 5px 9px 9px 0px rgba(166, 156, 166, 1);
                    -moz-box-shadow: 5px 9px 9px 0px rgba(166, 156, 166, 1);
                    box-shadow: 5px 9px 9px 0px rgba(166, 156, 166, 1);" onclick="edd_perfil()">Cancelar</button>
            </div>
        </div>

        <script>
            i = 0;

            function edd_perfil() {
                if (i % 2 == 0) {
                    document.getElementById('modal_perfil_edd').style.left = '0';
                    i = i + 1;
                } else {
                    document.getElementById('modal_perfil_edd').style.left = '-120%';
                    i = i + 1;
                }
            }
        </script>

        <h1 class="c-title">
            Minha Biblioteca
        </h1>

        <section>
            <?php if (mysqli_num_rows($query_livros) > 0) {
                while ($dados_livros = mysqli_fetch_assoc($query_livros)) {
            ?>
                    <div class="card">
                        <form action="detalhes_livro_pessoal.php" method="post">
                            <button class="tt" type="submit" name="detalhes_livro" value="<?= $dados_livros['id_livro'] ?>">
                                <img src=" <?= $dados_livros['imagem_livro'] ?> " alt="">
                            </button>
                        </form>
                        <p class="name"> <?= $dados_livros['nome_livro'] ?> </p>
                    </div>
                <?php
                }
            } else {
                ?>
                <p>Nenhum iivro registrado</p>
            <?php
            } ?>
        </section>

        <h1 class="c-title">
            Trocas canceladas
        </h1>

        <section>
            <?php if (mysqli_num_rows($query_trocas_recusadas) > 0) {
                while ($dados_trocas_recusadas = mysqli_fetch_assoc($query_trocas_recusadas)) {
            ?>
                    <div class="card">
                        <p class="name"> <?php $nn = substr($dados_trocas_recusadas['nome_livro'], 0, 22) . "...";
                                            echo $nn; ?> </p>
                        <p> <?= $dados_trocas_recusadas['motivo'] ?> </p>
                        <form action="system/ok_trocas_recusadas.php" method="post">
                            <button style="margin-top: 10px" type="submit" name="ok_trocas_recusadas" value="<?= $dados_trocas_recusadas['id_pedido_recusado'] ?>" class="bnt3">OK</button>
                        </form>
                    </div>
                <?php
                }
            } else {
                ?>
                <p>Nenhuma troca cancelada</p>
            <?php
            } ?>
        </section>

        <h1 class="c-title">
            Histórico de Trocas
        </h1>

        <section>
            <?php if (mysqli_num_rows($query_trocas_finalizadas) > 0) {
                $i = 0;
                while ($dados_trocas_finalizadas = mysqli_fetch_assoc($query_trocas_finalizadas)) {
                    $i = $i + 1;
            ?>
                    <div class="card" style="width: 100%; border: 1px solid black; border-radius: 5px; display:flex; align-items: center;padding: 5px; padding-left: 10px">
                        <p style="text-align: left;">Você trocou o livro <span style="color: #9c27b0; font; font-weight: 500"><?= $dados_trocas_finalizadas['nome_livro_01'] ?></span>
                            pelo livro <span style="color: #9c27b0; font; font-weight: 500"><?= $dados_trocas_finalizadas['nome_livro_02'] ?></span></p>
                    </div>
                <?php
                }
            } else {
                ?>
                <p>Nenhum livro foi trocado</p>
            <?php
            } ?>
        </section>

    </div>

    <footer class="l-footer">
        <nav>
            <ul class="c-menu">
                <a href="feed.php">
                    <li>Home</li>
                </a>
                <a href="trocas.php">
                    <li>Trocas</li>
                </a>
                <a href="meus_livros.php">
                    <li>Meus Livros</li>
                </a>
            </ul>
        </nav>

        <p class="c-copy">
            © 2022-2022, BooksColections
        </p>
    </footer>

</body>

</html>