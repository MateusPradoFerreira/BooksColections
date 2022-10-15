<?php
include('system/verifica_session.php');
include('system/conection.php');

$session = $_SESSION['usuario'];

$query_livros = coletar_dados("select * from livros inner join usuarios on livros.id_usuario_dono = usuarios.id_user where livros.id_livro = " . $_GET['detalhes_livro']);

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

<body>
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
        <section>
            <?php if (mysqli_num_rows($query_livros) > 0) {
                while ($dados_livros = mysqli_fetch_assoc($query_livros)) {
            ?>
                    <div class="l-detalhes">
                        <img class="c-detalhes-img" src=" <?= $dados_livros['imagem_livro'] ?> " alt="">

                        <div class="c-detalhes-infos">
                            <h1 class="c-title"> <?= $dados_livros['nome_livro'] ?> </h1> 

                            <form action="perfil_alheio.php">
                                <button class="pp" name="perfil_alheio" type="submit" value="<?= $dados_livros['id_usuario_dono'] ?>">
                                    Da biblioteca de <?= $dados_livros['nome_usuario'] ?>
                                </button>
                            </form>

                            <p class="d"> <?= $dados_livros['descricao'] ?> </p>

                            <?php
                            $id_livro = $dados_livros['id_livro'];
                            $query_pedidos = coletar_dados("select * from pedidos where id_livro = $id_livro and id_usuario_pedinte = $session");
                            $dados_pedidos = mysqli_fetch_assoc($query_pedidos);
                            if (mysqli_num_rows($query_pedidos) > 0) {
                            ?>
                                <form action="system/cancelar_pedido.php" method="post">
                                    <input type="number" name="cancelar_pedido_redire" value="asddsasd" style="display: none;">
                                    <button type="submit" name="cancelar_pedido" value="<?= $dados_pedidos['id_pedido'] ?>" class="bnt3" style="width: 200px">Cancelar Pedido</button>
                                </form>
                            <?php
                            } else {
                            ?>
                                <form action="system/realiza_pedido.php" method="post">
                                    <input type="number" name="realiza_pedido_redire" value="dasdsa" style="display: none;">
                                    <button type="submit" name="realiza_pedido" value="<?= $dados_livros['id_livro'] ?>" class="bnt2" style="width: 200px">Tenho interesse</button>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
            <?php
                }
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