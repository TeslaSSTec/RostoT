<?php
require_once "Secure/CheckAuth.php";
if (isset($_POST['action'])) {
    /* Регистрация*/
    if ($_POST['action'] == "Regist") {
        if (isset($_POST['Login']) && isset($_POST['Pass']) && isset($_POST['RePass'])&& isset($_POST['Eml'])&& isset($_POST['FN'])&& isset($_POST['LN'])&& isset($_POST['SN'])&& isset($_POST['Plan'])) {
            require "Secure/DBCred.php";
            $Sql = "SELECT COUNT(*) FROM `users` WHERE `Login` = '".$_POST['Login']."'";
            $Result = $DBase->query("$Sql");
            $DBase ->close();
            if ($Result ->fetch_assoc()['COUNT(*)'] != 0) {
                echo "LoginNot";
            } else {
                if ($_POST['Pass'] != $_POST['RePass']) {
                    echo "PassError";
                } else {
                    require "Secure/DBCred.php";
                    $Token = hash('sha256',$_POST['Login'].$_POST['Pass'].$_POST['Eml']);
                    $Sql = "INSERT INTO `users` (`Email`, `FirstName`, `SecondName`, `LastName`, `Pass`, `Plan`, `Status`, `Login`,`Token`) VALUES ('".$_POST['Eml']."', '".$_POST['FN']."', '".$_POST['SN']."', '".$_POST['LN']."', '".$_POST['Pass']."', '".$_POST['Plan']."', 'New', '".$_POST['Login']."','".$Token."')";
                    if ($Result = $DBase->query("$Sql"))
                    {
                        echo "Success";
                    }
                    else{
                        echo "SQLRequestFail";
                    }
                    $DBase -> close();

                }
            }
        }
    }
    /* Авторизация */
    if ($_POST['action'] == "Auth")
    {
        if(isset($_POST['LG']) && isset($_POST['PS']))
        {
            require "Secure/DBCred.php";
            $Sql = "SELECT `Token` FROM `users` WHERE `Login` = '".$_POST['LG']."' AND `Pass` = '".$_POST['PS']."'";
            if (($Result = $DBase ->query("$Sql")) -> num_rows == 1)
            {
                $Token = $Result->fetch_assoc()['Token'];
                setcookie('Token',$Token,time()+(60*60*24*30),"/","");
                echo "AuthSuccess";
            }
            else{
                echo "AuthInvalid";
            }
            $DBase -> close();

        }
    }
    /*Обновление расписания диапазоном*/
    if ($_POST['action'] == "ShedRngUpd" && $Status == "Adm")
    {
        if(isset($_POST['Start']) && isset($_POST['End']) && isset($_POST['Car']))
        {
            if ($_POST['Start']>$_POST['End'] || $_POST['Car'] < 1)
            {
                echo "RangeErr";
            }
            else
            {
                $Start = getdate($_POST['Start']);
                $End = getdate($_POST['End']);
                for ($i = $_POST['Start']; $i <= $_POST['End']; $i+=3600)
                {
                    $Sel = getdate($i); //Массив времени
                    if (($Sel['hours'] >= $Start['hours']) && ($Sel['hours'] <= $End['hours']))
                    {
                        require "Secure/DBCred.php";
                        $Sql = "SELECT COUNT(*) FROM `shedule` WHERE `TimeId` = '".$i."'";
                        $Result = $DBase->query("$Sql");
                        if ($Result -> fetch_assoc()['COUNT(*)'] == 0)
                        {
                            $Sql = "INSERT INTO `shedule` (`TimeId`, `Available`, `Busy`,`FormTime`) VALUES ('".$i."', '".$_POST['Car']."', '0','".date("Y-m-d H:i:s",$i)."')";
                            $DBase -> query("$Sql");
                        }
                        else
                        {

                            $Sql = "SELECT `Busy` FROM `shedule` WHERE `TimeId` = '".$i."'";
                            $Result = $DBase ->query("$Sql");
                            if (($Result -> fetch_assoc()['Busy']) <= $_POST['Car'])
                            {
                                $Sql = "UPDATE `shedule` SET `Available` = '".$_POST['Car']."' WHERE `shedule`.`TimeId` = '".$i."' AND `shedule`.`Busy` <= '".$_POST['Car']."'";
                                $DBase -> query("$Sql");
                            }
                        }
                        $DBase -> close();
                    };

                };
                echo "ShedUpdSuccess";

            }
        }
    }
    /* Запись на занятие*/
    if ($Status != "UnAuth" && $_POST['action'] == "Reserve" && isset($_POST['Time']))
    {
        require "Secure/DBCred.php";
        $Sql = "UPDATE `shedule` SET `Busy` = `Busy` + 1 WHERE `TimeId` = '".$_POST['Time']."' AND `shedule`.`Busy` < `shedule`.`Available`";
        print_r($Sql);
        if ($DBase -> query($Sql) == 1)
        {
            $Sql = "INSERT INTO `records` (`ShedTimeId`,`Id`) VALUES ('".$_POST['Time']."','".$UserData['Id']."')";
            print_r($DBase -> query($Sql));
        }

    }
} else {
    header('Location: index.php');
}
?>
