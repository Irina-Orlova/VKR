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
	//$c=$_SESSION['connection'];
//	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	$conn = OpenConnection(); 
	
	$login_user=$_SESSION['login_user'];
	$password_user=$_SESSION['password_user'];
	//echo " авторизация - ".$login_user." - ".$password_user;
	
	$query = "SELECT [id_users],[login_user],[pwd_user],[BasePPS].[dbo].[Spr_roles].id_role,[BasePPS].[dbo].[Spr_roles].name_role
	FROM [BasePPS].[dbo].[Spr_Users] inner join [BasePPS].[dbo].[Spr_roles]
	On [BasePPS].[dbo].[Spr_Users].id_role = [BasePPS].[dbo].[Spr_roles].id_role WHERE login_user='".$login_user."' AND pwd_user='".$password_user."'";
	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$stmt = sqlsrv_query($conn, $query);//, $params, $options
	$result = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
	
	//echo $result["id_users"];

	$option_Name_nlnform="Select Type_ED, Name_nlnform from Spr_formatInfo order by Name_nlnform";
	$stmt_option_nlnform = sqlsrv_query($conn, $option_Name_nlnform);
	
	$option_Name_Part="Select id_TypePart, Name_Part from Spr_structure order by Name_Part";
	$stmt_option_Name_Part = sqlsrv_query($conn, $option_Name_Part);
	
	$option_language="Select id_language, Name_lang from language order by Name_lang";
	$stmt_language = sqlsrv_query($conn, $option_language);
	
	$option_Spr_heading="Select id_heading, Name_heading from Spr_heading order by Name_heading";
	$stmt_Spr_heading = sqlsrv_query($conn, $option_Spr_heading);
	
	$option_level_edition="Select id_edition, Name_levEdition from level_Edition order by Name_levEdition";
	$stmt_level_edition = sqlsrv_query($conn, $option_level_edition);
	
	$select_autor="Select * from Author order by FirstName, Patronymic,LastName";
	$stmt_select_autor = sqlsrv_query($conn, $select_autor);
	
	$select_publ_office="select id_PBO,Name_PDO,Town,Region from publishing_office";
	$stmt_publ_office = sqlsrv_query($conn, $select_publ_office);
	

	$select_books="select ISBN, Volume ,Note, Date_Edit, Date_ExrtactB, User_id, Tom, Name from Books";
	$stmt_select_books = sqlsrv_query($conn, $select_books);
	
	$select_series="select ISSN,ISSN_O, Volume, Dare_Edit, Date_ExtractJ, RELEASE, Coverage,User_id, Name from Series";
	$stmt_select_series = sqlsrv_query($conn, $select_series);
	
