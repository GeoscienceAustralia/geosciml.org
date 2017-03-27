<?php include 'theme/header.inc'; ?>

    <div id="container-content">
        <?php include 'theme/right_menu.inc'; ?>

        <?php
            // read file contents from Markdown (.md) file
            require __DIR__ . '/vendor/autoload.php';
            $contents = file_get_contents('geosciml.org.md');
            $Parsedown = new Parsedown();
            echo $Parsedown->text($contents);
        ?>

    </div><!-- #content-container -->

<?php include 'theme/footer.inc'; ?>