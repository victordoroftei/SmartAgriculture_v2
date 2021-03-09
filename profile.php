<!DOCTYPE html>
<html>
<?php
    
    $srv = "localhost";
    $username = "root";
    $pass = "";
    $db = "users";

    $conn = new mysqli($srv, $username, $pass, $db);
    
    if($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile - Floarea Soarelui</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="shortcut icon" href="img/logoico.png">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="css/navbar.css">
	<link rel="stylesheet" href="css/loading.css">
    <link rel="stylesheet" href="css/input.css">
    <link rel="stylesheet" href="css/weather.css">
    <link rel="stylesheet" href="css/bg.css">
    <link rel="stylesheet" href="css/flower.css">
    <script src="js/pixi.min.js"></script>
    <script src="https://code.jquery.com/pep/0.4.3/pep.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<style>
    
.succespopup{
	width: auto;
    height: auto;
    color: white;
}
::-webkit-input-placeholder { /* Edge */
  color: white;
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color: white;
}

.form-group input[type=text]{
        outline: red;
        color: black;
        display: 16px;
    }

</style>
<body id="page-top">
<div style="z-index: 0; width: 100vw; height: 100%;position: fixed; top:0;left:0;opacity: 0.2;">
    <div class="bg-effect" style="z-index: -999999!important;"></div>
</div>
<?php
    
    if(!isset($_COOKIE["userID"]))
    {
        echo "<script>window.location.replace('login.php'); </script>";
    }
    else
    {
        //
    }

    function returnUsernameFromID($id)
    {
        global $srv, $username, $pass, $db, $conn;

        $selectQuery = "SELECT username FROM userData WHERE id=" . $id . ";";

        $result = $conn->query($selectQuery);

        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            
            return $row['username'];
        }
    }

    function getColumnDataFromID($columnName, $id)
    {
        global $srv, $username, $pass, $db, $conn;

        $selectQuery = "SELECT " . $columnName . " FROM userData WHERE id=" . $id . ";";

        $result = $conn->query($selectQuery);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            
            return $row[$columnName];
        }
    }

    function updateUserData($columnName, $id)
    {
        global $srv, $username, $pass, $db, $conn;

        if(isset($_POST[$columnName]))
        {
            if($columnName == "longitude" or $columnName == "latitude")
            {
                $value = doubleval($_POST[$columnName]);
                $value = strval($value);
            }

            else
            {
                $value = $_POST[$columnName];
            }

            $updateQuery = "UPDATE userData SET ". $columnName . "='" . $value . "' WHERE id=" . $id . ";";
            if ($conn->query($updateQuery) === TRUE) 
            {
                //$update1msg = "Datele personale au fost modificate cu succes!";
                $update1msg = "The data has been modified succesfully!";
            }
            else
            {
                //$update1msg = "Eroare fatala! S-au introdus date invalide.";
                $update1msg = "Fatal error! Invalid data!";
            }
        }

        return $update1msg;
    }

    function updateUserPass($id)
    {
        global $srv, $username, $pass, $db, $conn;

        if(isset($_POST['password']))
        {
            $passwordQ = hash('sha256', $_POST['password']);
            $updateQuery = "UPDATE userData SET  password='" . $passwordQ . "' WHERE id=" . $id . ";";
            
            if ($conn->query($updateQuery) === TRUE) 
            {
                //$update1msg = "Datele personale au fost modificate cu succes!";
                $update1msg = "The data has been modified succesfully!";
            }
            else
            {
                //$update1msg = "Eroare fatala! S-au introdus date invalide.";
                $update1msg = "Fatal error! Invalid data!";
            }
        }

        return $update1msg;
    }

    function nameValidation($name)
    {
        $len = strlen($name);

        if($name == "")
            return False;

        for($i = 0; $i <= $len - 1; $i++)
        {   
            if(!($name[$i] >= 'a' and $name[$i] <= 'z') and !($name[$i] >= 'A' and $name[$i] <= 'Z'))
            {
                return False;
            }
        }

        return True;
    }

    function usernameValidation($name)
    {
        $len = strlen($name);

        if($name == "")
            return False;

        for($i = 0; $i <= $len - 1; $i++)
        {   
            if($name[$i] == " ")
            {
                return False;
            }
        }

        return True;
    }

    function localizationValidation($localizare)
    {
        $len = strlen($localizare);

        for($i = 0; $i <= $len - 1; $i++)
        {
            if(!($localizare[$i] >= '0' and $localizare[$i] <= '9') and $localizare[$i] != '.' and $localizare[$i] != '-')
            {
                return False;
            }
        }

        return True;
    }

    function passLenValidation($pass)
    {
        $len = strlen($pass);

        if($len < 3)
        {
            return False;
        }

        else
        {
            return True;
        }
    }

    function form1($withErrors)
    {
        $errorString = "";
        $validString = "";

        if(isset($_POST['username']) and $_POST['username'] != "")
        {
            if(usernameValidation($_POST['username']))
            {
                $validString .= "The username has been updated!";
                updateUserData('username', $_COOKIE['userID']);
            }

            else
            {
                $errorString .= "The username is invalid";
            }
        }

        if(isset($_POST['firstname']) and $_POST['firstname'] != "")
        {
            if(nameValidation($_POST['firstname']))
            {
                $validString .= "<br>The firstname has been updated!";
                updateUserData('firstname', $_COOKIE['userID']);
            }

            else
            {
                $errorString .= "<br>The first name is invalid!";
            }
        }

        if(isset($_POST['lastname']) and $_POST['lastname'] != "")
        {
            if(nameValidation($_POST['lastname']))
            {
                $validString .= "<br>The surname has been updated!";
                updateUserData('lastname', $_COOKIE['userID']);
            }

            else
            {
                $errorString .= "<br>The surname is invalid!";
            }
        }

        if(isset($_POST['password']) and $_POST['password'] != "")
        {
            if(passLenValidation($_POST['password']))
            {
                $validString .= "<br>The password has been updated!";
                updateUserPass($_COOKIE['userID']);
            }

            else
            {
                $errorString .= "<br>The password is invalid! It must be at least 3 characters long.";
            }
        }

        if($withErrors)
        {
            return $errorString;
        }
        
        else
        {
            return $validString;
        }

        
    }

    function form2($withErrors)
    {
        $errorString = "";
        $validString = "";
        $ok = 1;

        if(isset($_POST['latitude']))
        {
            if(localizationValidation($_POST['latitude']))
            {
                //$validString .= "Numele de utilizator a fost actualizat!";
                $ok1 = 1;
            }

            else
            {   
                $ok1 = 0;
                //$errorString .= "Numele de utilizator este invalid!";
            }
        }

        if(isset($_POST['longitude']))
        {
            if(localizationValidation($_POST['longitude']))
            {
                //$validString .= "<br>Prenumele a fost actualizat!";
                
                $ok2 = 1;
            }

            else
            {   
                $ok2 = 0;
                //$errorString .= "<br>Prenumele este invalid!";
            }
        }

        if(isset($_POST['latitude']) and isset($_POST['longitude']))
        {
            if($withErrors)
            {
                if($ok1 == 0 or $ok2 == 0)
                {
                    return "Incorrect latitude and / or longitude!";
                }

                return "";
            }
            
            else
            {
                if($ok != 0 and $ok2 != 0)
                {
                    updateUserData('latitude', $_COOKIE['userID']);
                    updateUserData('longitude', $_COOKIE['userID']);
                    return "Modified latitude / longitude!";
                }

                return "";
            }
        }
    }?>
    <div class="containerLoading">
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
        </div>

