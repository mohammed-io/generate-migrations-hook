<?php

namespace MohammedIO\Templates;

class MigrationFileTemplate
{
    public function generate(array $config)
    {
        $upContent = $config['upContent'] ?? '//';
        $downContent = $config['downContent'] ?? '//';
        $upContentHash = $config['upContentHash'] ?? '';

        $className = $config['name'];

        return "<?php

use Illuminate\Database\Migrations\Migration;
use TCG\Voyager\Database\DatabaseUpdater;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Database\Types\Type;

class $className extends Migration
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
        // upHash=$upContentHash
        $upContent
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $downContent
    }
}

        ";
    }
}
