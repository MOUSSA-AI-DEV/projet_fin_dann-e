<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$indexes = DB::select('SHOW INDEXES FROM `references`');
foreach ($indexes as $index) {
    echo "Table: {$index->Table}, Non_unique: {$index->Non_unique}, Key_name: {$index->Key_name}, Column_name: {$index->Column_name}\n";
}
