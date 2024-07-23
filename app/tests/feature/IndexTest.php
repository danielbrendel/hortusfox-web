<?php

/*
    Testcase for IndexController
*/

use PHPUnit\Framework\TestCase;

/**
 * Class IndexTest
 */
class IndexTest extends Asatru\Testing\Test
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testViewAuth()
    {
        $response = $this->request(Asatru\Testing\Test::REQUEST_GET, '/auth')->getResponse();

        $this->assertInstanceOf(Asatru\View\ViewHandler::class, $response);
    }

    /**
     * @return void
     */
    public function testLogin()
    {
        $response = $this->request(Asatru\Testing\Test::REQUEST_POST, '/login', [
            'POST' => [
                'email' => $_ENV['TEST_USER_EMAIL'],
                'password' => $_ENV['TEST_USER_PASSWORD']
            ]
        ])->getResponse();
        
        $this->assertInstanceOf(Asatru\View\RedirectHandler::class, $response);
    }
}
    