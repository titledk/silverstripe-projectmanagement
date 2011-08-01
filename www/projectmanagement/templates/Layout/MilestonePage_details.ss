<div class="typography">
	<a href="$ProjectPageLink">Projects</a>
	&raquo;
	
	<% control Milestone %>
		<% control Project %>
			<a href="$Link">$Title</a>
		<% end_control %>
		&raquo; Milestone $Title
		
		<a href="{$Top.Link}edit/$ID">(edit)</a>
			
		<h2>Milestone $Title</h2>
		<p>
			<% if DueDate %>
				<em>Due $DueDate.Long</em>
				<br />
			<% end_if %>

			$ShortDecriptionFormatted
		</p>
		<% if Completed %>
		  <em>Completed</em>
		<% end_if %>
		
		
		<h3>Tasks</h3>
		<!--
		$ ProjectTaskTable				
		-->
		
		<div id="ProjectPage_details_ss" class="typography">
      <div id="Milestone_$ID" class="milestone">
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
                <br />
                <br />
                Priority: $Priority <br /> 
                Status: $Status
              </td>
              <td class="WorkLog" id="WorkLog_$ID">
                <% include WorkLogItem %>
              </td>
              <td class="Actions">
                <a href="{$Top.ProjectTaskPageLink}details/$ID">
                  <img alt="Task details" src="cms/images/show.png">
                </a>
                <a href="{$Top.ProjectTaskPageLink}edit/$ID">
                  <img alt="Edit this task" src="cms/images/edit.gif">
                </a>                
              </td>           
            </tr>
          <% end_control %>
          </tbody>
          <tfoot>
            <tr>
              <td>
                &nbsp;
              </td>
              <td class="Title">
                <strong>Total (Est.)</strong>               
              </td>
              <td class="Description" valign="top">
              </td>
            
              <td class="Estimate">
                <strong>$TotalHoursEstimated</strong>
              </td>
            </tr>
            
          </tfoot>
          
        </table>
      </div>  		
  		
  		
		</div>
		
		
		
		<h3>New Task/Bug/Requirement</h3>
		$TaskAddForm		
		
	<% end_control %>


</div>