<?php
// Include config file
require_once "dbconnect.php";
 
// Define variables and initialize with empty values
$fname = $lname = $company = $email = $username = $password = $confirm_password = "";
$fname_err = $lname_err = $company_err = $email_err = $username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM client_portal_user_register WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($fname_err) && empty($lname_err) && empty($company_err) && empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO client_portal_user_register (fname, lname, company, email, username, password) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_fname, $param_lname, $param_company, $param_email, $param_username, $param_password);
            
            // Set parameters
			$param_fname = $_POST['fname'];
			$param_lname = $_POST['lname'];
			$param_company = $_POST['company'];
			$param_email = $_POST['email'];
            $param_username = $_POST['username'];
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
               ?>
		  		<!-- message will pop up in a window and redirect me to the next page -->
		       <script type="text/javascript">
		       alert("Registration Successfull!");
		       window.location.href= "clientlogin.php";
		       </script>
		       <?php
            } else{
                ?>
		  		<!-- message will pop up in a window and redirect me to the next page -->
		       <script type="text/javascript">
		       alert("Registration failed, Try again!");
		       window.location.href= "clientregistration.php";
		       </script>
		       <?php
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client Portal Registration</title>

  
    

    <!-- Main css -->
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

    <div class="main">

        <div class="container">
            <div class="signup-content">
                <div class="signup-img">
                    <img src="Images/form-img.jpg" alt="">
                    <div class="signup-img-content">
                        <h2>Kaneya Tec Solutions, Client Portal</h2>
                        <p>Register Now</p>
                    </div>
                </div>
                <div class="signup-form">
                   <form action="clientregistration.php"<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-row">
                            <div class="form-group">
                    <div class="form-input <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                               <label>First Name</label>
                               <input type="text" name="fname" required="" class="form-control" value="<?php echo $fname; ?>">
                    <span class="help-block"><?php echo $fname_err; ?></span>
                    </div>
                    <div class="form-input <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>">
                               <label>Last Name</label>
                               <input type="text" name="lname" required="" class="form-control" value="<?php echo $lname; ?>">
                    <span class="help-block"><?php echo $lname_err; ?></span>
                     </div>
                    <div class="form-input <?php echo (!empty($company_err)) ? 'has-error' : ''; ?>">
                               <label>Company Name</label>
                               <input type="text" name="company" required="" class="form-control" value="<?php echo $company; ?>">
                    <span class="help-block"><?php echo $company_err; ?></span>
                     </div>
                    <div class="form-input <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                               <label>Email Address</label>
                               <input type="text" name="email" required="" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-input <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                               <label>Username</label>
                               <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                    </div> 
					<div class="form-input <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                               <label>Password</label>
                               <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
					<div class="form-input <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                               <label>Confirm Password</label>
                               <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                            </div>
                        </div>
                        <div class="form-submit">
                            <input type="submit" value="Submit" class="submit" id="submit" name="submit" />
                            <input type="submit" value="Reset" class="submit" id="reset" name="reset" />
                        </div>
						<h3 style="margin-right:470px; text-align:right; font-size: 15px;">Already have an account? <a href="clientlogin.php">Login Here</a>.</h3>
                    </form>
                </div>
            </div>
        </div>

    </div>

    
  
</body>
</html>