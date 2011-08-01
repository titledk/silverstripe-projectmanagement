<% if PercentComplete  %>
Completion: {$PercentComplete} 
({$HoursWorked}/{$HoursEstimatedAndWorked})


<br /> 
<% else %>
<% end_if %>


<% if HoursWorked %>
  Est. Remaining: $EstimatedHoursRemaining <br />
<% else %>
  Est.: $OriginalHourEstimate <a class="edit" href="/tasks/worklog/$ID">[+Log]</a><br />
<% end_if %>


<% control WorkLogs %>
<a class="edit" href="/worklogs/edit/$ID">$Date: $HoursSpent</a> <br />
<% end_control %>
<% if HoursWorked %>
Total: $HoursWorked <a class="edit" href="/tasks/worklog/$ID">[+Log]</a>
<% end_if %>
