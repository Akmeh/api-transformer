<?php
declare(strict_types=1);

namespace Tests\Unit;

use APITransformer\ItemTransformer;
use Codeception\TestCase\Test;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

/**
 * Class ItemTransformsTest
 * @package Tests\Unit
 */
class ItemTransformsTest extends Test
{

    /**
     * @test
     */
    public function transformAModelObjectShouldSucceed()
    {
        $faker = Factory::create();

        $data = [
            'id' => $faker->uuid,
            'name' => $faker->name,
            'comment' => $faker->text,
            'created_at' => $faker->dateTime,
            'updated_at' => $faker->dateTime,
        ];

        $model = \Mockery::mock(Model::class);
        $model->shouldReceive('find')->andReturnSelf();
        $model->shouldReceive('toArray')->andReturn($data);


        $dummy = new DummyItemTrasnformer();
        $result = $dummy->transform($model);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertEquals($result['data']['attributes']['name'], $data['name']);
        $this->assertEquals($result['data']['attributes']['comment'], $data['comment']);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function tryToSendAnInvalidModelWithoutIdShouldFail()
    {
        $faker = Factory::create();

        $data = [
            'name' => $faker->name,
            'comment' => $faker->text,
            'created_at' => $faker->dateTime,
            'updated_at' => $faker->dateTime,
        ];

        $model = \Mockery::mock(Model::class);
        $model->shouldReceive('find')->andReturnSelf();
        $model->shouldReceive('toArray')->andReturn($data);


        $dummy = new DummyItemTrasnformer();
        $dummy->transform($model);
    }


}


/**
 * Class DummyItemTrasnformer
 * @package Tests\Unit
 */
class DummyItemTrasnformer
{
    use ItemTransformer;

    public function transform(Model $model)
    {
        return $this->transformItem($model, new DummyTransformer(), 'test_item', ['test' => 'link']);
    }
}

/**
 * Class DummyTransformer
 * @package Tests\Unit
 */
class DummyTransformer extends TransformerAbstract
{
    public function transform(Model $model)
    {
        return $model->toArray();
    }


}

