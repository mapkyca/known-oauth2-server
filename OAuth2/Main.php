<?php

    namespace IdnoPlugins\OAuth2 {

        class Main extends \Idno\Common\Plugin {
	    
	   
            function registerPages() {
                \Idno\Core\site()->addPageHandler('/oauth2/authorise/', '\IdnoPlugins\OAuth2\Pages\Authorisation');
		\Idno\Core\site()->addPageHandler('/oauth2/access_token/', '\IdnoPlugins\OAuth2\Pages\Token');
		
		// Adding OAuth2 app page
		\Idno\Core\site()->addPageHandler('/account/oauth2/?', '\IdnoPlugins\OAuth2\Pages\Account\Applications');
		\Idno\Core\site()->template()->extendTemplate('account/menu/items', 'account/oauth2/menu');
            }

            function registerEventHooks() {
		
		
            }

        }

    }
