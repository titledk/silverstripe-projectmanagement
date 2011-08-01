<div class="typography">
	<% if ProjectTask %>
		<h2>Work log for "$ProjectTask.Title"</h2>
		<!--
		$ Form
		-->
		$WorkLogForm
		
	<% else %>
		<p>
			<em>Error, task not recognized.</em>
		</p>
	<% end_if %>

</div>