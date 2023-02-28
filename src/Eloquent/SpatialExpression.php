<?php

declare(strict_types=1);

namespace Brnbio\LaravelMysqlSpatial\Eloquent;

use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Expression;

/**
 * Class SpatialExpression
 *
 * @package Brnbio\LaravelMysqlSpatial\Eloquent
 */
class SpatialExpression extends Expression
{
    /**
     * @param Grammar $grammar
     * @return mixed
     */
    public function getValue(Grammar $grammar)
    {
        return 'ST_GeomFromText(?, ?, "axis-order=long-lat")';
    }

    /**
     * @return mixed
     */
    public function getSpatialValue()
    {
        return $this->value->toWkt();
    }

    /**
     * @return mixed
     */
    public function getSrid()
    {
        return $this->value->getSrid();
    }
}
