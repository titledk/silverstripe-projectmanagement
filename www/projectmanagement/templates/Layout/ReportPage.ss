<div class="typography">
	
	<h2>Reports</h2>
	
	<% if Projects %>
		<ul>
			<% control Projects %>
				<li>
					$Title <a href="{$Top.Link}estimate/$ID?print=1" target="_blank">[Estimate Report]</a>
				</li>
			<% end_control %>
		</ul>


	<% else %>
		<p>
			<em>No projects have been created yet.</em>	
		</p>
	<% end_if %>




</div>