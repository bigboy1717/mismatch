<?php
session_start();

if(!isset($S_SESSION['user_id'])){
    if(isset($_COOKIE['user_id']) && isset($_COOKIE['username'])){
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
    <title>Хочу пообщаться - Редактирование профиля</title>
    <link rel="stylesheet" type="" href="style.css" />
</head>
<body>
<a class='a' href="index.php">Вернуться назад</a>
    <h3 class='h3'>Хочу пообщаться - РЕДАКТИРОВАНИЕ ПРОФИЛЯ</h3>
    <?php
    require_once('appvars.php');
    require_once('connectionvars.php');

    if(!isset($_SESSION['user_id'])){
        echo '<p class="login vi">Пожалуйста <a style="color:green" href="login.php">авторизируйтесь</a> для открытия страницы</p>';  
        exit();
    }
    else{
        echo('<p class="login vi">Вы вошли в систему как ' . $_SESSION['username'] . ' ' .'<a style="color:green;" href="logout.php">Выйти</a></p>');
    }

    if(isset($_POST['submit'])){
        $first_name = trim($_POST['firstname']);
        $last_name = trim($_POST['lastname']);
        $gender = trim($_POST['gender']);
        $birthdate = trim($_POST['birthdate']);

        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $old_picture = trim($_POST['old_picture']);
        $new_picture = trim($_FILES['new_picture']['name']);
        $new_picture_type = $_FILES['new_picture']['type'];
        $new_picture_size = $_FILES['new_picture']['size'];
        list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
        $error = false;
        
        if(!empty($new_picture)){
            if((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') || ($new_picture_type == 'image/png')) && ($new_picture_size > 0) &&
            ($new_picture_size <= MM_MAXFILESIZE) && ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)){
                if(isset($_FILES['new_picture'])){
                    $target = MM_UPLOADPATH . basename($new_picture);
                    if(move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)){
                        if($old_picture != $new_picture){
                            @unlink(MM_UPLOADPATH . $old_picture);
                        }
                        else{
                            @unlink($_FILES['new_picture']['tmp_name']);
                            $error = true;
                            echo '<p class="error">Извините, возникла проблема с загрузкой вашей фотографии</p>';
                        }
                    }
                }
                else{
                    @unlink($_FILES['new_picture']['tmp_name']);
                    $error = true;
                    echo '<p class="error">Ваше изображение должно быть в формате GIF, JHEG или PNG ' . (MM_MAXFILESIZE / 1024) . ' КВ and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
                }
            }
            if(!$error){
                if(!empty($first_name) && !empty($last_name) && !empty($gender) && !empty($birthdate) && !empty($city) && !empty($state)){
                    if(!empty($new_picture)){
                        $query4 = "UPDATE mismatch_user SET first_name = :first_name, last_name = :last_name, gender = :gender, birthdate = :birthdate, city = :city, state = :state, picture = :new_picture
                        WHERE user_id = :user_id";
                        $user_id = $_SESSION['user_id'];
                        $result4=$pdo->prepare($query4);
                        $result4->bindParam(':new_picture', $new_picture);
                    }
                    else{
                        $query4 = "UPDATE mismatch_user SET first_name = :first_name, last_name = :last_name, gender = :gender, birthdate = :birthdate, city = :city, state = :state, picture = :new_picture
                        WHERE user_id = :user_id";
                        $user_id = $_SESSION['user_id'];
                        $result4=$pdo->prepare($query4);
                    }
                    $result4->bindParam(':first_name', $first_name);
                    $result4->bindParam(':last_name', $last_name);
                    $result4->bindParam(':gender', $gender);
                    $result4->bindParam(':birthdate', $birthdate);
                    $result4->bindParam(':city', $city);
                    $result4->bindParam(':state', $state);
                    $result4->bindParam(':user_id', $user_id);
                    $result4->execute();
                    echo '<p>Ваш профиль был успешно обновлен. Хотели бы Вы <a style="color:green;" href="viewprofile.php">просматривать ваш профиль</a>?</p>';
                    $result4=NULL;
                    exit();
                }
                else{
                    echo '<p class="error">Вы должны ввесте все данные профиля (фотография необязательна).</p>';
                }
            }
        }
        else{
            $query5 = "SELECT first_name, last_name, gender, birthdate, city, state, picture FROM mismatch_user WHERE user_id = :user_id";
            $user_id=$_SESSION["user_id"];
            $result5=$pdo->prepare($query5);
            $result5->bindParam(':user_id', $user_id);
            $result5->execute();
            $row4 = $result5->fetch();
            if($row4 != NULL){
                $first_name = $row4['first_name'];
                $last_name = $row4['last_name'];
                $gender = $row4['gender'];
                $birthdate = $row4['birthdate'];
                $city = $row4['city'];
                $state = $row4['state'];
                $old_picture = $row4['old_picture'];
            }
            else{
                echo '<p class="error">Возникла проблема с доступом к вашему профилю.</p>';
            }
        }
        $results5=NULL;
    }
    ?>
<div class='redact'>
<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
    <fieldset>
      <legend>Персональная информация</legend>
      <div class="divv2">
        <div>
      <label for="firstname">Имя:</label>
      <input type="text" id="firstname" name="firstname" value="<?php if (!empty($first_name)) echo $first_name; ?>" /><br />
      </div>
      <div>
      <label for="lastname">Фамилия:</label>
      <input type="text" id="lastname" name="lastname" value="<?php if (!empty($last_name)) echo $last_name; ?>" /><br />
      </div>
      <div>
      <label for="gender">Пол:</label>
      <select id="gender" name="gender">
        <option value="M" <?php if (!empty($gender) && $gender == 'M') echo 'selected = "selected"'; ?>>Мужской</option>
        <option value="F" <?php if (!empty($gender) && $gender == 'F') echo 'selected = "selected"'; ?>>Женский</option>
      </select><br />
      </div>
      <div>
      <label for="birthdate">Дата рождения:</label>
      <input type="text" id="birthdate" name="birthdate" value="<?php if (!empty($birthdate)) echo $birthdate; else echo 'Y-m-d'; ?>" /><br />
      </div>
      <div>
      <label for="city">Город:</label>
      <input type="text" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" /><br />
      </div>
      <div>
      <label for="state">Область:</label>
      <input type="text" id="state" name="state" value="<?php if (!empty($state)) echo $state; ?>" /><br />
      </div>
      <div>
      <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />
      <label for="new_picture">Фотография:</label>
      <input type="file" id="new_picture" name="new_picture" />
      <?php if (!empty($old_picture)) {
        echo '<img class="profile" src="' . MM_UPLOADPATH . $old_picture . '" alt="Profile Picture" />';
      } ?>       </div>
    </fieldset>
    <input type="submit" value="Сохранить профиль" name="submit" />
    </div>
</form>
</body> 
</html>
