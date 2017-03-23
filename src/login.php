<?php
require_once 'engine/LoginModel.class.php';

extract($_POST);

if (isset($user) && isset($password)) {
    //ao instanciar o model o endereço para a pasta config deve ser definido
    $loginModel = new LoginModel('../config/');
    $autentication = $loginModel->getAutentication($user, $password);

    switch ($autentication) {

        case 0:
            ?>
            <script type="text/javascript">
                alert('login efetuado com sucesso');
            </script>
            <script type="text/javascript">
                alert('sessão do usuário <?php echo $_SESSION['USER'] ?>. Código de login: <?php echo $_SESSION['ID'] ?>');
            </script>
            <?php
            break;

        case 1:
            ?>
            <script type="text/javascript">
                alert('login duplicado');
            </script>
            <?php
            break;

        case -1:
            ?>
            <script type="text/javascript">
                alert('login inválido');
            </script>
            <?php
            break;
    }
}
?>

<html>
    <head>
        <title>Example login</title>
        <meta charset='utf8'>
        <link href="bootstrap.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <h1 class="text-center">Example login</h1>
        <div class="container" style="width: 20%">
            <form action="" method="post" class="form-signin">
                <div class="form form-group">
                    <input type="text" name="user" class="form-control" placeholder="login">
                </div>
                <div class="form form-group">
                    <input type="password" name="password" class="form-control" placeholder="senha">
                </div>
                <div class="form form-group">
                    <input type="submit" value="login" class="btn btn-lg btn-primary btn-block">
                </div>
            </form>
        </div>

    </body>
</html>