<?php
/**
 * Created by PhpStorm.
 * User: seraph
 * Date: 3/24/2018
 * Time: 2:39 PM
 */

namespace App\Filters;
use Illuminate\Http\Request;

class Filters
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;
    /**
     * Create a new ThreadFilters instance.
     *
     * @param Request $request
     */
    protected $filters =[];

    /**
     * ThreadFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

    }
    /**
     * Apply the filters.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach($this->getFilters() as $filter=>$value ){
          if(method_exists($this, $filter)){
              $this->$filter($value);
          }
               }

        return $this->builder;
    }
    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters(){
       return $this->request->only($this->filters);
    }

}