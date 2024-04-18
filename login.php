<a class='a' href="index.php">Вернуться назад</a>
<?php
  require_once('connectionvars.php');
  session_start();
  $error_msg = "";
  $password_pattern = '/^(?=.*\d)(?=.*[A-Z])[A-Za-z\d]{5,}$/';
  
    if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      $user_username = trim($_POST['username']);
      $user_password = trim($_POST['password']);
      if (!empty($user_username) && !empty($user_password)) {

        $query1 = "SELECT user_id, username FROM mismatch_user WHERE username = :user_username AND password = :user_password";
    
    $result2=$pdo->prepare($query1);
    $result2->bindParam(':user_username',$user_username);
    $result2->bindParam(':user_password',$user_password);
    $result2->execute();
  
    $count2=$result2->rowCount();     
          if ($count2== 1) {

  
      $row=$result2->fetch();
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['username'] = $row['username'];
          setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));   

          setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30)); 

         $home_url="index.php";
         header('Location: ' . $home_url);
        }
        else {
          $error_msg = 'Извините, вы должны ввести действительное имя пользователя и пароль для входа в систему.';
        }
      }
      else {
        $error_msg = 'Извините, вы должны ввести свое имя пользователя и пароль для входа.';
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Хочу с тобой пообщаться - АВТОРИЗАЦИЯ</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3 class='h3'>Хочу с тобой пообщаться - АВТОРИЗАЦИЯ</h3>
<?php
  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
?>
<div class='auto'>
  <form class='form' method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend style="font-size: 30px; color: white;">Авторизация</legend>
      <div class='divv'>
      <div>
      <label for="username">Логин:</label>
      <input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br />
      </div>
      <div>
      <label for="password">Пароль:</label>
      <input type="password" name="password" />
      </div>
      </div>
    </fieldset>
    <input class='but' style="margin: auto;" type="submit" value="Авторизироваться" name="submit" />
  </form>
</div>
<?php
  }
  else {
    echo('<p class="login">Вы вошли как ' . $_SESSION['username'] . '.</p>');
  }
?>

</body>
</html>
