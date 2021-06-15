<?php
require "session_start.php";
require_once "connect.php";

$conn = OpenConnection(); 
	
$query = "SELECT [id_users],[login_user],[pwd_user],[BasePPS].[dbo].[Spr_roles].id_role,[BasePPS].[dbo].[Spr_roles].name_role
	FROM [BasePPS].[dbo].[Spr_Users] inner join [BasePPS].[dbo].[Spr_roles]
	On [BasePPS].[dbo].[Spr_Users].id_role = [BasePPS].[dbo].[Spr_roles].id_role WHERE login_user='".$_SESSION['login_user']."' AND pwd_user='".$_SESSION['password_user']."'";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_part = sqlsrv_query($conn, $query, $params, $options);
$row_count = sqlsrv_num_rows($stmt_part); 

?>
<div class="container-fluid">
	<section>
<nav class="spr form-group row">	
	<ul>
	<?php if ($row_count<=0){?>
		<li><a href="AuthorPubl.php" style="color:white;">Просмотр</a>
		</li>
			<ul>
				<li><a href="###">###</a></li>
			</ul>
		</li>
		<li class="menu-right"><a href="login.php" >Вход</a></li>
	</ul><?php

}
else{
?>
		<li><a href="AuthorPubl.php" style="color:white;">Просмотр</a>
		<ul>
				<li><a href="edition.php" style="color:white;">Публикации</a></li>
			</ul>
		
		</li>
		<li><a href="AddUser.php">Добавление пользователя</a>
			<ul>
				<li><a href="###">###</a></li>
			</ul>
		</li>
		<li>
			<a href="http://localhost:44380/" style="color: white;">Экспорт</a>
		</li>
		<li>
			<a href="http://localhost:44380/" style="color: white;">Отчет</a>
		</li>
		<li class="menu-right"><a href="logout.php" >Выход</a></li>
	</ul>	
	<?php }
?>			
</nav>
	</section>
	<div class="hFooter"></div>
</div>	
