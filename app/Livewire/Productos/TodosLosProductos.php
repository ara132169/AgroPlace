<?php

namespace App\Http\Livewire\Productos;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

class TodosLosProductos extends Component
{
    public $categoria = '';
    public $orden = 'recientes';
    public $precioMin = null;
    public $precioMax = null;

    public function render()
    {
        $query = Product::where('visibility', 1);

        if ($this->categoria) {
            $query->where('category', $this->categoria);
        }

        if ($this->precioMin !== null) {
            $query->where('price', '>=', $this->precioMin);
        }

        if ($this->precioMax !== null) {
            $query->where('price', '<=', $this->precioMax);
        }

        if ($this->orden === 'precio_asc') {
            $query->orderBy('price');
        } elseif ($this->orden === 'precio_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        return view('livewire.productos.todos-los-productos', [
            'productos' => $query->paginate(12),
            'categorias' => Category::orderBy('category_name')->get(),
        ])->layout('layouts.app');
    }
}

