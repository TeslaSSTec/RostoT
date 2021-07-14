<?php
require_once "Secure/CheckAuth.php";
if ($Status != "UnAuth" )
{
    header('Location: index.php');
    exit();
};
?>
<!doctype html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
    require_once "Addons/JQ&BS4.html";
    ?>
    <!--        sha-256           -->
    <script src="Addons/jquery.sha256.min.js"></script>

    <title>Авторизация</title>

</head>
<body>

<form id="form" class="container-sm" style="max-width:600px">
    <div class="text-center" style="margin-top: 25%">
        <h2 class="display-4">РОСТО-Т</h2>
        <h3>Авторизация</h3>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
            </div>
            <input type="text" class="form-control" oninput="HideAlrt()" id="Login" placeholder="Логин" required
                   pattern="[a-zA-Z0-9!@#$%^*_|-]{1,30}" minlength="2" maxlength="28">

        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
            </div>
            <input type="Password" class="form-control" oninput="HideAlrt()" id="Pass"
                   placeholder="Пароль" required pattern="[a-zA-Z0-9!@#$%^*_|-]{1,30}"
                   minlength="8" maxlength="28">
        </div>
    </div>

    <button id="SubBtn" type="submit" class="btn btn-lg container-fluid btn-primary" style="margin-bottom: 1em">
        Войти
    </button>
    <div class="container-fluid align-content-center">
        <a href="Regist.php" type="button" class="btn btn-outline-secondary container-fluid">
            Регистрация
        </a>
    </div>

</form>

<script type="text/javascript">
    function HideAlrt() {
        $("#SubBtn").attr("class", "btn btn-primary container-fluid");
        $("#SubBtn").text("Войти");

    };
    $('#form').submit(function auth(f) {
        f.preventDefault();
        $.ajax
        ({
            url: "Execute.php",
            type: "POST",
            data:
                ({
                    action: 'Auth',
                    LG: $.trim($("#Login").val()),
                    PS: $.sha256($.trim($("#Pass").val())),
                }),
            success: function (data) {
                if (data == "AuthInvalid") {
                    $("#SubBtn").attr("class", "btn btn-danger container-fluid");
                    $("#SubBtn").text("Неверный логин или пароль");
                } else {
                    if (data == "AuthSuccess") {
                        window.location.replace("index.php");
                    };
                }
            }
        })
    });
</script>
</body>
</html>