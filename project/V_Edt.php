<?php
require "session_start.php";
require_once 'connect.php';

$conn = OpenConnection(); 
//$OptionValue=$_GET['OptionValue'];
$OptionValue=trim($_POST['OptionValue']);

 $sql="SELECT *
FROM View_edition WHERE id_Edt=".iconv('cp1251','UTF-8',$OptionValue)."";

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
/* $sql="Select * FROM [BasePPS].[dbo].[edition] 
 INNER JOIN [BasePPS].[dbo].publishing_office 
 ON edition.EDN_id_PBO = dbo.publishing_office.id_PBO 
 INNER JOIN level_Edition 
 ON edition.id_edition = level_Edition.id_edition 
 INNER JOIN Spr_formatInfo 
 ON edition.Spr_formatInfo_Type_ED = Spr_formatInfo.Type_ED 
 INNER JOIN language 
 ON edition.EDN_id_language = language.id_language 
 WHERE edition.id_Edt=".iconv('cp1251','UTF-8',$OptionValue)."";*/
$stmt = sqlsrv_query($conn, $sql, $params, $options);//, $params, $options
$row_count = sqlsrv_num_rows($stmt); 
$row_bux_table = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);
$_SESSION['id_Edt']=iconv('cp1251','UTF-8',$row_bux_table["id_Edt"]);
?>
<div id="div-2" class="container">
     <div class="row div-2" id="div2_1">
        <p class="col"><label >Год издания:<span class="color-text">*</span></label><br>
        <input type="number" id="year" name="year" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Year"]); ?>"></p>
        <p class="col"><label >Страна издания:<span class="color-text"></span></label><br>
        <input type="text" id="Region" name="Region" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Region"]); ?>"></p>
        <p class="col"><label >Город издания:<span class="color-text"></span></label><br>
        <input type="text" id="Town" name="Town" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Town"]); ?>"></p>
        <p class="col"><label >Язык издания:<span class="color-text">*</span></label><br>
        <input type="text" id="Name_lang" name="Name_lang" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Name_lang"]); ?>"></p>
	</div>
	<div class="row div-2" id="div2_2">
    <p class="col"><label >Издательство:<span class="color-text"></span></label><br>
        <input type="text" id="Name_PDO" name="Name_PDO" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Name_PDO"]); ?>"></p>
		<p class="col"><label >Страна издательства:<span class="color-text"></span></label><br>
        <input type="text" id="PublOff_Region" name="PublOff_Region" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["PublOff_Region"]); ?>"></p>
        <p class="col"><label >Город издательства:<span class="color-text"></span></label><br>
        <input type="text" id="PublOff_Town" name="PublOff_Town" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["PublOff_Town"]); ?>"></p>
	</div>
    <div class="row div-2" id="div2_3">
		<p class="col"><label >Уровень издания:<span class="color-text">*</span></label><br>
        <input type="text" id="Name_levEdition" name="Name_levEdition" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Name_levEdition"]); ?>"></p>
		<p class="col"><label >Вид издания:<span class="color-text">*</span></label><br>
        <input type="text" id="Name_nlnform" name="Name_nlnform" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Name_nlnform"]); ?>"></p>
	</div>
    <div class="row div-2" id="div2_4">
        <br><p class="col"><label >Регулярность:<span class="color-text"></span></label><br>
        <input type="text" id="Regularity" name="Regularity" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Regularity"]); ?>"></p>
		<p class="col"><label >Доступность восприятия:<span class="color-text"></span></label><br>
        <input type="text" id="Type_Access" name="Type_Access" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Type_Access"]); ?>"></p>
    </div>
    <div class="row div-2" id="div2_5">  
<? 
    if ($row_bux_table["EDN_type_ed"]=='Series'){
    ?>  
        <p class="col"><label >Издание:<span class="color-text">*</span></label><br>
        <input type="text" id="PublOffice_Region" name="PublOffice_Region" class="form-control col-sm-12" readonly value="Журнал"></p>
        <p class="col"><label >ISSN<span class="color-text">*</span></label><br>
        <input type="text" id="ISSN" name="ISSN" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["ISSN"]); ?>"></p>
        <p class="col"><label >ISSN_O<span class="color-text">*</span></label><br>
        <input type="text" id="ISSN_O" name="ISSN_O" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["ISSN_O"]); ?>"></p>
    <?php
    }
    else{
    ?>
         <p class="col"><label>Издание:<span class="color-text">*</span></label><br>
        <input type="text" id="PublOffice_Region" name="PublOffice_Region" class="form-control col-sm-12" readonly value="Книга"></p>
        <p class="col"><label >ISBN<span class="color-text">*</span></label><br>
        <input type="text" id="PublOffice_Town" name="PublOffice_Town" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["ISBN"]); ?>"></p>
    <?php
    }
?>   
    </div>
    <div class="row div-2" id="div2_6">
		<p class="col"><label >DOI издания:<span class="color-text"></span></label><br>
        <input type="text" id="DOI_ED" name="DOI_ED" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["DOI_ED"]); ?>"></p>
		<p class="col"><label >URL-адрес на издание:<span class="color-text"></span></label><br>
        <input type="text" id="URL_ISI" name="URL_ISI" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["URL_ISI"]); ?>"></p>
	</div>
    <div class="row div-2" id="div2_7">
		<p class="col"><label >Номер выпуска, том:<span class="color-text"></span></label><br>
        <input type="text" id="Release" name="Release" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Release"]); ?>"></p>
		<p class="col"><label >Количесткво страниц:<span class="color-text"></span></label><br>
        <input type="text" id="Volume" name="Volume" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Volume"]); ?>"></p>
<? 
    if ($row_bux_table["EDN_type_ed"]=='Series'){
    ?>        
        <p class="col"><label >Период выпуска (с ХХХХ года):<span class="color-text"></span></label><br>
        <input type="text" id="Coverage" name="Coverage" class="form-control col-sm-12" readonly value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["Coverage"]);} ?>"></p>
        
    </div>
    <div class="row div-2" id="div2_8">
    <p class="col"><label >Примечания:<span class="color-text"></span></label><br>
        <textarea  type="text" id="Note" name="Coverage" class="form-control col-sm-12" rows="4" readonly><?php echo iconv('cp1251','UTF-8',$row_bux_table["Coverage"]); ?></textarea></p>
    </div>
    <div><input type="hidden" name="id_Edt" id="id_Edt" value="<?php echo iconv('cp1251','UTF-8',$row_bux_table["id_Edt"]);?>" /></div>
    <hr><hr>

</div>
