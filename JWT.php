<?php


namespace IdnoPlugins\OAuth2 {

    use Idno\Entities\User;
    use Idno\Core\Webservice;
    use Idno\Core\Idno;
    
    /**
     * Generate a JWT
     */
    class JWT {
        
        private $user;
        
        private $expiry;
        
        public function __construct(User $user, int $expiry) {
            $this->user = $user;
            $this->expiry = $expiry;
        }
       
        public function __toString() {
            
            $header = json_encode([
                'typ' => 'JWT',
                'alg' => 'HS256'
            ]);

            $payload = json_encode([
                'user_id' => $this->user->getOwner()->getID(),
                'role' => 'user',
                'exp' => $this->expiry
            ]);
            
            // Encode Header
            $base64UrlHeader = trim(Webservice::base64UrlEncode($header), ',');

            // Encode Payload
            $base64UrlPayload = trim(Webservice::base64UrlEncode($payload), ',');

            // Create Signature Hash
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, Idno::site()->config()->site_secret, true);

            // Encode Signature to Base64Url String
            $base64UrlSignature = trim(Webservice::base64UrlEncode($signature), ',');
            
            return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
            
        }
    }
    
}