<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Rules\ValidatePrice;
use Illuminate\Support\Facades\File;




class ProductController extends Controller
{
    public function addProduct(Request $request){
        $data = [
           'pageTitle'=>'Añadir producto',
           'categories'=>Category::orderBy('category_name','asc')->get()
        ];
        return view('back.pages.tienda.agregar-productos',$data);
     } //End Method

     
     public function getProductCategory(Request $request)
     {
         $category_id = $request->category_id;
     
         // Validar que el ID exista
         if (!$category_id || !is_numeric($category_id)) {
             return response()->json([
                 'status' => 0,
                 'data' => '<option value="">ID de categoría inválido</option>'
             ]);
         }
     
         // Buscar la categoría, evitar error 500 si no existe
         $category = Category::find($category_id);
         if (!$category) {
             return response()->json([
                 'status' => 0,
                 'data' => '<option value="">Categoría no encontrada</option>'
             ]);
         }
     
         // Obtener subcategorías de la categoría padre
         $subcategories = SubCategory::where('category_id', $category_id)
             ->where('is_child_of', 0)
             ->orderBy('subcategory_name', 'asc')
             ->get();
     
         $html = '';
         foreach ($subcategories as $item) {
             $html .= '<option value="' . $item->id . '">' . $item->subcategory_name . '</option>';
             
             // Agregar sub-subcategorías (hijos)
             if ($item->children->count() > 0) {
                 foreach ($item->children as $child) {
                     $html .= '<option value="' . $child->id . '">-- ' . $child->subcategory_name . '</option>';
                 }
             }
         }
     
         // Si no hay subcategorías
         if (empty($html)) {
             $html = '<option value="">No hay subcategorías disponibles</option>';
         }
     
         return response()->json([
             'status' => 1,
             'data' => $html
         ]);
     }
     


    public function createProduct(Request $request){
        /**
         * VALIDATE THE FORM
         * -----------------
         */
        $request->validate([
            'name'=>'required|unique:products,name',
            'summary'=>'required|min:10',
            'product_image'=>'required|mimes:png,jpg,jpeg,wepb|max:5000',
            'category'=>'required|exists:categories,id',
            'subcategory'=>'nullable|exists:sub_categories,id',
            'price'=>['required',new ValidatePrice],
            'compare_price'=>['nullable',new ValidatePrice],
        ],[
            'name.required'=>'Ingresa el nombre del producto',
            'name.unique'=>'Este nombre ya existe.',
            'summary.required'=>'Ingresa una descripción para este producto',
            'product_image.required'=>'Elige una imagen',
            'category.required'=>'Selecciona una categoría',
            'subcategory.required'=>'Selecciona una subcategoría',
            'price.required'=>'Ingresa una subcategoría'
        ]);

        $product_image = null;
        
        if( $request->hasFile('product_image') ){
            $path = 'images/products/';
            $file = $request->file('product_image');
            $filename = 'PIMG_'.time().uniqid().'.'.$file->getClientOriginalExtension();
            $upload = $file->move(public_path($path), $filename);
                /*
             
            $maxWidth = 1080;
            $maxHeight = 1080;
            $full_path = $path.$filename;
            $image = Image::make($file->path());
    
            $image->height() > $image->width() ? $maxWidth = null : $maxHeight = null;
            $image->fit($maxWidth, $maxHeight, function($constraint){
                  $constraint->upsize();
            });
             $upload = $image->save($full_path);*/
           

            if( $upload ){
                $product_image = $filename;
            }
        }

     

        //SAVE PRODUCT DETAILS
        $product = new Product();
        $product->user_type = 'seller';
        $product->seller_id = auth('seller')->id();
        $product->name = $request->name;
        $product->summary = $request->summary;
        $product->category = $request->category;
        $product->subcategory = $request->subcategory;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->visibility = $request->visibility;
        $product->product_image = $product_image;
        $saved = $product->save();

        if( $saved ){
            return response()->json(['status'=>1,'msg'=>'Se ha añadido un nuevo producto.']);
        }else{
            return response()->json(['status'=>0,'msg'=>'Hubo un error, intenta nuevamente.']);

        }
    } //End Method


public function allProducts(Request $request){
    $data = [
        'pageTitle'=>'Mis productos'
    ];
    return view('back.pages.tienda.productos',$data);
} //End Met

public function editProduct(Request $request){
    $product = Product::with('images')->findOrFail($request->id);
    $categories = Category::orderBy('category_name','asc')->get();
    $subcategories = SubCategory::where('category_id',$product->category)
                                ->where('is_child_of',0)
                                ->orderBy('subcategory_name','asc')
                                ->get();
     $data = [
         'pageTitle'=>'Editar Producto',
         'categories'=>$categories,
         'subcategories'=>$subcategories,
         'product'=>$product
     ];
     return view('back.pages.tienda.editar-producto',$data);
 } //End Method

