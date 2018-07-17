<?php
declare(strict_types=1);


namespace Tests\Unit;

use APITransformer\CollectionTransformer;
use Codeception\TestCase\Test;
use Faker\Factory;
use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class CollectionTransformTest
 * @package Tests\Unit
 */
class CollectionTransformTest extends Test
{

    /**
     * @test
     */
    public function validCollectionShouldReturnValid()
    {

        $faker = Factory::create();
        $data = [
            [
                'id' => $faker->uuid,
                'name' => $faker->name,
                'comment' => $faker->text,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ],
            [
                'id' => $faker->uuid,
                'name' => $faker->name,
                'comment' => $faker->text,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ],
            [
                'id' => $faker->uuid,
                'name' => $faker->name,
                'comment' => $faker->text,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime,
            ],
        ];

        $collection = Collection::make($data);


        /*        $model = \Mockery::mock(Model::class);
                $model->shouldReceive('find')->andReturnSelf();
                $model->shouldReceive('toArray')->andReturn($data);
        */

        $dummy = new DummyCollectionTransformer();
        $result = $dummy->transform($collection);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertEquals($result['data']['attributes']['name'], $data['name']);
        $this->assertEquals($result['data']['attributes']['comment'], $data['comment']);


    }
}


/**
 * Class DummyItemTrasnformer
 * @package Tests\Unit
 */
class DummyCollectionTransformer
{

    use CollectionTransformer;

    public function transform(Model $model)
    {
        return $this->transformCollection(
            $model, new DummyTransformer(), 'test_collection', ['test' => 'collection']
        );
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
