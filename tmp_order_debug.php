<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$app['config']->set('app.env', 'testing');
$app['config']->set('database.default', 'sqlite');
$app['config']->set('database.connections.sqlite.database', __DIR__.'/database/database.sqlite');

// Run migrations if needed
$kernel->call('migrate', ['--force' => true]);

use App\Models\Item\Category;
use App\Models\Item\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$user = User::factory()->create();
$category = Category::create(['name' => 'Debug', 'description' => 'Debug', 'is_active' => true]);
$menuItem = MenuItem::create([
    'name' => 'Debug Item',
    'description' => 'Debug',
    'cost_price' => 10,
    'selling_price' => 20,
    'category_id' => $category->id,
    'sku' => 'DBG-1',
    'barcode' => 'DBG-1',
    'is_active' => true,
    'track_stock' => false,
    'stock_quantity' => 5,
    'low_stock_threshold' => 1,
]);

Auth::login($user);

$request = Request::create('/api/orders', 'POST', [
    'type' => 'takeaway',
    'items' => [[
        'menu_item_id' => $menuItem->id,
        'quantity' => 1,
        'modifiers' => [],
    ]],
]);

$controller = new App\Http\Controllers\Order\OrderController();
$response = $controller->store($request);

echo $response->getStatusCode() . PHP_EOL;
echo $response->getContent() . PHP_EOL;
