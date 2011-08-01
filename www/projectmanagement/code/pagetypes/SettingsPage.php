<?php
class SettingsPage extends PmBasePage {
	public static $db = array(
	);
	public static $has_one = array(
	);

	function requireDefaultRecords() {
		parent::requireDefaultRecords();
		
		// default pages
		if($this->class == 'SettingsPage') {
			if(!SiteTree::get_by_link('settings')) {
				$page = new SettingsPage();
				$page->Title = "Settings";
				$page->Content = "";
				$page->URLSegment = 'settings';
				$page->Status = 'Published';
				$page->ShowInMenus = 0;
				$page->Sort = 9;
				$page->write();
				$page->publish('Stage', 'Live');
				$page->flushCache();
				DB::alteration_message('Settings page created', 'created');
			}
		}
	}	
	
	

}
class SettingsPage_Controller extends PmBasePage_Controller {

	public function init() {
		parent::init();
	}
	
}