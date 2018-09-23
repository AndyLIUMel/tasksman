<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use App\Task;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ModelTestCase;

class ProjectTest extends ModelTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Project(), [
            'name','description'
        ]);
    }
    
    public function test_task_relation(){
        $m = new Project();
        $r = $m->tasks();
        $this->assertHasManyRelation($r, $m, new Task());
    }
}
