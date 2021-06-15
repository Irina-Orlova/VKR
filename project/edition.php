<?php
require "session_start.php";?><!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
        <title>Авторизация</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/Main.css">
		<script src="js/jquery-3.4.1.js"></script>
			
		
    </head>
    <body>
<?php 
$OptionValue=strip_tags($_POST['OptionValue']);
$OptionValue=iconv('UTF-8','cp1251',$OptionValue);
require_once "connect.php";
require "blocks/header.php";
include 'spr.php';

$conn = OpenConnection(); 
	
$login_user=$_SESSION['login_user'];
$password_user=$_SESSION['password_user'];
$id_Edt=strip_tags($_POST['id_Edt']);
	
$query = "SELECT [id_users],[login_user],[pwd_user],[BasePPS].[dbo].[Spr_roles].id_role,[BasePPS].[dbo].[Spr_roles].name_role
	FROM [BasePPS].[dbo].[Spr_Users] inner join [BasePPS].[dbo].[Spr_roles]
	On [BasePPS].[dbo].[Spr_Users].id_role = [BasePPS].[dbo].[Spr_roles].id_role WHERE login_user='".$_SESSION['login_user']."' AND pwd_user='".$_SESSION['password_user']."'";
	
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn, $query);//, $params, $options
$result = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
$id_User=$result['id_users'];

