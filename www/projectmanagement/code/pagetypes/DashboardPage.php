<?php
class DashboardPage extends PmBasePage {

	public static $db = array(
	);

	public static $has_one = array(
	);

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// default pages
		if($this->class == 'DashboardPage') {
			if(SiteTree::get_by_link('home')) {
				$homepage = SiteTree::get_by_link('home');
				if ($homepage->ClassName != "DashboardPage") {
					$homepage->ClassName = "DashboardPage";
					$homepage->Title = "Dashboard";
					$homepage->Content = "";
					$homepage->write();
					$homepage->publish('Stage', 'Live');
					$homepage->flushCache();
					DB::alteration_message('Home page altered to Dashboard page', 'created');
						
				}
			} else {
				$homepage = new DashboardPage();
				$homepage->Title = "Dashboard";
				$homepage->Content = "";
				$homepage->URLSegment = 'home';
				$homepage->Status = 'Published';
				$homepage->Sort = 1;
				$homepage->write();
				$homepage->publish('Stage', 'Live');
				$homepage->flushCache();
				DB::alteration_message('Home page created', 'created');
			}			
		}
		
		//Set/update SiteConfig
		$siteConfig = DataObject::get_one('SiteConfig');
		if(!$siteConfig) {
			$siteConfig = new SiteConfig();
		}
		
		$siteConfig->Title = "pm.title.dk";
		$siteConfig->Tagline = "Simple Project Management";

		$siteConfig->write();		
		
		
	}	
	
	
	
}
class DashboardPage_Controller extends PmBasePage_Controller {

	public function init() {
		parent::init();
		//temporary dashboard disabling
		Director::redirect("projects");
	}

	
	//Project
	
	function ProjectTable($statusType = NULL){
		$projectField = PmBase::project_table_field(NULL,$statusType);	
		
		
		$fields = new FieldSet();
		$fields->push($projectField);
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'ProjectTable', $fields, $actions);
		return $form;
	}
	
	
	//Project Task
	
	function ProjectTaskTable(){
		$tableField = PmBase::task_table_field();		
		$fields = new FieldSet();
		$fields->push($tableField);;
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'ProjectTaskTable', $fields, $actions);
		return $form;
	}
	
	
	
}