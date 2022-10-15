<?php
include('system/verifica_session.php');
include('system/conection.php');

$session = $_SESSION['usuario'];

// Dados dos livros

$query_livros = coletar_dados("select * from livros 
inner join usuarios on livros.id_usuario_dono = usuarios.id_user 
where livros.id_usuario_dono = $session 
order by nome_livro");

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

    <div class="l-section" style="min-height: 100vh;">
        <h1 class="c-title">
            Minha biblioteca
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
                        <p class="name"> <?php $nn = substr($dados_livros['nome_livro'], 0, 22) . "...";
                                            echo $nn; ?> </p>
                        <form action="system/remover_livro.php" method="post">
                            <button type="submit" name="remover_livro" value="<?= $dados_livros['id_livro'] ?>" class="bnt3">Excluir livro</button>
                        </form>
                    </div>
                <?php
                }
            } else {
                ?>
                <p>Nenhum livro registrado</p>
            <?php
            } ?>
        </section>

        <button class="bnt2" onclick="edd_perfil()" style="width: 50px; height: 50px; border-radius: 50%; position: absolute; right: 10vw">+</button>
        <br><br>

        <div class="l-modal-add-livro" id="modal_perfil_edd">
            <div class="c-modal-add-box">
                <h1 class="c-title">
                    Adicionar Livro
                </h1>

                <div class="l-login-form__form">
                    <form enctype="multipart/form-data" action="system/add_livro.php" method="POST" enctype="multipart/form-data" class="l-login-form__form" style="display: flex; justify-content: space-between;flex-wrap: wrap;">
                        <p>Nome do Livro</p>
                        <input type="text" style="width: 100%" name="nome_livro" required maxlength="40">

                        <p>Descrição</p>
                        <textarea style="resize: none; width: 100%; height: 100px" name="descricao" required maxlength="800"></textarea>

                        <p>Capa do livro</p>
                        <input type="file" style="width: 100%" name="imagem_livro" required>

                        <button type="submit" name="aad">Registrar</button>
                        <button style="background-color: rgb(211,47,47)" onclick="edd_perfil()">Cancelar</button>
                    </form>
                </div>
            </div>
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