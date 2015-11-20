<?php

namespace AppBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of FOSUserProvider
 */
class FOSUserProvider extends BaseClass
{
    public function connect(UserInterface $user, UserResponseInterface $response) 
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        
        $service = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($service);
        
        $setterId = $setter . 'Id';
        $setterAccessToken = $setter . 'AccessToken';
        
        if (null !== $previousUser = $this->userManager->findUserBy([$property => $username])) {
            $previousUser->$setterId(null);
            $previousUser->$setterAccessToken(null);
            $this->userManager->updateUser($previousUser);
        }
        
        $user->$setterId($username);
        $user->$setterAccessToken($response->getAccessToken());
        $this->userManager->updateUser($user);
    }
    
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) 
    {
        $username = $response->getUsername();
        $realUserName = $response->getRealName();
        
        $user = $this->userManager->findUserBy([
            $this->getProperty($response) => $username,                
        ]);
        $service = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($service);
        $setterAccessToken = $setter . 'AccessToken';
        $setterId = $setter . 'Id';
        
        if (null === $user) {
            $user = $this->userManager->createUser();
            
            $user->$setterId($username);
            $user->$setterAccessToken($response->getAccessToken());
            
            $user->setUsername($realUserName);
            $user->setEmail(uniqid() . '@sfcrm.dev');
            $user->setPlainPassword(uniqid());
            $user->setEnabled(true);
            
            $this->userManager->updateUser($user);
            
            return $user;                    
        }
        
        $user = parent::loadUserByOAuthUserResponse($response);
        $user->$setterAccessToken($response->getAccessToken());
        
        return $user;        
    }
}
