<?php
class LoggedDataObject extends DataObject {
	//database
	public static $db = array(
		"IsLog" => "Enum('False, True', 'False')",
		"LogForID" => "Int"
	);
	
	static $defaults = array(
		"IsLog" => "False"
	);	
	
	static $summary_fields = array(
		'ID',
		'Title',
		'ClassName',
		'IsLog',
		'LogForID'
	); 	

	function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName("IsLog");
		$fields->removeByName("LogForID");		
		return $fields;
    }	

	function getFrontEndFields() {
		$fields = parent::getFrontendFields();
		$fields->removeByName("IsLog");
		$fields->removeByName("LogForID");		
		return $fields;
    }	    
    
    
	//We save a copy after each time a logged data object has been saved
	function onAfterWrite() {
		parent::onAfterWrite();
		//echo "test: " . $this->IsLog;
		if ($this->IsLog == "False") {
			if (strlen($this->Title) > 0) {
				LoggedDataObject::writelog($this);	
			}
		}
	}
	
	/*
	function onBeforeDelete() {
		parent::onBeforeDelete();		
		if ($this->IsLog == "False") {
			//if (strlen($this->Title) > 0) {
				LoggedDataObject::writelog($this);	
			//}
		}
	}
	*/

	//getting DataObjects without logs
	public static function get($callerClass, $filter = "", $sort = "", $join = "", $limit = "", $containerClass = "DataObjectSet") {
		$dos = DataObject::get($callerClass, $filter, $sort, $join, $limit, $containerClass);
		//TODO
		//this should be optimized for production use
		$newdos = new DataObjectSet();
		if ($dos) {
			foreach ($dos as $do) {
				if ($do->IsLog == "False") {
					$newdos->push($do);
				}
			}
			return $newdos;
		} else {
			return false;
		}
	}	

	//TODO
	//getting DataObjects without logs
	public static function getlog($callerClass, $filter = "", $sort = "", $join = "", $limit = "", $containerClass = "DataObjectSet") {
	}	
	
	
	
	
	static function writelog($obj) {
		$className = $obj->ClassName;
		//$logClass = $className . "_Log";
		$logClass = $className;
		$log = new $logClass();
		//$fields = Object::uninherited_static($className, 'db');
		$fields = Object::combined_static($className, 'db');
		//var_dump(Object::combined_static($className, 'db'));
		
		foreach ($fields as $attr => $type) {
			//echo $attr . "<br />";
			$log->$attr = $obj->$attr;
		}
		$log->IsLog = "True";
		$log->LogForID = $obj->ID;
		$log->write();
	}	
	
	
}