<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\FormField;

foreach(FormField::all() as $f) {
    $changed = false;
    if(is_string($f->options)) {
        $f->options = json_decode($f->options, true);
        $changed = true;
    }
    if(is_string($f->accepted_file_types)) {
        $f->accepted_file_types = json_decode($f->accepted_file_types, true);
        $changed = true;
    }
    if($changed) {
        $f->save();
        echo "Fixed field ID {$f->id}\n";
    }
}
echo "Done.\n";
