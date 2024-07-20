<?php

/*
    Testcase for Test Example
*/

use PHPUnit\Framework\TestCase;

/**
 * This class holds your test methods
 */
class ExampleTest extends Asatru\Testing\Test
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
    public function testIndex()
    {
        $response = $this->request(Asatru\Testing\Test::REQUEST_GET, '/auth')->getResponse();
        $this->assertInstanceOf(Asatru\View\ViewHandler::class, $response);
    }
}
    