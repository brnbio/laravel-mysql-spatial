<?php

use Brnbio\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GeometryModel.
 *
 * @property int                                          id
 * @property \Brnbio\LaravelMysqlSpatial\Types\Point      location
 * @property \Brnbio\LaravelMysqlSpatial\Types\LineString line
 * @property \Brnbio\LaravelMysqlSpatial\Types\LineString shape
 */
class GeometryModel extends Model
{
    use SpatialTrait;

    protected $table = 'geometry';

    protected $spatialFields = ['location', 'line', 'multi_geometries'];
}
