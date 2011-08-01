<?php
class Project extends PmBase {
	//database
	public static $db = array(
		"Status" => "Enum('Active, Completed, Cancelled, Suspended, Standby, Deleted', 'Active')",
		"Type" => "Enum('Client, Internal, Private, Idea, N.A.', 'N.A.')",
		"Priority" => "Enum('A. (High), B. (Medium), C. (Low), N.A.', 'N.A.')",
		"DueDate" => "Date"	
	);
	public static $has_one = array(
		"Account" => "Account"
	);
	public static $has_many = array(
		"Milestones" => "Milestone",
		"Tasks" => "ProjectTask"	
	);
	public static $many_many = array(); 
	public static $belongs_many_many = array(); 

	//formatting
	public static $casting = array(); //adds computed fields that can also have a type (e.g. 
	public static $field_labels = array(
		"ShortDescription" => "Project Description"
	);
	public static $singular_name = "Project";
	public static $plural_name = "Projects";

	static $default_sort = "Priority, DueDate";
	

	static $summary_fields = array(
		'ID',
		'Title',
		'Account.Title',	
		'ClassName',
		'IsLog',
	); 	

	function getTotalHoursEstimated(){
		$total = 0;
		$milestones = $this->Milestones();
		if ($milestones) {
			foreach ($milestones as $milestone) {
				$total = $total + $milestone->getTotalHoursEstimated();
			}
		}
		
		return $total;
	}	
	
  function getOriginalHoursEstimated(){
    $total = 0;
    $milestones = $this->Milestones();
    if ($milestones) {
      foreach ($milestones as $milestone) {
        $total = $total + $milestone->getOriginalHoursEstimated();
      }
    }
    
    return $total;
  } 	
	
  function getTotalHoursWorked(){
    $total = 0;
    $milestones = $this->Milestones();
    if ($milestones) {
      foreach ($milestones as $milestone) {
        $total = $total + $milestone->getTotalHoursWorked();
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
 
  function getTotalHoursEstimatedAndWorked(){
    $total = 0;
    $total = $this->getTotalHoursWorked() + $this->getTotalHoursEstimated();
    return $total;
  }      
  
	

	function getFrontEndFields() {
		$fields = parent::getFrontendFields();
		//$fields->removeByName("Status");
		$fields->removeByName("AccountID");

		//description is disabled for now
		$fields->removeByName("Description");

		
		//STATUS
		$statuses = singleton('Project')->dbObject('Status')->enumValues();

		$statusField = new OptionsetField(
		  'Status',
		  'Status',
		  $statuses,
		  'Active'
		);
		//$fields->push($statusField);
		
		
		//PRIORITY
		$priorities = singleton('Project')->dbObject('Priority')->enumValues();
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
		
		
		//TYPE
		$types = singleton('Project')->dbObject('Type')->enumValues();
		$typesSorted = array();
		$typesSorted["N.A."] = "N.A.";
		foreach ($types as $type) {
			if ($type != "N.A.") {
				//$typesSorted[] = $type;
				$typesSorted[$type] = $type;
			}
		} 
		$typeField = new DropdownField(
		  'Type',
		  'Type',
		  $typesSorted
		);
		//$fields->push($typeField);
		
		//DATE
		$dateField = new DateField("DueDate");
		$dateField->setConfig("showcalendar", true);
		
		//$fields->push($dateField);
		
		
		$fields->removeByName("Type");
		$fields->removeByName("DueDate");
		
		$holder = new CompositeField();
		$holder->setID("TypeDueDateHolder");
		$holder->addExtraClass("cf");
		$holder->push($dateField);
		$holder->push($typeField);
		
		$fields->push($holder);		
		
		
		
		
		
		
		return $fields;
		
		
    }	 	
	
	function Link(){
		$page = SiteTree::get_one("ProjectPage");
		$link = $page->Link() . "details/" . $this->ID;
		return $link;		
	}
	
	
	//CRUD settings
	
	/*static $can_create = Boolean;
	public function canCreate() {return false;}
	public function canView() {return false;}
	public function canEdit() {return false;}
	public function canDelete() {return false;}
	*/

	//defaults
	/*
	public static $default_sort = "Sort ASC, Name ASC";
	public static $defaults = array();//use fieldName => Default Value
	public function populateDefaults() {
		parent::populateDefaults();
	}
	*/
    
	function MilestoneTable(){
		$tableField = PmBase::milestone_table_field($this->ID);		
		$fields = new FieldSet();
		$fields->push($tableField);;
		
		$actions = new FieldSet();
		//$actions->push(new FormAction("dosave", "Save"));
		
		$projectPage = SiteTree::get_one("ProjectPage");
		
		$form = new Form($projectPage, 'MilestoneTable', $fields, $actions);
		return $form;
	}    

	function MilestoneAddForm(){
		$milestone = new Milestone();		
		$fields = $milestone->getFrontendFields();
		$projectID = new HiddenField("ProjectID");
		$projectID->setValue($this->ID);
		$fields->push($projectID);
		
		$actions = new FieldSet();
		$actions->push(new FormAction("dosave", "Save"));
		$page = DataObject::get_one("MilestonePage");
		
		$form = new Form($page, 'Form', $fields, $actions);
		//$form->loadDataFrom($milestone);
		return $form;
		
	}	
	


}
