<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * AgreementLifeControllerTest
 */
class AgreementLifeControllerTest extends WebTestCase
{
    private function login($username = 'admin1@sfcrm.dev', $password = 'demo')
    {
        $client = self::createClient();
        $crawler = $client->request('get', '/login');
        $form = $crawler->selectButton('Zaloguj')->form( array('_username' => $username, '_password' => $password));
        $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        return $client;
        
    }
    
    public function testAgreementLifeList()
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/agreement/life/list');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Pusta strona', $client->getResponse()->getContent());
        $this->assertContains('Pusta strona', $crawler->filter('.content-header h1')->text());
        $this->assertGreaterThan(0, $crawler->filter('h4')->count());
        
    }
    
}
