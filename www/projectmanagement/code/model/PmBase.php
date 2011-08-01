<?php
/**
 * PM Base
 * @author titledk
 *
 * Base Class for all Project Management DataObjects
 */
class PmBase extends LoggedDataObject {
	public static $db = array(
		"Title" => "Varchar(255)",
		"ShortDescription" => "Text",
		"Description" => "HTMLText",
	);

	public static function get($callerClass, $filter = "", $sort = "", $join = "", $limit = "", $containerClass = "DataObjectSet") {
		$dos = LoggedDataObject::get($callerClass, $filter, $sort, $join, $limit, $containerClass);
		return $dos;
	}
	
	function getShortDecriptionFormatted(){
		$obj = $this->dbObject('ShortDescription');
		$str = $obj->XML();
		$str = preg_replace("/(http:\/\/[^\s]+)/", "<a href=\"$1\">$1</a>", $str);
		return $str;		
	}	
	
	
	//example taken from
	//http://doc.silverstripe.org/tablelistfield	
	public static function project_table_field($AccountID = NULL, $statusType = NULL){
		$resultSet = new DataObjectSet();
		
		$filter = "LoggedDataObject.IsLog = 'False'";
		if ($AccountID) {
			$filter .= " AND AccountID = $AccountID";
		}
		if ($statusType == "Active") {
			$filter .= " AND Status = 'Active'";
		}
		if ($statusType == "Completed") {
			$filter .= " AND Status = 'Completed'";
		}
		if ($statusType == "Standby") {
			$filter .= " AND Status = 'Standby'";
		}
		
		
		
		//$sort = "Title ASC";
		$sort = NULL;
		$join = '';
		$instance = singleton('Project');		
		$query = $instance->buildSQL($filter, $sort, null, $join);
		//$query->groupby[] = 'Member.ID';
	 
		$field = new TableListField(
			'ProjectReport', //name
			'Project',
			array(
				'Indicator' => '',
				'Title' => 'Title',
				'Type' => 'Type',
				'Priority' => 'Priority',
				'DueDate' => 'DueDate',
				'Status' => 'Status',									
				'Show' => '',
				'Edit' => ''
			)
		);
	 
		$field->setCustomQuery($query);
		
		$projectPage = SiteTree::get_one("ProjectPage");
		
		
		$field->setFieldFormatting(array(
			//'Email' => '<a href=\"mailto: $Email\" title=\"Email $FirstName\">$Email</a>',
			//'Edit' => '<a href=\"admin/security/index/1?executeForm=EditForm&ID=1&ajax=1&action_callfieldmethod&fieldName=Members&ctf[childID]=$ID&ctf[ID]=1&ctf[start]=0&methodName=edit\"><img src=\"cms/images/edit.gif\" alt=\"Edit this member\" /></a>'
			'Title' => '<a href=\"' . $projectPage->Link() . 'details/$ID\">$Title</a>',
			'Show' => '<a href=\"' . $projectPage->Link() . 'details/$ID\"><img src=\"cms/images/show.png\" alt=\"View this project\" /></a>',
			'Edit' => '<a href=\"' . $projectPage->Link() . 'edit/$ID\"><img src=\"cms/images/edit.gif\" alt=\"Edit this project\" /></a>'			
		));
		
		$field->setHighlightConditions(array( 
			array( "rule" => '$Priority == "A. (High)"', "class" => "priority-high" ), 
			array( "rule" => '$Priority == "B. (Medium)"', "class" => "priority-medium" ),
			array( "rule" => '$Priority == "C. (Low)"', "class" => "priority-low" ), 
		));
		
		
		
	 	/*
		$field->setFieldCasting(array(
			'DateJoined' => 'Date->Nice',
			'PaidUntil' => 'Date->Nice'
		));
		*/
	 	
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
		$field->setPermissions(array());		
		
		return $field;	
	}	
	
	
	
	
	//example taken from
	//http://doc.silverstripe.org/tablelistfield	
	public static function milestone_table_field($projectID = NULL){
		$resultSet = new DataObjectSet();
		
		$filter = "LoggedDataObject.IsLog = 'False'";
		if ($projectID) {
			$filter .= " AND ProjectID = $projectID";
		}
		
		$sort = "DueDate, Title ASC";
		$join = '';
		$instance = singleton('Milestone');		
		$query = $instance->buildSQL($filter, $sort, null, $join);
		//$query->groupby[] = 'Member.ID';
	 
		$field = new TableListField(
			'MilestoneReport', //name
			'Milestone',
			array(
				//'ID' => 'ID',
				'Title' => 'Title',
				'DueDate' => 'DueDate',
				'Status' => 'Status',
				'Project.Title' => 'Project',									
				'Show' => '',
				'Edit' => ''
			)
		);
	 
		$field->setCustomQuery($query);
		
		$projectPage = SiteTree::get_one("ProjectPage");
		$milestonePage = SiteTree::get_one("MilestonePage");
		
		
		$field->setFieldFormatting(array(
			'Title' => '<a href=\"' . $milestonePage->Link() . 'details/$ID\">$Title</a>',
			'Project.Title' => '<a href=\"' . $projectPage->Link() . 'details/$ProjectID\">$ProjectTitle</a>',		
			'Show' => '<a href=\"' . $milestonePage->Link() . 'details/$ID\"><img src=\"cms/images/show.png\" alt=\"View this project\" /></a>',
			'Edit' => '<a href=\"' . $milestonePage->Link() . 'edit/$ID\"><img src=\"cms/images/edit.gif\" alt=\"Edit this project\" /></a>'
		));
	 	/*
		$field->setFieldCasting(array(
			'DateJoined' => 'Date->Nice',
			'PaidUntil' => 'Date->Nice'
		));
		*/
	 	
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
		$field->setPermissions(array());		
		
		return $field;	
	}

