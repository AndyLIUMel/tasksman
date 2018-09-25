<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Http\Controllers;

use Mockery as m;
use App\Task;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\TaskController;

Class TaskControllerTest extends TestCase{
    
    protected $db;
    protected $taskMock;
    
    public function setUp(){
        $this->afterApplicationCreated(function () {
            $this->db = m::mock(
                Connection::class.'[select,update,insert,delete]',
                [m::mock(\PDO::class)]
            );
        
        
            $manager = $this->app['db'];
            $manager->setDefaultConnection('mock');
            $r = new \ReflectionClass($manager);
            $p = $r->getProperty('connections');
            $p->setAccessible(true);
            $list = $p->getValue($manager);
            $list['mock'] = $this->db;
            $p->setValue($manager, $list);
            $this->projectMock = m::mock(Task::class . '[update, delete]');
        });
        
        parent::setUp();
    
    }
    
    public function testStore(){
        $controller = new TaskController();
        $data = [
            'title' => 'test_title',
            'project_id' => 123456,
        ];
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));
        
        $this->db->getPdo()->shouldReceive('lastInsertId');
        $this->db->shouldReceive('insert')->once()
            /*->withArgs([
                'insert into "tasks" ("name", "updated_at", "created_at") values (?, ?, ?)',
                m::on(function ($arg) {
                    return is_array($arg) &&
                        $arg[0] == 'New Task';
                })
            ])*/
            ->andReturn(true);
        
        $response = $controller->store($request);

        $this->assertEquals($data['title'], json_decode($response)->title);
        $this->assertEquals($data['project_id'], json_decode($response)->project_id);
    }
    
    public function testMarkAsCompleted(){
        $controller = new TaskController();
        $observer = $this->getMockBuilder(Task::class)
                         ->setMethods(['update'])
                         ->getMock();
        $observer->expects($this->once())->method('update');
        $controller->markAsCompleted($observer);
        //$this->assertEquals('Task updated!',json_decode($controller->markAsCompleted($observer)));

    }
}