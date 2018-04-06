<html>
    <head>
        <title>KDD Data Display</title>
        <link type="text/css" rel="stylesheet" href="../Styles/style.css">
        <link type="text/css" rel="stylesheet" href="../Styles/button.css">
        <script src="../Scripts/jquery.js"></script>
        <script src="../Scripts/script.js"></script>
    </head>
    <body>
        <div class="block" id="header">
            <h1>KDD Data Display</h1>
        </div>
        <div class="block" id="first-level-select">
            <form action="index.php" method="post">
                <p>Select city: </p>
                <select name="city">
                    <option value="bj">Beijing</option>
                    <option value="ld">London</option>
                </select>
                <p><input type="submit" value="Next"></p>
            </form>
        </div>
        <?php
            if (strlen($_POST["city"]) == 0) {
                die();
            }
        ?>
        <div class="block" id="second-level-select">
            <?php
                include "../Entities/Utils.php";

                if ($_POST["city"] == "bj") {
                    $sql = "select max(utctime) as `maxValue`, min(utctime) as `minValue` from KDD.bj_17_18_aq;";
                } else if ($_POST["city"] == "ld") {
                    $sql = "select max(utctime) as `maxValue`, min(utctime) as `minValue` from KDD.ld_17_18_aq;";
                }
                $dateSpan = Utils::sqlToMaxMin($sql);
            ?>
            <form method="post" action="kdd_display.php">
                <input type="hidden" name="city" value="<?php echo($_POST["city"])?>">
                <p>Input date (from <?php echo($dateSpan[0])?> to <?php echo($dateSpan[1])?>):
                    <input type="date" name="start_date"> TO <input type="date" name="end_date">
                </p>
                <input type="submit" value="OK">
            </form>
        </div>
    </body>
</html>