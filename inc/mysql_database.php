<?php

/* 
 * The MIT License
 *
 * Copyright 2015 Sergey Chekriy <sergey.chekriy@me.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once(LIB_PATH.DS."config_db.php");

class SC1971_MySQLDatabase extends SC1971_Database{

  private $magic_quotes_active; //MySQL specific
  private $real_escape_string_exists;//MySQL specific
  


	public function open_connection() {
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS,DB_NAME);
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
		
		if (!$this->connection) {
			die("Database connection failed: " . mysql_error());
		}
	}

	public function close_connection() {
		if(isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}

        //we should have all functions which are present in parent class, as we use them
        //in CRUDObject
        public function free_statement() {
               
        }
        
	public function query($sql) {
		$this->last_query = $sql;
		$this->stid = mysqli_query($this->connection,$sql);
                return ((!$this->stid) ? false : true);
	}
        
        public function insert($sql) {
		$this->last_query = $sql;
		$result = mysqli_query($this->connection,$sql);
		
                if ($result) { 
                    return $this->insert_id(); 
                } else {
                    return -1;
                }
                
	}
	
	public function escape_value( $value ) {
		if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysqli_real_escape_string($this->connection, $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
	
	  
	public function affected_rows() {
	    return mysqli_affected_rows($this->connection);
	}
	
	
	public function fetch_array(){
	    return mysqli_fetch_array($this->stid);	
	}
		
        
        //additional public function
        public function num_rows($result_set) {
	   return mysql_num_rows($result_set);
	}

        //additional private function
        private function insert_id() {
	    // get the last id inserted over the current db connection
	    return mysqli_insert_id($this->connection);
	}
}


