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

function make_vocabs_list_rdf($vocabs, $register, $vocabs_graph) {
    foreach ($vocabs as $vocab) {
        if (isset($vocab->sissvoc_end_point)) {
            $x = $vocabs_graph->resource($vocab->sissvoc_end_point, 'skos:ConceptScheme');
            $x = $vocabs_graph->resource($vocab->sissvoc_end_point, 'dcat:Dataset');
            $x = $vocabs_graph->resource($vocab->sissvoc_end_point, 'gapd:Vocabulary');
            $x->add('rdfs:label', $vocab->title);
            $x->add('reg:register', $register);
        }
    }

    return $vocabs_graph;
}

function make_page_html() {
    $publisher = 'CGI Geoscience Terminology Working Group';
    $vocabs = get_all_vocabs($publisher);

    $html = '<h1>CGI Vocabularies Register</h1>' .
        '<p>This is the static register (index) of CGI\'s vocabularies. Its purpose is to present the static URIs to each of the vocabs. This is particularly useful for machines that wish to automatically index our vocabularies, rather than humans who can search for them manually.</p>' .
        '<p>This page you are now viewing is a typical HTML web page. You can also find this list of vocabularies in the Semantic Web Machine-redable RDF format:</p>'.
        '<ul><li><a href="?_format=text/turtle">RDF version, turtle format</a></li></ul>'.
        '<p>These vocabularies are all published for general discovery in the <a href="http://www.ands.org.au/">Australian National Data Service (ANDS)</a>\'s <a href="http://vocabs.ands.org.au/">Research Vocabularies Australia (RVA) Portal</a>:</p>' .
        '<ul><li><a href="https://vocabs.ands.org.au/search/#!/?p=1&publisher=CGI%20Geoscience%20Terminology%20Working%20Group">CGI\'s vocabs in the RVA portal</a></li></ul>' .
        '<h2>Vocabularies</h2>';

    return $html . make_vocabs_list_html($vocabs);
}

function make_page_rdf($rdf_format) {
    $publisher = 'CGI Geoscience Terminology Working Group';
    $vocabs = get_all_vocabs($publisher);

    // the empty graph
    $graph = new EasyRdf_Graph();

    // special namespaces
    EasyRdf_Namespace::set('dpr', 'http://promsns.org/def/dpr#');
    EasyRdf_Namespace::set('gapd', 'http://pid.geoscience.gov.au/def/ont/gapd#');
    EasyRdf_Namespace::set('reg', 'http://purl.org/linked-data/registry#');

    // the vocab register as a resource
    $register = $graph->resource('http://resource.geosciml.org/def/voc/', 'reg:Register');
    $register->add('rdfs:label', 'CGI\'s Vocabulary Register');
    $register->add('reg:containedItemClass', 'skos:ConceptScheme');

    // CGI as a resource
    $cgi = $graph->resource('<http://pid.geoscience.org.au/org/cgi>', 'org:Organisation');
    $cgi->add('rdfs:label', 'Commission for Geoscience Information');
    $cgi->add('rdfs:comment', 'The Commission for the Management and Application of Geoscience Information (CGI) is a working subcommittee of the International Union of Geological Sciences.');
    $cgi->add('dpr:isLDF', "false");
    $cgi->add('reg:subregister', $register);

    // all the vocabs as resources
    $vocabs_graph = make_vocabs_list_rdf($vocabs, $register, $graph);

    return $vocabs_graph->serialise($rdf_format);
}

/*
 * Make the response: RDF or HTML
 */
$mimetypes = array(
    'text/turtle',
    'text/n3',
    'application/n-triples',
    'application/rdf+xml',
    'application/rdf+json',
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

// cater for QSA _format directive
if (isset($_GET['_format'])) {
    if (in_array($_GET['_format'], $mimetypes)) {
        $mimetype = $_GET['_format'];
    }
}

// set the MIME type
header('Content-Type: ' . $mimetype);

// decide whether it's RDF or HTML
switch ($mimetype) {
    // RDF
    case 'text/turtle':
        print make_page_rdf('turtle');
        break;
    case 'text/n3':
    case 'application/n-triples':
        print make_page_rdf('turtle');
        break;
    case 'application/rdf+xml':
        print make_page_rdf('rdfxml');
        break;
    case 'application/rdf+json':
        print make_page_rdf('jsonld');
        break;
    // HTML
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