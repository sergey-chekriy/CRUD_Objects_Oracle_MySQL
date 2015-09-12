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

require_once(LIB_PATH.DS.'mysql_database.php');

abstract class SC1971_MySQLCRUDObject extends SC1971_CRUDObject  {

  // make use of late static binding
  // to understand fully logic, see example, class Person inherited from OracleCRUDObject
  // and script person_crud.php  
    
  protected static $table_name="null";
  protected static $table_fields = array();
  protected static $table_fields_types = array(); //to make cleaner insert queries (Oracle specific)
  protected static $id_sequence = "null";      //name of sequence (Oracle specific)
  
  protected static $db_class='SC1971_MySQLDatabase';
	
  // public CRUD: (C)reate (R)ead (U)pdate (D)elete methods
  // each OracleCRUDObject is basically correspond to Oracle table row
  // object can create itself and store in db, read itself (or array of OracleCRUDObjects) from db,
  // update itself in db and delete itself from db
  // design restriction: 
  //     object attributes (see Person class) should be named exactly as corresponding Oracle table fields
  //     see object_from_table_row() method for undertanding why it should be so.
  
  
 public function create(){

            $id=0;
            $attributes = $this->prepared_attributes_insert();
		
	    $sql = "INSERT INTO ".static::$table_name." (";
	    $sql .= join(",",array_keys($attributes));
	    $sql .= ") VALUES ('";           
            $sql .= join("','",array_values($attributes));		    
            $sql .= "') ";
           
	    $this->{static::$id_field} = static::db()->insert($sql);
            static::db()->free_statement();
            if ($this->{static::$id_field} !== -1)
            {
	      return true;
	    } else {
	      return false;
	    }
  } 
  
  
 
  //(D)elete - overloaded, we added MySQL specific ' LIMIT 1'
  public function delete(){
		
	  $sql = "DELETE FROM ".static::$table_name." ";
	  $sql .= "WHERE ".static::$id_field."=". static::db()->escape_value($this->{static::$id_field});
	  $sql .= " LIMIT 1";
          
	  static::db()->query($sql);
          static::db()->free_statement();
	  return (static::db()->affected_rows() == 1) ? true : false;
	  //after deletion from db object instance still exist and we can communicate
          //to user deletion event with additional info (properties of object which was deleted)
  	
  }
 	
  
 
   
 
	
	
}


class_alias('SC1971_MySQLCRUDObject', 'BaseCRUDObject');