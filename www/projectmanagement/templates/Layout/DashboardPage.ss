<div class="typography">

	<h2>$Title</h2>

	<h3>Active Projects</h3>
	
	<% if ProjectTable(Active) %>
		$ProjectTable(Active)
	<% else %>
		<p>
			<em>No active projects.</em>	
		</p>
	<% end_if %>
	<a href="$ProjectPageLink">[Add a New Project]</a>
	<a href="{$ProjectPageLink}/completed">[Completed Projects]</a>
	<a href="{$ProjectPageLink}/all">[All Projects]</a>




</div>