<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Page;
use App\Models\Service;
use App\Models\News;
use App\Models\Tender;
use App\Models\Form;
use App\Models\Faq;
use App\Models\ChatbotKnowledge;

echo "=== Phase 11 Search Verification ===\n\n";
echo "Driver: " . config('scout.driver') . "\n\n";

$models = [
    Page::class,
    Service::class,
    News::class,
    Tender::class,
    Form::class,
    Faq::class,
    ChatbotKnowledge::class,
];

foreach (['hubungi', 'pengambilan', 'tanah', 'bayaran', 'pajakan', 'land', 'tender'] as $q) {
    echo "Q: '$q'\n";
    $total = 0;
    foreach ($models as $cls) {
        $cnt = $cls::search($q)->get()->count();
        if ($cnt > 0) {
            echo "  " . class_basename($cls) . ": $cnt\n";
            $total += $cnt;
        }
    }
    echo "  TOTAL: $total\n\n";
}
