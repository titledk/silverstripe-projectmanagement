<div class="typography">
	
	<h2>Active Projects</h2>
	
	<% if Projects %>
		$ProjectTableActive
		
		<a href="{$Link}new">[Create new project]</a>
		<a href="{$Link}completed">[Completed projects]</a>
		<a href="{$Link}standby">[Projects on standby]</a>
		<a href="{$Link}all">[All projects]</a>		
		<br />
		<br />
		
	<% else %>
		<p>
			<em>No projects have been created yet.</em>	
		</p>
	<% end_if %>

	<!--
	//TODO make AJAX version of this
	<h2>Create New Project</h2>

	$ Form
	-->


</div>