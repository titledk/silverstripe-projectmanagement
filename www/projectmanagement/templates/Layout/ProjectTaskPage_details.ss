<div class="typography">
	<a href="$ProjectPageLink">Projects</a>
	&raquo;
	<% control ProjectTask %>
		$BreadCrumb
		&raquo;
		
		<% if ParentTask %>
			<% control ParentTask %>
				<a href="$Link">$Title</a> &raquo;
			<% end_control %>
		<% end_if %>
		
		$Title	
		<a href="{$Top.Link}edit/$ID">(edit)</a>	
		<h2>$Title</h2>
		<p>
			$ShortDecriptionFormatted
		</p>	

		<table>
			<tr>
				<th>Due</th>
				<td>$DueDate.Long</td>
			</tr>
			<tr>
				<th>Type</th>
				<td>$Type</td>
			</tr>
			<tr>
				<th>Priority</th>
				<td>$Priority</td>
			</tr>
			<% if Type != "Requirement" %>
				<tr>
					<th>Original Hour Estimate</th>
					<td>$OriginalHourEstimate</td>
				</tr>
				<tr>
					<th>Estimated Hours Remaining</th>
					<td>$EstimatedHoursRemaining</td>
				</tr>
			<% end_if %>
			
			

		</table>
		
		<% if Type == "Requirement" %>
			<h3>Tasks</h3>
			$SubTaskTable
			
			<h3>Add Task</h3>
			$SubTaskAddForm
		<% end_if %>
		
	<% end_control %>


</div>