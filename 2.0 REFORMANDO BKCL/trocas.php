<?php
include('system/verifica_session.php');
include('system/conection.php');

$session = $_SESSION['usuario'];

// Dados dos livros

$query_meus_pedidos = coletar_dados("select * from pedidos 
inner join usuarios on pedidos.id_usuario_dono = usuarios.id_user 
inner join livros on livros.id_livro = pedidos.id_livro
where pedidos.stat != 'EM ANDAMENTO' and pedidos.id_usuario_pedinte = $session 
order by nome_livro");

// Dados dos pedidos

$query_pedidos_para_mim = coletar_dados("select * from pedidos 
inner join usuarios on pedidos.id_usuario_pedinte = usuarios.id_user 
inner join livros on livros.id_livro = pedidos.id_livro
where pedidos.stat != 'EM ANDAMENTO' and pedidos.id_usuario_dono = $session 
order by nome_livro");

// trocas em andamento

$query_trocas_anadamento = coletar_dados("select * from trocas_andamento 
inner join pedidos on trocas_andamento.id_pedido_01 = pedidos.id_pedido  
where trocas_andamento.id_pedido_01 = pedidos.id_pedido and pedidos.id_usuario_pedinte = $session 
or trocas_andamento.id_pedido_01 = pedidos.id_pedido and pedidos.id_usuario_dono = $session");

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

    <div class="l-section">
        <h1 class="c-title">
            Meus Pedidos
        </h1>

        <section>
            <?php if (mysqli_num_rows($query_meus_pedidos) > 0) {
                while ($dados_meus_pedidos = mysqli_fetch_assoc($query_meus_pedidos)) {
            ?>
                    <div class="card">
                        <form action="detalhes_livro.php">
                            <button class="tt" type="submit" name="detalhes_livro" value="<?= $dados_meus_pedidos['id_livro'] ?>">
                                <img src=" <?= $dados_meus_pedidos['imagem_livro'] ?> " alt="">
                            </button>
                        </form>

                        <p class="name"> <?php $nn = substr($dados_meus_pedidos['nome_livro'], 0, 22) . "...";
                                            echo $nn; ?> </p>

                        <form action="perfil_alheio.php">
                            <button class="pp" name="perfil_alheio" type="submit" value="<?= $dados_meus_pedidos['id_usuario_dono'] ?>">
                                <p>Dono: <?php $nn = substr($dados_meus_pedidos['nome_usuario'], 0, 20) . "...";
                                            echo $nn; ?> </p>
                            </button>
                        </form>

                        <p style="margin-bottom: 10px; font-weight: 700;"> <?= $dados_meus_pedidos['stat'] ?> </p>
                        <form action="detalhes_livro.php">
                            <button type="submit" name="detalhes_livro" value="<?= $dados_meus_pedidos['id_livro'] ?>" class="bnt">Detalhes</button>
                        </form>
                        <form action="system/cancelar_pedido.php" method="post">
                            <button type="submit" name="cancelar_pedido" value="<?= $dados_meus_pedidos['id_pedido'] ?>" class="bnt3">Cancelar Pedido</button>
                        </form>
                    </div>
                <?php
                }
            } else {
                ?>
                <p>Nenhum pedido realizado</p>
            <?php
            } ?>
        </section>

        <h1 class="c-title">
            Pedidos direcionados a mim
        </h1>

        <section>
            <?php if (mysqli_num_rows($query_pedidos_para_mim) > 0) {
                while ($dados_pedidos_para_mim = mysqli_fetch_assoc($query_pedidos_para_mim)) {
            ?>
                    <div class="card">
                        <img src=" <?= $dados_pedidos_para_mim['imagem_livro'] ?> " alt="">
                        <p class="name">Pedido por <?php $nn = substr($dados_pedidos_para_mim['nome_usuario'], 0, 22) . "...";
                                                    echo $nn; ?> </p>

                        <form action="perfil_alheio.php">
                            <input type="number" name="aceitar_troca" value="<?= $dados_pedidos_para_mim['id_pedido'] ?>" style="display: none;">
                            <button type="submit" name="perfil_alheio" value="<?= $dados_pedidos_para_mim['id_usuario_pedinte'] ?>" class="bnt">Aceitar Troca</button>
                        </form>

                        <form action="system/recusar_troca.php" method="post">
                            <button type="submit" name="recusar_troca" value="<?= $dados_pedidos_para_mim['id_pedido'] ?>" class="bnt3">Recusar</button>
                        </form>
                    </div>
                <?php
                }
            } else {
                ?>
                <p>Não há pedidos direcionados a mim</p>
            <?php
            } ?>
        </section>

        <h1 class="c-title">
            Trocas em andamento
        </h1>

        <section>
            <?php if (mysqli_num_rows($query_trocas_anadamento) > 0) {
                while ($dados_trocas_anadamento = mysqli_fetch_assoc($query_trocas_anadamento)) {
                    global $query_livros_troca_andamento;
                    $query_livros_troca_andamento = coletar_dados("select * from pedidos 
                inner join livros on pedidos.id_livro = livros.id_livro
                where id_pedido = " . $dados_trocas_anadamento['id_pedido_01'] . " or id_pedido = " . $dados_trocas_anadamento['id_pedido_02']);
            ?>
                    <div class="card2">

                        <?php
                        while ($dados_livros_troca_andamento = mysqli_fetch_assoc($query_livros_troca_andamento)) {
                        ?>
                            <img src=" <?= $dados_livros_troca_andamento['imagem_livro'] ?> " alt="">
                        <?php
                        }
                        ?>

                        <?php
                        if ($dados_trocas_anadamento['aceitou_primeiro'] == $session) {
                        ?>
                            <p style="width: 100%; padding: 10px" class="name">Aguardando confirmação do outro usuario</p>
                        <?php
                        } elseif ($dados_trocas_anadamento['aceitou_primeiro'] != $session and $dados_trocas_anadamento['aceitos'] == 1) {
                        ?>
                            <p class="name" style="width: 100%; padding: 10px"> <?= $dados_trocas_anadamento['statu'] ?> </p>
                        <?php
                        } elseif ($dados_trocas_anadamento['aceitou_primeiro'] == 0 and $dados_trocas_anadamento['aceitos'] == 0) {
                        ?>
                            <p class="name" style="width: 100%; padding: 10px"> <?= $dados_trocas_anadamento['statu'] ?> </p>
                        <?php
                        }
                        ?>

                        <form action="system/finalizar_troca.php">
                            <button type="submit" style="min-width: 420px" name="finalizar_troca" value="<?= $dados_trocas_anadamento['id_trocas_andamento'] ?>" <?php
                                                                                                                                                                    if ($dados_trocas_anadamento['aceitou_primeiro'] == $session) {
                                                                                                                                                                        echo 'disabled';
                                                                                                                                                                    }
                                                                                                                                                                    ?> class="bnt2">Finalizar Troca</button>
                        </form>

                        <form action="system/cancelar_troca_nn.php" method="post">
                            <input type="number" name="cancelar_troca_nn2" value="<?= $dados_trocas_anadamento['id_pedido_02'] ?>" style="display: none;">
                            <button type="submit" style="min-width: 420px" name="cancelar_troca_nn" value="<?= $dados_trocas_anadamento['id_pedido_01'] ?>" class="bnt3">Cancelar Troca</button>
                        </form>
                    </div>
                <?php
                }
            } else {
                ?>
                <p>Nenhuma troca em andamento</p>
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