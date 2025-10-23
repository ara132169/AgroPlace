<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Client;
use App\Models\Seller;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CartController;
use App\Models\Cart;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;





class FrontEndController extends Controller
{
        public function index()
    {
        // Productos recientes visibles con relación al vendedor
        $productos = Product::with('seller')
            ->where('visibility', 1)
            ->latest()
            ->limit(10)
            ->take(4)
            ->get();

        $recientes = Product::latest()->take(3)->get(); // solo 3
        
        // Categorías con subcategorías (por si querés usarlas también en inicio)
        $categorias = Category::orderBy('ordering')->get();

        $vendedores = Seller::select('id', 'username', 'name', 'picture')->latest()->take(4)->get();

        $fechaLimite = Carbon::now()->subDays(3);

        $productosConDescuento = Product::whereNotNull('compare_price')
            ->where('created_at', '>=', $fechaLimite)
            ->latest()
            ->take(2)
            ->get();

        // Si no hay descuentos activos recientes, mostramos 2 productos aleatorios
        if ($productosConDescuento->isEmpty()) {
            $productosConDescuento = Product::inRandomOrder()->take(2)->get();
        }

        // 🔥 NUEVO: Productos vistos recientemente por el usuario
        $vistos = session()->get('vistos', []);
        $productosVistos = Product::whereIn('id', $vistos)->get();
        // Si no hay productos vistos, mostramos 2 productos aleatorios
        if ($productosVistos->isEmpty()) {
            $productosVistos = Product::inRandomOrder()->take(8)->get();
        }

            $recomendaciones = Product::where('visibility', 1)
        ->inRandomOrder()
        ->limit(3) // Puedes cambiar la cantidad si deseas más
        ->get();

        $mejoresVendidos = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_vendidos'))
        ->join('order_items', 'products.id', '=', 'order_items.product_id')
        ->groupBy(
            'products.id',
            'products.name',
            'products.slug',
            'products.summary',
            'products.price',
            'products.compare_price',
            'products.product_image',
            'products.category',
            'products.visibility',
            'products.created_at',
            'products.updated_at',
            'products.user_type' // 👈 este campo causó el error
        )
        ->orderByDesc('total_vendidos')
        ->take(3)
        ->get();

      
     
        return view('inicio', compact(
            'vendedores',
            'productos',
            'categorias',
            'recientes',
            'productosConDescuento',
            'productosVistos',
            'recomendaciones',
            'mejoresVendidos'
        ));
    }

  



    public function getProductsBySubcategory($categorySlug, $subcategorySlug)
    {
        $category = Category::where('category_slug', $categorySlug)->first();
        $subcategory = SubCategory::where('subcategory_slug', $subcategorySlug)->first();

        if (!$category || !$subcategory) {
            return redirect()->route('inicio')->with('error', 'Categoría o subcategoría no encontrada.');
        }

        $productos = Product::where('subcategory', $subcategory->id)
            ->where('visibility', 1)
            ->get();

        return view('front.subcategoria', compact('productos', 'category', 'subcategory'));
    }

    public function getProductsBySubsubcategory($categorySlug, $subcategorySlug, $subsubcategorySlug)
    {
        // Buscar la categoría, subcategoría y subsubcategoría por su slug
        $category = Category::where('category_slug', $categorySlug)->first();
        $subcategory = SubCategory::where('subcategory_slug', $subcategorySlug)->first();
        $subsubcategory = SubCategory::where('subcategory_slug', $subsubcategorySlug)->first();

        if (!$category || !$subcategory || !$subsubcategory) {
            return redirect()->route('inicio')->with('error', 'Categoría, subcategoría o subsubcategoría no encontrada.');
        }

        // Obtener los productos de la subsubcategoría
        $productos = Product::where('subcategory', $subsubcategory->id)
            ->where('visibility', 1)
            ->get();

        return view('front.subsubcategoria', compact('productos', 'category', 'subcategory', 'subsubcategory'));
    }


     public function show($slug)
    {
        $producto = Product::where('slug', $slug)->with(['seller.shop', 'category'])->firstOrFail();

        $productosRelacionados = Product::where('seller_id', $producto->seller_id)
            ->where('id', '!=', $producto->id) // Excluye el producto actual
            ->latest()
            ->take(4)
            ->get();

        return view('front.productos.producto-index', compact('producto', 'productosRelacionados'));
    }

  


