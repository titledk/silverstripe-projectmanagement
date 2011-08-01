<?php
	include("html_to_doc.inc.php");
	
	$htmltodoc= new HTML_TO_DOC();
	
	$htmltodoc->createDoc("test.html","This is the title",true);
	
	
	//$htmltodoc->createDocFromURL("http://yahoo.com","test");
	

?>