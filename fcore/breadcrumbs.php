<?php


include_once('config.php');

const LINK_BRANDS_ORDER = 0;
const LINK_LINEUP_ORDER = 1;
const LINK_MODEL_ORDER = 2;
const LINK_GROUPS_ORDER = 3;
const LINK_ITEMS_ORDER = 4;


class Breadcrumbs{

	function  __construct(){	
		// array for storing items
		$this->data = [ array('name' => '', 'link' => ''), array('name' => '', 'link' => ''), array('name' => '', 'link' => '') , array('name' => '', 'link' => '') ];
	}
	
	
	function set( $act, $name, $link ){
		if (!$act){
			$this->data = [ array('name' => $name, 'link' => $link), array('name' => '', 'link' => ''), array('name' => '', 'link' => '') , array('name' => '', 'link' => '') ];
			return;
		}
		
		if ($act == LINK_LINEUP){
			$this->data[LINK_MODEL_ORDER] = array('name' => '', 'link' => '');
			$this->data[LINK_GROUPS_ORDER] = array('name' => '', 'link' => '');
			$this->data[LINK_LINEUP_ORDER] = array(
				'link' => $link,
				'name' => $name
				);
			
		}
		else if($act == LINK_MODEL){
			$this->data[LINK_GROUPS_ORDER] = array('name' => '', 'link' => '');	
			$this->data[LINK_MODEL_ORDER] = array(
				'link' => $link,
				'name' => $name
				);			
		
		}
		else if($act == LINK_GROUPS){
			$this->data[LINK_GROUPS_ORDER] = array(
				'link' => $link,
				'name' => $name
				);
		}
		
	}
	
	function get( $currAct ){
		/*	return array (assoc_array(link, name))    */
		
		$res = [];
		if (!$currAct){
			$res = []; //array_slice($this->data, 0, 1 + LINK_BRANDS_ORDER);
		}
		else if ($currAct == LINK_LINEUP){
			$res = array_slice($this->data, 0, LINK_LINEUP_ORDER);
		}
		else if ($currAct == LINK_MODEL){
			$res = array_slice($this->data, 0, LINK_MODEL_ORDER);
		}
		else if ($currAct == LINK_GROUPS){
			$res = array_slice($this->data, 0, LINK_GROUPS_ORDER);
		}
		else if ($currAct ==  LINK_ITEMS){
			$res = array_slice($this->data, 0,  LINK_ITEMS_ORDER);
		}
		else if ($currAct ==  LINK_SEARCH){
			$res = array_slice($this->data, 0,  LINK_ITEMS_ORDER);
		}
		
		return $res;
	}
	

}



?>