    public function agregarAlCarrito($id)
    {
        $producto = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "product_id" => $producto->id,
                "name" => $producto->name,
                "price" => $producto->price,
                "quantity" => 1,
                "image" => $producto->product_image,
            ];
        }

        session()->put('cart', $cart);

        $cartCount = collect($cart)->sum('quantity');

        // Usar session flash para pasar el mensaje
        session()->flash('success', '¡Producto agregado al carrito!');
        session()->flash('cartCount', $cartCount);

        return redirect()->back();  // Redirige de vuelta a la misma página

    }


    public function verCarrito()
{
    $cliente = auth('client')->user();
    $cartItems = Cart::where('client_id', $cliente->id)->get();

    return view('front.layout.pages.cliente.carrito.index', compact('cartItems'));
}




    public function perfilVendedor($username)
    {
        // Cargar vendedor con su tienda
        $vendedor = Seller::where('username', $username)
                        ->with('shop')
                        ->firstOrFail();

        // Obtener productos del vendedor con su categoría
        $productos = Product::where('seller_id', $vendedor->id)
                            ->with('category')
                            ->latest()
                            ->get();

        // Obtener categorías únicas asociadas a los productos
        
        $categorias = DB::table('categories')
        ->leftJoin('products', 'categories.id', '=', 'products.category')
        ->select('categories.id', 'categories.category_name', 'categories.category_slug', DB::raw('COUNT(products.id) as total_productos'))
        ->groupBy('categories.id', 'categories.category_name', 'categories.category_slug')
        ->get();

        // Retornar vista con datos
        return view('front.vendedores.perfil', compact('vendedor', 'productos', 'categorias'));
    }

  
    

    public function verProducto($slug)
    {
        $producto = Product::where('slug', $slug)->firstOrFail();

        // Guardar en la sesión los productos vistos
        $vistos = session()->get('vistos', []);
    
        // Evitar duplicados
        if (!in_array($producto->id, $vistos)) {
            $vistos[] = $producto->id;
        }
    
        // Mantener solo los últimos 8
        if (count($vistos) > 8) {
            array_shift($vistos);
        }
    
        session()->put('vistos', $vistos);
    
        return view('producto.detalle', compact('producto'));
    }


    public function productosPorCategoria($slug)
    {
        $categoria = Category::where('category_slug', $slug)->firstOrFail();
    
        $productos = Product::with('category')
            ->where('category', $categoria->id)
            ->get();

        return view('front.layout.pages.categorias', compact('categoria', 'productos'));
    }
    
    
    
    public function productosPorSubcategoria($slug)
    {
        $subcategoria = Subcategory::where('subcategory_slug', $slug)->firstOrFail();
        $productos = Subcategory::all();
    
        return view('front.layout.pages.subcategorias', compact('subcategoria', 'productos'));
    }


        public function mostrarTiendas()
        {
            // Usar Shop en lugar de VendorShop y agregar cache para mejor rendimiento
            $tiendas = \Cache::remember('tiendas_listado', 1800, function () {
                return Shop::with(['seller'])
                    ->whereHas('seller', function($query) {
                        $query->where('status', 1);
                    })
                    ->orderBy('shop_name')
                    ->paginate(12);
            });

            // Agregar las variables que necesita el layout
            $fechaLimite = Carbon::now()->subDays(3);
            
            $productosConDescuento = \Cache::remember('productos_descuento_tiendas', 1800, function () use ($fechaLimite) {
                return Product::whereNotNull('compare_price')
                    ->where('visibility', 1)
                    ->where('created_at', '>=', $fechaLimite)
                    ->latest()
                    ->take(4)
                    ->get();
            });

            // Si no hay descuentos activos recientes, mostramos productos aleatorios
            if ($productosConDescuento->isEmpty()) {
                $productosConDescuento = Product::where('visibility', 1)
                    ->inRandomOrder()
                    ->take(4)
                    ->get();
            }

            return view('front.layout.pages.tiendas', compact('tiendas', 'productosConDescuento'));
        }

        public function detalleTienda($id)
    {
        try {
            // Buscar la tienda con cache para mejor rendimiento
            $tienda = \Cache::remember("tienda_detalle_{$id}", 3600, function () use ($id) {
                return Shop::with(['seller'])->findOrFail($id);
            });

            // Obtener productos de la tienda con paginación optimizada
            $productos = \App\Models\Product::where('seller_id', $tienda->seller_id)
                ->where('visibility', 1)
                ->with(['category'])
                ->latest()
                ->paginate(12);

            return view('front.layout.pages.tienda-detalle', compact('tienda', 'productos'));
            
        } catch (\Exception $e) {
            return redirect()->route('tiendas.index')->with('error', 'Tienda no encontrada');
        }
    }

  

}
