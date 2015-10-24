<?php
    $pdf = Zend_Pdf::load('c:/xampp/htdocs/stellusv1/uploads/Factura 2633.pdf');
    $metadata = $pdf->getMetadata();
    $metadataDOM = new DOMDocument();
    $metadataDOM->loadXML($metadata);
     
    $xpath = new DOMXPath($metadataDOM);
    $pdfPreffixNamespaceURI = $xpath->query('/rdf:RDF/rdf:Description')
                                    ->item(0)
                                    ->lookupNamespaceURI('pdf');
    $xpath->registerNamespace('pdf', $pdfPreffixNamespaceURI);
     
    $titleNode = $xpath->query('/rdf:RDF/rdf:Description/pdf:Title')->item(0);
    $title = $titleNode->nodeValue;
