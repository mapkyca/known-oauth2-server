<?php

namespace IdnoPlugins\OAuth2;

use Idno\Core\TokenProvider;

class OIDCToken {
    
    /**
     * When given a token, generate an OIDC token from it
     * @param \IdnoPlugins\OAuth2\Token $token
     * @return array
     */
    public static function generate(Token $token) : array {
        
        $nonce = new TokenProvider();
        
        $application = Application::getOne($token->key);
        if (empty($application)) {
            throw new OAuth2Exception(\Idno\Core\Idno::site()->language()->_("The Application for this token could not be found"));
        }
        
        $oidc = [
            'iss' => $application->getURL(), // Issuer site
            'sub' => $token->getOwner()->getID(), // Return the SUBJECT id
            'aud' => $token->key,    // Audience (client ID)
            'exp' => time() + $token->expires_in, // Expires in
            'iat' => time(), // Issue time
            'nonce' => $nonce->generateHexToken(4), // Add a nonce
        ];


        // Have we asked for email address?
        if (strpos($token->scope, 'email') !== false) {
            $oidc['email'] = $token->getOwner()->email;
        } 

        // Add some profile information if asked for
        if (strpos($token->scope, 'profile') !== false) {

            $oidc['preferred_username'] = $token->getOwner()->getHandle();
            $oidc['name'] = $token->getOwner()->getName();
            $oidc['picture'] = $token->getOwner()->getIcon();
            $oidc['profile'] = $token->getOwner()->getURL();
            
            if ($tz = $token->getOwner()->getTimezone()) {
                $oidc['zoneinfo'] = $tz;
            }
        }
        
        return $oidc;
    }
}