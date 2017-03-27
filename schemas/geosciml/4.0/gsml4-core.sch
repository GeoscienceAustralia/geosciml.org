<?xml version="1.0" encoding="UTF-8"?>
<schema  see="http://www.opengis.net/spec/gsml/4.0/req/gsml4xsd/code-sch" xmlns="http://purl.oclc.org/dsdl/schematron" 
queryBinding="xslt2" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<!--
        This schematron schema checks the core XML encoding requirements of OGC GeoSciML 4.0, as specified
        in the requirements class: http://www.opengis.net/spec/gsml/4.0/req/gsml4xsd/code-sch
    -->
    <xsl:import-schema schema-location="http://schemas.opengis.net/sweCommon/2.0/simple_components.xsd" namespace="http://www.opengis.net/swe/2.0"/>
	<title>OGC GeoSciML 4.0 XML encoding tests<br/>
	<p> This schematron schema checks the core XML encoding requirements of OGC GeoSciML 4.0, as specified
        in the requirements class: http://www.opengis.net/spec/gsml/4.0/req/gsml4xsd/code-sch</p></title>
	<ns prefix="om" uri="http://www.opengis.net/om/2.0"/>
	<ns prefix="gml" uri="http://www.opengis.net/gml/3.2"/>
	<ns prefix="xlink" uri="http://www.w3.org/1999/xlink"/>
	<ns prefix="swe" uri="http://www.opengis.net/swe/2.0"/>
	
	
	<!-- the logic for sch:report is opposite to assert; you report if the statement is true -->
	<pattern id="xlink-title">
		<title>Test recommendation: /req/gsml4xsd/codelist</title>
		<rule context="*[@xlink:href]">
			<report test="not(starts-with(@xlink:href, '#') ) and not(@xlink:title)">If an xlink:href is used to reference a controlled vocabulary item, the element should encode the
        xlink:title attribute with a text description of the referenced item.If an xlink:href is used to reference a controlled vocabulary item, the
        element should encode the xlink:title attribute with a text description of the referenced item.</report>
		</rule>
	</pattern>
	
	<pattern id="unit-of-measure">
		<title>Test requirement: /req/gsml4xsd/unit-of-measure</title>
		<rule context="swe:Quantity">
			<assert test="swe:uom">Quantity must have a UOM</assert>
		</rule>
		<rule context="om:OM_Measurement/om:result">
			<assert test="@uom">OM_Measurement must have a UOM</assert>
		</rule>
	</pattern>
	

</schema>
