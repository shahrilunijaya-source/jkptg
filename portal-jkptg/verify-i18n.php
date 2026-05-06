<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

function flatten(array $arr, string $prefix = ''): array {
    $out = [];
    foreach ($arr as $k => $v) {
        $key = $prefix === '' ? $k : "$prefix.$k";
        if (is_array($v)) {
            $out = array_merge($out, flatten($v, $key));
        } else {
            $out[$key] = $v;
        }
    }
    return $out;
}

$ms = flatten(require __DIR__ . '/lang/ms/messages.php');
$en = flatten(require __DIR__ . '/lang/en/messages.php');

$msKeys = array_keys($ms);
$enKeys = array_keys($en);

echo "=== Phase 10 i18n Audit ===\n\n";
echo "Total keys: MS=" . count($msKeys) . " EN=" . count($enKeys) . "\n\n";

$msOnly = array_diff($msKeys, $enKeys);
$enOnly = array_diff($enKeys, $msKeys);

echo "Keys only in MS (" . count($msOnly) . "):\n";
foreach ($msOnly as $k) echo "  $k\n";
echo "\nKeys only in EN (" . count($enOnly) . "):\n";
foreach ($enOnly as $k) echo "  $k\n";

echo "\nIdentical values (suspicious — likely untranslated EN):\n";
$dup = 0;
foreach ($ms as $k => $v) {
    if (isset($en[$k]) && is_string($v) && is_string($en[$k]) && trim($v) === trim($en[$k]) && mb_strlen($v) > 6) {
        echo "  [$k] = " . substr($v, 0, 60) . "\n";
        $dup++;
        if ($dup >= 30) { echo "  ... (truncated)\n"; break; }
    }
}

echo "\n=== Translatable content audit ===\n";
$models = [
    \App\Models\Page::class      => ['title', 'body'],
    \App\Models\Service::class   => ['name', 'summary'],
    \App\Models\News::class      => ['title', 'body'],
    \App\Models\Tender::class    => ['title', 'description'],
    \App\Models\Form::class      => ['name', 'description'],
    \App\Models\Faq::class       => ['question', 'answer'],
    \App\Models\Cawangan::class  => ['name', 'address'],
    \App\Models\ChatbotKnowledge::class => ['question', 'answer'],
    \App\Models\ChatbotQuickReply::class => ['label'],
];
foreach ($models as $cls => $fields) {
    $rows = $cls::all();
    $missing = [];
    foreach ($rows as $row) {
        foreach ($fields as $f) {
            $en = $row->getTranslation($f, 'en', false);
            if (empty($en)) $missing[$f] = ($missing[$f] ?? 0) + 1;
        }
    }
    $total = $rows->count();
    $name = class_basename($cls);
    if (count($missing) === 0) {
        echo sprintf("  %-22s %d rows  EN=complete\n", $name, $total);
    } else {
        echo sprintf("  %-22s %d rows  EN missing: ", $name, $total);
        foreach ($missing as $f => $n) echo "$f($n) ";
        echo "\n";
    }
}
