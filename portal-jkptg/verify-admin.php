<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Page;
use App\Models\Service;
use App\Models\News;
use App\Models\Tender;
use App\Models\Form;
use App\Models\Faq;
use App\Models\Cawangan;
use App\Models\ChatbotKnowledge;
use Filament\Facades\Filament;

$panel = Filament::getPanel('admin');
echo "Panel id={$panel->getId()} path={$panel->getPath()}\n";
echo "Plugins: ";
foreach ($panel->getPlugins() as $p) {
    echo $p->getId() . ' ';
}
echo "\n";

echo "Discovered resources:\n";
foreach ($panel->getResources() as $resourceClass) {
    $count = $resourceClass::getModel()::count();
    $label = $resourceClass::getPluralModelLabel();
    $hasTrans = in_array(\Filament\Resources\Concerns\Translatable::class, class_uses_recursive($resourceClass));
    echo sprintf("  %-50s %4d records  translatable=%s  label=%s\n",
        class_basename($resourceClass), $count, $hasTrans ? 'Y' : 'N', $label);
}

echo "\nNav groups:\n";
foreach ($panel->getNavigation() as $group => $items) {
    echo "  - " . ($group === '' ? '(default)' : $group) . " (" . count($items) . " items)\n";
}
