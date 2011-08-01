<?php
class PmBasePage extends Page {

	public static $db = array(
	);
	public static $has_one = array(
	);
	
	
}
class PmBasePage_Controller extends Page_Controller {

	function DevMode(){
		if (isset($_GET["devmode"])) {
			return true;			
		} else {
			return false;
		}
	}
	
	public function init() {
		parent::init();
		if (!Permission::check("ADMIN")) {
			Security::permissionFailure ($this, "Please log in to enter " . $this->SiteConfig()->Title . ".");
		}
		if(Director::is_ajax() || $_GET["ajaxDebug"]) {
		   $this->isAjax = true;
		} else {
			$this->isAjax = false;
			Requirements::css("libs/thirdparty/jqueryui/css/custom-theme/jquery-ui-1.8.2.custom.css");		

			Requirements::customScript('
				var $Link = "' . $this->Link() . '";
			');			
			
			Requirements::css("projectmanagement/css/styles.css");
			Requirements::javascript("sapphire/thirdparty/jquery/jquery.js");
			Requirements::javascript("libs/thirdparty/jqueryui/js/jquery-ui-1.8.2.custom.min.js");
			Requirements::javascript("libs/thirdparty/jquery.textarearesizer/jquery.textarearesizer.compressed.js");
			Requirements::javascript("projectmanagement/javascript/PmBasePage.js");
			Requirements::javascript("projectmanagement/javascript/" . $this->ClassName . ".js");


		
		} 		
	}
	
	function RenderedViaAjax(){
		return $this->isAjax;
	}
	

	function ProjectPageLink(){
		if ($page = DataObject::get_one("ProjectPage")) {
			return $page->Link();
		}
	}

	function MilestonePageLink(){
		if ($page = DataObject::get_one("MilestonePage")) {
			return $page->Link();
		}
	}		
	
	function ProjectTaskPageLink(){
		if ($page = DataObject::get_one("ProjectTaskPage")) {
			return $page->Link();
		}
	}		
	
}