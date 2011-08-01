<div class="typography">
	<% if Project %>
		<% if RenderedViaAjax %>
		<% else %>
		<a href="{$Top.Link}details/$Project.ID">[Back to Project]</a>
		<% end_if %>
		<h2>Edit Project "$Project.Title"</h2>
		$Form
	<% else %>
		<p>
			<em>Error, project not recognized.</em>
		</p>
	<% end_if %>

</div>