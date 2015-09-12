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
* copies or substafintial portions of the Software.

* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

require_once(LIB_PATH.DS.'oracle_database.php');

abstract class SC1971_OracleCRUDObject extends SC1971_CRUDObject  {

  
  // see base class for documentation
    
  protected static $table_name="null";
  protected static $table_fields = array();
  protected static $table_fields_types = array(); //to make cleaner insert queries (Oracle specific)
  protected static $id_sequence = "null";         //name of sequence (Oracle specific)
	
  protected static $db_class='SC1971_OracleDatabase';
  
  
//(C)reate - override to add clause 'returning
  public function create(){

            $id=0;
            $attributes = $this->prepared_attributes_insert();
		
	    $sql = "INSERT INTO ".static::$table_name." (";
	    $sql .= join(",",array_keys($attributes));
	    $sql .= ") VALUES (";           
            $sql .= join(",",array_values($attributes));		    
            $sql .= ") ";
            $sql .= "returning ".static::$id_field." into :ID";
           
            $this->{static::$id_field} = static::db()->insert($sql);
            static::db()->free_statement();
            if ($this->{static::$id_field} !== -1)
            {
	      return true;
	    } else {
	      return false;
	    }
  }  
 
  //(D)elete - overloaded, we added Oracle specific ' AND ROWNUM=1'
  public function delete(){
		
	  $sql = "DELETE FROM ".static::$table_name." ";
	  $sql .= "WHERE ".static::$id_field."=". static::db()->escape_value($this->{static::$id_field});
	  $sql .= " AND ROWNUM=1";
          
	  static::db()->query($sql);
          static::db()->free_statement();
	  return (static::db()->affected_rows() == 1) ? true : false;
	  //after deletion from db object instance still exist and we can communicate
          //to user deletion event with additional info (properties of object which was deleted)
  	
  }
 	
  
 
   //overloaded with Oracle specific
   protected function prepared_attributes_insert() {
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
           if (static::$table_fields_types[$key] === "id"){
             $clean_attributes[$key]=static::$id_sequence.".nextval"; //oracle specific  
           }   
           else if (static::$table_fields_types[$key] === "number"){
             $clean_attributes[$key]= static::db()->escape_value($value); //oracle specific  
           }
           else if (static::$table_fields_types[$key] === "created"){
              $clean_attributes[$key] =  'sysdate';  //oracle specific
           } 
           else if (static::$table_fields_types[$key] === "date"){
              $clean_attributes[$key] =  "TO_DATE('".$value."','DD-MM-YY HH24:MI:SS')";  //oracle specific         
           } else {
	      $clean_attributes[$key] = "'".static::db()->escape_value($value)."'";
           }
	  }
	  return $clean_attributes;
   }
   
 
	
	
}

class_alias('SC1971_OracleCRUDObject', 'BaseCRUDObject');