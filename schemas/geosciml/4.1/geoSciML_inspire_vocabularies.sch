<?xml version="1.0" encoding="UTF-8"?>
<!-- Schematron to check that INSPIRE code list values are being used for the
 GeoSciML properties to which they apply.
 This Schematron cannot currently be used with a processor in Schema aware
 mode as some of the INSPIRE code list documents accessed are not Schema
 valid and so stop the processing. 2015-05-21.  -->
<schema xmlns="http://purl.oclc.org/dsdl/schematron" queryBinding="xslt2">
 
 <ns prefix="xlink" uri="http://www.w3.org/1999/xlink"/>
 <ns prefix="xsi" uri="http://www.w3.org/2001/XMLSchema-instance" />
 <ns prefix="gsmlb" uri="http://www.opengis.net/gsml/4.1/GeoSciML-Basic"/>
 <ns prefix="gsmlbh" uri="http://www.opengis.net/gsml/4.1/Borehole"/>
 <ns prefix="gml" uri="http://www.opengis.net/gml/3.2"/>
 <ns prefix="swe" uri="http://www.opengis.net/swe/2.0"/>
 <ns prefix="cl" uri="http://inspire.ec.europa.eu/codelist_register/codelist"/>
 
 <!-- Nils -->
 <let name="nilVocabulary" value="document('http://inspire.ec.europa.eu/codelist/VoidReasonValue/VoidReasonValue.en.xml')"/>
 <pattern id="nilReason">
  <title>Check nilReason is from INSPIRE VoidReasonValue code list</title>
  <rule context="@nilReason">
   <assert test="$nilVocabulary//cl:containeditems/cl:value/@id[. = current()]">
    nilReason value <value-of select="."/> should come from the list
    <value-of select="$nilVocabulary//cl:containeditems/cl:value/@id"/>
   </assert>
  </rule>
 </pattern>
 
 <!-- General pattern for testing simple by reference properties against list of URIs in vocabulary -->
 <pattern abstract="true" id="by-ref.property.vocabulary">
  <title>Abstract pattern for testing that by reference property href's come from a given vocabulary.</title>
  <p>Test that the specified property elements xlink:href attributes come from
   the specified vocabulary.</p>
  <rule context="$property">
   <assert test="$vocabulary//cl:containeditems/cl:value/@id[ . = current()/@xlink:href]">
    Property value <value-of select="@xlink:href"/> should come from the list
    <value-of select="$vocabulary//cl:containeditems/cl:value/@id"/>
   </assert>
  </rule>
 </pattern>

 <!-- General pattern testing properties specified by inline swe:Category
  against list of URIs in vocabulary -->
 <pattern abstract="true" id="swe_Category">
  <rule context="$category_path">
   <assert test="$vocabulary//cl:containeditems/cl:value/@id[ . = current()/swe:identifier]">
    The category identifier <value-of select="swe:identifier"/> should come from
    the list <value-of select="$vocabulary//cl:containeditems/cl:value/@id"/>
   </assert>
   <assert test="swe:label">label should not be empty</assert>
   <assert test="swe:codeSpace/@xlink:href = $vocab_uri"> The codeSpace
     <value-of select="swe:codeSpace/@xlink:href"/> should be "<value-of select="$vocab_uri"/>".</assert>
  </rule>
 </pattern>

 <pattern id="inspire.vocabulary.MappingFrameValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:MappedFeature/gsmlb:mappingFrame[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/MappingFrameValue/MappingFrameValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.GeochronologicEraValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:GeologicEvent/gsmlb:olderNamedAge[not(@nilReason)] | //gsmlb:GeologicEvent/gsmlb:youngerNamedAge[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/GeochronologicEraValue/GeochronologicEraValue.en.xml')"
  />
 </pattern>
  
 <pattern id="inspire.vocabulary.EventProcessValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:GeologicEvent/gsmlb:eventProcess[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/EventProcessValue/EventProcessValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.EventEnvironmentValue" is-a="swe_Category">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="category_path" value="//gsmlb:GeologicEvent/gsmlb:eventEnvironment/swe:Category"/>
  <param name="vocabulary" value="document('http://inspire.ec.europa.eu/codelist/EventEnvironmentValue/EventEnvironmentValue.en.xml')"/>
  <param name="vocab_uri" value="'http://inspire.ec.europa.eu/codelist/EventEnvironmentValue'"/>
 </pattern>
 
 <pattern id="inspire.vocabulary.GeologicUnitTypeValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:GeologicUnit/gsmlb:geologicUnitType[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/GeologicUnitTypeValue/GeologicUnitTypeValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.LithologyValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:RockMaterial/gsmlb:lithology[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/LithologyValue/LithologyValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.CompositionPartRoleValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:CompositionPart/gsmlb:role[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/CompositionPartRoleValue/CompositionPartRoleValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.FaultTypeValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:ShearDisplacementStructure/gsmlb:faultType[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/FaultTypeValue/FaultTypeValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.FoldProfileTypeValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:Fold/gsmlb:profileType[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/FoldProfileTypeValue/FoldProfileTypeValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.NaturalGeomorphologicFeatureTypeValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:NaturalGeomorphologicFeature/gsmlb:naturalGeomorphologicFeatureType[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/NaturalGeomorphologicFeatureTypeValue/NaturalGeomorphologicFeatureTypeValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.GeomorphologicActivityValue" is-a="swe_Category">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="category_path" value="//gsmlb:NaturalGeomorphologicFeature/gsmlb:activity/swe:Category"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/GeomorphologicActivityValue/GeomorphologicActivityValue.en.xml')"
  />
  <param name="vocab_uri" value="'http://inspire.ec.europa.eu/codelist/GeomorphologicActivityValue/GeomorphologicActivityValue'"/> </pattern>
 
 <pattern id="inspire.vocabulary.AnthropogenicGeomorphologicFeatureTypeValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlb:AnthopogenicGeomorphologicFeature/gsmlb:anthropogenicGeomorphologicFeatureType[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/AnthropogenicGeomorphologicFeatureTypeValue/AnthropogenicGeomorphologicFeatureTypeValue.en.xml')"
  />
 </pattern>
 
 <pattern id="inspire.vocabulary.BoreholePurposeValue" is-a="by-ref.property.vocabulary">
  <title>INSPIRE Vocabulary</title>
  <p>Check that property uses values from the appropriate INSPIRE vocabulary.</p>
  <param name="property"
   value="//gsmlbh:BoreholeDetails/gsmlbh:purpose[not(@nilReason)]"/>
  <param name="vocabulary"
   value="document('http://inspire.ec.europa.eu/codelist/BoreholePurposeValue/BoreholePurposeValue.en.xml')"
  />
 </pattern>
 
</schema>
