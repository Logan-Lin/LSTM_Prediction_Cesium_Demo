<html>
    <head>
        <title>Similarity Demo</title>
        <link rel="stylesheet" type="text/css" href="../Styles/style.css">
        <link rel="stylesheet" type="text/css" href="../Styles/button.css">
        <script src="../Scripts/jquery.js"></script>
        <script src="../Scripts/script.js"></script>
    </head>
    <body>
        <script src="../Scripts/w3.js"></script>
        <div w3-include-html="../Entities/float-panel.html"></div>
        <script>w3.includeHTML();</script>
        <div class="block">
            <h1>Similarity Demo</h1>
        </div>
        <div class="block" id="first-level-select">
            <form method="post" action="index.php">
                <p>
                    <?php
                        $user_filter_html = '<input type="checkbox" name="user_filter"';
                        if ($_POST["user_filter"] == 'on') {
                            $user_filter_html .= " checked";
                        }
                        $user_filter_html .= "> Filter pairs whose similarity belows ";
                        echo $user_filter_html;

                        $filter_value_html = '<input type="text" name="filter_value" class="int-input"  value="';
                        if (strlen($_POST["filter_value"]) > 0) {
                            $filter_value_html .= $_POST["filter_value"];
                        } else {
                            $filter_value_html .= "0";
                        }
                        $filter_value_html .= '">';
                        echo $filter_value_html;
                    ?>
                </p>
                <p>
                    <?php
                        $max_row_html = 'Maximum pairs in the table: 
                        <input type="number" name="max_row" class="int-input" value="';
                        if (strlen($_POST["max_row"]) > 0) {
                            $max_row_html .= $_POST["max_row"];
                        } else {
                            $max_row_html .= '100';
                        }
                        $max_row_html .= '">';
                        echo $max_row_html;
                    ?>
                </p>
                <p>
                    <?php
                        $sort_ascend_html = '<input type="radio" name="sort" value="ascend"';
                        if ($_POST["sort"] == 'ascend' or strlen($_POST["sort"]) == 0) {
                            $sort_ascend_html .= 'checked';
                        }
                        $sort_ascend_html .= '>Ascend';
                        echo $sort_ascend_html;
                    ?>
                    <a style="margin: 0 5px 0 5px"></a>
                    <?php
                        $sort_descend_html = '<input type="radio" name="sort" value="descend"';
                        if ($_POST["sort"] == 'descend') {
                            $sort_descend_html .= 'checked';
                        }
                        $sort_descend_html .= '>Descend';
                        echo $sort_descend_html;
                    ?>
                    <a style="margin: 0 5px 0 5px"></a>
                    <input type="submit" class="submit_button" value="Find Pair">
                </p>
            </form>
        </div>
        <?php
            if (strlen($_POST["max_row"]) == 0) {
                die();
            }
            $sql = "select user_id1, user_id2, Similar from NextPre.OPTICS_Similar  where Similar >= ";
            if ($_POST["user_filter"] == "on" && strlen($_POST['filter_value']) > 0) {
                $sql .= "{$_POST['filter_value']}";
            } else {
                $sql .= "0";
            }
            $sql .= " order by Similar";
            if ($_POST["sort"] == "descend") {
                $sql .= " desc";
            }
            $totalRow = 0;
            if (strlen($_POST["max_row"]) > 0) {
                $totalRow = intval($_POST["max_row"]);
            }
            include "../Entities/Utils.php";

            $result = Utils::getSqlResult($sql);
        ?>
        <div class="block" id="second-level-select">
            <form method="post" action="pair_display.php">
                Select one pair: 
                <div class="scroll-div">
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
                                while (($row = $result->fetch_assoc()) && $totalRow-- > 0) {
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
                <p><input type="checkbox" name="show_labels"> Show labels</p>
                <input type="submit" class="submit_button" value="Display">
            </form>
        </div>
    </body>
</html>