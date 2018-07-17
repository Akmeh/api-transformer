<?php
declare(strict_types=1);

namespace APITransformer;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\TransformerAbstract;

/**
 * Trait ItemTransformer
 * @package APITransformer
 */
trait ItemTransformer
{

    /**
     * @param Model $model
     * @param TransformerAbstract $transformer
     * @param string $resource
     * @param array $meta
     * @return array
     */
    public function transformItem(
        Model $model, TransformerAbstract $transformer, string $resource, array $meta = []
    ) : array  {

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer(env('BASE_URL')));

        $resource = new FractalItem($model, $transformer, $resource);

        if (count($meta)) {
            $resource->setMeta($meta, $resource);
        }
        return $manager->createData($resource)->toArray();
    }

}
