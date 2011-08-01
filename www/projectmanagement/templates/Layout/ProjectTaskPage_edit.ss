<div class="typography">
	<% if ProjectTask %>

    <% if RenderedViaAjax %>
    <% else %>

      <a href="$ProjectPageLink">Projects</a>
      &raquo;
      <% control ProjectTask %>
        $BreadCrumb
        &raquo;
        $Title  
        <a href="{$Top.Link}details/$ID">(view)</a>
      <% end_control %>


    <% end_if %>


			
		<h2>Edit Task "$ProjectTask.Title"</h2>
		$Form
	<% else %>
		<p>
			<em>Error, task not recognized.</em>
		</p>
	<% end_if %>

</div>