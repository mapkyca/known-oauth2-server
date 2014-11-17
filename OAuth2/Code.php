<?php

namespace IdnoPlugins\OAuth2 {


    class Code extends \Idno\Common\Entity {
	
	function __construct() {
	    
	    parent::__construct();
	    
	    $this->code = hash('sha256', mt_rand() . microtime(true));
	    $this->expires = strtotime('now + 10 minutes');
	}

	function saveDataFromInput() {

	    if (empty($this->_id)) {
		$new = true;
	    } else {
		$new = false;
	    }

	    if ($time = \Idno\Core\site()->currentPage()->getInput('created')) {
		if ($time = strtotime($time)) {
		    $this->created = $time;
		}
	    }

	    $this->setAccess('PRIVATE');
	    return $this->save();
	}

    }

}