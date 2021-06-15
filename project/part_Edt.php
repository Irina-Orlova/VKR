<?php
require "session_start.php";
require_once 'connect.php';
$conn = OpenConnection(); 

$login_user=$_SESSION['login_user'];
$password_user=$_SESSION['password_user'];
	
$query = "SELECT [id_users],[login_user],[pwd_user],[BasePPS].[dbo].[Spr_roles].id_role,[BasePPS].[dbo].[Spr_roles].name_role
	FROM [BasePPS].[dbo].[Spr_Users] inner join [BasePPS].[dbo].[Spr_roles]
	On [BasePPS].[dbo].[Spr_Users].id_role = [BasePPS].[dbo].[Spr_roles].id_role WHERE login_user='".$login_user."' AND pwd_user='".$password_user."'";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn, $query);//, $params, $options
$result = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
$id_User=$result['id_users'];

$id_Edt=trim($_POST['id_Edt']);
$Name_part=trim($_POST['Name_part']);
$Num_Article=trim($_POST['Num_Article']);
$PageBg=trim($_POST['PageBg']);
$PageEnd=trim($_POST['PageEnd']);
$PageCount=trim($_POST['PageCount']);
$URL_Art=trim($_POST['URL_Art']);
$id_TypePart=trim($_POST['id_TypePart']);
$Part_Namelang=trim($_POST['Part_Namelang']);
$date_Ins=date("d.m.y");

$sql_part="Select id_part,
id_language,
Name_part,
NumArticle,
PageBg,
PageEnd,
PageCount,
URL_Art,
Date_Ins,
User_Ins,
Copy_id,
Date_EdA,
User_EdA,
Date_ExtractA,
edition_id_Edt,
Spr_structure_id_TypePart from structural_part
where Name_part like '".iconv("UTF-8","cp1251",$Name_part)."' and edition_id_Edt=$id_Edt";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_part = sqlsrv_query($conn, $sql_part, $params, $options);
$row_count = sqlsrv_num_rows($stmt_part); 
if ($row_count == 0){
    $InsertPart="Insert into structural_part (
    id_language,
    Name_part,
    NumArticle,
    PageBg,
    PageEnd,
    PageCount,
    URL_Art,
    Date_Ins,
    User_Ins,
    edition_id_Edt,
    Spr_structure_id_TypePart) values (
        ".iconv("UTF-8","cp1251", $Part_Namelang)."
        ,'".iconv("UTF-8",'cp1251', $Name_part)."'
        ,'".iconv("UTF-8","cp1251", $Num_Article)."'
        ,'".iconv("UTF-8","cp1251", $PageBg)."'
        ,'".iconv("UTF-8","cp1251", $PageEnd)."'
        ,".iconv("UTF-8","cp1251", $PageCount)."
        ,'".iconv("UTF-8","cp1251", $URL_Art)."'
        ,'".iconv("UTF-8","cp1251", $date_Ins)."'
        ,".iconv("UTF-8","cp1251", $id_User)."
        ,".iconv("UTF-8","cp1251", $id_Edt)."
        ,".iconv("UTF-8","cp1251", $id_TypePart)." 
        )";

        $stmt_InsertPart = sqlsrv_query($conn,$InsertPart);
		if (!$stmt_InsertPart){
			$message_error[]='Ошибка добавление статьи!';
		}
        else{
			$Part_true='<br>Вы успешно добавили статью!';
			//echo'<div style="color:green;" class="alert alert-success" role="alert" id="message">'.array_shift($message_true).'</div><hr>';
			//exit("<meta http-equiv='refresh' content='0; url= edition.php'>");
		}
        $id_Part="SELECT scope_identity() AS scope_identity"; 
		$stmt_id_Part = sqlsrv_query($conn,$id_Part);
		$str_id_Part = sqlsrv_fetch_array($stmt_id_Part,SQLSRV_FETCH_ASSOC);
		$id_Part=$str_id_Part['scope_identity'];	
}
else{
    $message_error[]="Данная статья уже существует!";
}

if($message_error)
{
    echo'<div style="color:red;" class="alert alert-danger" role="alert" id="message">'.array_shift($message_error).'</div><hr>';
   // exit("<meta http-equiv='refresh' content='0; url= edition.php'>");
    
}
else{
    echo '<div style="color:green;" class="alert alert-success" role="alert" id="message">'.$Part_true.'</div><hr>';
  //  exit("<meta http-equiv='refresh' content='0; url= edition.php'>");
            
}	


/*
		if($message_error)
{
    $_SESSION['mess']='<div style="color:red;" class="alert alert-danger" role="alert" id="message">'.array_shift($message_error).'</div><hr>';
    exit("<meta http-equiv='refresh' content='0; url= edition.php'>");
    
}
else{
    $_SESSION['mess']= '<div style="color:green;" class="alert alert-success" role="alert" id="message">'.$Part_true.'</div><hr>';
    exit("<meta http-equiv='refresh' content='0; url= edition.php'>");
            
}	
*/
?>