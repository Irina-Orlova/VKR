<?php 
require "session_start.php";
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Авторизация</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/Main.css">
		<script src="js/jquery-3.4.1.js"></script>
		

	<?php
require_once 'connect.php';

$conn = OpenConnection(); 
//$_SESSION['logged_user']=$login_user;
//$_SESSION['password_user']=$password;

echo "<style type='text/css'>
			#exit { display: none; }
			</style>";
			if ($_SESSION['mess']){
				echo $_SESSION['mess'];
			   }
			   unset($_SESSION['mess']);

if(!empty($_REQUEST['login_user']) and !empty($_REQUEST['password_user']))
 {
	$errors=array();
	$login_user =strip_tags(trim($_POST['login_user']));
	$password_user=strip_tags($_POST['password_user']);

	$query = "SELECT [id_users],[login_user],[pwd_user],[BasePPS].[dbo].[Spr_roles].id_role,[BasePPS].[dbo].[Spr_roles].name_role
	FROM [BasePPS].[dbo].[Spr_Users] inner join [BasePPS].[dbo].[Spr_roles]
	On [BasePPS].[dbo].[Spr_Users].id_role = [BasePPS].[dbo].[Spr_roles].id_role WHERE login_user='".$login_user."' AND pwd_user='".$password_user."'";
	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt = sqlsrv_query($conn, $query);//, $params, $options
	$result = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);

	
	if ($result)
	{ 
		echo '<div style="color:green;" class="alert alert-success" role="alert" id="message">Авторизация прошла успешно!<hr></div>';
		$_SESSION['login_user'] = $login_user;
		$_SESSION['password_user'] = $password_user;
		echo "<style type='text/css' >.aut { display: none; }</style>";	
	}
	else
	{
		$errors[]='Неверно введен логин или пароль!';
		echo '<div style="color:red;" class="alert alert-danger" role="alert" id="message">'.array_shift($errors).'<hr></div>';
	}
		
}
require "blocks/header.php";
	   include 'spr.php';


//
?>
	</head>
	<body>

		<div class='container'>

		<form action="login.php" id="login" method="POST" name="login" class="aut"> 
		
			<div align="center" class="h3 mb-3 font-weight-normal text-center" id="text-name">Авторизация</div>	
													
			<div class="input-group col-md-4 offset-md-4">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-check-fill.svg" alt="" width="16" height="16" title="Bootstrap"/></span>
				</div>		
					<input type="text" name="login_user" class="login_user form-control col-sm-50" id="login_user" placeholder="Логин" value="" required />
			</div>
			<div class="input-group col-md-4 offset-md-4">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1"><img src="img/icons/key.svg" alt="" width="16" height="16" title="Bootstrap"/></span>
				</div>  
				<input type="password" name="password_user" class="form-control" placeholder="Пароль" value="" required>
			</div>				
				<div class="input-group col-md-3 offset-md-3">
	
					<br><input type="submit" class="superbutton " value="Войти" name="do_login" align="center">
				
				</div>
		
		</form>
	

		<div class="hFooter"></div>
	</div>
		<div class="clear"></div>
   <?php 

   require "blocks/footer.php"?>
<script src="js/script.js?version={version}"></script>
	</body>
</html>