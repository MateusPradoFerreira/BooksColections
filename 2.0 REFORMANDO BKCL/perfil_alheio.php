<?php
include('system/verifica_session.php');
include('system/conection.php');

$session = $_SESSION['usuario'];

// Dados dos livros

$query_livros = coletar_dados("select * from livros 
    inner join usuarios on livros.id_usuario_dono = usuarios.id_user 
    where livros.id_usuario_dono = " . $_GET['perfil_alheio'] . " 
    order by nome_livro");

// Dados do usuario

$query_user = coletar_dados("select * from usuarios where id_user = " . $_GET['perfil_alheio']);
$dados_user = mysqli_fetch_assoc($query_user);

$numero_livros = mysqli_fetch_assoc(coletar_dados("select count(id_livro) as quant from livros where id_usuario_dono = " . $_GET['perfil_alheio']));

$foto_perfil = substr(mysqli_fetch_assoc(coletar_dados("select nome_usuario from usuarios where id_user = $session"))['nome_usuario'], 0, 1);

$foto_perfil_2 = substr(mysqli_fetch_assoc(coletar_dados("select nome_usuario from usuarios where id_user = " . $_GET['perfil_alheio']))['nome_usuario'], 0, 1);

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

        <nav id="menu2">
            <ul class="c-menu">
                <a style="margin-left: 30px" href="system/logout.php">
                    <li style="padding: 0;">Sair</li>
                </a>
                <li>
                    <?php
                    if (mysqli_num_rows(coletar_dados("select * from pedidos_recusados where id_usuario_pedinte = $session")) > 0) {
                        echo '<div class="ponto" style="background-color: red; width: 10px; height: 10px; position: absolute; margin-left: 37px;margin-top: 3px; border-radius: 50%"></div>';
                    }
                    ?>
                    <button onclick="chama_menu()" class="c-foto_perfil" style="cursor: pointer; border: none">
                        <?php echo $foto_perfil; ?>
                    </button>
                </li>
            </ul>
        </nav>

        <nav id="menu">
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

    <div class="l-menu2" id="menutty">
        <hr>
        <nav>
            <ul class="c-menu2">
                <a href="perfil_pessoal.php">
                    <li>Perfil</li>
                </a>
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
        <hr>
    </div>

    <script> 
        i = 0;

        function chama_menu() {
            if (i % 2 == 0) {
                document.getElementById('menutty').style.height = '290px';
                document.getElementById('menutty').style.paddingBottom = '10px';
                i = i + 1;
            } else {
                document.getElementById('menutty').style.height = '0';
                document.getElementById('menutty').style.paddingBottom = '0';
                i = i + 1;
            }
        }
    </script>

    <div class="l-section">
        <h1 class="c-title">
            Sobre <?= $dados_user['nome_usuario'] ?>
        </h1>

        <div>

            <div class="c-foto_perfil2">
                <?php echo $foto_perfil_2; ?>
            </div>
            <br>
            <p style="margin-bottom: 15px"><span style="color: #8e24aa; font-weight: 500">Email:</span> <?= $dados_user['email'] ?></p>
            <p style="margin-bottom: 15px"><span style="color: #8e24aa; font-weight: 500">Telefone:</span> <?= $dados_user['telefone'] ?></p>
            <p style="margin-bottom: 15px"><span style="color: #8e24aa; font-weight: 500">N° livros:</span> <?= $numero_livros['quant'] ?></p>
            <br><br>
        </div>

        <h1 class="c-title">
            Biblioteca pessoal de <?= $dados_user['nome_usuario'] ?>
        </h1>

        <section>
            <?php if (mysqli_num_rows($query_livros) > 0) {
                while ($dados_livros = mysqli_fetch_assoc($query_livros)) {
                    if (mysqli_num_rows(coletar_dados("select * from pedidos where id_livro = " . $dados_livros['id_livro'] . " and stat = 'EM ANDAMENTO'")) == 0) {
            ?>
                        <div class="card">
                            <form action="detalhes_livro.php">
                                <button class="tt" type="submit" name="detalhes_livro" value="<?= $dados_livros['id_livro'] ?>">
                                    <img src=" <?= $dados_livros['imagem_livro'] ?> " alt="">
                                </button>
                            </form>

                            <p class="name"> <?php $nn = substr($dados_livros['nome_livro'], 0, 22) . "...";
                                                echo $nn; ?> </p>

                            <form action="detalhes_livro.php">
                                <button type="submit" name="detalhes_livro" value="<?= $dados_livros['id_livro'] ?>" class="bnt">Detalhes</button>
                            </form>

                            <?php
                            $query_pedidos = coletar_dados("select * from pedidos 
                    where id_usuario_pedinte = $session 
                    and id_livro = " . $dados_livros['id_livro'] . "
                    and id_usuario_dono = " . $_GET['perfil_alheio']);

                            $aceitar_troca = 0;
                            if (isset($_GET['aceitar_troca'])) {
                            ?>
                                <form action="system/pedir_em_troca.php" method="post">
                                    <input type="number" name="id_dono_do_livro" value="<?= $dados_user['id_user'] ?>" style="display: none;">
                                    <input type="number" name="id_pedido_original" value="<?= $_GET['aceitar_troca'] ?>" style="display: none;">
                                    <button type="submit" name="pedir_em_troca" value="<?= $dados_livros['id_livro'] ?>" class="bnt2">Pedir em Troca</button>
                                </form>
                                <?php
                                $aceitar_troca = 1;
                            }

                            if ($aceitar_troca == 0) {
                                if (mysqli_num_rows($query_pedidos) == 0) {
                                ?>
                                    <form action="system/realiza_pedido.php" method="post">
                                        <input type="number" name="realiza_pedido_redire" value="adsfasdf" style="display: none;">
                                        <button type="submit" name="realiza_pedido" value="<?= $dados_livros['id_livro'] ?>" class="bnt2">Tenho interesse</button>
                                    </form>
                                <?php } else {
                                ?>
                                    <form action="system/cancelar_pedido.php" method="post">
                                        <input type="number" name="cancelar_pedido_redire" value="dfaadsf" style="display: none;">
                                        <button type="submit" name="cancelar_pedido" value="<?= mysqli_fetch_assoc($query_pedidos)['id_pedido'] ?>" class="bnt3">Cancelar Pedido</button>
                                    </form>
                            <?php }
                            } ?>

                        </div>
                <?php
                    }
                }
            } else {
                ?>
                <p>Este usuario Não possui nenhum livro disponivel para troca</p>
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