<html>
    <head>
        <title>Community Demo</title>
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

        <div class="block">
            <h1>Prediction demo</h1>
        </div>
        <div class="table-container" id="container">
            <div class="table-row" id="selection-row">
                <div class="table-cell block" id="first-level-select">
                    <form method="post" action="index.php">
                        Select one user from community: 
                        <select name="user_id"><?php 
                            include "../Entities/Utils.php";

                            $sql = "select user_id as value from NextPre.user_sequence GROUP BY user_id";
                            $selected = null;
                            if (strlen($_POST["user_id"]) > 0) {
                                $selected = $_POST["user_id"];
                            }
                            echo Utils::sqlToSelections($sql, $selected)
                        ?></select>
                        <p></p>
                        <input type="submit" class="submit_button" value="Next">
                    </form>
                </div>
                <div class="table-cell block" id="second-level-select">
                    <?php
                        if (strlen($_POST["user_id"]) == 0) {
                            die();
                        }

                        $seq_id_array = Utils::getSequenceArray($_POST["user_id"]);
                        $pre_id_array = Utils::getPredictArray($_POST["user_id"]);
                    ?>
                    <form method="post" action="predict_display.php">
                        <input type="hidden" name="user_id" value=<?=$_POST["user_id"]?>>
                        <p><input type="checkbox" name="show_labels"> Show labels</p>
                        <input type="submit" value="Display" class="submit_button">
                    </form>
                    <table>
                        <tr>
                            <td>Index</td>
                            <td>User Sequence</td>
                            <td>Prediction</td>
                        </tr>
                        <?php
                            $count = 0;
                            $seq_number = count($seq_id_array);
                            $pre_number = count($pre_id_array);
                            while ($count < $seq_number || $count < $pre_number) {
                                echo "<tr>";
                                $index = $count + 1;
                                echo "<td>{$index}</td>";
                                echo "<td>{$seq_id_array[$count]}</td>";
                                echo "<td>{$pre_id_array[$count]}</td>";
                                echo "</tr>";
                                $count++;
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>