	//example taken from
	//http://doc.silverstripe.org/tablelistfield	
	public static function task_table_field($milestoneID = NULL){
		$resultSet = new DataObjectSet();
		
		$filter = "LoggedDataObject.IsLog = 'False'";
		if ($milestoneID) {
			$filter .= " AND MilestoneID = $milestoneID AND ParentTaskID = 0";
		}
		
		$sort = "Sort ASC, Priority ASC, DueDate, Title ASC";
		$join = '';
		$instance = singleton('ProjectTask');		
		$query = $instance->buildSQL($filter, $sort, null, $join);
		//$query->groupby[] = 'Member.ID';
	 
		$field = new TableListField(
			'ProjectTaskReport', //name
			'ProjectTask',
			array(
				//'ID' => 'ID',
				'Title' => 'Title',
				'Type' => 'Type',
				'Priority' => 'Priority',
				'DueDate' => 'DueDate',
				'Status' => 'Status',
				'OriginalHourEstimate' => 'Orig Est',
				'EstimatedHoursRemaining' => 'Curr Est',			
				'Milestone.Title' => 'Milestone',									
				'Show' => '',
				'Edit' => ''
			)
		);
	 
		$field->setCustomQuery($query);
		
		$projectPage = SiteTree::get_one("ProjectPage");
		$milestonePage = SiteTree::get_one("MilestonePage");
		$taskPage = SiteTree::get_one("ProjectTaskPage");
		
		
		$field->setFieldFormatting(array(
			'Title' => '<a href=\"' . $taskPage->Link() . 'details/$ID\">$Title</a>',
			'Milestone.Title' => '$BreadCrumb',		
			'Show' => '<a href=\"' . $taskPage->Link() . 'details/$ID\"><img src=\"cms/images/show.png\" alt=\"View this project\" /></a>',
			'Edit' => '<a href=\"' . $taskPage->Link() . 'edit/$ID\"><img src=\"cms/images/edit.gif\" alt=\"Edit this project\" /></a>'
		));
	 	/*
		$field->setFieldCasting(array(
			'DateJoined' => 'Date->Nice',
			'PaidUntil' => 'Date->Nice'
		));
		*/
	 	
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
		$field->setPermissions(array());		
		
		return $field;	
	}	

	public static function subtask_table_field($taskID = NULL){
		$resultSet = new DataObjectSet();
		
		$filter = "LoggedDataObject.IsLog = 'False'";
		if ($taskID) {
			$filter .= " AND ParentTaskID = $taskID";
		}
		
		$sort = "Sort ASC, Priority ASC, Title ASC";
		$join = '';
		$instance = singleton('ProjectTask');		
		$query = $instance->buildSQL($filter, $sort, null, $join);
		//$query->groupby[] = 'Member.ID';
	 
		$field = new TableListField(
			'ProjectSubTaskReport', //name
			'ProjectTask',
			array(
				//'ID' => 'ID',
				'Title' => 'Title',
				'Type' => 'Type',
				'Priority' => 'Priority',
				'DueDate' => 'DueDate',
				'Status' => 'Status',
				'OriginalHourEstimate' => 'Original Estimate',
				'EstimatedHoursRemaining' => 'Current Estimate',			
				'Milestone.Title' => 'Milestone',									
				'Show' => '',
				'Edit' => ''
			)
		);
	 
		$field->setCustomQuery($query);
		
		$projectPage = SiteTree::get_one("ProjectPage");
		$milestonePage = SiteTree::get_one("MilestonePage");
		$taskPage = SiteTree::get_one("ProjectTaskPage");
		
		
		$field->setFieldFormatting(array(
			'Title' => '<a href=\"' . $taskPage->Link() . 'details/$ID\">$Title</a>',
			'Milestone.Title' => '$BreadCrumb',		
			'Show' => '<a href=\"' . $taskPage->Link() . 'details/$ID\"><img src=\"cms/images/show.png\" alt=\"View this project\" /></a>',
			'Edit' => '<a href=\"' . $taskPage->Link() . 'edit/$ID\"><img src=\"cms/images/edit.gif\" alt=\"Edit this project\" /></a>'
		));
	 	/*
		$field->setFieldCasting(array(
			'DateJoined' => 'Date->Nice',
			'PaidUntil' => 'Date->Nice'
		));
		*/
	 	
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
		$field->setPermissions(array());		
		
		return $field;	
	}		
	
	
	
}