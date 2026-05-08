<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Page;
use App\Models\Service;
use App\Models\News;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotSetting;
use App\Models\Setting;
use Illuminate\Support\Facades\App;

App::setLocale('ms');
$page = Page::where('slug', 'mengenai-jkptg')->first();
echo "BM Page: " . $page->title . "\n";
echo "BM Body: " . strip_tags($page->body) . "\n\n";

App::setLocale('en');
$page->refresh();
echo "EN Page: " . $page->title . "\n";
echo "EN Body: " . strip_tags($page->body) . "\n\n";

App::setLocale('ms');
$svc = Service::where('slug', 'pengambilan-tanah')->first();
echo "Service: " . $svc->name . " | category=" . $svc->category . " | " . $svc->processing_days . " days\n";
echo "Process steps (BM): " . implode(' -> ', $svc->process_steps) . "\n\n";

$news = News::where('important', true)->orderByDesc('published_at')->first();
echo "Important news: [" . $news->type . "] " . $news->title . "\n\n";

$kb = ChatbotKnowledge::where('slug', 'kb-pengambilan-tempoh')->first();
echo "KB question: " . $kb->question . "\n";
echo "KB answer: " . $kb->answer . "\n";
echo "KB keywords: " . implode(', ', $kb->keywords) . "\n\n";

$cb = ChatbotSetting::current();
echo "Chatbot driver=" . $cb->driver . " model=" . $cb->model . " cap=RM" . $cb->cost_cap_rm . " mtd=RM" . $cb->cost_month_to_date_rm . "\n";

echo "Site title (cached): " . json_encode(Setting::get('site.title')) . "\n";
echo "Visitor count: " . Setting::get('site.visitor_count') . "\n";
