<html>
    <head>
        <title>Location Display</title>
        <link rel="stylesheet" type="text/css" href="../Styles/style.css">
        <link rel="stylesheet" type="text/css" href="../Styles/button.css">
        <link rel="stylesheet" type="text/css" href="../Styles/seperate.css">
        <script src="../Scripts/jquery.js"></script>
        <script src="../Scripts/script.js"></script>
    </head>
    <body>
        <script src="../Scripts/w3.js"></script>
        <div w3-include-html="../Entities/float-panel.html"></div>
        <script>w3.includeHTML();</script>
        <div class="block" id="header">
            <h1>Location Display</h1>
        </div>
        <div class="table-container">
            <div class="table-row">
                <div class="table-cell block" id="first-level-select">
                    <form method="post" action="index.php">
                        <p>
                            Select location level: 
                            <select name="level" id="level-select">
                                <?php
                                    for ($i = 1; $i <= 4; $i += 1) {
                                        echo "<option value='$i'";
                                        if (strlen($_POST["level"]) > 0 && $i == intval($_POST["level"])) {
                                            echo " selected='selected'";
                                        }
                                        echo ">Level $i</option>";
                                    }
                                ?>
                            </select>
                            <p></p>
                            <input type="submit" class="submit_button" value="Next">
                        </p>
                    </form>
                </div>
                <div class="table-cell block" id="second-level-select">
                    <?php 
                        if (strlen($_POST["level"]) == 0) {
                            die();
                        }
                    ?>
                    <form method="post" action="location_display.php">
                        <input type="hidden" name="level" value="<?php echo $_POST['level']?>">
                        <p>
                            Select location id:<br>
                            <select name="location[]" style="height: 400px; width: 150px" multiple>
                                <?php
                                    include "../Entities/Utils.php";

                                    $sql = "select level_{$_POST['level']} as value from NextPre.OPTICS_Sequence GROUP BY value;";
                                    echo Utils::sqlToSelections($sql, null);
                                ?>
                            </select>
                        </p>
                        <p>
                            <input type="checkbox" name="show_label"> 
                            Show labels
                        </p>
                        <input type="submit" class="submit_button" value="Display">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>