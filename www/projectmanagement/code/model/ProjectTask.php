<?php
class ProjectTask extends PmBase {
	//database
	public static $db = array(
		"Type" => "Enum('Task, Bug, Requirement', 'Task')",
		"Status" => "Enum('New, Active, Completed, Cancelled, Deleted', 'New')",
		"Priority" => "Enum('A. (High), B. (Medium), C. (Low), N.A.', 'N.A.')",		
		"DueDate" => "Date",
		"OriginalHourEstimate" => "Decimal",
		//"EstimatedHoursRemaining" => "Decimal",	 //obsolete as introduced in WorkLog (26th september 2010)
		"Sort" => "Int",		
	);
	public static $has_one = array(
		"Project" => "Project",
		"Milestone" => "Milestone",
		"ParentTask" => "ProjectTask"
	);
	public static $has_many = array(
		"WorkLogs" => "WorkLog",
		"SubTasks" => "ProjectTask"
	);
	
	
	public static $many_many = array(); 
	public static $belongs_many_many = array(); 

	//formatting
	public static $casting = array(); //adds computed fields that can also have a type (e.g. 
	//public static $field_labels = array("Name" => "Carrot Name");
	public static $singular_name = "Task";
	public static $plural_name = "Tasks";

	static $summary_fields = array(
		'ID',
		'Title',
		'Milestone.Title',	
		'ClassName',
		'IsLog',
	); 		

	
	static $default_sort = "Status DESC, Priority, \"Sort\"";	
	

	//defaults
	/*
	public static $default_sort = "Sort ASC, Name ASC";
	public static $defaults = array();//use fieldName => Default Value
	public function populateDefaults() {
		parent::populateDefaults();
	}
	*/
	
	function HoursWorked(){
	  $logs = $this->WorkLogs();
    $hours = 0;
    if ($logs) {
      foreach ($logs as $log) {
        $hours = $hours + $log->HoursSpent;
      }
    }
    return $hours;
	}
	
  
  /*
   * Calculation of estimated hours remaining
   * if no estimations exist in the work log,
   * the original estimation is taken
   */
  function EstimatedHoursRemaining(){
    if ($this->Status == "Completed") {
      return 0;
    }  
    $logs = $this->WorkLogs(NULL, NULL, NULL, NULL, "ID DESC");
    $hours = false;
    
    if ($logs) {
      foreach ($logs as $log) {
        //echo "test: " . $log->EstimatedHoursRemaining;       
        if ($log->EstimatedHoursRemaining > 0) {
          $hours = $log->EstimatedHoursRemaining;
        }
      }
    }

    if ($hours == 0) {
      $hours = $this->OriginalHourEstimate;
    }

    return $hours;
  }
  
  function PercentComplete(){
    $worked = $this->HoursWorked();
    $remaining = $this->EstimatedHoursRemaining();
    if (($worked > 0) && ($remaining > 0)) {
      $completion = $worked / ($remaining + $worked);
      return round($completion * 100) . "%";
    } else {
      return false;
      //return "N.A.";
    }
  } 

  function HoursEstimatedAndWorked(){
    $total = 0;
    $total = $this->HoursWorked() + $this->EstimatedHoursRemaining();
    return $total;
  }     
  
  
	
	function getTrCssClass(){
		$priority = $this->Priority;
		$str = "";
		if ($priority == "A. (High)") {
			$str = "priority-high";
		}
		if ($priority == "B. (Medium)") {
			$str = "priority-medium";
		}
		if ($priority == "C. (Low)") {
			$str = "priority-low";
		}		
		return $str;	
	}
	
	
	function Link(){
		$page = SiteTree::get_one("ProjectTaskPage");
		$link = $page->Link() . "details/" . $this->ID;
		return $link;		
	}		
	
	function getMilestoneTitle(){
		if ($this->MilestoneID > 0) {
			return $this->Milestone()->Title;
		}
	}
	
	function getBreadCrumb(){
		if ($this->MilestoneID > 0) {
			$milestone = $this->Milestone();
			$project = $milestone->Project();
			
			
			$link = '<a href="' . $project->Link() . '#Milestone_' . $milestone->ID . '">' . $project->Title . '</a>' .
			' &raquo; ' .
			'<a href="' . $milestone->Link() . '">' . $milestone->Title . '</a>';
			
			
			return $link;
		}
	}
	
	

