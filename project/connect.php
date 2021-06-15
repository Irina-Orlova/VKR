<?php
function OpenConnection()  
{  
    try  
    {  
        require "session_start.php";
        //$report_redirect = "http:/localhost:44380/"
        $serverName = "192.168.20.170";
        $connectionInfo = array("UID" => "i.orlova", "PWD" => "34#Gfa", "Database"=>"BasePPS");
        $conn = sqlsrv_connect($serverName, $connectionInfo);
  
        if($conn)
        {
          //  echo "Соединение установлено.\n";
            $_SESSION['connection'] = $conn;
        }
        else
        {
            echo "Не удалось установить соединение.\n<br>";
            die( print_r( sqlsrv_errors(), true));
        }
            //sqlsrv_close( $conn);
    }
    catch(Exception $e)  
    {  
        echo("Error!");  
    }  
    return $conn;
}

?>