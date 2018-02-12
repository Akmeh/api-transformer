# API Transformer

Using Laravel/Lumen as a framework and Fract PHP League I need a fast way to create controllers giving them proper format 


## How to use it on your controllers

```
<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use APITransformer\Transformer;
use App\Country;
use App\Transformers\CountryTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class CountriesController
 * @package App\Http\Controllers
 */
class CountriesController extends  Controller
{

    protected $defaultCollection = 'countries';

    use Transformer;

    public function index(Request $request)
    {
        $collection = Country::all();
        $response = $this->transformCollection(
            $collection, new CountryTransformer(), 'countries', $request
        );

        $httpCode = $collection->count() ? Response::HTTP_OK : Response::HTTP_NO_CONTENT;
        return response()->json($response, $httpCode);
    }
}


```