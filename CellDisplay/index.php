<html>
    <head>
        <title>Cell Display</title>
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
            <h1>User's cell sequence</h1>
        </div>

        <div class="block">
            <form method="post" action="cell_display.php">
                Select one user id:
                <select name="user_id">
                    <?php
                        include "../Entities/Utils.php";

                        $sql = "select user_id as value from NextPre.user_cell_data GROUP BY user_id";
                        echo Utils::sqlToSelections($sql, null)
                    ?> 
                </select>
                <p id="show-label">
                    <input type="checkbox" name="show_label"> 
                    Show label
                </p>
                <p>
                    <input type="radio" name="data" value="cell"> Display cell<br>
                    <input type="radio" name="data" value="block"> Display block
                </p>
                <input type="submit" class="submit_button" value="Next">
            </form>
        </div>
    </body>
</html>