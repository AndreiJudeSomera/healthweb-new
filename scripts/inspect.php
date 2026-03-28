<?php
require __DIR__ . '/../vendor/autoload.php';
try {
  $r = new ReflectionMethod(\App\Http\Controllers\PatientRecordController::class, 'store');
  foreach ($r->getParameters() as $p) {
    $t = $p->getType();
    echo($t ? $t->getName() : 'none') . PHP_EOL;
  }
} catch (Throwable $e) {
  echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
