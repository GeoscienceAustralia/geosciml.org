<?php
include '../settings.php';
require $PROJECT_HOME_DIR . 'vendor/autoload.php';
date_default_timezone_set('Australia/Brisbane');

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
        if (isset($vocab->sissvoc_end_point)) {
            $html .= "\t" . '<li><a href="' . stripslashes($vocab->sissvoc_end_point) . '/concept">' . $vocab->title . ' concepts</a></li>' . "\n";
        }
    }

    $html .= '</ul>'."\n";

    return $html;
}

function make_vocabs_list_rdf($vocabs) {
    foreach ($vocabs as $vocab) {
        if (isset($vocab->sissvoc_end_point)) {

        }
    }
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

// http://stackoverflow.com/questions/1049401/how-to-select-content-type-from-http-accept-header-in-php
function getBestSupportedMimeType($mimeTypes = null) {
    // Values will be stored in this array
    $AcceptTypes = Array ();

    // Accept header is case insensitive, and whitespace isn’t important
    $accept = strtolower(str_replace(' ', '', $_SERVER['HTTP_ACCEPT']));
    // divide it into parts in the place of a ","
    $accept = explode(',', $accept);
    foreach ($accept as $a) {
        // the default quality is 1.
        $q = 1;
        // check if there is a different quality
        if (strpos($a, ';q=')) {
            // divide "mime/type;q=X" into two parts: "mime/type" i "X"
            list($a, $q) = explode(';q=', $a);
        }
        // mime-type $a is accepted with the quality $q
        // WARNING: $q == 0 means, that mime-type isn’t supported!
        $AcceptTypes[$a] = $q;
    }
    arsort($AcceptTypes);

    // if no parameter was passed, just return parsed data
    if (!$mimeTypes) return $AcceptTypes;

    $mimeTypes = array_map('strtolower', (array)$mimeTypes);

    // let’s check our supported types:
    foreach ($AcceptTypes as $mime => $q) {
        if ($q && in_array($mime, $mimeTypes)) return $mime;
    }
    // no mime-type found
    return null;
}


$mimetypes = array(
    'text/turtle',
    'text/n3',
    'application/n-triples',
    'application/rdf+xml',
    'application/json+xml',
    'text/html',
    'application/xhtml+xml'
);
// find the strpos of each mimetype in Accept header
$mimestring = $_SERVER['HTTP_ACCEPT'];
foreach ($mimetypes as $m) {
    if (strpos($mimestring, $m) === 0) {
        $mimetype = $m;
        break;
    }
}
if (empty($mimetype)) {
    $mimetype = 'text/html';
}


// decide whether it's RDF or HTML
switch ($mimetype) {
    case 'text/turtle':
    case 'text/n3':
    case 'application/n-triples':
    case 'application/rdf+xml':
    case 'application/json+xml':
        header('Content-Type: text/plain');
        print 'RDF coming';
        break;
    case 'text/html':
    case 'application/xhtml+xml':
        include $PROJECT_HOME_DIR . 'theme/header.inc';

        echo '<div id="container-content">';

        include $PROJECT_HOME_DIR . 'theme/right_menu.inc';

        print make_page_html();

        echo '</div><!-- #content-container -->';

        include $PROJECT_HOME_DIR . 'theme/footer.inc';
        break;
}