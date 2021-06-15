<?php require "session_start.php";?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Добавление пользователя</title>
		
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/Main.css">
	
		<script src="js/jquery-3.4.1.js"></script>
		<script src="js/maskinput.js"></script>
	
		
	</head>
	<body>
	<?php
require_once 'connect.php';
$conn = OpenConnection(); 

if ($_SESSION['login_user']=='' && $_SESSION['password_user']=='')
{
	echo "<style type='text/css' >.bgr2 { display: none; }</style>";
	echo '
	<div class="alert alert-danger d-flex align-items-center" role="alert">
	<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
	  <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
	</svg>
	<div>
		Ошибка! Авторизуйтесь, пожалуйста!
	</div>
  </div>';
}
else {}

$data=$_POST;
 if (!empty($_REQUEST['do_login']) )
 {
	if (trim($_POST['LastName'])=='')	
	{
		$message[]='Фамилия не введена. Введите, пожалуйста!'.$LastName;
	}	
	 if (trim($_POST['FirstName'])=='')	
	{
		$message[]='Имя не введено. Введите, пожалуйста!'.$LastName;
	}	
	 if (trim($_POST['Patronymic'])=='')	
	{
		$message[]='Отчество не введено. Введите, пожалуйста!'.$LastName;
	}	
	 if (trim($_POST['login_user'])=='')
	{
		$message[]='Логин пользователя не введен. Введите, пожалуйста!';
	}
	 if (trim($_POST['password_user'])=='')
	{
		$message[]='Пароль не введен. Введите, пожалуйста!';
	}
	
	 if($_POST["check_password"] !== $_POST["password_user"])	
	{
		$message[]='Пароли не совпадают. Введите повторно!';
	}

	$SelectLog="SELECT id_users, login_user from Spr_Users where login_user='".trim($_POST['login_user'])."'";
	$params = array();
	$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);
	$stmt_SelectLog = sqlsrv_query($conn, $SelectLog, $params, $options);	
	$row_count = sqlsrv_num_rows($stmt_SelectLog); 	
	if ($row_count)
	{
		$message[]='Пользователь с таким логином уже существует!';
	}

	if (empty($message))
	{
			$Users="Select 
			LastName,
			FirstName, 
			Patronymic,
			login_user,
			pwd_user,
			id_role,
			UID
			from Spr_Users
			where LastName='".iconv("UTF-8","cp1251",trim($_POST["LastName"]))."' 
			and FirstName='".iconv("UTF-8","cp1251",trim($_POST["FirstName"]))."' 
			and Patronymic='".iconv("UTF-8","cp1251",trim($_POST["Patronymic"]))."'
			and id_role=6";
			$stmt_Users = sqlsrv_query($conn, $Users, $params, $options);	
			$row_Users = sqlsrv_num_rows($stmt_Users); 	
			if ($row_Users)
			{
				//echo "<br> Строка в бд есть";
				$message_error[]='Пользователь роли Автор уже существует в системе! Вы можите авторизоваться!';
				$_SESSION['mess'] = '<div style="color:red;" class="alert alert-danger" role="alert" id="message">'.array_shift($message_error).'</div><hr>';
    			exit("<meta http-equiv='refresh' content='0; url= ../login.php'>");
				
			}
			else{
				//echo "<br> Будет select в таблицу авторов";
				$FIO_Author="Select UID, 
				LastName,
				FirstName, 
				Patronymic, 
				Country, 
				JobName, 
				email, 
				Phone_Number,
				Office_depart_kod_office,
				Tab_num
				from Author
				where LastName='".iconv("UTF-8","cp1251",trim($_POST["LastName"]))."' 
				and FirstName='".iconv("UTF-8","cp1251",trim($_POST["FirstName"]))."' 
				and Patronymic='".iconv("UTF-8","cp1251",trim($_POST["Patronymic"]))."'";
				$stmt_FIO_Author = sqlsrv_query($conn, $FIO_Author, $params, $options);	
				$row_count_FIO = sqlsrv_num_rows($stmt_FIO_Author); 	
				if ($row_count_FIO)
				{
					//echo '<br> Автор есть в таблице авторы. Надо взять id';
					if(trim($_POST["office_dep"])=="NULL")
					{
						$message_error[]='Вы не являетесь сотрудником МГТУ! Можите посмотреть данные в роли "Гостя"';
					}
					else{
						$str_UID = sqlsrv_fetch_array($stmt_FIO_Author,SQLSRV_FETCH_ASSOC);
						$UID=$str_UID['UID'];	
						$Office_depart_kod_office=$str_UID['Office_depart_kod_office'];	
						$Tab_num=$str_UID['Tab_num'];

						$InsertUser="Insert into Spr_Users(
						LastName,
						FirstName,
						Patronymic,
						login_user, 
						pwd_user, 
						UID) values (
							'".iconv("UTF-8","cp1251",trim($_POST["LastName"]))."',
							'".iconv("UTF-8","cp1251",trim($_POST["FirstName"]))."',
							'".iconv("UTF-8","cp1251",trim($_POST['Patronymic']))."', 
							'".iconv("UTF-8","cp1251",trim($_POST["login_user"]))."', 
							'".iconv("UTF-8","cp1251",trim($_POST["password_user"]))."', 
							".$UID.")";	
						$stmt_InsertUser = sqlsrv_query($conn,$InsertUser);
						}
							
				}
				else{
					if(trim($_POST['office_dep'])=='NULL')
					{
						$message_error[]='Вы не являетесь сотрудником МГТУ! Можите посмотреть данные в роли "Гостя"';
					}
					else{
						$InsertUID="Insert into Author(
							LastName, 
							FirstName, 
							Patronymic, 
							Country, 
							JobName, 
							email, 
							Phone_Number, 
							Office_depart_kod_office, 
							Tab_num ) values (
								'".iconv("UTF-8","cp1251",trim($_POST["LastName"]))."'
								,'".iconv("UTF-8",'cp1251',trim($_POST["FirstName"]))."'
								,'".iconv("UTF-8","cp1251",trim($_POST["Patronymic"]))."'
								,'".iconv("UTF-8","cp1251",trim($_POST["Country"]))."'
								,'".iconv("UTF-8","cp1251",trim($_POST["JobName"]))."'
								,'".iconv("UTF-8","cp1251",trim($_POST["email"]))."'
								,'".iconv("UTF-8","cp1251",trim($_POST["Phone_Number"]))."'
								,'".iconv("UTF-8","cp1251",trim($_POST["office_dep"]))."'
								,'".iconv("UTF-8","cp1251",trim($_POST["Tab_num"]))."')";
						//echo $InsertUID;
						$stmt_InsertUID = sqlsrv_query($conn,$InsertUID);
						if (!$stmt_InsertUID){
							$message_error[]='Ошибка добавление автора!';
						}
						
						$id_UID="SELECT scope_identity() AS scope_identity"; 
						$stmt_id_UID = sqlsrv_query($conn,$id_UID);
						$str_UID = sqlsrv_fetch_array($stmt_id_UID,SQLSRV_FETCH_ASSOC);
							$UID=$str_UID['scope_identity'];	
							$UID=(int)$UID;
					
						$InsertUser="Insert into Spr_Users(
						LastName,
						FirstName,
						Patronymic,
						login_user, 
						pwd_user, 
						UID) values (
							'".iconv("UTF-8","cp1251",trim($_POST["LastName"]))."',
							'".iconv("UTF-8","cp1251",trim($_POST["FirstName"]))."',
							'".iconv("UTF-8","cp1251",trim($_POST["Patronymic"]))."', 
							'".iconv("UTF-8","cp1251",trim($_POST["login_user"]))."', 
							'".iconv("UTF-8","cp1251",trim($_POST["password_user"]))."', 
							".$UID.")";
						$stmt_InsertUser = sqlsrv_query($conn,$InsertUser);
					}
				
				}
				if (!$stmt_InsertUser){
					$message_error[]='Ошибка добавление пользователя!';
				}
				else{
				$message_true[]='Вы успешно добавили нового пользователя!';
				}
			}

			if($message_error)
			{
				echo '<div style="color:red;" class="alert alert-danger" role="alert" id="message">'.array_shift($message_error).'</div><hr>';
			}
			else{
				$_SESSION['mess']='<div style="color:green;" class="alert alert-success" role="alert" id="message">'.array_shift($message_true).'</div><hr>';
				exit("<meta http-equiv='refresh' content='0; url= login.php'>");
			}	
	}
		else
		{
			echo '<div style="color:red;" class="alert alert-danger" role="alert" id="message">'.array_shift($message).'</div><hr>';
		}
 }
 

