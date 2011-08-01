(function($) {
	$(function () {

    $("select#ProjectID").change(function(){
      var projectID = $(this).val();
      location.href = $Link + "project/" + projectID;
    });
		
		
	});
})(jQuery);