# GeologicTimeScale
Initialise based on https://www.seegrid.csiro.au/subversion/xmml/ontologies/tags/final-GTS/Geoscience/GeologicTimeScale/ 
as described in Cox & Richard 2015 "A geologic timescale ontology and service" http://link.springer.com/article/10.1007/s12145-014-0170-6 

Now disentangled from ISO 19100 so the ontology file thors.ttl has no dependencies on any non-standard RDF graphs, and gts.ttl only imports the clean thors.ttl 

Alignments with the ISO 19100 models are now provided through 

thors/iso.ttl
gts/iso.ttl 

and with W3C OWL-Time and W3C SOSA/SSN through 

thors/w3c.ttl
gts/w3c.ttl 


