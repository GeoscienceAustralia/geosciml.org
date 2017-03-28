<?php
    include '../settings.php';
    require $PROJECT_HOME_DIR . 'vendor/autoload.php';
    include $PROJECT_HOME_DIR . 'theme/header.inc';
?>

    <div id="container-content">
        <?php include $PROJECT_HOME_DIR . 'theme/right_menu.inc'; ?>

        <?php
            // read file contents from Markdown (.md) file
            $contents = file_get_contents($PROJECT_HOME_DIR . 'pages/earthresourceml.org.md');
            $Parsedown = new Parsedown();
            echo $Parsedown->text($contents);
        ?>

    </div><!-- #content-container -->

<?php include $PROJECT_HOME_DIR . 'theme/footer.inc'; ?>
