<div class="typography">
	<% if Milestone %>
		<a href="{$Top.Link}details/$Milestone.ID">[Back to Milestone]</a>	
		<h2>Edit Milestone "$Milestone.Title"</h2>
		$Form
	<% else %>
		<p>
			<em>Error, milestone not recognized.</em>
		</p>
	<% end_if %>

</div>