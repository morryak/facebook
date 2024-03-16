<?php

declare(strict_types=1);

namespace App\AppBundle\Doctrine\Migrations;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

class IgnoreFksListener
{
    /**
     * This listener is called when the schema has been generated (from mapping data of entities).
     *
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();

        foreach ($schema->getTables() as $table) {
            $fks = $table->getForeignKeys();

            foreach ($fks as $fk) {
                $table->removeForeignKey($fk->getName());
            }
        }
    }
}
