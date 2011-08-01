var $dialog = "";

(function($) {
	$(function () {
		
		$('a.toggle').click(function(){
			var box = $(this).parent().parent().find(".togglebox");
			//console.log(box);
			box.toggle();
			return false;
		});			
		
		
		$('textarea:not(.processed)').TextAreaResizer();
		//$('textarea').TextAreaResizer();

		
		/*
		$('.field.date input.text').live('click', function() {
			console.log("test 1");
			$(this).datepicker('show');
			console.log("test 2");
			return;
			
			var holder = $(this).parents('.field.date:first'), config = holder.metadata();
			if(!config.showcalendar) return;
			
			if(config.locale && $.datepicker.regional[config.locale]) {
				config = $.extend(config, $.datepicker.regional[config.locale], {});
			}
			
			// Initialize and open a datepicker 
			// live() doesn't have "onmatch", and jQuery.entwine is a bit too heavyweight for this, so we need to do this onclick.
			$(this).datepicker(config);
			$(this).datepicker('show');
		});
		*/
		
		
		
		$dialog = $('<div></div>')
		.html('')
		.dialog({
			autoOpen: false,
			title: 'Dialog',
			modal: true,
			resizable: true,
			width: 560,
			height: 600
		});
		
		function SetDialogStandardButtons() {
			$dialog.dialog( "option", "buttons", { 
				"Save": function() { 
					var form = $(".ui-dialog-content form");
					var url = form.attr("action");
					var formdata = form.serialize();
					
					$(this).dialog("close");
					$("#Form_ProjectTableActive fieldset").html('<div style="text-align:center;padding:30px;"><img src="/libs/images/ajax-loader-big.gif" /></div>');

					$.post(url, formdata, function(data){
						$.get($Link + "ProjectTableActive/field/ProjectReport", function(data){
							$("#Form_ProjectTableActive fieldset").html(data);
						});
					});					
				
				},

				"Save & View": function() { 
					var form = $(".ui-dialog-content form");
					var url = form.attr("action");
					var formdata = form.serialize();
					
					$(this).dialog("close");
					$("#Form_ProjectTableActive fieldset").html('<div style="text-align:center;padding:30px;"><img src="/libs/images/ajax-loader-big.gif" /></div>');
	
					$.post(url, formdata, function(data){
						location.href = $Link + "details/" +  form.find("#Form_Form_ID").val();
						
						
					});					
			
				},				
				
				
				
				"View": function() { 
					var form = $(".ui-dialog-content form");
					location.href = $Link + "details/" +  form.find("#Form_Form_ID").val();
				},				
				
				"Cancel": function() { 
					$(this).dialog("close"); 
				}
			} );		
		}
		
		//ProjectPage TEST CASE
		$('.ProjectPage .TableListField td.Edit a').live("click",function() {
			var href = $(this).attr("href");

			/*
			//iframe solution is temporary
			var iframe = $('<iframe name="invoice_frame" src="' + href + '?iframe=1"></iframe>');
			iframe.css("width",930);
			iframe.css("height",780);

			//$dialog.dialog( "option", "title", "Order No " + orderNo );				
			$dialog.dialog( "option", "width", 960 );	
			$dialog.dialog( "option", "height", 700);	
			$dialog.html(iframe);
			SetDialogStandardButtons();
			$dialog.dialog('open');	
			*/
			
			
			//get method is not yet implemented
			
			$.get(href,function(data){
				var html = data;
				var title = $(html).find("h2").text();
				$dialog.dialog( "option", "title", title );
				
				var html2 = $(html).clone();
				
				$(html2).find("h2").remove();
				$(html2).find(".Actions").remove();
				
				$dialog.html(html2);
				SetDialogStandardButtons();
				$dialog.dialog('open');
				$dialog.find('textarea:not(.processed)').TextAreaResizer();
			})
			
			
			
			
			// prevent the default action, e.g., following a link
			return false;
		});			
		
		
		
		
		
	});
})(jQuery);