<?php
class ProjectPage extends PmBasePage {
	public static $db = array(
	);
	public static $has_one = array(
	);

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// default pages
		if($this->class == 'ProjectPage') {
			if(!SiteTree::get_by_link('projects')) {
				$page = new ProjectPage();
				$page->Title = "Projects";
				$page->Content = "";
				$page->URLSegment = 'projects';
				$page->Status = 'Published';
				$page->Sort = 2;
				$page->write();
				$page->publish('Stage', 'Live');
				$page->flushCache();
				DB::alteration_message('Project page created', 'created');
			}
		}
	}	
	
	

}
class ProjectPage_Controller extends PmBasePage_Controller {

	public function init() {
		parent::init();
	}
	
	function Projects(){
		$projects = PmBase::get("Project");
		return $projects;
	}
	
	//if urlParam ID is supplied
	//editing or listing of a project is expected
	//else an empty project object is created
	function getOrCreateProject(){
		$id = (int) Director::urlParam("ID");
		if ($id > 0) {
			$project = DataObject::get_by_id("Project", $id);
			if (!$project) {
				return false;
			}
		}  else {
			$project = new Project();
		}
		return $project;
	}
	
	function ProjectTableActive(){
		return $this->ProjectTable("Active");
	}
	
	function ProjectTable($statusType = NULL){
		$projectField = PmBase::project_table_field(NULL,$statusType);	
		
		$fields = new FieldSet();
		$fields->push($projectField);
		
		//these are required for date popups
		//taken from DateField_View_JQuery::onAfterRender
		Requirements::javascript(THIRDPARTY_DIR . "/jquery-metadata/jquery.metadata.js");
		Requirements::javascript(SAPPHIRE_DIR . "/javascript/DateField.js");		
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'ProjectTable' . $statusType, $fields, $actions);
		return $form;
	}

	
	function Form(){
		$project = $this->getOrCreateProject();		
		$fields = $project->getFrontendFields();
		if ($project->ID > 0) {
			$hiddenID = new HiddenField("ID");
			$hiddenID->setValue($project->ID);
			$fields->push($hiddenID);
		} else {
			//field removing is postponed for now
			//FIXME
			/*
			//remove status when adding new projects
			//checking up on title, becaus thats always a part of a posted form
			if (isset($_POST["Title"])) {
				//this is a post
			} else {
				$status = new ReadonlyField("Status");
				$status->setValue("Active");
				$fields->push($status);
				//$fields->removeByName("Status");
			}
			*/
		}
		
		$actions = new FieldSet();
		$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'Form', $fields, $actions);

		if ($project->ID > 0) {
			$form->loadDataFrom($project);
		}		
		
		
		return $form;
		
	}
	function dosave($data, $form){
		if (isset($data["ID"])) {
			$id = (int) $data["ID"];
			$project = DataObject::get_by_id("Project", $id);
		} else {
			$project = new Project();
		}
		$form->saveInto($project);
		$project->write();
		
		$link = $this->Link() . "details/" . $project->ID;
		
		if($this->isAjax) {
			echo "saved";
		} else {
			Director::redirect($link);
		}  			
		
		
		/*
		if (isset($data["ID"])) {
			Director::redirect($this->Link());			
		} else {
			Director::redirectBack();
		}
		*/
	}
	
	//details
	function Project(){
		$project = $this->getOrCreateProject();
		return $project;
	}
	
	
	
	//urls
	
	function details() {
    //these are required for date popups
    //taken from DateField_View_JQuery::onAfterRender
    Requirements::javascript(THIRDPARTY_DIR . "/jquery-metadata/jquery.metadata.js");
    Requirements::javascript(SAPPHIRE_DIR . "/javascript/DateField.js");

    Requirements::javascript("projectmanagement/javascript/ProjectPage_details.js");
	  return $this;
  }
	
	
	
	
	function edit(){
		//iframe solution
		/*
		if (isset($_GET["iframe"])) {
			return $this->renderWith(array("ProjectPage_edit","PrintPage"));
		} else {
			return $this->renderWith(array("ProjectPage_edit","Page"));
		}
		*/
		
		//ajax solution (currently disabled)
		if($this->isAjax) {
			return $this->renderWith(array("ProjectPage_edit"));
		} else {
			return $this->renderWith(array("ProjectPage_edit","Page"));
		}  		


		
	}
	
	
	
}