<?php

/**
 * Class/file loader and path management.
 *
 *
 */
class AdapterHandle extends Object {

	var $Adapters=array();

	/**
	 * Returns an array of filenames of PHP files in the given directory.
	 *
	 * @param string $path Path to scan for files
	 * @param string $suffix if false, return only directories. if string, match and return files
	 * @return array  List of directories or files in directory
	 * @access private
	*/
	function GetAdapter($type) {


		App::import($type);
			
		$_this =& App::getInstance();
			
		if ($_this){
			if (strpos($class, '.') !== false) {
				$value = explode('.', $class);
				$count = count($value);
					
				if($count===2){
					if(!in_array($value[1],key($_this->$Adapters))){
						$_this->$Adapters[$value[1]]= & new $value[1]();
					}
					return $_this->$Adapters[$value[1]];
				}
			}else{
				if(!in_array($type,key($_this->$Adapters))){
					$_this->$Adapters[$type]= & new $type();
				}
				return $_this->$Adapters[$type];
			}


		}

	}



	function &getInstance() {
		static $instance;
		if (!$instance) {
			$instance =& new AdapterHandle();
			//$instance[0]->__map = (array)Cache::read('file_map', '_cake_core_');
		}
		return $instance;
	}


}