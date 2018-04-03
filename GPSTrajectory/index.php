<html>
    <head>
        <title>Show GPS Trajectory</title>
        <link rel="stylesheet" type="text/css" href="../Styles/style.css">
        <link rel="stylesheet" type="text/css" href="../Styles/button.css">
        <script src="../Scripts/jquery.js"></script>
        <script src="../Scripts/script.js"></script>
    </head>
    <body>
        <script src="../Scripts/w3.js"></script>
        <div w3-include-html="../Entities/float-panel.html"></div>
        <script>w3.includeHTML();</script>
        <div class="block" id="header">
            <h1>GPS Trajectory Display</h1>
        </div>
        <div class="block" id="first-level-select">
            <form method="post" action="index.php">
                <p>
                    Select user ID: 
                    <select name="user_id">
                        <?php
                            include "../Entities/Utils.php";

                            $sql = "select Id as value from NextPre.GPS GROUP BY Id;";
                            $selected = null;
                            if (strlen($_POST["user_id"]) > 0) {
                                $selected = $_POST["user_id"];
                            }
                            echo Utils::sqlToSelections($sql, $selected);
                        ?>
                    </select>
                    <input type="submit" class="submit_button" value="List Date">
                </p>
            </form>
        </div>
        <?php
        if (strlen($_POST["user_id"]) == 0) {
            die();
        }
        $sql = "select Date from NextPre.GPS " .
        "where Id like '{$_POST["user_id"]}' group by Date";
        $result = Utils::getSqlResult($sql);
        ?>
        <div class="block" id="second-level-select">
            <form method="post" action="gps_display.php">
                <input type="hidden" name="user_id" value="<?php echo($_POST["user_id"])?>">
                <p id="date-select">
                    Select Date: <select name="date">
                       <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $date = $row["Date"];
                                echo("<option value='{$date}'>{$date}</option>");
                            }
                        }
                       ?> 
                    </select>
                </p>
                <p id="time-select">
                    Time interval(hh:mm:ss, leave empty for full day): 
                    <input type="time" name="start_time" class="time-input"> - 
                    <input type="time" name="end_time" class="time-input">
                </p>
                <p id="show-label">
                    <input type="checkbox" name="show_label"> 
                    Show label
                </p>
                <p id="show-staypoint">
                    <input type="checkbox" name="show_staypoint"> 
                    Show staypoint
                </p>
                <input type="submit" class="submit_button" value="Submit">
            </form>
        </div>
    </body>
</html>