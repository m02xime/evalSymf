<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class MooviesControllerTest extends WebTestCase
{

    public function testGetAll()
    {
        $client = static::createClient();
        $client->request('GET', '/api/getall');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetByOne()
    {
        $client = static::createClient();
        $client->request('GET', '/api/get/1');
        $this->assertResponseStatusCodeSame(200);
    }
}
