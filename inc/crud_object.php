<?php

/*
* The MIT License (MIT)

* Copyright (c) 2015 Sergey Chekriy

* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.

* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

require_once(LIB_PATH.DS.'database.php');

//SC1971 it is just my signature, Sergey Chekriy, 1971 is my year of birth :))
abstract class SC1971_CRUDObject  {

// make use of late static binding
// to understand fully logic, see example, class User inherited from OracleCRUDObject
// and script person_crud.php  
    
  protected static $table_name="null";
  protected static $table_fields = array();
  protected static $table_fields_types = array(); //to make cleaner insert queries (Oracle specific)
  protected static $id_sequence = "null";      //name of sequence (Oracle specific)
  protected static $id_field='id';
  protected static $db_class='SC1971_Database';
  
  public static function db(){
    $class =  static::$db_class; 
    return $class::get_instance();
  }
 
	
  // public CRUD: (C)reate (R)ead (U)pdate (D)elete methods
  // each OracleCRUDObject is basically correspond to Oracle table row
  // object can create itself and store in db, read itself (or array of OracleCRUDObjects) from db,
  // update itself in db and delete itself from db
  // design restrictions: 
  // (1) table should always contain primary key, can be named on Oracle level 
  //     by any name, but this name should be coded into $id_field (defauld ='id). 
  // (2) object attributes (see User class in example) should be named exactly as corresponding Oracle table fields
  //     see object_from_table_row() method for undertanding why it should be so.
  
  
  
  
  //(C)reate
  public function create(){

            $id=0;
            $attributes = $this->prepared_attributes_insert();
		
	    $sql = "INSERT INTO ".static::$table_name." (";
	    $sql .= join(",",array_keys($attributes));
	    $sql .= ") VALUES (";           
            $sql .= join(",",array_values($attributes));		    
            $sql .= ") ";
            
            $idf = static::$id_field;
           
            $this->{static::$id_field} = static::db()->insert($sql);
            static::db()->free_statement();
            if (static::$idf !== -1)
            {
	      return true;
	    } else {
	      return false;
	    }
  }
  
  //(R)ead
  public static function read_all() {
		return static::read_by_sql("SELECT * FROM ".static::$table_name);
  }
  
  public static function read_by_id($id=0) {
      $result_array = static::read_by_sql("SELECT * FROM ".static::$table_name." WHERE ".static::$id_field."=".static::db()->escape_value($id));
		return !empty($result_array) ? array_shift($result_array) : false;
   }

  
  public static function read_by_sql($sql="") {
   
    static::db()->query($sql);
    $object_array = array();
   
    while (($row = static::db()->fetch_array()) != false) {
      $object_array[] = static::object_from_table_row($row);
    }
    
    return $object_array;
  }
  

  public static function count_all() {
	  $sql = "SELECT COUNT(*) FROM ".static::$table_name;
          static::db()->query($sql);
	  $row = static::db()->fetch_array();
          return array_shift($row);
  }	
  
  //(U)pdate
  public function update(){
	//escape values 
	$attributes = $this->prepared_attributes();
        $attribute_pairs = array();
        foreach($attributes as $key => $value) {
        	 $attribute_pairs[] = "{$key}='{$value}'";
        }


        $sql = "UPDATE ".static::$table_name." SET ";
	$sql .= join(", ",$attribute_pairs);
        $sql .= " WHERE ".static::$id_field."=". $this->db()->escape_value($this->{static::$id_field});
	$result = static::db()->query($sql);
        static::db()->free_statement();
	return $result;
  }
  
  public function save(){
        return isset($this->{static::$id_field}) ? $this->update() : $this->create();
  	
  } 
 
  //(D)elete
  public function delete(){
		
	  $sql = "DELETE FROM ".static::$table_name." ";
          $sql .= "WHERE ".static::$id_field."=". static::db()->escape_value($this->{static::$id_field});
	
          
	  static::db()->query($sql);
          static::db()->free_statement();
	  return (static::db()->affected_rows() == 1) ? true : false;
	  //after deletion from db object instance still exist and we can communicate
          //to user deletion event with additional info (properties of object which was deleted)
  	
  }
 	
  
  //private & protected methods
	
  private static function object_from_table_row($record) {
	   // Could check that $record exists and is an array
	   // Simple, long-form approach:
	    $class_name = get_called_class();
		$object = new $class_name;
		
		foreach($record as $attribute=>$value){
                  $attr = strtolower($attribute); //specific for Oracle, where table columns are always uppercase
		  if($object->has_attribute($attr)) {
		    $object->$attr = $value;
		  }
		}
		return $object;
  }
	
  private function has_attribute($attribute) {
	  // get_object_vars returns an associative array with all attributes 
	  // (incl. private ones!) as the keys and their current values as the value
	  $object_vars = get_object_vars($this);
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $object_vars);
	}
  
   protected function attributes() {
          // return an array of attribute names and their values
	  $attributes = array();
	  foreach(static::$table_fields as $field) {
	    if(property_exists( $this, $field)) {
	      $attributes[$field] = $this->$field;
	    }
	  }
	  return $attributes;
   }
   
   protected  function prepared_attributes_insert() {
 	  return $this->prepared_attributes();
   }
   
   protected  function prepared_attributes() {
	  $clean_attributes = array();
	  // prepare values before submitting sql, use for all queries except insert queries, 
          // for insert sql queries use prepared_attributes_insert()
	  
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = static::db()->escape_value($value);
	  }
	  return $clean_attributes;
   }
   

 	

	
	
}