if ($_SESSION['login_user']=='' && $_SESSION['password_user']=='')
{
	echo "<style type='text/css' >.form { display: none; }</style>";
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

$option_Name_Edt="Select distinct Name_Edt, id_Edt from edition order by Name_Edt";
$stmt_Name_Edt = sqlsrv_query($conn, $option_Name_Edt);

$option_Name_nlnform="Select Type_ED, Name_nlnform from Spr_formatInfo order by Name_nlnform";
$stmt_option_nlnform = sqlsrv_query($conn, $option_Name_nlnform);
	
$option_language="Select id_language, Name_lang from language order by Name_lang";
$stmt_language = sqlsrv_query($conn, $option_language);
$stmt_lang = sqlsrv_query($conn, $option_language);
	
$option_level_edition="Select id_edition, Name_levEdition from level_Edition order by Name_levEdition";
$stmt_level_edition = sqlsrv_query($conn, $option_level_edition);
	
$select_publ_office="select distinct id_PBO,Name_PDO,Town,Region from publishing_office";
$stmt_publ_office = sqlsrv_query($conn, $select_publ_office);

$select_TypePart="select * from Spr_structure";
$stmt_TypePart = sqlsrv_query($conn, $select_TypePart);
 
	
if(isset($_REQUEST["add"]))
{
	$ISSN=strip_tags($_POST['ISSN']);
	$ISSN_O=strip_tags($_POST['ISSN_O']);
	$ISBN=strip_tags($_POST['ISBN']);

	$ISSN = !empty($_POST['ISSN']) ? "'".$_POST['ISSN']."'": "NULL";
	$ISSN_O = !empty($_POST['ISSN_O']) ? "'".$_POST['ISSN_O']."'": "NULL";
	$ISBN = !empty($_POST['ISBN']) ? "'".$_POST['ISBN']."'": "NULL";

	/*if(empty($_POST['ISSN']))
	{
		$ISSN="NULL";
		$ISSN_O="NULL";
	}
	else {
		if($ISSN_O=='')
		{
			$ISSN_O="NULL";
		}
		$ISBN="NULL";
	}*/

	$Date_Edit=date("d.m.y");
	$Date_Extract='NULL';

	$select_id_PublOffice="select id_PBO,Name_PDO,Town,Region from publishing_office 
	where Name_PDO like '".iconv("UTF-8","cp1251",trim($_POST["Name_PDO"]))."'";
	$stmt_id_PublOffice = sqlsrv_query($conn, $select_id_PublOffice, $params, $options);
	$row_count = sqlsrv_num_rows($stmt_id_PublOffice); 
	if (!$row_count){
		$insertPublOffice="insert into publishing_office(Name_PDO,Town,Region) values(
		'".iconv("UTF-8","cp1251",trim($_POST["Name_PDO"]))."',
		'".iconv("UTF-8","cp1251",trim($_POST["PublOffice_Town"]))."',
		'".iconv("UTF-8","cp1251",trim($_POST['PublOffice_Region']))."')";
		$stmt_insertPublOffice = sqlsrv_query($conn, $insertPublOffice);
		if (!$stmt_insertPublOffice){
			$message_error[]='Ошибка добавление издательства!';
		}
		$id_PublOffice="SELECT scope_identity() AS id_PBO"; 
		$stmt_id_PublOffice = sqlsrv_query($conn, $id_PublOffice);

	}
	$str_id_PublOffice = sqlsrv_fetch_array($stmt_id_PublOffice,SQLSRV_FETCH_ASSOC);
	$id_PublOffice=$str_id_PublOffice['id_PBO'];	

	$insertEdn="Insert into edition (
	EDN_id_PBO,
	Name_Edt,
	Year,
	Town,
	Region,
	Regularity,
	Type_Access,
	EDN_id_language,
	Spr_formatInfo_Type_ED,
	id_edition,
	EDN_type_ed,
	DOI_ED,
	URL_ISI,
	ISSN,
	ISSN_O,
	ISBN,
	Release,
	Volume,
	Coverage,
	Note,
	Date_Edit,
	User_id,
	Date_Extract) values(
		".$id_PublOffice.",
		'".iconv("UTF-8","cp1251",trim($_POST["Name_edition"]))."',
		'".iconv("UTF-8","cp1251",trim($_POST['year']))."', 
		'".iconv("UTF-8","cp1251",trim($_POST["Town"]))."', 
		'".iconv("UTF-8","cp1251",trim($_POST["Region"]))."', 
		'".iconv("UTF-8","cp1251",trim($_POST["Regularity"]))."',
		'".iconv("UTF-8","cp1251",trim($_POST["Type_Access"]))."',
		".iconv("UTF-8","cp1251",trim($_POST['Name_lang'])).", 
		".iconv("UTF-8","cp1251",trim($_POST["Name_nlnform"])).", 
		".iconv("UTF-8","cp1251",trim($_POST["Name_levEdition"])).",
		'".iconv("UTF-8","cp1251",trim($_POST["EDN_type_ed"]))."',
		'".iconv("UTF-8","cp1251",trim($_POST['DOI_ED']))."', 
		'".iconv("UTF-8","cp1251",trim($_POST["URL_ISI"]))."', 
		".iconv("UTF-8","cp1251","$ISSN").", 
		".iconv("UTF-8","cp1251","$ISSN_O").",
		".iconv("UTF-8","cp1251","$ISBN").",
		'".iconv("UTF-8","cp1251",trim($_POST['Release']))."', 
		'".iconv("UTF-8","cp1251",trim($_POST["Volume"]))."', 
		'".iconv("UTF-8","cp1251",trim($_POST["Coverage"]))."', 
		'".iconv("UTF-8","cp1251",trim($_POST["Note"]))."',
		'$Date_Edit',
		".$id_User.", 
		$Date_Extract)";
	$stmt_insertEdn = sqlsrv_query($conn, $insertEdn);
	$id_edition="SELECT scope_identity() AS scope_identity"; 
	$stmt_id_edition = sqlsrv_query($conn,$id_edition);
	$str_id_edition = sqlsrv_fetch_array($stmt_id_edition,SQLSRV_FETCH_ASSOC);
	$id_edition=$str_id_edition['scope_identity'];	
  //  echo "<br>издание - ".$id_edition;
	if (!$stmt_insertEdn){
		$message_error[]='Ошибка добавление издания!';
	}
	else{
		$message_true[]='Вы успешно добавили издания!';	
	}

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
	where Name_part like '".iconv("UTF-8","cp1251",$Name_part)."'";

	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt_part = sqlsrv_query($conn, $sql_part, $params, $options);
	$row_count = sqlsrv_num_rows($stmt_part); 
	//echo $row_count;
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
			,".iconv("UTF-8","cp1251", $id_edition)."
			,".iconv("UTF-8","cp1251", $id_TypePart)." 
			)";

		$stmt_InsertPart = sqlsrv_query($conn,$InsertPart);
		$id_Part="SELECT scope_identity() AS scope_identity"; 
		$stmt_id_Part = sqlsrv_query($conn,$id_Part);
		$str_id_Part = sqlsrv_fetch_array($stmt_id_Part,SQLSRV_FETCH_ASSOC);
		$id_Part=$str_id_Part['scope_identity'];	
		//echo "статья".$id_Part;
		if (!$InsertPart){
			$error='Ошибка добавление статьи!';
		}
		else{
			$Part_true='<br>Вы успешно добавили статью!';
			//echo'<div style="color:green;" class="alert alert-success" role="alert" id="message">'.array_shift($message_true).'</div><hr>';
			//exit("<meta http-equiv='refresh' content='0; url= edition.php'>");
		}
	
	}
	else{
		$message_error[]="Данная статья уже существует!";
	}	
	if($message_error)
	{
		echo '<div style="color:red;" class="alert alert-danger" role="alert" id="message">'.array_shift($message_error).'</div><hr>';
	}
	else{
		echo '<div style="color:green;" class="alert alert-success" role="alert" id="message">'.array_shift($message_true).$Part_true.'</div><hr>';
				
	}	
		

}


			
			?>

	<div class="form">
        <form class="form-border" action="" method="POST">
            <div id="text-1" class="description-text">
            	<span>Описание издания.</span><br>
            	<span>Поля, отмеченные звездочкой, обязательны для заполнения.</span>
            </div>
			<div id="div-1" class="container">
				<div class="row">
				
					<p class="col"><label >Наименование издания:<span class="color-text">*</span></label>
                	<select class="Name_Edt form-control col-sm-12 w-auto p-2" name="Name_Edt" id='Name_Edt' >
                    	<option selected disabled>Выберите</option>
					<?php			
			 while ($row_name_edit = sqlsrv_fetch_array($stmt_Name_Edt,SQLSRV_FETCH_ASSOC))
			 {
				echo "<option value='".iconv("cp1251","UTF-8",$row_name_edit["id_Edt"])."'>".iconv("cp1251","UTF-8",$row_name_edit["Name_Edt"])."</option>"; 
			 }
	?>   
                </select></p>
				<input name="addEdt" type='button' class="addEdt col btn btn-outline-primary" id="addEdt"  value="Добавить издание">		
				</div>
				<?if ($_SESSION['mess']){
				echo $_SESSION['mess'];
			   }
			   unset($_SESSION['mess']);?>
			
			</div>

			<div name=res id=res class=res> </div>
			<div id="divAdd" class="container">
				<p class="col"><label >Наименование издания:<span class="color-text">*</span></label><br>
				<input type="text" id="Name_edition" name="Name_edition" class="form-control col-sm-12" required=""></p>
				<div class="row div-2" id="div2_1">
					<p class="col"><label >Год издания:<span class="color-text">*</span></label><br>
					<input type="number" id="year" name="year" class="form-control col-sm-12" maxlength="4" required=""></p>
					<p class="col"><label >Страна издания:<span class="color-text"></span></label><br>
					<input type="text" id="Region" name="Region" class="form-control col-sm-12" ></p>
					<p class="col"><label >Город издания:<span class="color-text"></span></label><br>
					<input type="text" id="Town" name="Town" class="form-control col-sm-12" ></p>
					<p class="col"><label >Язык издания:<span class="color-text">*</span></label><br>
                    <select class="form-control col-sm-12" name="Name_lang" id="Name_lang" required="">
                        <option selected disabled value="">Выберите</option>
