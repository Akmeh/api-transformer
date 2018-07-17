<?php
declare(strict_types=1);


namespace Tests\Unit;

use APITransformer\CollectionTransformer;
use Codeception\TestCase\Test;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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

    }
}


/**
 * Class DummyItemTrasnformer
 * @package Tests\Unit
 */
class DummyCollectionTransformer
{

    use CollectionTransformer;

    public function transform(Collection $collection)
    {
        $request = Request::createFromGlobals();
        $request->request->set('limit', '10');
        return $this->transformCollection(
            $collection, new Dummy2Transformer(), 'test_collection', $request
        );
    }
}

/**
 * Class DummyTransformer
 * @package Tests\Unit
 */
class Dummy2Transformer extends TransformerAbstract
{
    public function transform(Model $model)
    {
        return $model->toArray();
    }


}