if(isset($_REQUEST["add"]))
	{
	$Name_structure=strip_tags($_POST['Name_structure']);
	$Name_formatInfo=strip_tags($_POST['Name_formatInfo']);
	$language=strip_tags($_POST['languageEdit']);
	$level_edition=strip_tags($_POST['level_edition']);
	$journal=strip_tags($_POST['journal']);
	$year=strip_tags($_POST['year']);
	$ISN=strip_tags($_POST['ISN']);
	$release=strip_tags($_POST['release']);
	$number=strip_tags($_POST['number']);
	$edition_name=strip_tags($_POST['edition_name']);
	$section_name=strip_tags($_POST['section_name']);
	$page=strip_tags($_POST['page']);
	$pageBg=strip_tags($_POST['pageBg']);
	$pageEnd=strip_tags($_POST['pageEnd']);
	$author=strip_tags($_POST['author']);
	$typeHeading=strip_tags($_POST['typeHeading']);
	$Name_part=strip_tags($_POST['Name_part']);
	$valume=strip_tags($_POST['valume']);
	
/*	echo "<br>Name_structure = ".$Name_structure;
	echo "<br>Name_formatInfo = ".$Name_formatInfo;
	echo "<br>language = ".$language;
	echo "<br>level_edition = ".$level_edition;
	echo "<br>journal = ".$journal;
	echo "<br>year = ".$year;
	echo "<br>ISN = ".$ISN;
	echo "<br>release = ".$release;
	echo "<br>number = ".$number;
	echo "<br>edition_name = ".$edition_name;
	echo "<br>section_name = ".$section_name;
	echo "<br>page = ".$page;
	echo "<br>pageBg = ".$pageBg;
	echo "<br>pageEnd = ".$pageEnd;
	echo "<br>author = ".$author;
	echo "<br>typeHeading = ".$typeHeading;
	echo "<br>Name_part = ".$Name_part;
	echo "<br>valume = ".$valume;
	*/
	
	switch($Name_structure){
		case 1: $InsertE="Insert into Series (ISSN, ISSN_O, Volume,Dare_Edit,Date_ExtractJ,RELEASE,Coverage,User_id, Name) values (".$ISN.",'".iconv('UTF-8','cp1251',$number)."',".$valume.",'".date("d-m-y")."', '".date("d-m-y")."','".iconv('UTF-8','cp1251',$release)."','".iconv('UTF-8','cp1251',$section_name)."', ".$result["id_users"].",'".iconv('UTF-8','cp1251',$journal)."')";
		break;
		
		case 2: $InsertE="Insert into Books (ISBN,Volume, Note, Date_Edit, Date_ExrtactB, User_id, Tom, Name) values (".$ISN.",".$valume.",'".iconv('UTF-8','cp1251',$section_name)."' ,'".date("d-m-y")."','".date("d-m-y")."',".$result["id_users"].",'".iconv('UTF-8','cp1251',$release)."','".iconv('UTF-8','cp1251',$journal)."')";
		break;
	}
	$stmt = sqlsrv_query($conn,$InsertE);
	echo $InsertE;
	$str="SELECT scope_identity() AS scope_identity";
	$stmt_ins = sqlsrv_query($conn,$str);
	while ($strD = sqlsrv_fetch_array($stmt_ins,SQLSRV_FETCH_ASSOC))
	{			
		$id_str=$strD['scope_identity'];	
	}
	//echo $InsertE;
	echo "fjgif = ".$strD['scope_identity'];
		
		$select_publ_ID_office="select id_PBO,Name_PDO,Town,Region from publishing_office where Name_PDO like '%".iconv('cp1251','UTF-8',$journal)."'";
		$stmt_publ_ID_office = sqlsrv_query($conn, $select_publ_ID_office);
		$stmt_ID_publ = sqlsrv_query($conn, $select_publ_ID_office);//, $params, $options
		$result_ID_publ = sqlsrv_fetch_array($stmt_ID_publ,SQLSRV_FETCH_ASSOC);
		$id_PBO = $result_ID_publ["id_PBO"];
		//echo "id = ". $result_ID_publ["id_PBO"];
		
		
	
		
		switch($Name_structure){
		case 1: $InsertE="Insert into edition (Name_Edt, YEAR, Town, Region, Regularity, Type_Access, publishing_office_id_PBO, Spr_formatInfo_Type_ED, Books_ISBN, Series_ISSN, id_language, id_edition) values (".$ISN.",'".iconv('UTF-8','cp1251',$number)."',".$valume.",'".date("d-m-y")."', '".date("d-m-y")."','".iconv('UTF-8','cp1251',$release)."','".iconv('UTF-8','cp1251',$section_name)."', ".$result["id_users"].",'".iconv('UTF-8','cp1251',$journal)."')";
		break;
		
		case 2: $InsertE="Insert into Books (ISBN,Volume, Note, Date_Edit, Date_ExrtactB, User_id, Tom, Name) values (".$ISN.",".$valume.",'".iconv('UTF-8','cp1251',$section_name)."' ,'".date("d-m-y")."','".date("d-m-y")."',".$result["id_users"].",'".iconv('UTF-8','cp1251',$release)."','".iconv('UTF-8','cp1251',$journal)."')";
		break;
		}
	}
	else
	{
		echo "Ошибка -  ";
	}
	

			
			?>
        <form class="form-border" action="" method="POST">
            <div id="text-1" class="description-text">
            <span>Описание публикации.</span><br>
            <span>Поля, отмеченные звездочкой, обязательны для заполнения.</span>
            </div>
            <div id="div-1" class="container">
                <div class="row">
                    <p class="col-sm"><label >Тип публикации:<span class="color-text">*</span></label><br>
                    <select class="container-fluid" name="Name_structure"  id='Name_structure'>
                        <option selected disabled>Выберите</option>
			<?php			
			while ($row_Name_Part = sqlsrv_fetch_array($stmt_option_Name_Part,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv('cp1251','UTF-8',$row_Name_Part["id_TypePart"])."'>".iconv('cp1251','UTF-8',$row_Name_Part["Name_Part"])."</option>"; 
			}
	?>
                     </select></p>
                    <br><p class="col-sm"><label>Вид издания:<span class="color-text">*</span></label><br>
                    <select class="container-fluid" name="Name_formatInfo" required>
                        <option selected disabled>Выберите</option>
                        		<?php			
			while ($row_nlnform = sqlsrv_fetch_array($stmt_option_nlnform,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv('cp1251','UTF-8',$row_nlnform["Type_ED"])."'>".iconv('cp1251','UTF-8',$row_nlnform["Name_nlnform"])."</option>"; 
			}
	?>
                        </select></p>

                </div>
				
				<div class="row">
                    <p class="col-sm"><label >Язык описания:<span class="color-text">*</span></label><br>
                    <select class="container-fluid" name="languageEdit" required>
                        <option selected disabled>Выберите</option>
<?php			
			while ($row_language = sqlsrv_fetch_array($stmt_language,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv('cp1251','UTF-8',$row_language["id_language"])."'>".iconv('cp1251','UTF-8',$row_language["Name_lang"])."</option>"; 
			}
	?>               </select></p>
						
					<p class="col-sm"><label >Уровень издания:<span class="color-text">*</span></label><br>
                    <select class="container-fluid" name="level_edition"  id='level_edition'>
                        <option selected disabled>Выберите</option>
			<?php			
			while ($level_edition = sqlsrv_fetch_array($stmt_level_edition,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv('cp1251','UTF-8',$level_edition["id_edition"])."'>".iconv('cp1251','UTF-8',$level_edition["Name_levEdition"])."</option>"; 
			}
	?>
                     </select></p>
				</div>
                <hr>
            </div>

            <div id="div-2" class="container">
				 <label >Журнал/Книга:<span class="color-text">*</span></label><br>
                <input name="journal" list="journals" class="container-fluid">
                <datalist id="journals" >
					<?php					
					while ($row_publ_office = sqlsrv_fetch_array($stmt_publ_office,SQLSRV_FETCH_ASSOC))
					{
						echo "<option value='".iconv('cp1251','UTF-8',$row_publ_office["Name_PDO"])."'>".iconv('cp1251','UTF-8',$row_publ_office["id_PDO"])."</option>"; 
					}
					?>
                </datalist><br>
                <div class="row div-2">
                    <p class="col"><label >Год:<span class="color-text">*</span></label><br>
                    <input type="number" id="year" name="year"></p>
                    <p class="col"><label >Сквозной номер:<span class="color-text">*</span></label><br>
                    <input type="text" id="ISN" name="ISN"></p>
                    <div class="w-100"></div>
                    <p class="col"><label >Выпуск/Том:</label><br>
                    <input type="text" id="release" name="release"></p>
                    <p class="col"><label >Номер:</label><br>
                    <input type="text" id="number" name="number"></p>
					<p class="col"><label >Значение:</label><br>
                    <input type="text" id="valume" name="valume"></p>
                </div>
                <p class="class-input"><label >Название (тема) выпуска:</label><br>
                <input class="container-fluid" type="text" id="edition_name" name="edition_name"></p>

                <p class="class-input"><label >Название раздела журнала:</label><br>
                <input class="container-fluid" type="text" id="section_name" name="section_name"></p>

                <div class="row div-2">
                    <p class="col-sm"><label >Язык:<span class="color-text">*</span></label><br>
                    <input type="text" id="language" name="language"></p>
                    <p class="col-sm"><label >Страницы:</label><br>
                    <input type="number" id="page" name="page"></p>
                    <p class="col-sm"><label >Страница начала:</label><br>
                    <input type="number" id="id-2" name="pageBg"></p>
					<p class="col-sm"><label >Страница конца:</label><br>
                    <input type="number" id="id-2" name="pageEnd"></p>
                </div>
                <p class="class-input"><label >Заглавие на русском язык:</label><br>
                <input class="container-fluid" type="text" id="Name_part" name="Name_part"></p>
                <p class="class-input"><label >Авторы:</label><br>
                <input class="container-fluid" type="text" list="authors" id="author" name="author"></p>
				<datalist id="authors" >
					<?php
					while ($row_select_autor = sqlsrv_fetch_array($stmt_select_autor,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv('cp1251','UTF-8',$row_select_autor["FirstName"])." ".iconv('cp1251','UTF-8',$row_select_autor["Patronymic"])." ".iconv('cp1251','UTF-8',$row_select_autor["[LastName]"])."'>"; 
			}
					
					?>
                </datalist><br>
                
                <p class="class-input"><label >Разделы тематического рубрикатора:<span class="color-text">*</span></label><br>
				<select class="container-fluid" name="typeHeading" required>
                        <option selected disabled>Выберите</option>
<?php			
			while ($row_heading = sqlsrv_fetch_array($stmt_Spr_heading,SQLSRV_FETCH_ASSOC))
			{
				echo "<option value='".iconv('cp1251','UTF-8',$row_heading["id_heading"])."'>".iconv('cp1251','UTF-8',$row_heading["Name_heading"])."</option>"; 
			}
	?>
				</select>
				</p>
                <p class="class-input"><label >Информация о финансовой поддержки данной работы:</label><br>
                <input class="container-fluid" type="text" id="finans" name="finans"></p>
                <hr>
            </div>
            <div class="container">  
               <!-- <button type="button" class="btn btn-success container-fluid">Отправить</button>-->
				<br><input class="btn btn-success container-fluid" type="submit" name="add" id="add" value="Добавить"></p>
            </div>
            <br>
            <br>
        </form>
        <?php require "blocks/footer.php"?>
    </body>
<script src="js/script.js?version={version}"></script>
</html>
