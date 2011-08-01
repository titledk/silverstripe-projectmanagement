<?php
class MilestonePage extends PmBasePage {
	public static $db = array(
	);
	public static $has_one = array(
	);

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// default pages
		if($this->class == 'MilestonePage') {
			if(!SiteTree::get_by_link('milestones')) {
				$page = new MilestonePage();
				$page->Title = "Milestones";
				$page->Content = "";
				$page->URLSegment = 'milestones';
				$page->Status = 'Published';
				$page->Sort = 3;
				$page->write();
				$page->publish('Stage', 'Live');
				$page->flushCache();
				DB::alteration_message('Milestone page created', 'created');
			}
		}
	}	
	
	

}
class MilestonePage_Controller extends PmBasePage_Controller {

	public function init() {
		parent::init();
	}
	
	function Milestones(){
		$projects = PmBase::get("Milestone");
		return $projects;
	}
	
	//if urlParam ID is supplied
	//editing or listing of a milestone is expected
	//else an empty milestone object is created
	function getOrCreateMilestone(){
		$id = (int) Director::urlParam("ID");
		if ($id > 0) {
			$milestone = DataObject::get_by_id("Milestone", $id);
			if (!$milestone) {
				return false;
			}
		}  else {
			$milestone = new Milestone();
		}
		return $milestone;
	}
	

	function MilestoneTable(){
		$tableField = PmBase::milestone_table_field();		
		$fields = new FieldSet();
		$fields->push($tableField);;
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'MilestoneTable', $fields, $actions);
		return $form;
		
		
	}
	
	
	
	function Form(){
		$milestone = $this->getOrCreateMilestone();		
		$fields = $milestone->getFrontendFields();
		if ($milestone->ID > 0) {
			$hiddenID = new HiddenField("ID");
			$hiddenID->setValue($milestone->ID);
			$fields->push($hiddenID);
		}
		
		$actions = new FieldSet();
		$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'Form', $fields, $actions);
		$form->loadDataFrom($milestone);
		return $form;
		
	}
	function dosave($data, $form){
		if (isset($data["ID"])) {
			$id = (int) $data["ID"];
			$milestone = DataObject::get_by_id("Milestone", $id);
		} else {
			$milestone = new Milestone();
		}
		$form->saveInto($milestone);
		$milestone->write();

		$link = $this->Link() . "details/" . $milestone->ID;
		Director::redirect($link);		
		
		/*
		if (isset($data["ID"])) {
			Director::redirect($this->Link());			
		} else {
			Director::redirectBack();
		}
		*/
	}
	
	//details
	function Milestone(){  
		$milestone = $this->getOrCreateMilestone();
		return $milestone;
	}
	
	
	
	
	
}