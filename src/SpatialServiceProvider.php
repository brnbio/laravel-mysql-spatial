<?php

declare(strict_types=1);

namespace Brnbio\LaravelMysqlSpatial;

use Brnbio\LaravelMysqlSpatial\Connectors\ConnectionFactory;
use Brnbio\LaravelMysqlSpatial\Doctrine\Geometry;
use Brnbio\LaravelMysqlSpatial\Doctrine\GeometryCollection;
use Brnbio\LaravelMysqlSpatial\Doctrine\LineString;
use Brnbio\LaravelMysqlSpatial\Doctrine\MultiLineString;
use Brnbio\LaravelMysqlSpatial\Doctrine\MultiPoint;
use Brnbio\LaravelMysqlSpatial\Doctrine\MultiPolygon;
use Brnbio\LaravelMysqlSpatial\Doctrine\Point;
use Brnbio\LaravelMysqlSpatial\Doctrine\Polygon;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type as DoctrineType;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\DatabaseServiceProvider;

/**
 * Class SpatialServiceProvider
 *
 * @package Brnbio\LaravelMysqlSpatial
 */
class SpatialServiceProvider extends DatabaseServiceProvider
{
    /**
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });

        if (class_exists(DoctrineType::class)) {
            // Prevent geometry type fields from throwing a 'type not found' error when changing them
            $geometries = [
                'geometry'           => Geometry::class,
                'point'              => Point::class,
                'linestring'         => LineString::class,
                'polygon'            => Polygon::class,
                'multipoint'         => MultiPoint::class,
                'multilinestring'    => MultiLineString::class,
                'multipolygon'       => MultiPolygon::class,
                'geometrycollection' => GeometryCollection::class,
            ];
            $typeNames = array_keys(DoctrineType::getTypesMap());
            foreach ($geometries as $type => $class) {
                if ( !in_array($type, $typeNames)) {
                    DoctrineType::addType($type, $class);
                }
            }
        }
    }
}
