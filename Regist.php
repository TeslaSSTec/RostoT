<?php
require_once 'Secure/CheckAuth.php';
if ($Status != "UnAuth" )
{
    header('Location: index.php');
    exit();
};
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
    <!--        sha-256           -->
    <script src="Addons/jquery.sha256.min.js"></script>
    <style>
        .mar {
            margin-bottom: 0.2em;
        }
    </style>

    <title>Регистрация</title>

</head>
<body>

<form id = "form" class="container-sm" style="max-width:600px">
    <div class="text-center" style="margin-top: 10%">
        <h2 class="display-4">РОСТО-Т</h2>
        <h3>Регистрация</h3>
    </div>
    <div class="form-group">
        <label for="Login">Логин</label>
        <input type="text" class="form-control" oninput="HideLoginAlrt()" id="Login" placeholder="Введите логин [a-z,A-Z,0-9,!@#$%^*_-|]" required pattern="[a-zA-Z0-9!@#$%^*_|-]{1,30}" minlength="2" maxlength="28">

    </div>
    <div class="form-group">
        <label for="Password">Пароль</label>
        <input type="Password" class="form-control mar" oninput="HidePassAlrt()" id="Password1" placeholder="Ведите пароль [a-z,A-Z,0-9,!@#$%^*_-|]" required pattern="[a-zA-Z0-9!@#$%^*_|-]{1,30}" minlength="8" maxlength="28">
        <input type="Password" class="form-control" oninput="HidePassAlrt()" id="Password2" placeholder="Повторите пароль" required pattern="[a-zA-Z0-9!@#$%^*_|-]{1,30}" minlength="8" maxlength="28">
    </div>
    <div class="form-group">
        <label for="Email">E-mail</label>
        <input type="email" class="form-control" id="email" placeholder="Электронная почта (Для восстановления доступа)" required maxlength="48">
    </div>
    <div class="form-group">
        <label for="FirstName">Инициалы</label>
        <input type="text" class="form-control mar" id="LastName" placeholder="Фамилия" required maxlength="28">
        <input type="text" class="form-control mar" id="FirstName" placeholder="Имя" required maxlength="28">
        <input type="text" class="form-control" id="SecondName" placeholder="Отчество" required maxlength="28">
    </div>
    <div class="form-group">
        <label for="Plan">Выберите тариф</label>
        <select required id="Plan" class="form-control">
            <option value="start">Стартовый</option>
            <option value="class">Классик</option>
            <option value="person">Персональный</option>
        </select>
    </div>
    <button id="SubBtn" type="submit" class="btn btn-primary container-fluid" >Зарегистрироваться</button>
    <a href="Auth.php" class="d-block mx-auto text-center btn-link" style = "margin-bottom: 10%; margin-top: 1em">Войти</a>

</form>
<script type="text/javascript">

    function HideLoginAlrt ()
    {

        $("#LoginCheck").remove();
    };
    function HidePassAlrt ()
    {
        $("#PassCheck").remove();
    };
    $('#form').submit(function regist(f)
    {
        f.preventDefault();
        if ($.sha256($.trim($("#Password1").val())) != $.sha256($.trim($("#Password2").val())))
        {
            $("#PassCheck").remove();
            $("<label id = 'PassCheck' for='Password2' class='text-danger'>Пароли не совпадают</label>").insertAfter("#Password2");
        }
        else {
            $.ajax
            ({
                url: "Execute.php",
                type: "POST",
                data:
                    ({
                        action: 'Regist',
                        Login: $.trim($("#Login").val()),
                        Pass: $.sha256($.trim($("#Password1").val())),
                        RePass: $.sha256($.trim($("#Password2").val())),
                        Eml: $("#email").val(),
                        FN: $.trim($("#FirstName").val()),
                        LN: $.trim($("#LastName").val()),
                        SN: $.trim($("#SecondName").val()),
                        Plan: $("#Plan").val(),
                        Token: $.sha256($.trim($("#Login").val()) + $.trim($("#Password1").val())+ $("#email").val()),

                    }),
                success: function (data) {
                    if (data == "LoginNot") {
                        $("#LoginCheck").remove();
                        $("<label id = 'LoginCheck' for='Login' class='text-danger'>Логин занят</label>").insertAfter("#Login")
                    } else{
                        if (data =="PassError")
                        {
                            $("#PassCheck").remove();
                            $("<label id = 'PassCheck' for='Password2' class='text-danger'>Пароли не совпадают</label>").insertAfter("#Password2");
                        }
                        else {
                            if (data == "Success") {
                                $("#SubBtn").attr("class","btn btn-success container-fluid");
                                $("#SubBtn").html("Вы успешно зарегистрировались <br> <small> Ожидайте проверки аккаунта... </small>");
                                setTimeout(function () {
                                    window.location.replace("Auth.php");
                                },3000);

                            };
                        }
                    }
                }


            });
        }
    });
</script>
</body>
</html>