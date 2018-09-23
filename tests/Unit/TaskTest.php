<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ModelTestCase;

class TaskTest extends ModelTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Task(), [
            'title','project_id'
        ]);
    }
}
