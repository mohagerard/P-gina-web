<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file

$con = mysqli_connect("localhost", "servergym", "servergym123", "servergym");
 
// Check connection
if($con === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// mysqli_close($con);


 
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] === "POST"){
 
    // Check if email is empty
    if(empty(trim($_POST["Email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["Email"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["Password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["Password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT NIF_soci, Email, Password FROM Socis WHERE Email = ? ";
         

        if($stmt = mysqli_prepare($con, $sql)){
            
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $_POST["Email"]);
            
            // Set parameters
            $param_email = $email; 
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                
                mysqli_stmt_store_result($stmt);
                // Check if email exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $NIF, $email, $password);
                    if(mysqli_stmt_fetch($stmt)){

                        echo "$password";

                        if($password == $_POST["Password"]){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["NIF_soci"] = $NIF;
                            $_SESSION["Email"] = $email;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/login.css">
    <style>
        html {
            background: url(/imatges/fondo.jpg) no-repeat center fixed;
            background-size: cover;
        }

    </style>
    <div class="logo"><a href="/imatges/logo.png"><img src="/imatges/logo.png"></a>
    </div>
</head>

<body>

    <div class="wrapper" id="all">
        <header>
            <h1>Gimnàs urgell</h1>
        </header>

        <!--NAVERGADOR BÀSIC-->
        
        <div id="log">
            <h2>Login:</h2>
            <p>Si us plau, completa les caselles per accedir.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label>Email</label>
                    <input type="email" name="Email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Password</label>
                    <input type="password" name="Password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
            </form>
        </div>
        <footer>
            <h3>Contacte</h3>
            <p>+34 936 274 369 <br> gimnasurgell@urgell.cat</p>
            <h4>Direcció</h4>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2896.899133758291!2d1.138288232236692!3d41.63878416097292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xafbb60cd111e20f3!2sInstitut%20Alfons%20Costafreda!5e0!3m2!1ses!2ses!4v1606416515835!5m2!1ses!2ses" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </footer>
    </div>
</body>

</html>
