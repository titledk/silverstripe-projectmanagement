<?php
class WorkLogPage extends PmBasePage {
	public static $db = array(
	);
	public static $has_one = array(
	);

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// default pages
		if($this->class == 'WorkLogPage') {
			if(!SiteTree::get_by_link('worklogs')) {
				$page = new WorkLogPage();
				$page->Title = "Work Logs";
				$page->Content = "";
				$page->URLSegment = 'worklogs';
				$page->Status = 'Published';
				$page->Sort = 7;
				$page->write();
				$page->publish('Stage', 'Live');
				$page->flushCache();
				DB::alteration_message('Work Log page created', 'created');
			}
		}
	}	
	
	

}
class WorkLogPage_Controller extends PmBasePage_Controller {

	public function init() {
		parent::init();
	}

  function WorkLogs(){
    $objs = PmBase::get("WorkLog");
    return $objs;
  }

  function ProjectField(){
    $projects = PmBase::get("Project", NULL, "Title");
    $array = array();
    $array[0] = "Select Project...";
    if ($projects) {
      foreach ($projects as $project) {
        $array[$project->ID] = $project->Title;
      }
    }
    $projectField = new DropdownField(
      'ProjectID',
      'Project',
      $array
    );
    
    if (isset($this->projectFilter)) {
      $projectField->setValue($this->projectFilter);
    }
        
    
    
    return $projectField;    
  }



	function WorkLogTable(){
		//$tableField = PmBase::milestone_table_field();		
		
    $resultSet = new DataObjectSet();
    
    $filter = "LoggedDataObject.IsLog = 'False'";
    if (isset($this->projectFilter)) {
      $filter .= " AND Milestone.ProjectID = " . $this->projectFilter;
    }
    if (isset($this->taskFilter)) {
      $filter .= " AND TaskID = " . $this->taskFilter;
    }
    if (isset($this->milestoneFilter)) {
      $filter .= " AND MilestoneID = " . $this->milestoneFilter;
    }        
    
    
    $sort = "Date DESC, Title ASC";
    $join = '
    INNER JOIN ProjectTask ON ProjectTask.ID = WorkLog.TaskID
    INNER JOIN Milestone ON Milestone.ID = ProjectTask.MilestoneID
    ';
    $instance = singleton('WorkLog');   
    $query = $instance->buildSQL($filter, $sort, null, $join);
    //$query->groupby[] = 'Member.ID';
   
    $field = new TableListField(
      'WorkLogReport', //name
      'WorkLog',
      array(
        //'ID' => 'ID',
        'Task.MilestoneTitle' => 'Milestone',                
        'Task.Title' => 'Task',
        'ShortDescription' => 'Description',
        'Date' => 'Date',
        'HoursSpent' => 'Hours Spent'//,
        //'EstimatedHoursRemaining' => 'Estimated Hours Remaining'
        //'Show' => '',
        //'Edit' => ''
      )
    );
   
    $field->setCustomQuery($query);
    
    $worklogPage = SiteTree::get_one("WorkLogPage");
    
    $field->setFieldFormatting(array(
      'Task.Title' => '<a href=\"' . $worklogPage->Link() . 'task/$TaskID\">$TaskTitle</a>', 
      'Task.MilestoneTitle' => '<a href=\"' . $worklogPage->Link() . 'milestone/$MilestoneID\">$MilestoneTitle</a>',    
    ));
    
    $field->setFieldCasting(array(
      'Date' => 'Date->Nice'
    ));
    
    
    /*
    $field->setShowPagination(true);
    if(isset($_REQUEST['printable'])) {
      $field->setPageSize(false);
    } else {
      $field->setPageSize(20);
    }
   
    $field->setPermissions(array(
      'export',
      //'delete',
      'print'
    ));
    */
    //remove delete permissions
    //$field->setPermissions(array());    
    
   
				
		
		
		
		$fields = new FieldSet();
		$fields->push($field);
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		$form = new Form($this, 'WorkLogTable', $fields, $actions);
		return $form;
		
		
	}
	
	
	function project(){
	  $this->projectFilter = Director::urlParam("ID");
    return $this;
	}
	
  function task(){
    $this->taskFilter = Director::urlParam("ID");
    return $this;
  }
  function FilteredTask(){
    if (isset($this->taskFilter)) {
      $task = DataObject::get_by_id("ProjectTask",$this->taskFilter);
      return $task;
    }
  }
  
  function milestone(){
    $this->milestoneFilter = Director::urlParam("ID");
    return $this;
  }
  function FilteredMilestone(){
    if (isset($this->milestoneFilter)) {
      $task = DataObject::get_by_id("Milestone",$this->milestoneFilter);
      return $task;
    }
  } 


  
	
  function edit(){
    if($this->isAjax) {
      return $this->renderWith(array("WorkLogPage_edit"));
    } else {
      return $this;
    }     
  }  


  function getOrCreateWorkLog(){
    $id = (int) Director::urlParam("ID");
    if ($id > 0) {
      $log = DataObject::get_by_id("WorkLog", $id);
      if (!$log) {
        return false;
      }
    }  else {
      $log = new WorkLog();
    }
    return $log;
  }
  

  function Form(){
    $log = $this->getOrCreateWorkLog();    
    $fields = $log->getFrontendFields();

    if ($log->ID > 0) {
      $hiddenID = new HiddenField("ID");
      $hiddenID->setValue($log->ID);
      $fields->push($hiddenID);
    }

    
    $actions = new FieldSet();
    $actions->push(new FormAction("dosave", "Save"));
    $form = new Form($this, 'Form', $fields, $actions);
    $form->loadDataFrom($log);
    return $form;
    
  }
  function dosave($data, $form){
    //var_dump($data);
    //return false;  
    if (isset($data["ID"])) {
      $id = (int) $data["ID"];
      $log = DataObject::get_by_id("WorkLog", $id);
    } else {
      $log = new WorkLog();
    }
    $form->saveInto($log);
    $log->write();

    $link = $this->Link() . "details/" . $task->ID;
    if($this->isAjax) {
      echo "saved";
    } else {
      Director::redirect($link);
    }   
  }    
    
		
	
	
}