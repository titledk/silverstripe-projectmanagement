(function($) {
	$(function () {


    $('a.milestonetoggle').click(function(){
      var id = $(this).attr('id').replace(/milestonetoggle_/gi, '');
      var box = $("#Milestone_" + id);
      //console.log(id);
      //console.log(box);
      box.toggle();
      return false;
    });   


		$('td.WorkLog a.edit').live("click",function() {
			var td = $(this).parent();
			var tr = td.parent();
			var td_id = td.attr('id').replace(/WorkLog_/gi, '');
			
			var href = $(this).attr("href");

    /*
      console.log(href);
      return false;
    */
			
			$.get(href,function(data){
				var html = data;
				var title = $(html).find("h2").text();
				$dialog.dialog( "option", "title", title );


        $dialog.dialog( "option", "height", 440); 
				
				var html2 = $(html).clone();
				
				$(html2).find("h2").remove();
				$(html2).find(".Actions").remove();
				
				$dialog.html(html2);
				//SetDialogStandardButtons();

        $dialog.dialog( "option", "buttons", { 
          "Save": function() { 
            var form = $(".ui-dialog-content form");
            var url = form.attr("action");
            var formdata = form.serialize();
            
            $(this).dialog("close");
            td.html('<div><img src="/libs/images/ajax-loader-big.gif" /></div>');
      
            $.post(url, formdata, function(data){
              $.get("/tasks/worklogitem/" + td_id, function(data){
                td.html(data);
                //task has been marked as completed
                if (($("input#Form_WorkLogForm_MarkAsCompleted:checked").length) > 0) {
                  tr.removeClass("New");
                  tr.addClass("Completed"); 
                }

              });
            });         
          
          },
          "Save & Update": function() { 
            var form = $(".ui-dialog-content form");
            var url = form.attr("action");
            var formdata = form.serialize();
            
            $(this).dialog("close");
            td.html('<div><img src="/libs/images/ajax-loader-big.gif" /></div>');
      
            $.post(url, formdata, function(data){
              location.reload();              
            });         
          
          },          

          "Cancel": function() { 
            $(this).dialog("close"); 
          }
        } );   


				
				$dialog.dialog('open');
				$dialog.find('textarea:not(.processed)').TextAreaResizer();
			})
			
			
			
			
			// prevent the default action, e.g., following a link
			return false;
		});			
		


    $('a.edittask').live("click",function() {
      var href = $(this).attr("href");
      
      $.get(href,function(data){
        var html = data;
        var title = $(html).find("h2").text();
        $dialog.dialog( "option", "title", title );


        $dialog.dialog( "option", "height", 680); 
        
        var html2 = $(html).clone();
        
        $(html2).find("h2").remove();
        $(html2).find(".Actions").remove();
        
        $dialog.html(html2);
        //SetDialogStandardButtons();

        $dialog.dialog( "option", "buttons", { 

          "Save": function() { 
            var form = $(".ui-dialog-content form");
            var url = form.attr("action");
            var formdata = form.serialize();
            
            $(this).dialog("close");
            //td.html('<div><img src="/libs/images/ajax-loader-big.gif" /></div>');
      
            $.post(url, formdata, function(data){
              location.reload();              
            });         
          
          },          

          "Cancel": function() { 
            $(this).dialog("close"); 
          }
        } );   


        
        $dialog.dialog('open');
        $dialog.find('textarea:not(.processed)').TextAreaResizer();
      })
      
      // prevent the default action, e.g., following a link
      return false;
    });     
    
		
		
		
		
	});
})(jQuery);