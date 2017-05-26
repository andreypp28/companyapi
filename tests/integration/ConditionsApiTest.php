<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ConditionsApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic functional test for Conditions.
     */
    public function testGetConditions()
    {
        factory(\WA\DataStore\Condition\Condition::class, 40)->create();

        $this->json('GET', 'conditions')
            ->seeJsonStructure([
                'data' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'packageId',
                            'nameCond',
                            'condition',
                            'value',
                            'created_at' => [
                                'date',
                                'timezone_type',
                                'timezone',
                            ],
                            'updated_at' => [
                                'date',
                                'timezone_type',
                                'timezone',
                            ],
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages',
                    ],
                ],
                'links' => [
                    'self',
                ],
            ]);
    }

    public function testGetConditionById()
    {
        $condition = factory(\WA\DataStore\Condition\Condition::class)->create();

        $res = $this->json('GET', 'conditions/'.$condition->id)
            ->seeJson([
                'type' => 'conditions',
                'packageId' => $condition->packageId,
                'nameCond' => $condition->name,
                'condition' => $condition->condition,
                'value' => $condition->value,
            ]);
    }

    public function testCreateCondition()
    {
        $this->json('POST', 'conditions',
            [
                'data' => [
                    'type' => 'conditions',
                    'attributes' => [
                        'packageId' => 1,
                        'nameCond' => 'ConditionNameLink',
                        'condition' => 'ConditionCondition',
                        'value' => 'ConditionValueLink',
                    ],
                ],
            ])
            ->seeJson([
                'type' => 'conditions',
                'packageId' => 1,
                'nameCond' => 'ConditionNameLink',
                'condition' => 'ConditionCondition',
                'value' => 'ConditionValueLink',
            ]);
    }

    public function testUpdateCondition()
    {
        $condition1 = factory(\WA\DataStore\Condition\Condition::class)->create();
        $condition2 = factory(\WA\DataStore\Condition\Condition::class)->create();

        $this->assertNotEquals($condition1->id, $condition2->id);

        $this->json('GET', 'conditions/'.$condition1->id)
            ->seeJson([
                'type' => 'conditions',
                'packageId' => $condition1->packageId,
                'nameCond' => $condition1->name,
                'condition' => $condition1->condition,
                'value' => $condition1->value,
            ]);

        $res = $this->json('PATCH', 'conditions/'.$condition1->id,
            [
                'data' => [
                    'type' => 'conditions',
                    'attributes' => [
                        'packageId' => $condition2->packageId,
                        'nameCond' => $condition2->name,
                        'condition' => $condition2->condition,
                        'value' => $condition2->value,
                    ],
                ],
            ])
            ->seeJson([
                'id' => $condition1->id,
                'packageId' => $condition2->packageId,
                'name' => $condition2->name,
                'condition' => $condition2->condition,
                'value' => $condition2->value,
            ]);
    }

    public function testDeleteConditionIfExists()
    {
        $condition = factory(\WA\DataStore\Condition\Condition::class)->create();
        $responseDel = $this->call('DELETE', 'conditions/'.$condition->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', 'conditions/'.$condition->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteConditionIfNoExists()
    {
        $responseDel = $this->call('DELETE', 'conditions/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
