<?php
    session_start();
    //page switcher
    $page = "";
    if(isset($_GET["page"])) {
        switch($_GET['page']) {
            case "home":
                $page = "home";
            break;
            case "categories":
                $page = "categories";
            break;
            case "topics":
                $page = "topics";
            break;
            case "discussion":
                $page = "discussion";
            break;
            case "profile":
                $page = "profile";
            break;
    
    
            case "test":
                $page = "test";
            break;
            default:
                $page = "home";
        }
    } else {
        $page = "home";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Offtopic</title>
        
        <!-- Compiled and minified materialize CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

        <!-- google fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">

        <!-- materialize icons -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        
        <!-- font awesome -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
        
        <!-- main css -->
        <link href="public/css/main.css" rel="stylesheet">
    
        <?php
            /* Dinamical css, depend on the page */
            echo "<style>";
            include("public/css/$page.css");
            echo "</style>";
        ?>

        <!-- Shortcut icon -->
        <link rel="shortcut icon" href="/images/offtopicLogo.png" type="image/x-icon">

        <!-- Quill reach text editor -->
        <script src="//cdn.quilljs.com/1.3.5/quill.js"></script>
        <link href="//cdn.quilljs.com/1.3.5/quill.snow.css" rel="stylesheet">
    </head>
    <body>
        <?php
            require ("config/connection.php");
            $loggedIn = true;

            if($loggedIn) {
                include("resources/sections/headerUser.php");
            } else {
                include("resources/sections/headerGeneral.php");
            }

            ?>
            <div class="sideSpace">
            <?php
            include("resources/pages/$page.php");
            ?>
            </div>
            <?php
            include("resources/sections/footer.php");
        ?>

        <!--Import jQuery before materialize.js-->
        <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
        
        <!-- own scripts main and dinamic depends on the page -->
        <script src="public/js/main.js"></script>
        <?php
            echo "<script src='public/js/" . $page . ".js'></script>";
        ?>
    </body>
</html>