<header>
    <div class="d-flex flex-column flex-md-row align-items-center p-0 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="display-4 my-0 mr-md-auto">Росто-Т</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="btn btn-outline-secondary" href='index.php'>Запись</a>
            <?php
            if ($Status == "Adm")
            {
                echo "<a class=\"btn btn-outline-secondary\" href='Shedule.php'>Расписание</a>";
            }
            ?>

           <!-- <a class="p-2 text-dark" href="#">Features</a>
            <a class="p-2 text-dark" href="#">Enterprise</a>
            <a class="p-2 text-dark" href="#">Support</a>
            <a class="p-2 text-dark" href="#">Pricing</a>-->
        </nav>
        <button type="button" class="btn btn-outline-primary" onclick="SignOut()"> <i class="fa fa-sign-out" aria-hidden="true"></i> Выйти</button>
    </div>
</header>
<script type="text/javascript">
    function SignOut() {
        document.cookie = "Token=Un; max-age=0";
        window.location.replace("Auth.php");
    }
</script>