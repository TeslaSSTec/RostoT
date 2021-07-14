<?php
$DBase = new mysqli('localhost','root','root','rosto_t');
if ($DBase -> connect_errno)
{
    echo "ConnectDBError";
    exit();
}
?>