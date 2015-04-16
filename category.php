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

if (isset($_POST['email'])) {
  $loginUsername=$_POST['email'];
  $password=$_POST['password'];
  $newPassword = md5(sha1($password));
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_conn, $conn);
  
  $LoginRS__query=sprintf("SELECT email, password, last_name FROM user_account WHERE email=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($newPassword, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $conn) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
	
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	  
	$_SESSION['loggedInUser'] = mysql_fetch_assoc($LoginRS);
	
	
	
	//echo $userLastName;
	//echo $_SESSION['MM_Username'];
	
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
<?php 
if(isset($_SESSION['loggedInUser'])){
	$loggedInUser = $_SESSION['loggedInUser'];
	$userLabel = $loggedInUser['last_name'];
}
else{
	$loggedInUser = false;
	$userLabel = "Login";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>iDreams</title>
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

		<!-- scripts -->
		<script src="themes/js/jquery-1.7.2.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>				
		<script src="themes/js/superfish.js"></script>	
		<script src="themes/js/jquery.scrolltotop.js"></script>
</head>

	<body>
    	<div id="top-bar" class="container">
			<div class="row">
				<div class="span4">
					<form method="POST" class="search_form">
						<input type="text" class="input-block-level search-query" Placeholder="eg. T-sirt">
					</form>
				</div>
				<div class="span8">
					<div class="account pull-right">
						<ul class="user-menu">			
							<li><a href="cart.php">My Cart</a></li>
							<li><a href="checkout.php">Checkout</a></li>				
							<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?php echo $userLabel;?>
                            <span class="caret"></span></a>
                            	<div class="dropdown-menu">
                                <?php if(!$loggedInUser){ ?>
              						<form action="<?php echo $loginFormAction; ?>" id="form_login" style="margin: 0; padding: 3px 5px;" accept-charset="utf-8" method="POST">
               							 <fieldset class="control-group">
                    							<div class="controls">
                        								<div class="input-prepend" style="display: inline">
                           									 <span class="add-on"><i class="icon-envelope"></i></span>
                                                             	<input type="email" placeholder="Email Address"  name="email" id="form_email" autocomplete="on" class="span2">
                        								</div>
                    							</div>
                						</fieldset>
                						<fieldset class="control-group">
                   								 <div class="controls">
                        								<div class="input-prepend" style="display: inline">
                            								<span class="add-on"><i class="icon-lock"></i></span>
                                                       	    <input type="password" placeholder="Password" name="password" id="form_password" class="span2">
                        						        </div>
                    							  </div>
               						 </fieldset>
                                     	<p id="forgotPassword"><a href="#">Forgot Your Password?</a>
                						<p class="navbar-text"><button type="submit" class="btn btn-inverse large" id="loginButton">Sign In</button></p>
                                     	<p id="createAccount"><a href="register.php">Create New Account</a>
            					</form>
                                <?php } else { ?>
                                <ul class="list-group">
                                  <li class="list-group-item"><a href="logout.php">Logout</a></li>
                                </ul>
                                <?php } ?>
       					      </div>
                            </li>		
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div id="wrapper" class="container">
			<section class="navbar main-menu">
				<div class="navbar-inner main-menu">				
					<a href="index.php" class="logo pull-left"><img src="themes/images/logo.png" class="site_logo" alt=""></a>
                    <nav id="menu" class="pull-right">
                        <?php require('menu.php'); ?>
					</nav>
				</div>
			</section>
			<section class="main-content">
				<div class="row">
							<div class="span12">
                            	<br />
								<div id="myCarousel" class="myCarousel carousel slide">
									<div class="carousel-inner">
										<div class="active item">
											<ul class="thumbnails">												
												<li class="span3">
													<div class="product-box">
														<span class="sale_tag"></span>
														<p><a href="product_detail.php"><img src="themes/images/ladies/1.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Ut wisi enim ad</a><br/>
														<a href="products.php" class="category">Commodo consequat</a>
														<p class="price">$17.25</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<span class="sale_tag"></span>
														<p><a href="product_detail.php"><img src="themes/images/ladies/2.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Quis nostrud exerci tation</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$32.50</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/3.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Know exactly turned</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$14.20</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/4.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">You think fast</a><br/>
														<a href="products.php" class="category">World once</a>
														<p class="price">$31.45</p>
													</div>
												</li>
											</ul>
										</div>
										<div class="item">
											<ul class="thumbnails">
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/5.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Know exactly</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$22.30</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/6.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Ut wisi enim ad</a><br/>
														<a href="products.php" class="category">Commodo consequat</a>
														<p class="price">$40.25</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/7.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">You think water</a><br/>
														<a href="products.php" class="category">World once</a>
														<p class="price">$10.45</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/8.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Quis nostrud exerci</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$35.50</p>
													</div>
												</li>																																	
											</ul>
										</div>
									</div>							
								</div>
                                <br />
                                <div id="myCarousel" class="myCarousel carousel slide">
									<div class="carousel-inner">
										<div class="active item">
											<ul class="thumbnails">												
												<li class="span3">
													<div class="product-box">
														<span class="sale_tag"></span>
														<p><a href="product_detail.php"><img src="themes/images/ladies/1.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Ut wisi enim ad</a><br/>
														<a href="products.php" class="category">Commodo consequat</a>
														<p class="price">$17.25</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<span class="sale_tag"></span>
														<p><a href="product_detail.php"><img src="themes/images/ladies/2.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Quis nostrud exerci tation</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$32.50</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/3.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Know exactly turned</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$14.20</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/4.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">You think fast</a><br/>
														<a href="products.php" class="category">World once</a>
														<p class="price">$31.45</p>
													</div>
												</li>
											</ul>
										</div>
										<div class="item">
											<ul class="thumbnails">
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/5.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Know exactly</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$22.30</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/6.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Ut wisi enim ad</a><br/>
														<a href="products.php" class="category">Commodo consequat</a>
														<p class="price">$40.25</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/7.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">You think water</a><br/>
														<a href="products.php" class="category">World once</a>
														<p class="price">$10.45</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/8.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Quis nostrud exerci</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$35.50</p>
													</div>
												</li>																																	
											</ul>
										</div>
									</div>							
								</div>
                                <br />
                                <div id="myCarousel" class="myCarousel carousel slide">
									<div class="carousel-inner">
										<div class="active item">
											<ul class="thumbnails">												
												<li class="span3">
													<div class="product-box">
														<span class="sale_tag"></span>
														<p><a href="product_detail.php"><img src="themes/images/ladies/1.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Ut wisi enim ad</a><br/>
														<a href="products.php" class="category">Commodo consequat</a>
														<p class="price">$17.25</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<span class="sale_tag"></span>
														<p><a href="product_detail.php"><img src="themes/images/ladies/2.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Quis nostrud exerci tation</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$32.50</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/3.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Know exactly turned</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$14.20</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/4.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">You think fast</a><br/>
														<a href="products.php" class="category">World once</a>
														<p class="price">$31.45</p>
													</div>
												</li>
											</ul>
										</div>
										<div class="item">
											<ul class="thumbnails">
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/5.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Know exactly</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$22.30</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/6.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Ut wisi enim ad</a><br/>
														<a href="products.php" class="category">Commodo consequat</a>
														<p class="price">$40.25</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/7.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">You think water</a><br/>
														<a href="products.php" class="category">World once</a>
														<p class="price">$10.45</p>
													</div>
												</li>
												<li class="span3">
													<div class="product-box">
														<p><a href="product_detail.php"><img src="themes/images/ladies/8.jpg" alt="" /></a></p>
														<a href="product_detail.php" class="title">Quis nostrud exerci</a><br/>
														<a href="products.php" class="category">Quis nostrud</a>
														<p class="price">$35.50</p>
													</div>
												</li>																																	
											</ul>
										</div>
									</div>							
								</div>
							</div>						
						</div>
			</section>
			<section id="copyright">
				<span>Copyright 2015 iDreams All right reserved.</span>
			</section>
	</body>
</html>