<div id="wrapper" class="appear">
        <div class="d-flex flex-column" id="content-wrapper">
        <div id="content"  style="background: transparent;">
            
        <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top" style="position: absolute; top: 0; width: 100vw; z-index: 1;">
				<div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                <div class="list-group list-group-horizontal" style=" height: 50px; z-index: 0;"><a class="list-group-item list-group-item-action active text-nowrap text-left" style="width: 200px; left: 110%; transform: transitionX(-50%); background-color: white; color:black; border-color: gray;" href="/index.php"><i class="fas fa-tachometer-alt"></i><span style="margin: 3px;">Control Panel</span><a href="/index.php"></a></a>
                            <a class="list-group-item list-group-item-action text-center  bg-success" href="/profile.php" style="background: rgb(255,255,255); left: 110%; transform: transitionX(-50%);"><i class="fas fa-user"></i><span>Profile</span></a>
                            <a href="/profile.php"></a>
                            <a class="list-group-item list-group-item-action text-center" style="width:15rem; left: 110%; transform: transitionX(-50%);" href="/messages.php"><i class="fa fa-commenting"></i><span>Contact</span></a></div>
                            <ul class="nav navbar-nav flex-nowrap ml-auto">
						<li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-search"></i></a>
							<div class="dropdown-menu dropdown-menu-right p-3 animated--grow-in" aria-labelledby="searchDropdown">
								<form class="form-inline mr-auto navbar-search w-100">
									<div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
										<div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
									</div>
								</form>
							</div>
						</li>
						<li class="nav-item dropdown no-arrow mx-1"></li>
						<div class="d-none d-sm-block topbar-divider"></div>
						<li class="nav-item dropdown no-arrow">
							<div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600"><?php echo returnUsernameFromID($_COOKIE["userID"]);?></span><img class="border rounded-circle img-profile" src="img/avatar.jpg"></a>
								<div class="dropdown-menu shadow dropdown-menu-right animated--grow-in">
									<a class="dropdown-item" href="profile.php"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a>
										<div class="dropdown-divider"></div><a class="dropdown-item" href="#" onclick="window.location.replace('disconnect.php')"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Disconnect</a></div>
				</div>
				</li>
                </ul>
