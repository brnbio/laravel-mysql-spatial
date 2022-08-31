<?php

use Brnbio\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WithSridModel.
 *
 * @property int                                          id
 * @property \Brnbio\LaravelMysqlSpatial\Types\Point      location
 * @property \Brnbio\LaravelMysqlSpatial\Types\LineString line
 * @property \Brnbio\LaravelMysqlSpatial\Types\LineString shape
 */
class WithSridModel extends Model
{
    use SpatialTrait;

    protected $table = 'with_srid';

    protected $spatialFields = ['location', 'line'];

    public $timestamps = false;
}
