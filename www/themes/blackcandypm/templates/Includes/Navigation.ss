<% if CurrentMember %>
<ul>
 	<% control Menu(1) %>	  
  		<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span>$MenuTitle.XML</span></a></li>
   	<% end_control %>
</ul>
<% end_if %>