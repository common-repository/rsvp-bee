<?php
class BaseObj {
	protected $id = 0;  // the ID in the database
    protected $table_name; //the name of the table in the database
    protected $fields = array(); // all data fields in the object. includes table fields
    protected $table_fields = array(); // fields that are stored in the database $table_name
 
    function __get($key){
        // magic method to get the value of a $key from $fields as if it were an instance variable of the class
    }
 
    function __set($key, $val){
        // magic method to set the value of $fields[$key] as if it were an instance variable of the class
    }
 
    public function set_from_form_admin(){
        // populate the object with values from form submission using integrated naming convention based on class name and $fields[key]
    }
 
    public function load(){
        // load single instance of object $table_fields from db $table_name
    }
 
    public function insert(){
        // insert one row $table_fields to db $table_name
    }
 
    public function update(){
        // update one row $table_fields to db $table_name
    }
 
    public function delete(){
        // delete $id from db $table_name
    }
}
?>
