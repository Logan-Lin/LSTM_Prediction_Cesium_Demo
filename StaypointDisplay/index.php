<html>
    <head>
        <title>Staypoint Display</title>
        <link type="text/css" rel="stylesheet" href="../Styles/style.css">
        <link type="text/css" rel="stylesheet" href="../Styles/button.css">    
        <script src="../Scripts/jquery.js"></script>
        <script src="../Scripts/script.js"></script>        
    </head>
    <body>
        <script src="../Scripts/w3.js"></script>
        <div w3-include-html="../Entities/float-panel.html"></div>
        <script>w3.includeHTML();</script>
        <div class="block" id="header">
            <h1>Staypoint Display</h1>
        </div>
        <div class="block" id="first-level-select">
            <form method="post" action="index.php">
                <p>
                    Select user ID: 
                    <select name="user_id">
                        <?php
                            include "../Entities/Utils.php";

                            $sql = "select user_id as value from NextPre.OPTICS_Sequence GROUP BY user_id";
                            $selected = null;
                            if (strlen($_POST["user_id"]) > 0) {
                                $selected = $_POST["user_id"];
                            }
                            echo Utils::sqlToSelections($sql, $selected);
                        ?>
                    </select>
                    <input type="submit" class="submit_button" value="Next">
                </p>
            </form>
        </div>
        <?php
            if (strlen($_POST["user_id"]) == 0) {
                die();
            }
        ?>
        <div class="block" id="second-level-select">
            <form method="post" action="staypoint_display.php">
                <input type="hidden" name="user_id" value="<?php echo($_POST["user_id"])?>">
                <p id="date-input">
                    <?php
                        $sql = "select min(arvDT) as 'minValue', max(levDT) as 'maxValue' " . 
                        "from NextPre.OPTICS_Sequence where user_id like {$_POST['user_id']}";
                        $dateInterval = Utils::sqlToMaxMin($sql);
                    ?>
                    Date interval (<?php echo("{$dateInterval[0]} to {$dateInterval[1]}")?>, 
                    leave empty for all):<br>
                    <input type="date" name="start_date" class="date-input"> - 
                    <input type="date" name="end_date" class="date-input">
                </p>
                <p id="show-label">
                    <input type="checkbox" name="show_label"> 
                    Show label
                </p>
                <input type="submit" class="submit_button" value="Submit">
            </form>
        </div>
    </body>
</html>