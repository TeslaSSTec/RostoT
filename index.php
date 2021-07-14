<?php
require_once 'Secure/CheckAuth.php';
if ($Status == "UnAuth") {
    header('Location: Auth.php');
    exit();
}
?>

<!doctype html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
    require_once "Addons/JQ&BS4.html";
    ?>
    <style type="text/css">
        .Width {
            min-width: 12em;
            max-width: 20em;
        }
    </style>
    <title>Главная</title>
</head>
<body>
<?php
require_once "Header.php";
require_once "Secure/Dates.php";
?>
<div class="container">
    <div class="card-deck">
        <?php
        require "Secure/DBCred.php";
        $Sql = "SELECT `TimeId`,`FormTime` FROM `shedule` WHERE `TimeId` >= '" . (time() + 3600) . "' AND `TimeId` <= '" . (time() + 3600 + (3600 * 24 * 10)) . "' AND `Busy` < `Available` ORDER BY `TimeId` ASC";
        $Result = $DBase->query("$Sql");
        $DBase->close();
        if ($Result->num_rows == 0) {
            echo "<div class=\"alert alert-danger container-fluid text-center\" role=\"alert\">
  Нет свободного времени
</div>";
        } else {

            $CurD = 0;
            $CurT = 0;
            while (($Info = $Result->fetch_assoc()) != false) {
                if (substr($Info['FormTime'], 8, 2) != $CurD) {
                    if ($CurD != 0) {
                        echo " 
                            </div>
                          </div>";
                    }
                    echo " 
                            <div class='card m-1' style='min-width: 15em'> 
                                <div class='card-header text-center p-2 '>
                                    <label class='m-0'>" . substr($Info['FormTime'], 8, 2) . " " . $Mnts[substr($Info['FormTime'], 5, 2) - 1] . " " . substr($Info['FormTime'], 0, 4) . " (" . $WDays[getdate($Info['TimeId'])['wday']] . ")</label> 
                                </div>
            <div class='card-body p-2'>";

                }
                $CurD = substr($Info['FormTime'], 8, 2);
                $Hr = substr($Info['FormTime'], 11, 2);
                if (($Hr >= 12 && $CurT < 12) || ($Hr >= 18 && $CurT < 18)) {
                    if ($CurT != 0) {
                        echo " <li class='dropdown-divider'></li>";
                    }

                }
                $CurT = $Hr;
                echo "<button class='btn btn-outline-primary m-1' value='" . $Info[TimeId] . "' onClick = 'Select(this)' value='" . $Info['TimeId'] . "'>
                " . substr($Info['FormTime'], 11, 5) . "
            </button>";
            }

            echo "</div> </div>";
        }
        ?>

    </div>
    <div class="container mt-3">
    <button id="Send" type="button" class="btn btn-secondary container-fluid" value="" onclick="Reserve(this)">Вы не выбрали время</button>
    </div>
</div>
<script type="text/javascript">
    function Select(e) {

        $("#Send").text("Записаться");
        $("#Send").attr("value", $(e).attr("value"));
        $("#Send").attr("class", "btn btn-success container-fluid")
        $(e).attr("class", "btn btn-success m-1");
    }

    function Reserve(e) {
        $.ajax
        ({
            url: "Execute.php",
            type: "POST",
            data:
                ({
                    action: 'Reserve',
                    Time: $(e).val(),
                }),
            success: function (data) {
                if (data == "ResSuccess")
                {
                    alert("Успех");
                }
            },

        });
    };
</script>

</body>
</html>