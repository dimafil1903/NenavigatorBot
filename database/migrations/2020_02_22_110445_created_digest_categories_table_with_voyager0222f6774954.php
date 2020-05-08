<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Database\Types\Type;

class CreatedDigestCategoriesTableWithVoyager0222f6774954 extends Migration
{
    public function __construct()
    {
        Type::registerCustomPlatformTypes();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Generated only to work with Voyager
        // upHash=9dff37fb8032578bdea2a5dfd3f9c53c
        SchemaManager::createTable(
            [
                "name" => "digest_categories",
                "oldName" => "digest_categories",
                "columns" => [
                    "0" => [
                        "name" => "id",
                        "type" => [
                            "name" => "integer",
                            "category" => "Numbers",
                            "default" => [
                                "type" => "number",
                                "step" => "any",
                            ],
                        ],
                        "default" => null,
                        "notnull" => "1",
                        "length" => null,
                        "precision" => "10",
                        "scale" => "0",
                        "fixed" => "",
                        "unsigned" => "1",
                        "autoincrement" => "1",
                        "columnDefinition" => null,
                        "comment" => null,
                        "oldName" => "id",
                        "null" => "NO",
                        "extra" => "auto_increment",
                        "composite" => "",
                    ],
                    "1" => [
                        "name" => "name",
                        "type" => [
                            "name" => "varchar",
                            "category" => "Strings",
                        ],
                        "default" => null,
                        "notnull" => "",
                        "length" => null,
                        "precision" => "10",
                        "scale" => "0",
                        "fixed" => "",
                        "unsigned" => "",
                        "autoincrement" => "",
                        "columnDefinition" => null,
                        "comment" => null,
                        "oldName" => "name",
                        "null" => "YES",
                        "extra" => "",
                        "composite" => "",
                    ],
                    "2" => [
                        "name" => "created_at",
                        "type" => [
                            "name" => "timestamp",
                            "category" => "Date and Time",
                        ],
                        "default" => null,
                        "notnull" => "",
                        "length" => null,
                        "precision" => "10",
                        "scale" => "0",
                        "fixed" => "",
                        "unsigned" => "",
                        "autoincrement" => "",
                        "columnDefinition" => null,
                        "comment" => null,
                        "oldName" => "created_at",
                        "null" => "YES",
                        "extra" => "",
                        "composite" => "",
                    ],
                    "3" => [
                        "name" => "updated_at",
                        "type" => [
                            "name" => "timestamp",
                            "category" => "Date and Time",
                        ],
                        "default" => null,
                        "notnull" => "",
                        "length" => null,
                        "precision" => "10",
                        "scale" => "0",
                        "fixed" => "",
                        "unsigned" => "",
                        "autoincrement" => "",
                        "columnDefinition" => null,
                        "comment" => null,
                        "oldName" => "updated_at",
                        "null" => "YES",
                        "extra" => "",
                        "composite" => "",
                    ],
                    "4" => [
                        "name" => "deleted_at",
                        "type" => [
                            "name" => "timestamp",
                            "category" => "Date and Time",
                        ],
                        "default" => null,
                        "notnull" => "",
                        "length" => null,
                        "precision" => "10",
                        "scale" => "0",
                        "fixed" => "",
                        "unsigned" => "",
                        "autoincrement" => "",
                        "columnDefinition" => null,
                        "comment" => null,
                        "oldName" => "deleted_at",
                        "null" => "YES",
                        "extra" => "",
                        "composite" => "",
                    ],
                ],
                "indexes" => [
                    "0" => [
                        "name" => "primary",
                        "oldName" => "primary",
                        "columns" => [
                            "0" => "id",
                        ],
                        "type" => "PRIMARY",
                        "isPrimary" => "1",
                        "isUnique" => "1",
                        "isComposite" => "",
                        "flags" => [
                        ],
                        "options" => [
                        ],
                        "table" => "digest_categories",
                    ],
                ],
                "primaryKeyName" => "primary",
                "foreignKeys" => [
                ],
                "options" => [
                    "create_options" => [
                    ],
                    "collate" => "utf8mb4_unicode_ci",
                    "charset" => "utf8mb4",
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('digest_categories');
    }
}

