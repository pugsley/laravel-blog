<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();

        // Wrap all tests in a transaction so we're not polluting the db
        DB::beginTransaction();
    }

    public function tearDown()
    {
        // If we're mocking objects with shouldReceive calls this
        // will trigger Mockery to check the calls have or haven't been made
        Mockery::close();
        DB::rollBack();
        parent::tearDown();
    }

}
