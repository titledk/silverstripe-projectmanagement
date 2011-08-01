<?php
class ProjectTaskPage extends PmBasePage {
	public static $db = array(
	);
	public static $has_one = array(
	);

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// default pages
		if($this->class == 'ProjectTaskPage') {
			if(!SiteTree::get_by_link('tasks')) {
				$page = new ProjectTaskPage();
				$page->Title = "Tasks";
				$page->Content = "";
				$page->URLSegment = 'tasks';
				$page->Status = 'Published';
				$page->Sort = 4;
				$page->write();
				$page->publish('Stage', 'Live');
				$page->flushCache();
				DB::alteration_message('Task page created', 'created');
			}
		}
	}	
	
	

}
class ProjectTaskPage_Controller extends PmBasePage_Controller {

	public function init() {
		parent::init();
	}
	
	function ProjectTasks(){
		$projects = PmBase::get("ProjectTask");
		return $projects;
	}
	
	//if urlParam ID is supplied
	//editing or listing of a milestone is expected
	//else an empty milestone object is created
	function getOrCreateProjectTask(){
		$id = (int) Director::urlParam("ID");
		if ($id > 0) {
			$task = DataObject::get_by_id("ProjectTask", $id);
			if (!$task) {
				return false;
			}
		}  else {
			$task = new ProjectTask();
		}
		return $task;
	}
	

	function ProjectTaskTable(){
		$tableField = PmBase::task_table_field();		
		$fields = new FieldSet();
		$fields->push($tableField);;
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'ProjectTaskTable', $fields, $actions);
		return $form;
	}
	
	
	
	function Form(){
		$task = $this->getOrCreateProjectTask();		
		$fields = $task->getFrontendFields();
		if ($task->ID > 0) {
			$hiddenID = new HiddenField("ID");
			$hiddenID->setValue($task->ID);
			$fields->push($hiddenID);
			
			$devmode = $this->DevMode();
			
			if ($task->OriginalHourEstimate > 0 && (!$devmode)) {
				$readOnlyField = new ReadonlyField("OriginalHourEstimate", "Original Hour Estimate");
				$fields->push($readOnlyField);
			}
		}
		
		$actions = new FieldSet();
		$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'Form', $fields, $actions);
		$form->loadDataFrom($task);
		return $form;
		
	}
	function dosave($data, $form){
		if (isset($data["ID"])) {
			$id = (int) $data["ID"];
			$task = DataObject::get_by_id("ProjectTask", $id);
		} else {
			$task = new ProjectTask();
		}
		$form->saveInto($task);
		$task->write();

		//$link = $this->Link() . "details/" . $task->ID;
    $link = "/milestones/details/" . $task->Milestone()->ID;
    
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
	function ProjectTask(){
		$task = $this->getOrCreateProjectTask();
		return $task;
	}
	
  
  //urls
  
  function worklog(){
    if($this->isAjax) {
      return $this->renderWith(array("ProjectTaskPage_worklog"));
    } else {
      //return $this->renderWith(array("ProjectTaskPage_worklog","Page"));
      return $this;
    }     
  }  

  function worklogitem(){
    $task = $this->getOrCreateProjectTask();
    return $task->renderWith(array("WorkLogItem"));
  }  

  
  function WorkLogForm(){
    $task = $this->getOrCreateProjectTask();    
    $log = new WorkLog();
    $fields = $log->getFrontendFields();
    if ($task->ID > 0) {
      $hiddenID = new HiddenField("TaskID");
      $hiddenID->setValue($task->ID);
      $fields->push($hiddenID);
    }
    $fields->push(new CheckboxField("MarkAsCompleted","Mark As Completed"));
    
    $actions = new FieldSet();
    $actions->push(new FormAction("dosaveworklog", "Save"));
    $form = new Form($this, 'WorkLogForm', $fields, $actions);
    //$form->loadDataFrom($log);
    return $form;
    
  }
  function dosaveworklog($data, $form){
    var_dump($data);
    
    $log = new WorkLog();
    $form->saveInto($log);
    $log->TaskID = $data["TaskID"];
    $log->write();
    
    if (isset($data["MarkAsCompleted"])) {
      $task = $log->Task();
      $task->Status = "Completed";
      $task->write();
    }    
    

    //$link = $this->Link() . "details/" . $task->ID;
    if($this->isAjax) {
      echo "saved";
    } else {
      //Director::redirect($link);
    }       

  }

  function edit(){

    if($this->isAjax) {
      return $this->renderWith(array("ProjectTaskPage_edit"));
    } else {
      return $this;
    }     


    
  }
  
	
	
	
	
}