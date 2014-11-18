<?php

namespace IdnoPlugins\OAuth2\Pages {

    class Token extends \Idno\Common\Page {

	function getContent() {

	    try {
		try {
		    $scope = $this->getInput('scope');
		    $state = $this->getInput('state');
		    $code = $this->getInput('code');
		    $grant_type = $this->getInput('grant_type');
		    $client_id = $this->getInput('client_id');
		    $redirect_uri = $this->getInput('redirect_uri');

		    if (!$grant_type)
			throw new \IdnoPlugins\OAuth2\OAuth2Exception("Required parameter grant_type is missing!", 'invalid_request', $state);
		    if (!$client_id)
			throw new \IdnoPlugins\OAuth2\OAuth2Exception("Required parameter client_id is missing!", 'invalid_request', $state);

		    switch ($grant_type) {

			case 'authorization_code':
			default:

			    // Check Application
			    if (!\IdnoPlugins\OAuth2\Application::getOne(['key' => $client_id]))
				throw new \IdnoPlugins\OAuth2\OAuth2Exception("I have no knowledge of the application identified by $client_id", 'unauthorized_client', $state);

			    // Check code 
			    if ((!($code_obj = \IdnoPlugins\OAuth2\Code::getOne(['code' => $code]))) || ($code_obj->expires < time()))
				throw new \IdnoPlugins\OAuth2\OAuth2Exception("Sorry, unknown or expired code!", 'invalid_grant', $state);

			    // Check state on object
			    if ($code_obj->state) {
				if ($code_obj->state != $state)
				    throw new \IdnoPlugins\OAuth2\OAuth2Exception("Invalid state given", 'access_denied', $state);
			    }

			    // Check redirect
			    if ($code_obj->redirect_uri) {
				if ($code_obj->redirect_uri != $redirect_uri)
				    throw new \IdnoPlugins\OAuth2\OAuth2Exception("Sorry, redirect_uri doesn't match the one given before!", 'access_denied', $state);
			    }

			    // OK so far, so generate new token
			    $token = new \IdnoPlugins\OAuth2\Token();
			    
			    // Add state and scope variables
			    $token->state = $state;
			    $token->scope = $code_obj->scope;

			    if (!$token->save())
				throw new \IdnoPlugins\OAuth2\OAuth2Exception("Server problem, couldn't generate new tokens. Try again in a bit...", 'invalid_grant', $state);

			    echo json_encode($token);
		    }
		} catch (\IdnoPlugins\OAuth2\OAuth2Exception $oa2e) {
		    $this->setResponse($oa2e->http_code);
		    echo json_encode($oa2e->jsonSerialize());
		}
	    } catch (\Exception $e) {
		$this->setResponse(400);

		echo json_encode([
		    'error' => 'invalid_request',
		    'error_description' => $e->getMessage()
		]);
	    }
	}

	function postContent() {
	    $this->getContent();
	}

    }

}