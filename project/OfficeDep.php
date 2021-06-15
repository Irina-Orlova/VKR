<?php
require "session_start.php";
require_once 'connect.php';

$conn = OpenConnection(); 

$LastName=trim($_POST['LastName']);
$FirstName=trim($_POST['FirstName']);
$Patronymic=trim($_POST['Patronymic']);

$sqlsotr="Select * from V_Shtat where FAM='".iconv('UTF-8','cp1251',trim($_POST['LastName']))."' and NAM1='".iconv('UTF-8','cp1251',trim($_POST['FirstName']))."' and NAM2='".iconv('UTF-8','cp1251',trim($_POST['Patronymic']))."'";
$params = array();
$options =  array("Scrollable" => SQLSRV_CURSOR_KEYSET);

$stmt_sqlsotr = sqlsrv_query($conn, $sqlsotr, $params, $options) or die("Не могу выполнить запрос!'");
$count_office = sqlsrv_num_rows($stmt_sqlsotr); 
if ($count_office!=0)
{
?>    
<div class="input-group col-md-10 offset-md-2">
    <div class="input-group-prepend">
		<span class="input-group-text" id="basic-addon1"><img src="img/icons/hand-index-thumb-fill.svg" alt="" width="16" height="16" title="Bootstrap"/></span>
	</div> 
    <select name="office_dep" class="office_dep form-control" id="office_dep" readonly required="required">
                                    <?php 
                                        while ($sotr = sqlsrv_fetch_array($stmt_sqlsotr,SQLSRV_FETCH_ASSOC))
                                        {
                                            if($sotr['Kod_aGr']==''){ 
                                            echo "<option value='".iconv('cp1251','UTF-8',$sotr["Kod_Dep"])."' id='".iconv('cp1251','UTF-8',$sotr["Name_Dep"])."'>".iconv('cp1251','UTF-8',$sotr["Name_Dep"])."</option>";
                                            }
                                            else{
                                                echo "<option value='".iconv('cp1251','UTF-8',$sotr["Kod_aGr"])."'>".iconv('cp1251','UTF-8',$sotr["NameGr"])."/".iconv('cp1251','UTF-8',$sotr["Name_Dep"])."</option>";
                                            } 
                                        ?>
    </select> 
</div>
    <div class="input-group col-md-10 offset-md-2">
        <div class="input-group-prepend">
			<span class="input-group-text" id="basic-addon1"><img src="img/icons/person-lines-fill.svg" alt="" width="18" height="18" title="Bootstrap"/></span>
		</div>	
        <div><input type="hidden" name="Tab_num" value="<?php echo iconv('cp1251','UTF-8',$sotr["SHIFR_SOTR"]);?>" /></div>
        <input type="text" name="JobName" class="form-control" id="JobName"  value="<?php echo iconv('cp1251','UTF-8',$sotr["shtat_name"]);} ?>" readonly  />
    </div>
<?php 
}
else 
{
    $_SESSION['mess'] = '<div style="color:red;" class="alert alert-danger" role="alert" id="message">Вы не являетесь сотрудником МГТУ! Можите посмотреть данные в роли &#171;Гостя&#187;!</div><hr>';
    exit("<meta http-equiv='refresh' content='0; url= ../login.php'>");
}