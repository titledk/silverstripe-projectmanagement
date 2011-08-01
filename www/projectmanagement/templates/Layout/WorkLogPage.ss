<div class="typography">
	
	<h2>$Title</h2>
	
	$ProjectField
	<br />
  <% if taskFilter %>
    <em>Task filter <strong>ON</strong>.</em>
    <br />
    <% control FilteredTask %>
      <% control Milestone %>
       <a href="{$Top.Link}project/$ProjectID">[back to $Project.Title]</a>
       <br />
       <a href="/projects/details/$ProjectID#Milestone_$ID">[View Project]</a>
       <br />
      <% end_control %>
    <% end_control %>
  <% end_if %>
  <% if milestoneFilter %>
    <em>Milestone filter <strong>ON</strong>.</em>
    <br />
    <% control FilteredMilestone %>
       <a href="{$Top.Link}project/$ProjectID">[back to $Project.Title]</a>
       <br />
       <a href="/projects/details/$ProjectID#Milestone_$ID">[View Project]</a>
       <br />
    <% end_control %>
  <% end_if %>


	<% if projectFilter %>
	<a href="/projects/details/$projectFilter">[View Project]</a>
	<br />
	<% end_if %>
	<br />
	
	<% if WorkLogs %>
		$WorkLogTable
	<% else %>
		<p>
			<em>No work logs have been created yet.</em>	
		</p>
	<% end_if %>

</div>