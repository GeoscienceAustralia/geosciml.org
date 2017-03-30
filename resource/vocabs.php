<?php
include '../settings.php';
require $PROJECT_HOME_DIR . 'vendor/autoload.php';


function get_vocabs($publisher, $p=1) {
    $url = "https://vocabs.ands.org.au/vocabs/filter";

    $headers = array(
        'Content-Type' => 'application/json'
    );

    $data = array(
        "filters" => array(
            "p" => $p,
            "publisher" => $publisher,
            "q" => ""
        )
    );

    $r = Requests::post($url, $headers, json_encode($data));

    $results = json_decode($r->body);
    $remaining =  $results->response->numFound - $p*10;
    return array($remaining, $results->response->docs);
}

function get_all_vocabs($publisher) {
    $vocabs = array();
    $remaining = 1000000;
    $page = 1;
    while ($remaining > 0) {
        $r = get_vocabs($publisher, $page);
        $vocabs = array_merge($vocabs, $r[1]);
        $remaining = $r[0];
        $page++;
    }
    return $vocabs;
}

function make_vocabs_list_html($vocabs) {
    $html = '<ul>'."\n";

    foreach ($vocabs as $vocab) {
        $html .= "\t".'<li><a href="' . stripslashes($vocab->sissvoc_end_point). '/concept">' . $vocab->title . ' concepts</a></li>' . "\n";
    }

    $html .= '</ul>'."\n";

    return $html;
}

function make_page_html() {
    $publisher = 'CGI Geoscience Terminology Working Group';
    $vocabs = get_all_vocabs($publisher);

    $html = '<h1>CGI Vocabularies Register</h1>' .
        '<p>This is the static register (index) of CGI\'s vocabularies. Its purpose is to present the static URIs to each of the vocabs. This is particularly useful for machines that wish to automatically index our vocabularies, rather than humans who can search for them manually.</p>' .
        '<p>These vocabularies are all published for general discovery in the <a href="http://www.ands.org.au/">Australian National Data Service (ANDS)</a>\'s <a href="http://vocabs.ands.org.au/">Research Vocabularies Australia (RVA) Portal</a>:</p>' .
        '<ul><li><a href="https://vocabs.ands.org.au/search/#!/?p=1&publisher=CGI%20Geoscience%20Terminology%20Working%20Group">CGI\'s vocabs in the RVA portal</a></li></ul>' .
        '<h2>Vocabularies</h2>';

    return $html . make_vocabs_list_html($vocabs);
}

/*
 * Static page content
 */
include $PROJECT_HOME_DIR . 'theme/header.inc';
?>

<div id="container-content">
    <?php include $PROJECT_HOME_DIR . 'theme/right_menu.inc'; ?>

<!--    --><?php
//    // read file contents from Markdown (.md) file
//    $contents = file_get_contents($PROJECT_HOME_DIR . 'pages/vocabs.md');
//    $Parsedown = new Parsedown();
//    echo $Parsedown->text($contents);
//    ?>

    <?php print make_page_html(); ?>

</div><!-- #content-container -->

<?php include $PROJECT_HOME_DIR . 'theme/footer.inc'; ?>
