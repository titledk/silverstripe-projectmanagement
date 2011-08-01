<div id="ProjectPage_details_ss" class="typography">
	<a href="$Link">Projects</a>
	&raquo;
	
	
	
	<% control Project %>
		$Title <a href="{$Top.Link}edit/$ID">(edit)</a>	
		<h2>$Title</h2>
		<% if DueDate %>
			<em>Due $DueDate.Long</em>
			<br />
		<% end_if %>
		<a href="{$Top.Link}milestones/$ID">[View/Add Milestones] (OLD)</a>
		<br />
		<!--
		<a href="#">[Add Milestone] (TODO)</a>
		<br />
		-->
		<a href="/worklogs/project/$ID">[Work Log]</a>
		<br />
		<br />
		<p>


			$ShortDecriptionFormatted



		</p>
		<style>
		  table#overviewtable td,th {
		    font-weight:bold;
		  }
      table#overviewtable td {
        text-align:right;
      }
		</style>
		<table id="overviewtable">
		  <tr>
  			<th>ORIGINAL HOURS ESTIMATED:</th>
  			<td>$getOriginalHoursEstimated</td>
		  </tr>
      <tr>
        <th>HOURS WORKED:</th>
        <td>$getTotalHoursWorked</td>
      </tr>
      <tr>
        <th>ESTIMATED HOURS REMAINING:</th>
        <td>$getTotalHoursEstimated</td>
      </tr>
      <tr>
        <th>PROJECT COMPLETION:</th>
        <td>
          $PercentComplete
          ({$TotalHoursWorked}/{$TotalHoursEstimatedAndWorked})
        </td>
      </tr>
		</table>
		<br />
		<br />

		
		
		
		<% control Milestones %>
			<a name="Milestone_$ID"></a>
			<h3><a class="discrete" href="{$Top.MilestonePageLink}details/$ID">Milestone $Title</a></h3>
      <% if Completed %>
        <em>Completed (Estimated: $OriginalHoursEstimated, Worked: $TotalHoursWorked)</em>
        <br />
        <a class="milestonetoggle" id="milestonetoggle_$ID" href="#">[Toggle Completed Milestone]</a>
        <br />
      <% else %>
        <!--
        <a href="#">[Add Task] (TODO)</a>
        <br />
        -->
      <% end_if %>
      <p>
        $ShortDecriptionFormatted
      </p>  
      
      
      <div id="Milestone_$ID" class="milestone <% if Completed %>completed<% end_if %>">
        <a href="/worklogs/milestone/$ID">[Work Log]</a>
        <br />
        <br />
  			<table class="Tasks">
  				<thead>
  					<tr>
  						<th>
  							&nbsp;
  						</td>
  						<th class="Title">
  							Task
  						</th>
  						<th class="Description">
  							Description
  						</th>
  						<th class="Estimate">
  							Hours
  						</th>
  						<th>
  							&nbsp;
  						</th>
  					</tr>
  				</thead>
  				<tbody>				
  				<% control Tasks %>
  					<tr class="$TrCssClass $Status">
  						<td class="Indicator"></td>
  						<td class="Title">
  							$Title
  						</td>
  						<td class="Description" valign="top">
  							$ShortDecriptionFormatted
  							<div class="togglebox">
  								<br />
  								Priority: $Priority <br /> 
  								Status: $Status <br />
  								Orig Est.: $OriginalHourEstimate <br />
  							</div>
  						</td>
  						<td class="WorkLog" id="WorkLog_$ID">
  							<% include WorkLogItem %>
  						</td>
  						<td class="Actions">
  							<a href="#" class="toggle">
  								<img alt="Toggle details" src="cms/images/approvecomment.png">
  							</a>
  							<a href="{$Top.ProjectTaskPageLink}details/$ID">
  								<img alt="Task details" src="cms/images/show.png">
  							</a>
  							<a class="edittask" href="{$Top.ProjectTaskPageLink}edit/$ID">
  								<img alt="Edit this task" src="cms/images/edit.gif">
  							</a>								
  						</td>						
  					</tr>
  				<% end_control %>
  				</tbody>
  				<% if Completed %>
  				<% else %>
  				<tfoot>
  					<tr>
  						<td>
  							&nbsp;
  						</td>
  						<td class="Title">
  							<strong>Total</strong>								
  						</td>
  						<td class="Description" >
                   <% if TotalHoursWorked %>
                     <strong>
                     Completion: $PercentComplete
                     ({$TotalHoursWorked}/{$TotalHoursEstimatedAndWorked})
                     </strong>
                   <% end_if %>
  						</td>
  					
  						<td class="Estimate">
  							<% if TotalHoursWorked %>
    							<strong>
    							 Orig. Est. $OriginalHoursEstimated <br />
    							 Curr. Est. $TotalHoursEstimated <br />
    							 Worked: $TotalHoursWorked
    						  </strong>
                <% else %>
                  <strong>
                    Est. $OriginalHoursEstimated
                  </strong>
                <% end_if %>
  							
  						</td>
  					</tr>
  					
  				</tfoot>
  				<% end_if %>
  				
  			</table>
      </div>
		<% end_control %>
			



		
	<% end_control %>


</div>