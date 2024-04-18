<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Хочу с тобой пообщаться - РЕГИСТРАЦИЯ</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <a class="a" href="index.php">Вернуться назад</a>
    <div class='reg'>
    <h3>Хочу с тобой пообщаться - РЕГИСТРАЦИЯ</h3>

    <?php
    require_once('appvars.php');
    require_once('connectionvars.php');

    if (isset($_POST['submit'])) {

        $username = trim($_POST['username']);
        $password1 = trim($_POST['password1']);
        $password2 = trim($_POST['password2']);
        $email = trim($_POST['email']); 

        if (!empty($username) && !empty($password1) && !empty($password2) && ($password1 == $password2)) {

            $zapros1 = "SELECT * FROM mismatch_user WHERE username = :username ";
            $result = $pdo->prepare($zapros1);
            $result->bindParam(':username', $username);
            $result->execute();
            $kol = $result->rowCount();

            if ($kol == 0) {
                $secret = $password1;
                $data = date("Y-m-d");
                $ins = "INSERT INTO mismatch_user (username, password, email, join_date) VALUES (:username,:secr,:email,:data)";

                $inser = $pdo->prepare($ins);
                $inser->bindParam(':username', $username);
                $inser->bindParam(':secr', $secret);
                $inser->bindParam(':email', $email); // Указываем email для вставки
                $inser->bindParam(':data', $data);
                $inser->execute();
                echo '<p>Ваш новый аккаунт был успешно создан. Теперь вы готовы <a href="login.php">Авторизоваться</a>.</p>';
                $inser = NULL;
                exit();
            } else {
                echo '<p class="error">Учетная запись уже существует для этого имени пользователя. Пожалуйста, используйте другой логин.</p>';
                $username = "";
            }
        } else {
            echo '<p class="error">Вы должны ввести все регистрационные данные, включая желаемый пароль, дважды.</p>';
        }
    }
    $inser = NULL;
    ?>
<p>Пожалуйста, введите ваше имя пользователя и пароль, чтобы войти в блог.</p>
    <form class='fff' method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend style="width: 600px;" class='leg'>Информация о регистрации</legend>
            <div class="divv2">
                <div>
            <label for="username">Логин:</label>
            <input type="text" name="username" id="username"
                value="<?php if (!empty($username))
                echo $username; ?>" /><br />
                </div>
                <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" value="<?php if (!empty($email))
                echo $email; ?>" /><br />
                </div>
                <div>
            <label for="password1">Пароль:</label>
            <input type="password" id="password1" name="password1" /><br />
</div>
<div>
            <label for="password2">Подтвердите пароль:</label>
            <input type="password" name="password2" id="password2" /><br />
</div>
<div>
            <label for="captcha">Введите код с изображения:</label><br />
            <img src="capcha.php" alt="Captcha Image"><br />
            <input type="text" name="captcha" id="captcha" /><br />
            </div>
</div>
        </fieldset>
        <input class='but' type="submit" value="Зарегистрироваться" name="submit" />
    </form>
    </div>

</body>

</html>