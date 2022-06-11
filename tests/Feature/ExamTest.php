<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ExamTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_exam()
    {
        $user = User::factory()->create();
        $subject = Subject::factory()->create();
        $data = [
            'name' => 'test exam title',
            'subject_id' => $subject->id,
            'user_id' => $user->id,
            'low' => 1,
            'medium' => 1,
            'high' => 1,
            "created_at" =>  \Carbon\Carbon::now(),
            "questions" => [
                ["id"=>1,"question"=>"1","level"=>"A","number"=>1,"answer"=>1,
                    "options"=>[
                        ["id"=>1,"option"=>"1","is_answer"=>true,"cols"=>"12","md"=>"4","number"=>1],
                        ["id"=>2,"option"=>"2","is_answer"=>false,"cols"=>"12","md"=>"4","number"=>2],
                        ["id"=>3,"option"=>"3","is_answer"=>false,"cols"=>"12","md"=>"4","number"=>3]]
                ],
                ["id"=> null, "question"=>"2","level"=>"M","number"=>2,"answer"=>2,
                    "options"=>[
                        [ "id"=>1, "option"=>"11","is_answer"=>false,"question_id"=>null,"cols"=>"12","md"=>"4","number"=>1],
                        ["id"=>2,"option"=>"22","is_answer"=>true,"question_id"=>null,"cols"=>"12","md"=>"4","number"=>2],
                        ["id"=>3,"option"=>"33","is_answer"=>false,"question_id"=>null,"cols"=>"12","md"=>"4","number"=>3]
                    ]
                ],
                [ "id"=>null,"question"=>"3","level"=>"B","number"=>3,"answer"=>3,
                    "options"=>[
                        ["id"=>1,"option"=>"111","is_answer"=>false,"question_id"=>null,"cols"=>"12","md"=>"4","number"=>1],
                        ["id"=>2,"option"=>"222","is_answer"=>false,"question_id"=>null,"cols"=>"12","md"=>"4","number"=>2],
                        ["id"=>3,"option"=>"333","is_answer"=>true,"question_id"=>null,"cols"=>"12","md"=>"4","number"=>3]
                    ]
                ]
            ]
        ];
        $response = $this->actingAs($user)
            ->post('/api/examenes',$data);
        $response->assertStatus(201);
    }

    public function test_request_empty_values()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user,'web')
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/examenes',[]);
        $response->assertStatus(422);
    }

    public function test_update_exam()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get('/api/examenes/1');
        $response->assertStatus(200);

    }
}