require "blocks/header.php";
include 'spr.php';
?>
	<div class="bgr2">	
		<form action="AddUser.php" id="User" method="POST" name="User"onsubmit="call()" > 
			<div class="container">
				<div align="center" class="h3 mb-1 text-center offset-md-2">Добавление автора системы</div>	
				<div class="row">
					<div class="input-group col-md-10 offset-md-2" >
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-lines-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
						</div>		
						<input type="text" name="LastName" class="LastName form-control" id="LastName" placeholder="Фамилия" value="<?php echo $_POST['LastName']; ?>"  />
					</div>
				</div>

				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-lines-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
						</div>		
							<input type="text" name="FirstName" class="form-control" id="FirstName" placeholder="Имя" value="<?php echo $_POST['FirstName']; ?>"  />
					</div>
				</div>

				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-lines-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
						</div>		
							<input type="text" name="Patronymic" class="form-control" id="Patronymic" placeholder="Отчество" value="<?php echo $_POST['Patronymic']; ?>"  />
					</div>
				</div>
				<div class="row input-group col-md-10 offset-md-2"><input name="prov" type='button' class="prov btn btn-outline-primary" id="prov" value="Проверить"><br></div>

				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-lines-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
						</div>		
							<input type="text" name="Country" class="form-control" id="Country" placeholder="Страна" value="<?php echo $_POST['Country']; ?>" disabled />
					</div>
				</div>

				

				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-lines-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
						</div>		
							<input type="text" name="email" class="form-control" id="email"  placeholder="Email" value="<?php echo $_POST['email']; ?>"  disabled />
					</div>
				</div>

				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-lines-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
						</div>		
							<input type="tel" name="Phone_Number" class="form-control" id="Phone_Number" placeholder="Телефон" value="<?php echo $_POST['Phone_Number']; ?>" disabled   />
					</div>
				</div>
			
				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-check-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
						</div>		
							<input type="text" name="login_user" class="form-control" id="login_user" placeholder="Логин"  value="<?php echo $_POST['login_user']; ?>" disabled />
					</div>
				</div>

				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/key.svg" alt="" width="16" height="16" title="Bootstrap"/></span>
						</div>  
						<input type="password" name="password_user" class="form-control" placeholder="Пароль" id="password_user"  value="<?php echo $_POST['password_user']; ?>" disabled>
					</div>
				</div>	

				<div class="row">
					<div class="input-group col-md-10 offset-md-2">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="img/icons/lock-fill.svg" alt="" width="16" height="16" title="Bootstrap"/></span>
						</div>  
						<input type="password" name="check_password" class="form-control" placeholder="Подтвердите пароль" id="check_password"  value="<?php echo $_POST['check_password']; ?>" disabled>
					</div>	
				</div>

				<div class="row" name=off id=off>
				<div><input type="hidden" name="Tab_num" value="" /></div>
					
				</div>

				<br><input type="submit" class="superbutton" value="Добавить" name="do_login" >
				
				<div>
				
				</div>
			</div>
		</form>	
	
	</div>
   <?php require "blocks/footer.php"?>
	<script src="js/script.js?version={version}"></script>
	</body>
</html>