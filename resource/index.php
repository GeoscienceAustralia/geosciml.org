<?php include '../theme/header.inc'; ?>

<div id="container-content">
    <?php include '../theme/right_menu.inc'; ?>

    <?php
        // read file contents from Markdown (.md) file
        require '../vendor/autoload.php';
        $contents = file_get_contents('../resource.geosciml.org.md');
        $Parsedown = new Parsedown();
        echo $Parsedown->text($contents);
    ?>

<?php include '../theme/footer.inc'; ?>
