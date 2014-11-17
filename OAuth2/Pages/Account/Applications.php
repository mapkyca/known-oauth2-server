<?php

namespace IdnoPlugins\OAuth2\Pages\Account {

    class Applications extends \Idno\Common\Page {

	function getContent() {
	    $this->gatekeeper();

	    $apps = \IdnoPlugins\OAuth2\Application::get(array(), array(), 99999, 0); // TODO: make this more complete / efficient

	    $t = \Idno\Core\site()->template();
	    $t->body = $t->__(array('applications' => $apps))->draw('account/oauth2');
	    $t->title = 'Manage OAuth2 Applications';
	    $t->drawPage();
	}

	function postContent() {
	    
	    $this->gatekeeper(); 

                $action = $this->getInput('action');

                switch ($action) {
		    case 'create' :
			break;
		    case 'delete' :
			break;
		}
	}

    }

}