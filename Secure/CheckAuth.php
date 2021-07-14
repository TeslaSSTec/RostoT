<?php
$Status = "UnAuth";
$UserData = "";
if (isset($_COOKIE['Token']))
{
    require "Secure/DBCred.php";
    $Sql = "SELECT * FROM users WHERE Token = '".$_COOKIE['Token']."'";
    if (($Result = ($DBase -> query("$Sql"))) -> num_rows == 1)
    {
        $UserData = $Result -> fetch_assoc();
        $Status = $UserData['Status'];
    };

};
?>