<?php			
			while ($row_language = sqlsrv_fetch_array($stmt_language,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv("cp1251","UTF-8",$row_language["id_language"])."'>".iconv("cp1251","UTF-8",$row_language["Name_lang"])."</option>"; 
			}
	?>               </select></p>
				</div>
				<div class="row div-2" id="div2_2">
					<p class="col"><label >Издательство:<span class="color-text">*</span></label><br>
					<input name="Name_PDO" id="Name_PDO" list="PDO" class="form-control col-sm-12" required="">
                	<datalist id="PDO" >
					<?php					
					while ($row_publ_office = sqlsrv_fetch_array($stmt_publ_office,SQLSRV_FETCH_ASSOC))
					{
						echo "<option data-value='".iconv("cp1251","UTF-8",$row_publ_office["id_PBO"])."' value='".iconv("cp1251","UTF-8",$row_publ_office["Name_PDO"])."'></option>"; 
					}
					?>
               		</datalist><br></p>
					<p class="col" id="Publ"><label >Страна издательства:<span class="color-text"></span></label><br>
					<input type="text" id="PublOffice_Region" name="PublOffice_Region" class="form-control col-sm-12" ></p>
					<p class="col" id="Publ_t"> <label >Город издательства:<span class="color-text"></span></label><br>
					<input type="text" id="PublOffice_Town" name="PublOffice_Town" class="form-control col-sm-12" ></p>
				</div>
				<div class="row div-2" id="div2_3">
					<p class="col"><label >Уровень издания:<span class="color-text">*</span></label><br>
                    <select class="form-control col-sm-12" name="Name_levEdition"  id='Name_levEdition' required="">
                        <option selected disabled value="">Выберите</option>
			<?php			
			while ($level_edition = sqlsrv_fetch_array($stmt_level_edition,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv("cp1251","UTF-8",$level_edition["id_edition"])."'>".iconv("cp1251","UTF-8",$level_edition["Name_levEdition"])."</option>"; 
			}
	?>
                     </select></p>
					<p class="col"><label>Вид издания:<span class="color-text">*</span></label><br>
                    <select class="form-control col-sm-12" name="Name_nlnform" id="Name_nlnform" required="">
                        <option selected disabled value="">Выберите</option>
                        		<?php			
			while ($row_nlnform = sqlsrv_fetch_array($stmt_option_nlnform,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv("cp1251","UTF-8",$row_nlnform["Type_ED"])."'>".iconv("cp1251","UTF-8",$row_nlnform["Name_nlnform"])."</option>"; 
			}
	?>
                        </select></p>
				</div>
				<div class="row div-2" id="div2_4">
					<br><p class="col"><label >Регулярность:<span class="color-text"></span></label><br>
					<input type="text" id="Regularity" name="Regularity" class="form-control col-sm-12" ></p>
					<p class="col"><label >Доступность восприятия:<span class="color-text"></span></label><br>
					<input type="text" id="Type_Access" name="Type_Access" class="form-control col-sm-12" ></p>
				</div>
				<div class="row div-2" id="div2_5">  
					<p class="col"><label >Издание:<span class="color-text">*</span></label><br>
                    <select class="form-control col-sm-12" name="EDN_type_ed" id="EDN_type_ed" required="">
                        <option selected disabled value="">Выберите</option>
                        <option value="Series">Журнал</option>
						<option value="Book">Книга</option>
                    </select></p>
					<p class="col" id='bl_ISSN'><label >ISSN<span class="color-text" >*</span></label><br>
					<input type="text" id="ISSN" name="ISSN" class="inputEdt form-control col-sm-12" ></p>
					<p class="col" id='bl_ISSN_O'><label >ISSN_O<span class="color-text"></span></label><br>
					<input type="text" id="ISSN_O" name="ISSN_O" class="inputEdt form-control col-sm-12" ></p>
					<p class="col" id='bl_ISBN'><label >ISBN<span class="color-text">*</span></label><br>
					<input type="text" id="ISBN" name="ISBN" class="inputEdt form-control col-sm-12" ></p>
				</div>
				<div class="row div-2" id="div2_6">
					<p class="col"><label >DOI издания:<span class="color-text"></span></label><br>
					<input type="text" id="DOI_ED" name="DOI_ED" class="form-control col-sm-12" ></p>
					<p class="col"><label >URL-адрес на издание:<span class="color-text"></span></label><br>
					<input type="text" id="URL_ISI" name="URL_ISI" class="form-control col-sm-12" ></p>
				</div>
				<div class="row div-2" id="div2_7">
					<p class="col"><label >Номер выпуска, том:<span class="color-text"></span></label><br>
					<input type="text" id="Release" name="Release" class="form-control col-sm-12" ></p>
					<p class="col"><label >Количесткво страниц:<span class="color-text"></span></label><br>
					<input type="number" id="Volume" name="Volume" class="form-control col-sm-12" ></p>      
					<p class="col"><label >Период выпуска (с ХХХХ года):<span class="color-text"></span></label><br>
					<input type="text" id="Coverage" name="Coverage" class="form-control col-sm-12" ></p>				
				</div>
				<div class="row div-2" id="div2_8">
					<p class="col"><label >Примечания:<span class="color-text"></span></label><br>
					<textarea  type="text" id="Note" name="Note" class="form-control col-sm-12" rows="4" ></textarea></p>
				</div>
				<hr><hr>

			</div>

			
			<div id="part" class="container">

			<div id="text-1" class="description-text">
            	<span>Описание структурной части статьи.</span><br>
            	<span>Поля, отмеченные звездочкой, обязательны для заполнения.</span>
            </div>

				<p class="col row"><label >Наименование статьи:<span class="color-text">*</span></label><br>
				<textarea  type="text" id="Name_part" name="Name_part" class="form-control col-sm-12" rows="4" required=""></textarea></p>
				<div class="row div-part1" id="div-part">
					<p class="col"><label >Номер статьи:<span class="color-text"></span></label><br>
					<input type="text" id="Num_Article" name="Num_Article" class="form-control col-sm-12"></p>
					<p class="col"><label >Страница начала:<span class="color-text"></span></label><br>
					<input type="number" id="PageBg" name="PageBg" class="form-control col-sm-12" ></p>
					<p class="col"><label >Конец страницы:<span class="color-text"></span></label><br>
					<input type="number" id="PageEnd" name="PageEnd" class="form-control col-sm-12" ></p>
					<p class="col"><label >Количество страниц в статье:<span class="color-text">*</span></label><br>
					<input type="number" id="PageCount" name="PageCount" class="form-control col-sm-12" ></p>
				</div>
				<div class="row div-part1" id="div-part">
					<p class="col"><label >URL статьи:<span class="color-text"></span></label><br>
					<textarea  type="text" id="URL_Art" name="URL_Art" class="form-control col-sm-12" rows="3" ></textarea></p>
				</div>

				<div class="row div-part1" id="div-part">
					<p class="col"><label>Вид издания:<span class="color-text">*</span></label><br>
                    <select class="form-control col-sm-12" name="id_TypePart" id="id_TypePart" required="">
                        <option selected disabled value="">Выберите</option>
                        		<?php			
			while ($TypePart = sqlsrv_fetch_array($stmt_TypePart,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv("cp1251","UTF-8",$TypePart["id_TypePart"])."'>".iconv("cp1251","UTF-8",$TypePart["Name_Part"])."</option>"; 
			}
	?>
                    </select></p>

					<p class="col"><label >Язык статьи:<span class="color-text">*</span></label><br>
                    <select class="form-control col-sm-12" name="Part_Namelang" id="Part_Namelang" required="">
                        <option selected disabled value="">Выберите</option>
<?php			
			while ($row_lang = sqlsrv_fetch_array($stmt_lang,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv("cp1251","UTF-8",$row_lang["id_language"])."'>".iconv("cp1251","UTF-8",$row_lang["Name_lang"])."</option>"; 
			}
	?>               </select></p>
				</div>

				<div name=result id=result class=result> </div>
			
			</div>



         
            <div class="container">  
               <!-- <button type="button" class="btn btn-success container-fluid">Отправить</button>-->
			   <input name="partEdt" type='button' class="partEdt col btn btn-outline-primary" id="partEdt"  value="Добавить">	
				<input class="btn btn-success container-fluid" type="submit" name="add" id="add" value="Добавить">
            </div>
            <br>
            <br>
        </form>
	</div>
        <?php require "blocks/footer.php"?>
    </body>
<script src="js/script.js?version={version}"></script>
</html>
