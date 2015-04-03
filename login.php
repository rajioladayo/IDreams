<?php require_once('Connections/conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $newPassword = md5(sha1($password));
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conn, $conn);
  
  $LoginRS__query=sprintf("SELECT email, password FROM user_account WHERE email=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($newPassword, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Bootstrap E-commerce Templates</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<!--[if ie]><meta content='IE=8' http-equiv='X-UA-Compatible'/><![endif]-->
		<!-- bootstrap -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">      
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">		
		<link href="themes/css/bootstrappage.css" rel="stylesheet"/>
		
		<!-- global styles -->
		<link href="themes/css/flexslider.css" rel="stylesheet"/>
		<link href="themes/css/main.css" rel="stylesheet"/>
        
        
		<link href="bootstrap/css/style.css" rel="stylesheet">
		<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
		<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css"> 

		<!-- scripts -->
		<script src="themes/js/jquery-1.7.2.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>				
		<script src="themes/js/superfish.js"></script>	
		<script src="themes/js/jquery.scrolltotop.js"></script>
	<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
	<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
		
		<!--[if lt IE 9]>			
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
    <body>		
		<div id="wrapper" class="container">					
			<section class="main-content">				
				<div class="row">
                 <div style="margin-top:10px"></div>	
					<div class="center-block">
                    <div class="row" id="error">	
                        <span class="text"><?php
                            if(isset($_SESSION['MM_Username'])){
                                echo "Invalid username or password";
                            }	
                        ?></span>
                    </div>			
						<h4 class="title"><span class="text"><strong>Sign In</strong></span></h4>
                       	<form method="POST" name="form1" action="<?php echo $loginFormAction; ?>"form-stacked">
                                <fieldset>	
                                  <div class="control-group">Email Address
                                    <div class="controls">
                                          <span id="sprytextfield1">
                                            <label for="paswword"></label>
                                        <span class="textfieldRequiredMsg">A value is required.</span></span> </div>
                                    <span id="sprytextfield2">
                                    <label for="username"></label>
                                    <input type="text" name="username" id="username">
                                    <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>                                    </div>	
                                     <div class="control-group">
                                        <label class="control-label">Password</label>
                                        <div class="controls">
                                          <span id="spryconfirm1">
                                            <label for="password1"></label>
                                        <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span> </div>
                                       <span id="sprytextfield3">
                                        <label for="password"></label>
                                        <input type="password" name="password" id="password">
                                    <span class="textfieldRequiredMsg">A value is required.</span></span>                                     </div>
<div class="actions"><input tabindex="9" id="registerButton" class="btn btn-inverse large" type="submit" value="Sign me in">
                                  </div>
                                </fieldset>
					  </form>	
                    </div>				
				</div>
			</section>	
            <div style="margin-bottom:10px"></div>	
			<section id="copyright">
				<span>Copyright 2015 iDreams All right reserved.</span>
			</section>
		</div>
		<script src="themes/js/common.js"></script>
		<script>
$(document).ready(function() {
				$('#checkout').click(function (e) {
					document.location.href = "checkout.php";
				})
			});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
        </script>		
    </body>
</html>