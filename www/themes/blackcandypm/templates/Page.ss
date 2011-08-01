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
		
		<!--[if IE 6]>
			<style type="text/css">
			 @import url(themes/blackcandy/css/ie6.css);
			</style> 
		<![endif]-->
	</head>
<body class="$ClassName">
	<div id="BgContainer">
		<div id="Container">
			<div id="Header">
				$SearchForm
		   		<a href="/">
					<h1>$SiteConfig.Title</h1>
		   		</a>
				<div id="TopMenu">
					<% if CurrentMember %>
						<a href="http://intra.title.dk/doku.php">[intra.title.dk]</a>
						<a href="/dump/export">[Back up]</a>
						
						<% if ClassName == "SettingsPage" %>
							<strong>
								<a href="/settings/">[Settings]</a>						
							</strong>
						<% else %>
							<a href="/settings/">[Settings]</a>
						<% end_if %>
						
						<a href="/Security/logout">[Log out]</a>
					<% end_if %>					
				</div>
			</div>
		
			<div id="Navigation">
				<% include Navigation %>
		  	</div>
	  	
		  	<div class="clear"><!-- --></div>
		
			<div id="Layout">
			  $Layout
			</div>
		
		   <div class="clear"><!-- --></div>
		</div>
		<div id="Footer">
			<% include Footer %>
		</div> 
	</div>
</body>
</html>