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

class SC1971_OracleDatabase extends SC1971_Database{
	

        public  $stid; //Oracle specific
  
    

	public function open_connection() {
             
                        $connection_string = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)
            (HOST = ".DB_SERVER.")(PORT = ".DB_PORT.")))(CONNECT_DATA=(SID=".DB_NAME.")))";
                        $this->connection = oci_connect(DB_USER, DB_PASS, $connection_string);

 		
		
		if (!$this->connection) {
			die("Database connection failed: " . oci_error());
		}
	}

	public function close_connection() {
		if(isset($this->connection)) {
			oci_close($this->connection);
			unset($this->connection);
		}
	}
        
        public function free_statement() {
                oci_free_statement($this->stid);
        }

	public function query($sql) {
		$this->last_query = $sql;
                $this->stid = oci_parse($this->connection, $sql);
                $result = oci_execute($this->stid);
                $this->affected_rows_value = oci_num_rows($this->stid);
                $committed = oci_commit($this->connection); //$commited, result from commit currently not analyzed, space for improvement
		return $result; //true or false
	}
        
        
        public function insert($sql) {
               
                
		$this->last_query = $sql;
                $this->stid = oci_parse($this->connection, $sql);
                oci_bind_by_name($this->stid,":ID",$id,32);
                $result = oci_execute($this->stid);
                $this->affected_rows_value = oci_num_rows($this->stid);
                $committed = oci_commit($this->connection); //$commited, result from commit currently not analyzed, space for improvement
                
                if ($result) { 
                    return $id; 
                } else {
                    return -1;
                }
                
	}
	
	public function escape_value( $value ) {
	        return str_replace(array("'", "\0"), array("''", ''), $value);
	}
	
 	  
	
	public function fetch_array(){
                $arr = oci_fetch_array($this->stid,OCI_ASSOC);	
        
                return $arr;
	}
		
       
	
	
}


