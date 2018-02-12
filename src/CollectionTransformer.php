<?php
declare(strict_types=1);

namespace APITransformer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\TransformerAbstract;

/**
 * Trait CollectionTransformer
 * @package APITransformer
 */
trait CollectionTransformer
{

    use CursorForCollections;

    /**
     * @param Collection $collection
     * @param TransformerAbstract $transformer
     * @param string $resource
     * @param Request $request
     * @return array
     */
    public function transformCollection(
        Collection $collection, TransformerAbstract $transformer, string $resource, Request $request
    ) : array  {

        $meta = [
            'total' => $collection->count(),
        ];

        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer(env('BASE_URL')));


        $resource = new FractalCollection($collection, $transformer, $resource);

        $resource->setCursor($this->getCursorFromRequest($request, $resource));

        $this->setMeta($meta, $resource);
        return $manager->createData($resource)->toArray();
    }

}
