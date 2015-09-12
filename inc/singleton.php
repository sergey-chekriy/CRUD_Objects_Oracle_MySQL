<?php

/* 
 * The MIT License
 *
 * Copyright 2015 Sergey Chekriy.
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

//almost universal singleton class, inherit from it and enjoy (any level of inheritance..i.e. grandchildren etc will still be 
//singletons, no issues with correct instance for class.
//singleton is ugly..I know.. with this implementation at least we have correct inheritance of signleton
//by use of array of instances..
abstract class SC1971_Singleton {
        private static $instances = array();
 
        
        public static function get_instance()
        {
            $cls = get_called_class(); // late-static-bound class name
            if (!isset(self::$instances[$cls])) {
                self::$instances[$cls] = new static;
            }
            return self::$instances[$cls];
        }
  
        //ensure singleton make __construct & __clone protected.. not possible to make new object of SC1971_Database from outside
        //this approach is not ideal, but singleton template doesn not have ideal implementation..        
        protected function __construct() {
            ;
        }
        
        //destrucror is public, even for singleton
        public function __destruct() {
            ;
        }
        
        private function __clone() {
            ;
        }
        
        public function __wakeup() {
           throw new Exception("SC1971_Singleton: cannot unserialize singleton");
        }
        
}
