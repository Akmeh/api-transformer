<?php
declare(strict_types=1);

namespace APITransformer;

/**
 * Trait MetaTransformer
 * @package APITransformer
 */
trait Transformer
{

    use ItemTransformer, CollectionTransformer;
    
    /**
     * @param array $meta
     * @param $resource
     */
    protected function setMeta(array $meta, $resource)
    {
        if (count($meta)) {
            foreach ($meta as $key => $value) {
                $resource->setMetaValue($key, $value);
            }
        }
    }
}