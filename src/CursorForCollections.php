<?php
declare(strict_types=1);

namespace APITransformer;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use League\Fractal\Pagination\Cursor;

/**
 * Trait CursorForCollections
 * @package APITransformer
 */
trait CursorForCollections
{

    /**
     * @var  string Default Collection to use
     */
    protected $defaultCollection;

    private $total = null;

    private function createQueryByRequest(Request $request)
    {
        if (empty($this->defaultCollection)) {
            throw new \InvalidArgumentException('The Default Collection need to be declared');
        }
        $query = DB::table($this->defaultCollection);

        $this->filter($request, $query);
        $this->exclude($request, $query);
        return $query;
    }

    /**
     * @param Request $request
     * @return int
     */
    public function getTotalFromRequest(Request $request) : int
    {
        if ($this->total === null) {
            $query = $this->createQueryByRequest($request);
            $this->total = $query->count();

        }
        return $this->total ?? 0;
    }

    /**
     * @param Request $request
     * @return Cursor
     */
    public function getCursorFromRequest(Request $request) : Cursor
    {
        if ($request->has('limit')) {
            $count = $request->get('limit') > 25
                ? 25
                : (int) $request->get('limit');

        } else {
            $count = 25;
        }

        $total = $this->getTotalFromRequest($request);
        $current = $request->has('offset') ? (int) $request->get('offset') : 1;
        $previous = $this->calculatePreviousPage($current);
        $next = $this->calculateNextPage($current, $total, $count);

        return new Cursor($current, $previous, $next, $count);
    }

    /**
     * @param int $current
     * @param int $total
     * @param int $count
     * @return int|null
     */
    private function calculateNextPage(int $current, int $total, int $count)
    {
        $pages = $total / $count;
        return $pages > $current ? ++ $current : null;
    }

    /**
     * @param int $current
     * @return int|null
     */
    private function calculatePreviousPage(int $current)
    {
        return $current !== 1 ? -- $current : null;
    }

    /**
     * Use the Filter parammeter to create a condition for each filter
     *
     * @param Request $request
     * @param Builder $query
     */
    private function filter(Request $request, Builder $query)
    {

        if ($request->has('filter')) {
            $filter = $request->get('filter');
            foreach ($filter as $key => $val) {

                if (filter_var($val, FILTER_VALIDATE_INT)) {
                    $val = (int) $val;
                }

                if ($val === 'true') {
                    $val = true;
                }
                if ($val === 'false') {
                    $val = false;
                }
                $query->where($key, $val);
            }
        }
    }

    /**
     * Use the Exclude parammeter to create a condition for exclude certain items
     *
     * @param Request $request
     * @param Builder $query
     */
    private function exclude(Request $request, Builder $query)
    {
        if ($request->has('exclude')) {
            $exclude = $request->get('exclude');
            foreach ($exclude as $key => $val) {
                $query->where($key, '<>', $val);
            }
        }
    }

}