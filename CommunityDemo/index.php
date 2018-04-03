<html>
    <head>
        <title>Community Demo</title>
        <link rel="stylesheet" type="text/css" href="../Styles/style.css">
        <link rel="stylesheet" type="text/css" href="../Styles/button.css">
        <link rel="stylesheet" type="text/css" href="../Styles/seperate.css">
        <script src="../Scripts/jquery.js"></script>
        <script src="../Scripts/script.js"></script>
        <style>
            #pair-select {
                margin: 0 10px 0 10px;
            }
        </style>
    </head>
    <body>
        <script src="../Scripts/w3.js"></script>
        <div w3-include-html="../Entities/float-panel.html"></div>
        <script>w3.includeHTML();</script>

        <div class="block">
            <h1>Community Demo</h1>
        </div>
        <div class="table-container" id="container">
            <div class="table-row" id="selection-row">
                <div class="table-cell block" id="first-level-select">
                    <form method="post" action="index.php">
                        Select one user from community: 
                        <select name="user_id"><?php 
                            include "../Entities/Utils.php";

                            $sql = "select UserId as value from NextPre.Communities";
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
                        $sql = "select user_id1, user_id2, Similar from NextPre.OPTICS_Similar 
                        where Similar >= 1 and user_id1 like {$_POST['user_id']} and user_id2 in 
                        (select UserId from NextPre.Communities) order by user_id2";
                        $result = Utils::getSqlResult($sql);
                    ?>
                    <form method="post" action="../SimilarityDemo/pair_display.php">
                        Select one pair: 
                        <div class="scroll-div" id="pair-select">
                            <table>
                                <tr>
                                    <td>Check</td>
                                    <td>User ID 1</td>
                                    <td>User ID 2</td>
                                    <td>Similarity</td>
                                </tr>
                                <?php
                                    if ($result->num_rows > 0) {
                                        $string = "";
                                        while (($row = $result->fetch_assoc())) {
                                            $pair_id_string = $row["user_id1"] . "-" . $row["user_id2"];
                                            $string .= "<tr>
                                                <td><input type='radio' name='pair_id' value='{$pair_id_string}'></td>
                                                <td>{$row['user_id1']}</td>
                                                <td>{$row['user_id2']}</td>
                                                <td>{$row['Similar']}</td>
                                            </tr>";
                                        }
                                        echo $string;
                                    }
                                ?>
                            </table>
                        </div>
                        <p></p>
                        <input type="checkbox" name="show_labels"> Show labels
                        <p></p>
                        <input type="submit" class="submit_button" value="Display">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>