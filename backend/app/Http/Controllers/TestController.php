<?php
namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    // Show all categories
    public function getCategories()
    {
        $categories = Cache::rememberForever('categories', function () {
            return Category::all();
        });

        return CategoryResource::collection($categories);
    }
}
