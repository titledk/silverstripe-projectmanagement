<?php
class Milestone extends PmBase {
	//database
	public static $db = array(
		"DueDate" => "Date",
		"Status" => "Enum('Active, Completed, Cancelled, Deleted', 'Active')",	
	);
	public static $has_one = array(
		"Project" => "Project"
	);
	public static $has_many = array(
		"Tasks" => "ProjectTask"
	);
	public static $many_many = array(); 
	public static $belongs_many_many = array(); 

	//formatting
	public static $casting = array(); //adds computed fields that can also have a type (e.g. 
	public static $field_labels = array(
		"ShortDescription" => "Milestone Description"
	);
	public static $singular_name = "Milestone";
	public static $plural_name = "Milestones";

	
	function getProjectTitle(){
		if ($this->ProjectID > 0) {
			return $this->Project()->Title;
		}
	}

  static $default_sort = "Title";

	

	static $summary_fields = array(
		'ID',
		'Title',
		'Project.Title',	
		'ClassName',
		'IsLog',
	); 		
	
  //TODO
  //combine with all other iterating methods for performance increasing
	function getCompleted(){
	  $tasks = $this->Tasks();
    $completed = true;
    foreach ($tasks as $task) {
      if ($task->Status != "Completed" && $task->Status != "Deleted") {
        return false;
      } 
    }
    return $completed;
	}

  //TODO
  //combine with all other iterating methods for performance increasing	
	function getOriginalHoursEstimated(){
		$total = 0;
		$tasks = $this->Tasks();
		if ($tasks) {
			foreach ($tasks as $task) {
				$total = $total + $task->OriginalHourEstimate;
			}
		}
		
		return $total;
	}
  function getTotalHoursEstimated(){
    $total = 0;
    $tasks = $this->Tasks();
    if ($tasks) {
      foreach ($tasks as $task) {
        $total = $total + $task->EstimatedHoursRemaining();
      }
    }
    
    return $total;
  }  
 
  function getTotalHoursEstimatedAndWorked(){
    $total = 0;
    $total = $this->getTotalHoursWorked() + $this->getTotalHoursEstimated();
    return $total;
  }   
 
  
  
  
  function getTotalHoursWorked(){
    $total = 0;
    $tasks = $this->Tasks();
    if ($tasks) {
      foreach ($tasks as $task) {
        $total = $total + $task->HoursWorked();
      }
    }
    return $total;
  }
    

  function PercentComplete(){
    $worked = $this->getTotalHoursWorked();
    $remaining = $this->getTotalHoursEstimated();
    if (($worked > 0) && ($remaining > 0)) {
      $completion = $worked / ($remaining + $worked);
      return round($completion * 100) . "%";
    } else {
      return false;
      //return "N.A.";
    }
  } 
  
  
	
	function getFrontEndFields() {
		$fields = parent::getFrontendFields();
		$fields->removeByName("Status");

		//description is disabled for now
		$fields->removeByName("Description");
		
		$dateField = new DateField("DueDate");
		$dateField->setConfig("showcalendar", true);
		$fields->push($dateField);
		
		$projects = PmBase::get("Project", NULL, "Title");
		$array = array();
		$array[0] = "Select Project...";
		if ($projects) {
			foreach ($projects as $project) {
				$array[$project->ID] = $project->Title;
			}
		}
		/*
		$array = array(
		  'NZ' => 'New Zealand',
		  'US' => 'United States',
		  'GEM'=> 'Germany',
		);
		*/
		$projectField = new DropdownField(
			'ProjectID',
			'Project',
			$array
		);		
		
		$fields->push($projectField);

		
		return $fields;
    }	
	
	function Link(){
		$page = SiteTree::get_one("MilestonePage");
		$link = $page->Link() . "details/" . $this->ID;
		return $link;		
	}	
	

	//defaults
	/*
	public static $default_sort = "Sort ASC, Name ASC";
	public static $defaults = array();//use fieldName => Default Value
	public function populateDefaults() {
		parent::populateDefaults();
	}
	*/
    
	function ProjectTaskTable(){
		$tableField = PmBase::task_table_field($this->ID);		
		$fields = new FieldSet();
		$fields->push($tableField);;
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		
		$projectPage = SiteTree::get_one("MilestonePage");
		
		$form = new Form($projectPage, 'ProjectTaskTable', $fields, $actions);
		return $form;
		
		
	}

	function TaskAddForm(){
		$task = new ProjectTask();		
		$fields = $task->getFrontendFields();
		$milestoneID = new HiddenField("MilestoneID");
		$milestoneID->setValue($this->ID);
		$fields->push($milestoneID);
		
		$actions = new FieldSet();
		$actions->push(new FormAction("dosave", "Add"));
		$page = DataObject::get_one("ProjectTaskPage");
		
		$form = new Form($page, 'Form', $fields, $actions);
		return $form;
	}		
	
	
    
    
}