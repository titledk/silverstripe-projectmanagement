<?php
class Account extends PmBase {
	//database
	public static $db = array();
	public static $has_one = array();
	public static $has_many = array(
		"Projects" => "Project"
	);
	public static $many_many = array(); 
	public static $belongs_many_many = array(); 

	//formatting
	public static $casting = array(); //adds computed fields that can also have a type (e.g. 
	//public static $field_labels = array("Name" => "Carrot Name");
	public static $singular_name = "Account";
	public static $plural_name = "Accounts";

	//CRUD settings
	
	//defaults
	/*
	public static $default_sort = "Sort ASC, Name ASC";
	public static $defaults = array();//use fieldName => Default Value
	public function populateDefaults() {
		parent::populateDefaults();
	}
	*/
}