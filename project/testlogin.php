<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
        <title>Авторизация</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/Main.css">
        
    </head>
    <body>
        <?php require "blocks/header.php";?>
        <div class="login-box">
			<form action="login.php" id="login" method="POST" name="login" > 
                <div align="center" class="h3 mb-3 font-weight-normal">Авторизация</div>								
                <div class="container input-group-test">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><img src="img/icons/person-check-fill.svg" alt="" width="16" height="16" title="Bootstrap"/></span>
                    </div>
                    <input type="text" name="user" class="form-control" id="user"placeholder="Логин" value="" />
                </div>
                <div class="input-group-test container">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><img src="img/icons/key.svg" alt="" width="16" height="16" title="Bootstrap"/></span>
                    </div>  
                    <input type="password" name="pass" class="form-control" placeholder="Пароль" value="">
                </div>		
                <div align="center">
                    <br>
                    <input type="submit" class="superbutton" value="Войти" name="do_login">
                </div>
			</form>
        </div>
        <?php require "blocks/footer.php"?>
    </body>
</html>