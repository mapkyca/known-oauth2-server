OAuth2 Server for Known
=======================

** EXPERIMENTAL **

This plugin provides an OAuth2 Server for Known, allowing users to create applications 
and allow clients to authenticate themselves for the API and website using an OAuth2 access token.

This plugin is an experimental basic implementation of the spec, so please, kick it around and report 
any issues you find!

Usage
-----

* Install in your plugins
* Create an application via settings
* Use the appropriate keys in your OAuth2 client

Support
-------
Currently the pluin supports:

* [x] response_type=code
* [x] grant_type=authorization_code
* [x] grant_type=refresh_token
* [x] state parameter validation
* [x] scope support

Example usage
-------------

To get a code:

```https://mysite.com/oauth2/authorise/?response_type=code&client_id=<your API Key>&redirect_uri=<path to your endpoint>```

You will be bounced to a login + authorisation page if necessary, so follow forwards.

As per the spec, you can omit the ```redirect_uri```, in which case the response will be a straight json encoded blob. If ```redirect_uri``` is specified you will be
forwarded to the endpoint, with appropriate parameters in the GET fields.


To get a token:

```https://mysite.com/oauth2/access_token/?grant_type=authorization_code&client_id=<your API Key>&redirect_uri=<path to your endpoint>```

You should get back a json encoded blob with an access token, expiry and refresh token.


To refresh a token:

If your access token has expired, you can update it with the refresh token.

```https://mysite.com/oauth2/access_token/?grant_type=refresh_token&refresh_token=<refresh token>```

Success will spit back a new access token, refresh token and expiry. It also results in the destruction of the original token.


Accessing the token
-------------------

On a successful login the token used will be saved to the current session in ```$_SESSION['oauth2_token']```, you can use this to check scope permissions, application ID and other details.

The scope granted to a given user is also saved against the user object in an array ```$user->oauth2[$client_id]['scope']```, which is also cross checked on login.

See
---
 * Author: Marcus Povey <http://www.marcus-povey.co.uk> 
 * OAuth2 Spec <https://tools.ietf.org/html/rfc6749>
