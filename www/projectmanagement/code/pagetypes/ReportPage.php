<?php
class ReportPage extends PmBasePage {
	public static $db = array(
	);
	public static $has_one = array(
	);

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// default pages
		if($this->class == 'ReportPage') {
			if(!SiteTree::get_by_link('reports')) {
				$page = new ReportPage();
				$page->Title = "Reports";
				$page->Content = "";
				$page->URLSegment = 'reports';
				$page->Status = 'Published';
				$page->Sort = 5;
				$page->write();
				$page->publish('Stage', 'Live');
				$page->flushCache();
				DB::alteration_message('Report page created', 'created');
			}
		}
	}	
	
	

}
class ReportPage_Controller extends PmBasePage_Controller {

	public function init() {
		parent::init();
	}
	
	function PrintMode(){
		if (isset($_GET["print"])) {
			return true;			
		} else {
			return false;
		}
	}
	
	function ShowLinks(){
		if ($this->PrintMode()) {
			return false;
		} else {
			return true;
		}
	}
	
	function Title(){
		$project = $this->getOrCreateProject();
		//TODO once different report types are set up this needs to be atuomatic
		$str = "Estimate for " . $project->Title;
		return $str;
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
	function Project(){
		$project = $this->getOrCreateProject();
		return $project;
	}	
	
	
	function Projects(){
		$projects = PmBase::get("Project");
		return $projects;
	}
	
	function estimate(){
		if ($this->PrintMode()) {
			if (isset($_GET["msword"])) {
				
				/*
				header("Content-type: application/vnd.ms-word");
				header("Content-Disposition: attachment;Filename=document_name.doc");
				
				echo "<html>";
				echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
				echo "<body>";
				echo "<b>My first document</b>";
				echo "</body>";
				echo "</html>";
				*/
				
				$html = $this->renderWith(array("ReportPage_estimate","PrintPage"));
				
				include("../projectmanagement/code/lib/html_to_doc-2005-12-10/html_to_doc.inc.php");
				$htmltodoc= new HTML_TO_DOC();
				$htmltodoc->createDoc($html,"Estimate",true);				
				
				
					
				
				
				
			} else {
				return $this->renderWith(array("ReportPage_estimate","PrintPage"));
			}
			
		} else {
			return $this->renderWith(array("ReportPage_estimate","Page"));
		}
	}	
	
	
}