<div class="typography">
	
	<h2>Completed Projects</h2>
	
	<% if Projects %>
		$ProjectTable(Completed)
		
		<a href="{$Link}all">View all projects</a>
	<% else %>
		<p>
			<em>No projects have been created yet.</em>	
		</p>
	<% end_if %>


</div>