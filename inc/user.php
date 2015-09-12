<?php


class User extends BaseCRUDObject {

    
    //db table name
    protected static $table_name="example_user";
    
    //all fields of db table
    protected static $table_fields=array('user_id', 'first_name', 'last_name', 'contact_email' );
    
    //db table types:
    //full list of types:
    //id - primary key
    //text - text
    //date - datetime
    //created - gets system datetime when object is created (saved into db)
    //number - number
    //those types are used mainy for insert (see code for full understanding)
    protected static $table_fields_types=array( 'user_id'=>'id', 'first_name'=>'text', 
                                                'last_name'=>'text', 'contact_email'=>'text',
                                                'contact_email'=>'text' );
  
    protected static $id_sequence = "example_user_id_seq";
   
    //our table primary key is called 'user_id' and we need to override class property $id_field 
    protected static $id_field='user_id'; 
  
    
    //all attributes should have exactly the same names as in table and $table_fields, $table_fields_types
    //see object_from_table_row() from base class for understanding
    public $user_id;
    public $first_name;
    public $last_name;
    public $contact_email;
    

    //"new" is a reserved word so we use "make" (or "build")
    public static function make($first_name, 
                                $last_name,
                                $contact_email
                                ) {
        
	   if(!empty($first_name) && !empty($last_name) && !empty($contact_email)) {
		    $user = new User();
		    $user->first_name = $first_name;
                    $user->last_name = $last_name;
                    $user->contact_email = $contact_email;
		   
		    return $user;
            } else {
                    return false;
            }
    }
  
  
    
    
  

}

