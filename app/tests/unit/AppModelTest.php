<?php

/*
    Testcase for AppModel
*/

use PHPUnit\Framework\TestCase;

/**
 * Class AppModelTest
 */
class AppModelTest extends Asatru\Testing\Test
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
    public function testQuery()
    {
        $data = AppModel::query('workspace');
        $this->assertNotNull($data);
    }
}
    