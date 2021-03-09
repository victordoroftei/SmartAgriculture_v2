<?php

    $srv = "localhost";
    $username = "root";
    $pass = "";
    $db = "users";

########################################################################################################################################################################################################################

    $conn = new mysqli($srv, $username, $pass, $db);

    if($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

########################################################################################################################################################################################################################

    global $errorMsg;
    global $globalResult;

    if(isset($_POST['login-username']) and isset($_POST['login-password']))
    {
        $passwordQ = hash('sha256', $_POST['login-password']);
        $selectQuery = "SELECT id, username, password FROM userData WHERE (username='" . $_POST['login-username'] . "' AND password='" . $passwordQ . "');";

        $result = $conn->query($selectQuery);

        $globalResult = $result;

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $cookie_value = $row["id"];

                if(!isset($_COOKIE['userID']))
                { 
                    setcookie("userID", $cookie_value, time() + (86400), "/");
                }
            }
        }
        
        else
        {
            $errorMsg = "There are no users with this username/password.";
        }
    }

########################################################################################################################################################################################################################

function isErrorMessage($msg)
{
    if($msg == "")
    {
        return False;
    }
    
    else
    {
        return True;
    }
}


function printErrorMessage($msg)
{
    return $msg;
} 

########################################################################################################################################################################################################################

?>

<?php 

########################################################################################################################################################################################################################

        $selectQuery = "SELECT MAX(id) AS id FROM userData LIMIT 1";

        $result = $conn->query($selectQuery);

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $lastID = $row["id"];
            }
        }
            
        else
        {
            echo "MASSIVE ERROR: 0 results!";
        }

        function passValidation($str)
        {
            $len = strlen($str);

            for($i = 0; $i <= $len - 1; $i++)
            {
                if(!($str[$i] >= 'a' and $str[$i] <= 'z') and !($str[$i] >= 'A' and $str[$i] <= 'Z') and !($str[$i] >= '0' and $str[$i] <= '9'))
                {
                    return False;
                }
            }

            return True;
        }

        function passLengthValidation($str)
        {
            $len = strlen($str);
            if($len < 3)
            {
                return False;
            }
            else
            {
                return True;
            }
        }

        function nameValidation($str)
        {
            $len = strlen($str);

            for($i = 0; $i <= $len - 1; $i++)
            {
                if(!($str[$i] >= 'a' and $str[$i] <= 'z') and !($str[$i] >= 'A' and $str[$i] <= 'Z') and $str[$i] != '-')
                {
                    return False;
                }
            }

            return True;
        }

        function usernameValidation($str)
        {
            $len = strlen($str);

            for($i = 0; $i <= $len - 1; $i++)
            {
                if($str[$i] == ' ')
                {
                    return False;
                }
            }

            return True;
        }

        global $errorMsgSignup;
        $errorMsgSignup = "";

        global $globalResult;

        if(isset($_POST['signup-first']) and isset($_POST['signup-last']) and isset($_POST['signup-username']) and isset($_POST['signup-password']))
        {
            if(!passValidation($_POST['signup-password']))
            {
                $errorMsgSignup = "The password contains invalid characters! It must contain only uppercase and lowercase letters and/or digits.";
            }

            else if(!passLengthValidation($_POST['signup-password']))
            {
                $errorMsgSignup = "The password is too short! It must be only at least 3 characters long.";
            }

            else if(!nameValidation($_POST['signup-first']))
            {
                $errorMsgSignup = "The firstname contains invalid characters! It must contain only uppercase and lowercase letters and/or hyphens.";
            }

            else if(!nameValidation($_POST['signup-last']))
            {
                $errorMsgSignup = "The lastname contains invalid characters! It must contain only uppercase and lowercase letters and/or hyphens.";
            }

            else if(!usernameValidation($_POST['signup-username']))
            {
                $errorMsgSignup = "The username contains invalid characters! It cannot contain whitespaces.";
            }

            else
            {   
                $nextID = $lastID + 1;
                $latitude = 0;
                $longitude = 0;

                $passwordQ = hash('sha256', $_POST['signup-password']);
                $addQuery = "INSERT INTO userData VALUES (" . $nextID . ", '" . $_POST['signup-username'] . "', '" . $_POST['signup-first'] . "', '" . $_POST['signup-last'] . "', '" . $passwordQ . "', " . $latitude . ", " . $longitude . ")";

                $result = $conn->query($addQuery);
                $globalResult = $result;

                if($result === TRUE)
                {
                    $dummy = 1;
                }
                else
                {
                    $errorMsgSignup = "An user with this username already exists!"; 
                }
            }
        }

