<!DOCTYPE html>
<html lang="en">
<head>
	<% base_tag %>
	<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
	$MetaTags(false)
	<link rel="shortcut icon" href="/favicon.ico" />
	
	<% require themedCSS(layout) %> 
	<% require themedCSS(typography) %> 
	<% require themedCSS(form) %> 

	<style>
		#Layout {
			background:#fff;
			padding:30px;
			overflow:visible!important;
			width:760px!important;
		}		
	</style>

</head>
<body>
	<div id="Layout">
	  $Layout
	</div>
</body>
</html>