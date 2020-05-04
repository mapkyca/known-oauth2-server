<?php

namespace IdnoPlugins\OAuth2\Tests;

use Firebase\JWT\JWT;

class OIDCTokenTest extends \Tests\KnownTestCase {
    
    public function oidcTokenProvider() {
        
        $application = \IdnoPlugins\OAuth2\Application::newApplication('test application');
        $application->save();
        
        $token = new \IdnoPlugins\OAuth2\Token();
        $token->setOwner($this->user());
        $token->scope = 'openid email profile';
        $token->key = $application->key;
        
        $application->delete(); // fudge it for the persistence check
        
        return [
            'Test OIDC' => [
                \IdnoPlugins\OAuth2\OIDCToken::generate($token),
                $application->getPublicKey(),
                $application->getPrivateKey()
            ]
        ];
        
        
    }
    
    /**
     * Test to see if we have a token that can be signed and validated
     * @param type $oidc
     * @param type $pubkey
     * @param type $prikey
     * @dataProvider oidcTokenProvider
     */
    public function testSigning($oidc, $pubkey, $prikey) {
        
        // Signing
        $jwt = JWT::encode($oidc, $prikey, 'RS256');
        
        $this->assertNotEmpty($jwt);
        
        // Validate
        $decoded = JWT::decode($jwt, $pubkey, ['RS256']);
        
        $this->assertNotEmpty($decoded);
        
        // Check equality
        foreach ($oidc as $key => $value) {
            $this->assertEquals($decoded->$key, $value);
        }
    }
    
    
    
}