<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Program;
use Stichoza\GoogleTranslate\GoogleTranslate;

$tr = new GoogleTranslate('ja');
$tr->setSource('id');

echo "Translating additional Program fields...\n";
foreach(Program::all() as $p) {
    foreach(['durasi', 'biaya'] as $attr) {
        $idVal = $p->getTranslation($attr, 'id');
        if (!$idVal) {
            $idVal = $p->getRawOriginal($attr);
            if ($idVal && strpos($idVal, '{') === 0) {
               $decoded = json_decode($idVal, true);
               $idVal = $decoded['id'] ?? $idVal;
            }
            $p->setTranslation($attr, 'id', $idVal);
        }
        
        if ($idVal && !$p->getTranslation($attr, 'jp', false)) {
            try {
                $jpVal = $tr->translate($idVal);
                $p->setTranslation($attr, 'jp', $jpVal);
                echo "Translated {$attr} for program {$p->id} to Japanese.\n";
            } catch (\Exception $e) {
                echo "Error translating {$attr} for program {$p->id}: " . $e->getMessage() . "\n";
            }
        }
    }
    $p->save();
}
echo "Done!\n";