 public function updateProduct(Request $request)
 {
     // Encontrar el producto a actualizar
     //$product = Product::findOrFail($request->product_id);
     $product = Product::with('images')->findOrFail($request->product_id);

 
     // Validación de los campos del formulario
     $request->validate([
         'name' => 'required|unique:products,name,' . $product->id,
         'summary' => 'required|min:10',
         'product_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5000',
         'category' => 'required|exists:categories,id',
         'subcategory' => 'nullable|exists:sub_categories,id',
         'price' => 'required|numeric',
         'compare_price' => 'nullable|numeric',
         'additional_images' => 'nullable|array',  // Se permite un array de imágenes
         'additional_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5000',  // Validación para cada imagen adicional
     ]);
 
     // Actualizar los datos del producto
     $product->update([
         'name' => $request->name,
         'summary' => $request->summary,
         'category' => $request->category,
         'subcategory' => $request->subcategory,
         'price' => $request->price,
         'compare_price' => $request->compare_price,
         'visibility' => $request->visibility,
     ]);
 
     // Manejar la imagen principal
     if ($request->hasFile('product_image')) {
         $file = $request->file('product_image');
         $filename = 'MAIN_' . uniqid() . '.' . $file->getClientOriginalExtension();
         $file->move(public_path('images/products/'), $filename);
         $product->product_image = $filename;
         $product->save();
     }
 
     // Manejar las imágenes adicionales
     if ($request->hasFile('additional_images')) {
         foreach ($request->file('additional_images') as $image) {
             // Generar un nombre único para cada imagen
             $filename = uniqid() . '.' . $image->getClientOriginalExtension();
             $image->move(public_path('images/products/'), $filename);
 
             // Guardar la imagen en la base de datos
             ProductImage::create([
                 'product_id' => $product->id,
                 'image' => $filename,  // Nombre de la imagen
             ]);
         }
     }
 
     // Retornar respuesta
     return response()->json([
         'status' => 1,
         'msg' => 'Producto actualizado correctamente.',
         'product' => $product,
     ]);
 }
 
 
    public function uploadProductImages(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5000',
        ]);
    
        $file = $request->file('file');
        $filename = 'PIMG_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/products/'), $filename);
    
        ProductImage::create([
            'product_id' => $request->product_id,
            'image_path' => $filename,
        ]);
    
        return response()->json(['status' => 1]);
    }
 

    public function getProductImages(Request $request){
        $product = Product::with('images')->findOrFail($request->id);
        $path = "images/products/";
        $html = "";
        if( $product->images->count() > 0 ){
            foreach( $product->images as $item ){
                $html.='<div class="box">
                <img src="/'.$path.$item->image.'">
                <a href="javascript:;" data-image="'.$item->id.'" class="btn btn-danger btn-sm" id="deleteProductImageBtn"><i class="fa fa-trash"></i></a>
                </div>';
            }
        }else{
            $html = '<span class="text-danger">No hay imagen(es)</span>';
        }

        return response()->json(['status'=>1,'data'=>$html]);
    } // End Method

    public function getSubcategories(Request $request)
    {
        $category_id = $request->category_id;

        $subcategories = SubCategory::where('category_id', $category_id)->get();

        $options = '';
        foreach ($subcategories as $subcat) {
            $options .= '<option value="'.$subcat->id.'">'.$subcat->name.'</option>';
        }

        return response()->json(['data' => $options]);
    }

    public function deleteProduct(Request $request){
        $product = Product::with('images')->findOrFail($request->product_id);

        //First, check if this product has additionals image(s) and delete them
        if( $product->images->count() > 1 ){
            $images_path = 'images/products/additionals/';
            foreach( $product->images as $item ){
                if( $item->image != null && File::exists(public_path($images_path.$item->image)) ){
                    File::delete(public_path($images_path.$item->image));
                }
                $pimage = ProductImage::findOrFail($item->id);
                $pimage->delete();
            }
        }

        //Delete actual product
        $path = 'images/products/';
        $product_image = $product->product_image;
        if( $product_image != null && File::exists(public_path($path.$product_image)) ){
            File::delete(public_path($path.$product_image));
        }
        $delete = $product->delete();

        if( $delete ){
            return response()->json(['status'=>1,'msg'=>'El producto se ha actualizado correctamente.']);
        }else{
            return response()->json(['status'=>0,'msg'=>'Hubo un error, intenta nuevamente.']);
        }
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






   
}
