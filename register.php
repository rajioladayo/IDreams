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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
	
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	  $insertSQL = sprintf("INSERT INTO user_account (first_name, last_name, email, phone, password) VALUES (%s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['first_name'], "text"),
						   GetSQLValueString($_POST['last_name'], "text"),
						   GetSQLValueString($_POST['email'], "text"),
						   GetSQLValueString($_POST['phone'], "text"),
						   GetSQLValueString($_POST['password'], "text"));
	
	  mysql_select_db($database_conn, $conn);
	  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());
	
	  $insertGoTo = "?";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
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
						<h4 class="title"><span class="text"><strong>Sign Up</strong></span></h4>
                       	<form method="post" name="form1" action="<?php echo $editFormAction; ?> class="form-stacked">
                                <fieldset>
                                    <div class="control-group">
                                        <label class="control-label">First Name</label>
                                        <div class="controls">
                                       	  <span id="sprytextfield5">
                                        	<label for="firstname"></label>
                                        	<input type="text" name="firstname" id="firstname">
                                   	  <span class="textfieldRequiredMsg">A value is required.</span></span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Last Name</label>
                                        <div class="controls">
                                       	  <span id="sprytextfield4">
                                        	<label for="lastname"></label>
                                        	<input type="text" name="lastname" id="lastname">
                                   	  <span class="textfieldRequiredMsg">A value is required.</span></span> </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Email Address</label>
                                        <div class="controls">
                                       	  <span id="sprytextfield3">
                                          <label for="email"></label>
                                          <input type="text" name="email" id="email">
                                      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span> </div>
                                    </div>	
                                    <div class="control-group">
                                        <label class="control-label">Phone Number</label>
                                        <div class="controls">
                                       	  <span id="sprytextfield2">
                                          <label for="phone"></label>
                                          <input type="text" name="phone" id="phone">
                                      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span> </div>
                                    </div>	
                                  <div class="control-group">
                                        <label class="control-label">Password</label>
                                        <div class="controls">
                                          <span id="sprytextfield1">
                                            <label for="paswword"></label>
                                            <input type="text" name="paswword" id="paswword">
                                        <span class="textfieldRequiredMsg">A value is required.</span></span> </div>
                                    </div>	
                                    <span id="spryconfirm1">
                                    <label for="password1"></label>
                                    <input type="password" name="password1" id="password1">
                                    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span>
<div class="actions"><input tabindex="9" id="registerButton" class="btn btn-inverse large" type="submit" value="Sign me up">
                                  </div>
                                </fieldset>

                                <input type="hidden" name="MM_insert" value="form1">
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
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "phone_number", {format:"phone_custom"});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "email");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
        </script>		
    </body>
</html>