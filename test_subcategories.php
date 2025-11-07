<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;
use App\Models\SubCategory;

echo "=== VERIFICACIÓN DE SUBCATEGORÍAS ===\n\n";

// Ver todas las categorías con sus subcategorías
$categories = Category::with(['subcategories' => function($query) {
    $query->orderBy('ordering', 'asc');
}, 'subcategories.children' => function($query) {
    $query->orderBy('ordering', 'asc');
}])->orderBy('ordering','asc')->get();

foreach ($categories as $category) {
    echo "CATEGORÍA: {$category->category_name} (ID: {$category->id})\n";
    echo "  Slug: {$category->category_slug}\n";
    echo "  Total subcategorías: " . $category->subcategories->count() . "\n";
    
    if ($category->subcategories->count() > 0) {
        foreach ($category->subcategories as $subcategory) {
            if ($subcategory->is_child_of == 0) {
                echo "    ├─ SUBCATEGORÍA: {$subcategory->subcategory_name} (ID: {$subcategory->id})\n";
                echo "       Slug: {$subcategory->subcategory_slug}\n";
                
                if ($subcategory->children->count() > 0) {
                    foreach ($subcategory->children as $child) {
                        echo "       └─ SUB-SUBCATEGORÍA: {$child->subcategory_name} (ID: {$child->id})\n";
                        echo "          Slug: {$child->subcategory_slug}\n";
                    }
                }
            }
        }
    }
    echo "\n";
}

echo "\n=== TODAS LAS SUBCATEGORÍAS ===\n\n";
$allSubcats = SubCategory::with('parentcategory')->orderBy('id')->get();
foreach ($allSubcats as $sub) {
    $parentCat = $sub->parentcategory ? $sub->parentcategory->category_name : 'SIN CATEGORÍA PADRE';
    echo "ID: {$sub->id} | {$sub->subcategory_name} | Categoría: {$parentCat} | is_child_of: {$sub->is_child_of}\n";
}
