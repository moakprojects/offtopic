<?php
    //page switcher
    $page = "";
    switch($_GET['page']) {
        case "home":
            $page = "home";
        break;
        case "categories":
            $page = "categories";
        break;
        case "test":
            $page = "test";
        break;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Offtopic</title>
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
        <link href="css/main.css" rel="stylesheet">
        <?php
            echo "<style>";
            include("css/$page.css");
            echo "</style>";
        ?>
    </head>
    <body>
        <?php
            include("src/sections/headerGeneral.php");
            ?>
            <div class="sideSpace">
            <?php
            include("src/pages/$page.php");
            ?>
            </div>
            <?php
            include("src/sections/footer.php");
        ?>

        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
        <script src="js/main.js"></script>
        <?php
            echo "<script src='js/" . $page . ".js'></script>";
        ?>
    </body>
</html>