	function getFrontEndFields() {
		$fields = parent::getFrontendFields();

		//$fields->removeByName("Type");
		//$fields->removeByName("Status");
		$fields->removeByName("ProjectID");
		
		//$fields->removeByName("ParentTaskID");
		$taskID = new HiddenField("ParentTaskID");
		$taskID->setValue(0);
		$fields->push($taskID);
		
		
		//description is disabled for now
		$fields->removeByName("Description");

		$milestones = PmBase::get("Milestone", NULL, "ProjectID, Title");
		$array = array();
		$array[0] = "Select Milestone...";
		if ($milestones) {
			foreach ($milestones as $milestone) {
				$array[$milestone->ID] = $milestone->getProjectTitle() . ":" . $milestone->Title;
			}
		}
		$milestoneField = new DropdownField(
			'MilestoneID',
			'Milestone',
			$array
		);		
		
		$fields->push($milestoneField);		

/*
		$priorities = singleton('ProjectTask')->dbObject('Priority')->enumValues();
		$prioritiesSorted = array();
		$pritoritesSorted["N.A."] = "N.A.";
		foreach ($priorities as $priority) {
			if ($priority != "N.A.") {
				$pritoritesSorted[$priority] = $priority;
			}
		} 
		$priorityField = new DropdownField(
		  'Priority',
		  'Priority',
		  $pritoritesSorted
		);
 */
 
 
    //STATUS
    $statuses = singleton('ProjectTask')->dbObject('Status')->enumValues();

    $statusField = new OptionsetField(
      'Status',
      'Status',
      $statuses,
      'Active'
    );
    //$fields->push($statusField);
    
    
    //PRIORITY
    $priorities = singleton('ProjectTask')->dbObject('Priority')->enumValues();
    /*
    $prioritiesSorted = array();
    foreach ($priorities as $priority) {
      if ($priority != "N.A.") {
        $pritoritesSorted[$priority] = $priority;
      }
    } 
    $pritoritesSorted["N.A."] = "N.A.";
    */
    $pritoritesSorted = $priorities;
    $priorityField = new OptionsetField(
      'Priority',
      'Priority',
      $pritoritesSorted,
      'N.A.'
    );
    //$fields->push($priorityField);

    
    $fields->removeByName("Status");
    $fields->removeByName("Priority");
    
    $holder = new CompositeField();
    $holder->setID("StatusPriorityHolder");
    $holder->addExtraClass("cf");
    $holder->push($statusField);
    $holder->push($priorityField);
    
    $fields->push($holder);
				
		
		
		
		$dateField = new DateField("DueDate");
		$dateField->setConfig("showcalendar", true);
		$fields->push($dateField);

		//disabling type for now
    $fields->removeByName("Type");
    
    
		return $fields;
    }		
	
	
	function SubTaskTable(){
		$tableField = PmBase::subtask_table_field($this->ID);		
		$fields = new FieldSet();
		$fields->push($tableField);;
		
		$actions = new FieldSet();
		
		$page = SiteTree::get_one("ProjectTaskPage");
		
		$form = new Form($page, 'SubTaskTable', $fields, $actions);
		return $form;
	}    

	function SubTaskAddForm(){
		$task = new ProjectTask();		
		$fields = $task->getFrontendFields();
		
		$taskID = new HiddenField("ParentTaskID");
		$taskID->setValue($this->ID);
		$fields->push($taskID);

		$milestoneID = new HiddenField("MilestoneID");
		$milestoneID->setValue($this->MilestoneID);
		$fields->push($milestoneID);
		
		$actions = new FieldSet();
		$actions->push(new FormAction("dosave", "Add"));
		$page = DataObject::get_one("ProjectTaskPage");
		
		$form = new Form($page, 'Form', $fields, $actions);
		return $form;
	}			
	
	
    
    
	
	//TODO
	/*
	 * Hours Used / (Hours Used + Estimated Hours Remaining)
	 */
	function PercentageCompleted(){
		
	}

	//TODO
	function HoursUsed(){
		
	}
	
	
}