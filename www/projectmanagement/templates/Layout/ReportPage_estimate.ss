<style>

	.typography h3 {
		margin-top:30px;
	}

	table.Tasks tbody {
		
		
	}
	table.Tasks td,
	table.Tasks th {
		padding:8px;
	}
	
	table.Tasks thead tr {
		color:#7F7F7F;
		background:#DFDFDF;
	}
	table.Tasks tr,
	table.Tasks td {
		border:1px solid #000;
	}
	table.Tasks td.Title {
		width:300px;
	}	
	table.Tasks td.Description {
		width:300px;
	}	
	table.Tasks td.Estimate {
		text-align:right;
	}	

	a.discrete{
		color:#666666;
	}
	h3 a.discrete {
		color:#000;
	}

	
</style>

<div class="typography">
	<% if PrintMode %>
	<% else %>
	<a href="$URLSegment">[back]</a>
	<% end_if %>
	<% if Project %>
		<% control Project %>
			<h2>Estimate for $Title</h2>
			<p>
				$ShortDecriptionFormatted
			</p>
			<strong>
				TOTAL HOURS ESTIMATED: $getOriginalHoursEstimated
			</strong>
			
			
			<% control Milestones %>
				<% if Top.ShowLinks %>
					<h3><a class="discrete" href="{$Top.MilestonePageLink}details/$ID">Milestone $Title</a></h3>
				<% else %>
					<h3>Milestone $Title</h3>
				<% end_if %>
				<p>
					$ShortDecriptionFormatted
				</p>				
				<table class="Tasks">
					<thead>
						<tr>
							<th class="Title">
								Task
							</th>
							<th class="Description">
								Description
							</th>
							<th class="Estimate">
								Hours Estimated
							</th>
						</tr>
					</thead>
					<tbody>				
					<% control Tasks %>
						<tr>
							<td class="Title">
								<% if Top.ShowLinks %>
									<a href="{$Top.ProjectTaskPageLink}edit/$ID" class="discrete">$Title</a>
								<% else %>
									$Title
								<% end_if %>								
							</td>
							<td class="Description" valign="top">
								$ShortDecriptionFormatted
							</td>
							<td class="Estimate">
								$OriginalHourEstimate
							</td>
						</tr>
					<% end_control %>
					</tbody>
					<tfoot>
						<tr>
							<td class="Title">
								<strong>Total</strong>								
							</td>
							<td class="Description" valign="top">
							</td>
							<td class="Estimate">
								<strong>$TotalHoursEstimated</strong>
							</td>
						</tr>
						
					</tfoot>
					
				</table>

			<% end_control %>
				
		
		<% end_control %>
	<% end_if %>




</div>