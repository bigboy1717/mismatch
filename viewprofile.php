<?php
session_start();

if (!isset($_SESSION['iser_id'])) {
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
        $_SESSION['user_id'] = $_COOKIE ['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Хочу пообщаться - Просмотр профиля</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<a class='a' href="index.php">Вернуться назад</a>
    <h3 class='h3'>Хочу пообщаться - ПРОСМОТР ПРОФИЛЯ</h3>
    <?php
    require_once('appvars.php');
    require_once('connectionvars.php');

    if (!isset($_SESSION['user_id'])) {
        echo '<p class="login">Пожалуйста <a href="login.php">авторизируйтесь</a>
        чтобы получить доступ к этой странице.</p>';
        exit();
    } else {
        echo ('<p class="login vi">Вы вошли как ' . $_SESSION['username'] . '. <a style="color:green;" href="logout.php">Выйти</a>.</p>');
    }

    if (!isset($_GET['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $query2 = "SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = :user_id";
    } else {
        $user_id = $_GET['user_id'];
        $query2 = 'SELECT username, first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id= :user_id';
    }

    $result3 = $pdo->prepare($query2);
    $result3->execute(['user_id' => $user_id]);
    $count3=$result3->rowCount();
echo '<div class="view">';
    if ($count3 == 1) {
        $row1 = $result3->fetch();
        echo '<table>';
        if (!empty($row1['username'])) {
            echo '<tr><td class="label">Логин:</td><td>' . $row1['username'] . '</td></tr>';
        }
        if (!empty($row1['first_name'])) {
            echo '<tr><td class="label">Имя:</td><td>' . $row1['first_name'] . '</td></tr>';
        }
        if (!empty($row1['last_name'])) {
            echo '<tr><td class="label">Фамилия:</td><td>' . $row1['last_name'] . '</td></tr>';
        }
        if (!empty($row1['password'])) {
            echo '<tr><td class="label">Пароль:</td><td>' . $row1['password'] . '</td></tr>';
        }
        
    }

    if (!empty($row1['gender'])) {
        echo '<tr><td class="label">Пол:</td><td>';
        if ($row1['gender'] == 'M') {
            echo "Мужской";
        } else if ($row1['gender'] == 'F') {
            echo "Женский";
        } else {
            echo '?';
        }
        echo '</td></tr>';
    }

    if (!empty($row1['birthdate'])) {
        if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
            echo '<tr><td class="label">Дата рождения:</td><td>' . $row1['birthdate'] . '</td></tr>';
        } else {
            list($year, $month, $day) = explode('-', $row1['birthdate']);
            echo '<tr><td class="label">Год рождения:</td><td>' . $year . '</td></tr>';
        }
    }
echo '</div>';
    if (!empty($row1['city']) || !empty($row1['state'])) {
        echo '<tr><td class="label">Место проживания:</td><td>' . $row1['city'] . " " .  $row1['state'] . '</td></tr>';
        if (!empty($row1['picture'])) {
            echo '<tr><td class="label">Фотография:</td><td><img src="' . MM_UPLOADPATH . $row1['picture'] . '" alt="Profile Picture" /></td></tr>';
        }
        echo '</table>';
        if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) {
            echo '<p>Хотели бы Вы <a style="color:green;" href="editprofile.php">отредактировать свой профиль</a>?</p>';
        }
    } else {
        echo '<p class="error">Возникла проблема с доступом к вашему профилю</p>';
    }
    $result3 = NULL;
    ?>
</body>

</html>