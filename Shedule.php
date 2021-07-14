<?php
require_once 'Secure/CheckAuth.php';
if ($Status != "Adm") {
    header('Location: index.php');
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

    <title>Расписание</title>

<body>
<?php
require_once "Header.php";
?>
<div class="container-fluid">
    <!-- Добавление, изменение машин -->

    <p class="h5">
        Добавление и изменение диапазона дат
    </p>
    <form id="Form">
        <div class="row container-fluid">
            <input id="StartD" type="date" class="col mx-1" style="min-width: 12em" required>
            <select id="StartH" class="form-control col mx-1" style="min-width: 6em" required>
                <option value="6">6.00</option>
                <option value="7">7.00</option>
                <option value="8">8.00</option>
                <option value="9">9.00</option>
                <option value="10">10.00</option>
                <option value="11">11.00</option>
                <option value="12">12.00</option>
                <option value="13">13.00</option>
                <option value="14">14.00</option>
                <option value="15">15.00</option>
                <option value="16">16.00</option>
                <option value="17">17.00</option>
                <option value="18">18.00</option>
                <option value="19">19.00</option>
                <option value="20">20.00</option>
                <option value="21">21.00</option>
                <option value="22">22.00</option>
                <option value="23">23.00</option>
            </select>
            <input id="EndD" type="date" class="col mx-1" style="min-width: 12em" required>
            <select id="EndH" class="form-control col mx-1" style="min-width: 6em" required>
                <option value="6">6.00</option>
                <option value="7">7.00</option>
                <option value="8">8.00</option>
                <option value="9">9.00</option>
                <option value="10">10.00</option>
                <option value="11">11.00</option>
                <option value="12">12.00</option>
                <option value="13">13.00</option>
                <option value="14">14.00</option>
                <option value="15">15.00</option>
                <option value="16">16.00</option>
                <option value="17">17.00</option>
                <option value="18">18.00</option>
                <option value="19">19.00</option>
                <option value="20">20.00</option>
                <option value="21">21.00</option>
                <option value="22">22.00</option>
                <option value="23">23.00</option>
            </select>
            <input id="Car" type="number" class="mx-1" value="1" style="min-width: 6em" required>
            <button type="submit" class="btn btn-primary mx-1" style="min-width: 10em">Обновить</button>
            <button type="button" class="btn btn-danger mx-1" style="min-width: 10em">Удалить</button>

        </div>
    </form>
    <!-- Вывод расписания -->
</div>
<?php
require "Secure/DBCred.php";
$CurD = getdate(time());
$StartMDay = (getdate(mktime("0", "0", "0", $CurD['mon'], '1', $CurD['year'])))['wday'];
if ($StartMDay == 0) {
    $StartMDay = 7;
}
$Sql = "SELECT * FROM `shedule` WHERE `TimeId` >= '" . mktime("0", "0", "0", $CurD['mon'], '1', $CurD['year']) . "' AND `TimeId` < '" . mktime("0", "0", "0", $CurD['mon'] + 1, '1', $CurD['year']) . "' ORDER BY `TimeId` ASC";
$Result = $DBase->query("$Sql");
$DBase -> close();

?>
<div class="container-fluid">
    <p class="h3 text-center py-3">Расписание</p>
    <table class="table table-bordered table-responsive">
        <thead>
        <tr>
            <th scope="col">Понедельник</th>
            <th scope="col">Вторник</th>
            <th scope="col">Среда</th>
            <th scope="col">Четверт</th>
            <th scope="col">Пятница</th>
            <th scope="col">Суббота</th>
            <th scope="col">Воскресенье</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $Flag = true;
        $Day = 1;
        $T = $Result->fetch_assoc();

        if ($StartMDay > 1) {
            echo "<tr>";
            for ($i = 1; $i < $StartMDay; $i++) {
                echo "
            <td>
                <p class='border rounded bg-secondary text-white container-fluid mb-1 text-center'>
                Прошлый месяц
                </p>
             </td>
            ";
            };
        };
        for ($Day = 1; $Day <= 32 && $Flag; $Day++) {
            if ($StartMDay == 1)
            {
                echo "<tr>";
            }
            $CntHrs = 0;
            echo "
            <td>
                <p class='border border-primary rounded bg-primary text-white container-fluid mb-1 text-center'>
                ".$Day.".".$CurD['mon'].".".$CurD['year']."
                ";
            while ((substr($T['FormTime'], 8, 2)) == $Day)
            {
                echo "
                <div class='input-group mb-1' style='min-width: 8em'>
                    <div class='input-group-prepend'>
                        <span class='input-group-text p-1'>".substr($T['FormTime'], 11, 2).":00</span>
                    </div>
                    <input type='number' class='form-control p-1' placeholder='".$T['Busy']."/".$T['Available']."' style='min-width: 3em'>
                    <div class='input-group-append'>
                        <div class='input-group-text py-1 px-2'>
                            <input type='checkbox' value='".$T['TimeId']."'>
                        </div>
                    </div>
                </div>
                ";
                $CntHrs ++;
                $Flag = ($T = $Result -> fetch_assoc());
            }

            if ($CntHrs == 0)
            {
                echo "<p class='text-center'>Занятий нет</p>";
            }
            echo "</td>";
            if ($StartMDay == 7)
            {
                echo "</tr>";
                $StartMDay = 1;
            }
            else {
                $StartMDay++;
            }


        }
        ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $("#Form").submit(
        function Update(f) {
            f.preventDefault();
            $("#Test").text($("#StartD").val());
            $.ajax({
                url: "Execute.php",
                type: "POST",
                data:
                    ({
                        action: 'ShedRngUpd',
                        Start: Date.parse($("#StartD").val()) / 1000 + (3600 * $("#StartH").val()) + (new Date().getTimezoneOffset() * 60),
                        End: Date.parse($("#EndD").val()) / 1000 + (3600 * $("#EndH").val()) + (new Date().getTimezoneOffset() * 60),
                        Car: $("#Car").val(),
                    }),
                success: function (data) {
                    if (data == "RangeErr") {
                        alert("Ошибка диапазона дат");
                    }
                    if (data == "ShedUpdSuccess") {
                        alert("Расписание обновлено");
                    }

                }

            })

        });
</script>

</body>
</html>