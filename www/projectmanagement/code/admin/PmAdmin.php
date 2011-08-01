<?php
class PmAdmin extends ModelAdmin {
 
	public static $managed_models = array(
		'Account',	
		'Project',	
		'Milestone',
		'ProjectTask',
		'WorkLog'
	);
	 
	static $url_segment = 'pm';
	static $menu_title = 'PM';
	static $menu_priority = 1; 
}