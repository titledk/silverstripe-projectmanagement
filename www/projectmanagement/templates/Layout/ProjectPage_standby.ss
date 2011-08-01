<div class="typography">
	
	<h2>Projects on Standby</h2>
	
	<% if Projects %>
		$ProjectTable(Standby)
		
		<a href="{$Link}all">View all projects</a>
	<% else %>
		<p>
			<em>No projects have been created yet.</em>	
		</p>
	<% end_if %>


</div>