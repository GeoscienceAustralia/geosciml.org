<div id="rightMenu" style="display:block; float:right; width:300px; background-color:#E5E5E5; padding:5px;">

    <?php
        // read file contents from Markdown (.md) file
        require $PROJECT_HOME_DIR . '/vendor/autoload.php';
        $contents = file_get_contents($PROJECT_HOME_DIR . 'pages/menu.geosciml.org.md');
        $Parsedown = new Parsedown();
        echo $Parsedown->text($contents);
    ?>

    <div style="display:block; width:300px; min-height:150px; background-image:url('/geosciml.org/theme/img/map_image2.jpg');"></div>

</div><!-- #rightMenu -->
