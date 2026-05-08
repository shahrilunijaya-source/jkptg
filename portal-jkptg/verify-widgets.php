<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\LlmCostMeter;
use App\Filament\Widgets\VisitorChart;

echo "=== StatsOverview ===\n";
$ref = new ReflectionClass(StatsOverview::class);
$method = $ref->getMethod('getStats');
$method->setAccessible(true);
$widget = new StatsOverview;
foreach ($method->invoke($widget) as $stat) {
    $r = new ReflectionClass($stat);
    $labelP = $r->getProperty('label'); $labelP->setAccessible(true);
    $valueP = $r->getProperty('value'); $valueP->setAccessible(true);
    $descP = $r->getProperty('description'); $descP->setAccessible(true);
    $colP = $r->getProperty('color'); $colP->setAccessible(true);
    echo sprintf("  [%-9s] %-25s = %-20s (%s)\n",
        $colP->getValue($stat), $labelP->getValue($stat), $valueP->getValue($stat), $descP->getValue($stat));
}

echo "\n=== LlmCostMeter ===\n";
$ref = new ReflectionClass(LlmCostMeter::class);
$method = $ref->getMethod('getStats');
$method->setAccessible(true);
$widget = new LlmCostMeter;
foreach ($method->invoke($widget) as $stat) {
    $r = new ReflectionClass($stat);
    $labelP = $r->getProperty('label'); $labelP->setAccessible(true);
    $valueP = $r->getProperty('value'); $valueP->setAccessible(true);
    $descP = $r->getProperty('description'); $descP->setAccessible(true);
    $colP = $r->getProperty('color'); $colP->setAccessible(true);
    echo sprintf("  [%-9s] %-25s = %-25s (%s)\n",
        $colP->getValue($stat), $labelP->getValue($stat), $valueP->getValue($stat), $descP->getValue($stat));
}

echo "\n=== VisitorChart ===\n";
$ref = new ReflectionClass(VisitorChart::class);
$method = $ref->getMethod('getData');
$method->setAccessible(true);
$widget = new VisitorChart;
$data = $method->invoke($widget);
echo "Labels: " . implode(' | ', $data['labels']) . "\n";
echo "Values: " . implode(' | ', $data['datasets'][0]['data']) . "\n";
echo "Total visits last 7d: " . array_sum($data['datasets'][0]['data']) . "\n";
