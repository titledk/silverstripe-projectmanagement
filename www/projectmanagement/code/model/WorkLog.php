<?php
/**
 * Work Log
 * @author titledk
 * 
 */
class WorkLog extends PmBase {
	//database
	public static $db = array(
		"Date" => "Date",
		"HoursSpent" => "Decimal",
    "EstimatedHoursRemaining" => "Decimal",		
	);
	public static $has_one = array(
		"Task" => "ProjectTask"
	);
	public static $has_many = array();
	public static $many_many = array(); 
	public static $belongs_many_many = array(); 

	//formatting
	public static $casting = array(); //adds computed fields that can also have a type (e.g. 
	//public static $field_labels = array("Name" => "Carrot Name");
	public static $singular_name = "Work Log";
	public static $plural_name = "Work Logs";

	static $summary_fields = array(
		'ID',
		'Title',
		'Task.Title',	
		'ClassName',
		'IsLog',
	); 		

  public static $field_labels = array(
    "ShortDescription" => "Log Description"
  );

	function getTaskTitle(){
	  return $this->Task()->Title;
	}

  function getMilestoneID(){
    $task = $this->Task();  
    return $task->Milestone()->ID;
  }
  function getMilestoneTitle(){
    $task = $this->Task();  
    return $task->Milestone()->Title;
  }

	//defaults
	/*
	public static $default_sort = "Sort ASC, Name ASC";
	public static $defaults = array();//use fieldName => Default Value
	public function populateDefaults() {
		parent::populateDefaults();
	}
	*/

  function getFrontEndFields() {
    $fields = parent::getFrontendFields();
    
    //description is disabled for now
    $fields->removeByName("Description");
    $fields->removeByName("TaskID");
    
    $fields->removeByName("Title");
    
    $dateField = new DateField("Date","Date",date("M d, Y"));
    $dateField->setConfig("showcalendar", true);
    $fields->push($dateField);

    
    return $fields;
    } 


}