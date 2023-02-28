<?php

declare(strict_types=1);

namespace Brnbio\LaravelMysqlSpatial;

use Brnbio\LaravelMysqlSpatial\Schema\Builder;
use Brnbio\LaravelMysqlSpatial\Schema\Grammars\MySqlGrammar;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type as DoctrineType;
use Illuminate\Database\Grammar;
use Illuminate\Database\MySqlConnection as Connection;
use Illuminate\Database\Schema\MySqlBuilder;

/**
 * Class MysqlConnection
 *
 * @package Brnbio\LaravelMysqlSpatial
 */
class MysqlConnection extends Connection
{
    /**
     * @param $pdo
     * @param string $database
     * @param string $tablePrefix
     * @param array $config
     * @throws Exception
     */
    public function __construct($pdo, string $database = '', string $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);

        if (class_exists(DoctrineType::class)) {
            // Prevent geometry type fields from throwing a 'type not found' error when changing them
            $geometries = [
                'geometry',
                'point',
                'linestring',
                'polygon',
                'multipoint',
                'multilinestring',
                'multipolygon',
                'geometrycollection',
                'geomcollection',
            ];
            $dbPlatform = $this->getDoctrineConnection()->getDatabasePlatform();
            foreach ($geometries as $type) {
                $dbPlatform->registerDoctrineTypeMapping($type, 'string');
            }
        }
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return Grammar
     */
    protected function getDefaultSchemaGrammar(): Grammar
    {
        return $this->withTablePrefix(new MySqlGrammar());
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return MySqlBuilder|Builder
     */
    public function getSchemaBuilder(): MySqlBuilder|Builder
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Builder($this);
    }
}