</div>
</nav>
            <div class="container-fluid w-50 mb-3 h-25">
                <h3 class="text-dark mb-2">Profile</h3>
                <div class="row mb-3 ">
                    <div class="col">
                        <div class="card  mt-2">
                            <div class="card-header py-3">
                                <p class=" m-0 font-weight-bold text-success">User Data</p>
                            </div>
                            <div class="card-body mb-2" >
                                <form action="profile.php" method="POST">
                                    <div class="form-row">
                                        <div class="col">
                                            <!-- <div class="form-group"><label for="username"><strong>Username</strong></label><input class="form-control" type="text" placeholder="<?php echo getColumnDataFromID("username", $_COOKIE["userID"]); ?>" name="username"></div> -->
                                            <span class="input_letters input--minoru">
				                            	<input class="input__field input__field--yoko" type="text" id="input-16" placeholder="<?php echo getColumnDataFromID("username", $_COOKIE["userID"]); ?>" name="username"/>
				                            	<label class="input__label input__label--yoko" for="input-16">
				                            		<span class="input__label-content input__label-content--yoko">Username</span>
				                            	</label>
				                            </span>
                                        </div>
                                        <div class="col">
                                            <!-- <div class="form-group"><label for="password"><strong>Password</strong></label><input class="form-control" type="password" placeholder="New Password" name="password"></div> -->
                                            <span class="input_letters input--minoru">
				                            	<input class="input__field input__field--yoko" id="input-16" type="password" placeholder="New Password" name="password"/>
				                            	<label class="input__label input__label--yoko" for="input-16">
				                            		<span class="input__label-content input__label-content--yoko">Password</span>
				                            	</label>
				                            </span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <!-- <div class="form-group"><label for="first_name"><strong>Firstname</strong></label><input class="form-control" type="text" placeholder="<?php echo getColumnDataFromID("firstname", $_COOKIE["userID"]); ?>" name="firstname"></div> -->
                                            <span class="input_letters input--minoru">
				                            	<input class="input__field input__field--yoko" type="text" id="input-16" placeholder="<?php echo getColumnDataFromID("firstname", $_COOKIE["userID"]); ?>" name="firstname"/>
				                            	<label class="input__label input__label--yoko" for="input-16">
				                            		<span class="input__label-content input__label-content--yoko">Firstname</span>
				                            	</label>
				                            </span>
                                        </div>
                                        <div class="col">
                                            <!-- <div class="form-group"><label for="last_name"><strong>Name</strong><br></label><input class="form-control" type="text" placeholder="<?php echo getColumnDataFromID("lastname", $_COOKIE["userID"]); ?>" name="lastname"></div> -->
                                            <span class="input_letters input--minoru">
				                            	<input class="input__field input__field--yoko" type="text" id="input-16" placeholder="<?php echo getColumnDataFromID("lastname", $_COOKIE["userID"]); ?>" name="lastname"/>
				                            	<label class="input__label input__label--yoko" for="input-16">
				                            		<span class="input__label-content input__label-content--yoko">Lastname</span>
				                            	</label>
				                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group" style="display: flex;">
								<button class="btn btn-success btn-sm" style="height: 3rem" type="submit" onclick="window.location.replace('index.php');">Save</button>
                                <div style="justify-concent:center; align-items: center; margin-left:15rem;"> 
											<p style="margin-top:auto;margin-left:auto;font-size: 1.3rem;" class=" text-success"><?php $string =  form1(0); echo $string; if($string != ""){ echo "<script>window.setTimeout(function(){ window.location.href = 'profile.php';}, 3000);</script>";} ?></p>
								</div>
								<div style="justify-concent:center; align-items: center; margin-left:15rem;"> 
											<p style="margin-top:auto;margin-left:auto;font-size: 1.3rem;" class=" text-danger"><?php echo form1(1); ?></p>
								</div>
							</div>
                                </form>
                            </div>
                        </div>
                        <div class="card" style="margin-top: 1rem; margin-bottom: 14rem;">
						<div class="card-header py-3">
							<p class="m-0 font-weight-bold text-success ">Location</p>
						</div>
						<div class="card-body">
						<form action="profile.php" method="POST">
								<!-- <div class="form-group"><label for="address"><strong>Latitude</strong></label><input class="form-control" type="text" placeholder="<?php echo getColumnDataFromID("latitude", $_COOKIE["userID"]);?>" name="latitude" required></div> -->
								
                                <span class="input_letters input--minoru">
                                <input class="input__field input__field--yoko" type="text" id="input-16" placeholder="<?php echo getColumnDataFromID("latitude", $_COOKIE["userID"]);?>" name="latitude"/>
					<label class="input__label input__label--yoko" for="input-16">
						<span class="input__label-content input__label-content--yoko">Latitude</span>
					</label>
				</span>
                                <div class="form-row">
									<div class="col">
										<!-- <div class="form-group"><label for="city"><strong>Longitude</strong></label><input class="form-control" type="text" placeholder="<?php echo getColumnDataFromID("longitude", $_COOKIE["userID"]);?>" name="longitude" required></div> -->
                                        <span class="input_letters input--minoru">
				                        	<input class="input__field input__field--yoko" type="text" id="input-16" placeholder="<?php echo getColumnDataFromID("longitude", $_COOKIE["userID"]);?>" name="longitude"/>
				                        	<label class="input__label input__label--yoko" for="input-16">
				                        		<span class="input__label-content input__label-content--yoko">Longitude</span>
				                        	</label>
				                        </span>
                                    </div>
								</div>
								
								<div class="form-group" style="display: flex;">
								<button class="btn btn-success btn-sm" style="height: 3rem" type="submit">Save</button>
								<div style="justify-concent:center; align-items: center; margin-left:15rem;"> 
											<p style="margin-top:auto;margin-left:auto;font-size: 1.3rem;" class=" text-success"><?php echo form2(0); ?></p>
		
								</div>
								<div style="justify-concent:center; align-items: center; margin-left:15rem;"> 
											<p style="margin-top:auto;margin-left:auto;font-size: 1.3rem;" class=" text-danger"><?php echo form2(1); ?></p>
								</div>
								</div>
								<div>Click <a class="text-success" data-toggle="collapse" aria-controls="collapse-1" href="#collapse-1" role="button">here</a> for a short tutorial on how to find out your geographical coordinates.
								<div class="collapse" id="collapse-1">
									<div class="card">
										<div class="card-body">
											<h4 class="card-title">On Desktop Devices:</h4>
											<div class="row">
												<div class="col" style="width: 543px;margin-left: 0px;">
													<h6 class="text-muted mb-2">Access Google Maps.</h6>
													<p style="max-width: 100%;">Right-click on the desired location.&nbsp;&nbsp;</p>
                                                    <p style="width: 30rem;">In the following menu, click on the coordinates to copy them.&nbsp;</p>
												</div>
												<div style="display: flex;">
												<div class="col"><img src="images/s1.png" style="width: 15rem; height:15rem;"></div>
												</div>
											</div>
										</div>
										<div class="card">
										<div class="card-body">
											<h4 class="card-title">On Mobile Devices:</h4>
											<div class="row">
												<div class="col" style="width: 543px;margin-left: 0px;">
													<h6 class="text-muted mb-2">Access Google Maps.</h6>
													<p style="max-width: 100%;">Press and hold on the desired location.&nbsp;&nbsp;</p>
													<p>Click on the menu at the bottom of the screen.&nbsp;&nbsp;</p>
													<p style="width: 30rem;">In the following menu, touch the coordinates to copy them.&nbsp;</p>
												</div>
												<div style="display: flex;">
												<div class="col"><img src="images/s2.jpg" style="width: 9rem; height:15rem;"></div>
												<div class="col"><img src="images/s3.jpg" style="width: 9rem; height:15rem;"></div>
												</div>
											</div>
										</div>
										<div  style="margin-left:10px;">For even more details, click <a class="text-success" href="https://support.google.com/maps/answer/18539?co=GENIE.Platform%3DDesktop&hl=ro">here</a>.
									</div>
								</div>
							</div>
							</div>
								
							</form>
						</div>
					</div>
                </div>
            </div>
        </div>
            
    </div>
    <!-- <div class="bg-effect" style="z-index: 5!important;"></div> -->
    
    <footer class="text-light bg-success footer-pos appear" style="color: rgb(231,166,26);">
        <div class="container">
            <div class="text-center my-auto copyright" style="font-size: 17px;">
            <span style="text-align: center;">Copyright © Floarea Soarelui 2021</span>
            <div>
            <a href="contacts.php" style=" text-decoration: none; margin-top: 3rem;"><img src="img/logoico.png" alt=""  style="width: 3rem;height:3rem;margin-top:1.5rem;"></a>
            </div> 
            <a href="contacts.php" style=" text-decoration: none; margin-top: 3rem;"><span style="text-align: center; margin-top: 3rem;">ABOUT US</span></a>
        </div>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>