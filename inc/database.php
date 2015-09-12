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

require_once(LIB_PATH.DS.'singleton.php');


//abstract database class (singleton)
//all specific classes like OracleDatabase, MySQLDatabase are inherited from it
abstract class SC1971_Database extends SC1971_Singleton{

        protected $connection;

        protected $affected_rows_value;

        //keeps last query, for us to know which query crashed system :-), get_last_query() to access..    
        protected $last_query; 

        //if you inherit from Singleton and override constructor
        //keep constructor protected, as we need to have singleton functionality
        protected function __construct() {
            $this->open_connection();
        }
        
        //destrucror is public, even for singleton
        public function __destruct() {
             $this->close_connection();
        }
        
        
	public function open_connection() {
             
	}

	public function close_connection() {
		
	}
        
        public function free_statement() {
               
        }

	public function query($sql) {
		
	}
        
        
        public function insert($sql) {
                
	}
	
	public function escape_value( $value ) {
	        return $value;
	}
	
 	  
	public function affected_rows() {
                return $this->affected_rows_value;
	}
	
	
	public function fetch_array(){

        }
		
        public function get_connection(){
            return $this->connection;
        }
	
        public function get_last_query(){
            return $this->last_query;
        }
        
        
}

