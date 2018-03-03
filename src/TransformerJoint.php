<?php
declare(strict_types=1);

namespace APITransformer;

use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item as FractalItem;


/**
 * Class     TransformerJoint
 *
 * When you need to include transformer inside of your transformer
 *
 * @package APITransformer
 */
class TransformerJoint
{

    /**
     * @param $model
     * @param $transformer
     * @return array
     */
    public static function transform($model, $transformer)
    {
        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $resource = new FractalItem($model, $transformer);
        return $manager->createData($resource)->toArray();
    }
}
