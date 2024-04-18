<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        if(isset($_SESSION['user_id']) && isset($_COOKIE['username'])){
            $_SESSION['user_id'] = $_COOKIE['user_id'];
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
    <title>Хочу с тобой пообщаться</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class='autoriz'>
    <h3 style="text-align: center; font-size: 30px">Хочу с тобой пообщаться</h3>
<?php
require_once 'connectionvars.php';
require_once 'appvars.php';

if(isset($_SESSION['username'])){
    echo '&#11088; <a href="viewprofile.php">Просмотреть профиль</a><br />';
    echo '&#11088; <a href="editprofile.php">Редактировать профиль</a><br />';
    echo '&#11088; <a href="logout.php">Выйти (' . $_SESSION['username'] . ')</a>';
}
else {
    echo '<a href="login.php">Авторизоваться &#9889; </a><br />';
    echo '<a href="signup.php">Зарегистрироваться &#9889; </a>';
    echo "<br>";
    echo '<a href="editprofile.php">Редактировать профиль &#9889; </a>';
    echo "<br>";
    echo '<a href="logout.php">Выйти &#9889; </a>';
}

$zapros="SELECT user_id, first_name, picture FROM mismatch_user WHERE first_name IS NOT NULL ORDER BY join_date DESC LIMIT 5";

$result=$pdo->prepare($zapros);

$result->execute();

echo '<h4>Последние пять участников:</h4>';
echo '<table class="tab">';

while($res = $result->fetch(PDO::FETCH_BOTH)){
    if(is_file(MM_UPLOADPATH . $res['picture']) && filesize(MM_UPLOADPATH . $res['picture']) > 0){
        echo '<tr><td><img src="' . MM_UPLOADPATH . $res['picture'] . '" alt="' . $res['first_name'] . '" /></td>';
    }
    else {
        echo '<tr><td><img src="' . MM_UPLOADPATH . 'nopic.jpg' . '"alt="' . $res['first_name'] . '" /></td>';
    }
    if(isset($_SESSION['user_id'])){
        echo '<td><a href="viewprofile.php?user_id=' . $res['user_id'] . '">' .$res['first_name'] . '</a></td></tr>';
    }
    else{
        echo '<td>' . $res['first_name'] . '</td></tr>';
    }
}
echo '</table>';
$result=NULL;
echo '</div>';
?>

</body>
</html>