?>

<!--########################################################################################################################################################################################################################-->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/loading.css">
    <link rel="shortcut icon" href="img/logoico.png">
    <script src="js/pixi.min.js"></script>
    <script src="https://code.jquery.com/pep/0.4.3/pep.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">
    <title>Log in</title>
</head>
<body>
<!-- Loading -->

<?php
    if((isset($globalResult->num_rows) and $globalResult->num_rows != 0) or isset($_COOKIE["userID"]))
    {
        echo "<script> window.location.replace('index.php'); </script>";
    }
?>
<!-- 1) Fara loading screen la eroare (login sau signup) -->
<!-- 2) Fara appear la section la eroare (login sau signup) -->
<!-- 3) div form-wrapper (above login/signup) -> php is-active -->
<!-- 4) -->
<?php 

    if(!isErrorMessage($errorMsg) and !isErrorMessage($errorMsgSignup))
    {
        echo '<div class="containerLoading">
        <div class="wrapperLoading">
            <div class="petal-wrapper">
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
                <div class="petal"></div>
            </div>
            <div class="core"></div>
            </div>
        </div>';
    }
?>

<section class="forms-section <?php if(!isErrorMessage($errorMsg) and !isErrorMessage($errorMsgSignup)){echo 'appear';} else{echo '';}?>">
    <div class="forms">
      <div class="form-wrapper <?php if(isErrorMessage($errorMsg) or (!isErrorMessage($errorMsg) and !isErrorMessage($errorMsgSignup))){echo 'is-active';} else{echo '';}?>">
        <button type="button" class="switcher switcher-login">
          Login
          <span class="underline"></span>
        </button>
        <form class="form form-login" method="POST" action="login.php">
          <fieldset>
            <div class="input-block">
              <label for="login-username">Username</label>
              <input id="login-username" type="username" name="login-username" placeholder="Username" required>
            </div>
            <div class="input-block">
              <label for="login-password">Password</label>
              <input id="login-password" type="password" name="login-password" placeholder="Password" required>
            </div>
          </fieldset>
  
        <?php 
              
            if(isErrorMessage($errorMsg))
            {
            echo '<div class="errorpopup"> <p>' . printErrorMessage($errorMsg) . '</p> </div>';
            } 
        ?>
          <button type="submit" class="btn-login">Login</button>
        </form>
      </div>
      <div class="form-wrapper <?php if(isErrorMessage($errorMsgSignup)){echo 'is-active';} else{echo '';}?>">
        <button id="signup" type="button" class="switcher switcher-signup">
          Sign Up
          <span class="underline"></span>
        </button>
        <form class="form form-signup" method="POST" action="login.php">
          <fieldset>
            <div class="input-block">
              <label for="signup-first">Firstname</label>
              <input id="signup-first" type="firstname" name="signup-first" placeholder="Ex.: Ion" required>
            </div>
            <div class="input-block">
              <label for="signup-last">Lastname</label>
              <input id="signup-last" type="lastname" name="signup-last" placeholder="Ex.: Popescu" required>
            </div>
            <div class="input-block">
              <label for="signup-username">Username</label>
              <input id="signup-username" type="username" name="signup-username" placeholder="Ex.: ion123" required>
            </div>
            <div class="input-block">
              <label for="signup-password">Password</label>
              <input id="signup-password" type="password" name="signup-password" placeholder="a-z A-Z 0-9" required>
            </div>
          </fieldset>
            
            <?php

            if(isErrorMessage($errorMsgSignup))
            {
                echo '<div class="errorpopup"> <p>' . printErrorMessage($errorMsgSignup) . '</p> </div>';
            } 

            ?> 

          <button type="submit" class="btn-signup">Continue</button>
        </form>
      </div>
    </div>
  </section>

  <script  src="js/script.js"></script>

  <div class="bg-effect"></div>
<!-- bootstrap scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<!-- end bootstrap script -->
</body>
</html>