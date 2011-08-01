<div class="typography">
	<a href="$Link">Projects</a>
	&raquo;
	
	<% control Project %>
		$Title <a href="{$Top.Link}details/$ID">(details)</a> <a href="{$Top.Link}edit/$ID">(edit)</a>	
		<h2>$Title</h2>
		<p>
			<% if DueDate %>
				<em>Due $DueDate.Long</em>
				<br />
			<% end_if %>

			$ShortDecriptionFormatted
		</p>
		
		<h3>Milestones</h3>
		$MilestoneTable
		
		<h3>Add Milestone</h3>
		$MilestoneAddForm
		
	<% end_control %>


</div>