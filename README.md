##Example of Auth0 User Authorization in Symfony
This example project of Symfony provides the implementation of Authorization and Authentication of Auth0's user in
Symfony 4 api endpoints.

###Requirements
* PHP 7+
* Symfony 4+
* [Auth0 Jwt Bundle](https://github.com/auth0/jwt-auth-bundle)
* [Symfony Security Bundle](https://github.com/symfony/security-bundle)

###Installation
To implement Authorization and Authentication, install the following dependencies:

**Auth0 Jwt Bundle**

`composer require auth0/jwt-auth-bundle:"~3.0"`

**Symfony Security Bundle**

`composer require symfony/security-bundle`

**Sensio Framework-Extra Bundle**

`composer require sensio/framework-extra-bundle`

###Documentation
For more information on how to Authorize Auth0 user, follow [this article](). Here, I will start from decoding the
fetched Access Token.

###Decode Access Token
Inject the Access Token to User Provider to decode it or get user profile. User Provider is a class that decode the 
Access token. To create User Provider class, **install Auth0 Jwt Bundle**. The User Provider class decodes the token for
those users who are assigned with certain roles which is later used in the route while accessing api endpoints.

Configure the User Provider class to decode the token at Symfony's file **config/packages/security.yaml** under 
**providers** key.

```
security:
      providers:
           a0:
                id:
                  a0_user_provider
```
Use Provider class under **providers** key **a0_user_provider** as service. Configure the provider class id at 
config/services.yaml
```
parameters:
services:
   a0_user_provider:
       class: App\Security\A0UserProvider
       arguments: ["@jwt_auth.auth0_service"]

```
To check whether th Access Token is issued by authorized issuer or not, configure at **config/packages/framework.yaml**
```
jwt_auth:
   domain: '%env(AUTH0_DOMAIN)%'
   authorized_issuer: https://%env(AUTH0_DOMAIN)%/
   api_identifier: '%env(AUTH0_AUDIENCE)%'

```
The final step is to access api endpoints.

###Access Api Endpoints
Before, accessing the api endpoints, configure **security.yml** file file under the key security’s firewall at 
**config/packages/security.yaml** for the url pattern **/api**. 
```
security:
   firewalls:
       secured_area:
             pattern: ^/api
             stateless: true
             simple_preauth:
               authenticator: jwt_auth.jwt_authenticator
```
Finally, authorize the api endpoints for url /api/hello using @Route annotation. **Annotations** are metadata that can 
be embedded in source code. The role **ROLE_AUTH0_AUTHENTICATED** for which the Access Token was decoded earlier is now 
used as **@IsGranted** annotation.

```
/**
* @Route("/api/hello", name="hello")
* @IsGranted("ROLE_AUTH0_AUTHENTICATED")
*/
public function hello()
{
   return $this->json([ 'message' => 'Hello World!' ]);
}
```
The user role **ROLE_AUTH0_AUTHENTICATED** in which Access Token is assigned can access the above api endpoints 
otherwise the user is forbidden.