<?php
class AdapterHandle extends Object
{var $Adapters=array();
function GetAdapter($type) {
	//echo $type;

	App::import($type);

	$_this =& AdapterHandle::getInstance();


	if ($_this){
			
		if (strpos($type, '.') !== false) {
			$value = explode('.', $type);
			$count = count($value);

			if($count===2){
				if(!in_array($value[1],array_keys($_this->Adapters))){

					$_this->Adapters[$value[1]]= & new $value[1]();
				}
				return $_this->Adapters[$value[1]];
			}
		}else{
			//echo var_dump(array_keys($_this->Adapters));
			if(!in_array($type,array_keys($_this->Adapters))){
				$_this->Adapters[$type]= & new $type();
			}
			return $_this->Adapters[$type];
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