<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\SubCategory;
use \Cviebrock\EloquentSluggable\Services\SlugService;


class CategoriesController extends Controller
{
    public function catSubcatList(Request $request){
        $data = [
            'pageTitle'=>'Administración de categorías y sub-categorías'
        ];
        return view('back.pages.admin.cats-subcats-list',$data);
    } 

    public function addCategory(Request $request){
        $data = [
            'pageTitle'=>'Add Category'
        ];
        return view('back.pages.admin.add-category',$data);
    }

    public function storeCategory(Request $request){
        //VALIDATE THE FORM
        $request->validate([
            'category_name'=>'required|min:5|unique:categories,category_name',
            'category_image'=>'required|image|mimes:png,jpg,jpeg,svg',
        ],[
            'category_name.required'=>':Attribute es requerido',
            'category_name.min'=>':Attribute debe contener al menos 5 caracteres',
            'category_name.unique'=>'Este :attribute ya existe',
            'category_image.required'=>':Attribute es requerido',
            'category_image.image'=>':Attribute debe ser una imagen',
            'category_image.mimes'=>':Attribute debe ser formato JPG,JPEG,PNG o SVG '
        ]);

        if( $request->hasFile('category_image') ){
            $path = 'images/categories/';
            $file = $request->file('category_image');
            $filename = time().'_'.$file->getClientOriginalName();

            if(!File::exists(public_path($path))){
                File::makeDirectory(public_path($path));
            }

            //Upload category image
            $upload = $file->move(public_path($path),$filename);

            if($upload){
                //Save category into Database
                $category = new Category();
                $category->category_name = $request->category_name;
                $category->category_image = $filename;
                $saved = $category->save();

                if( $saved ){
                    return redirect()->route('admin.manage-categories.add-category')->with('success','<b>'.ucfirst($request->category_name).'</b> La categoría se ha agregado.');
                }else{
                    return redirect()->route('admin.manage-categories.add-category')->with('fail','Hubo un error. Intenta nuevamente.');
                }
            }else{
                return redirect()->route('admin.manage-categories.add-category')->with('fail','Hubo un error al cargar el icono, intenta nuevamente.');
            }
        }

        
    }


    public function editCategory(Request $request){
        $category_id = $request->id;
        $category = Category::findOrFail($category_id);
        $data = [
            'pageTitle'=>'Editar Categoria',
            'category'=>$category
        ];
        return view('back.pages.admin.edit-category',$data);
    }


    public function updateCategory(Request $request){
        $category_id = $request->category_id;
        $category = Category::findOrFail($category_id);

        //VALIDATE THE FORM
        $request->validate([
            'category_name'=>'required|min:5|unique:categories,category_name,'.$category_id,
            'category_image'=>'nullable|image|mimes:png,jpg,jpeg,svg',
        ],[
            'category_name.required'=>':Attribute es requerido',
            'category_name.min'=>':Attribute debe contener al menos 5 caracteres',
            'category_name.unique'=>'Este :attribute ya existe',
            'category_image.image'=>':Attribute debe ser una imagen',
            'category_image.mimes'=>':Attribute debe ser formato JPG,JPEG,PNG o SVG'
        ]);

        if( $request->hasFile('category_image') ){
            $path = 'images/categories/';
            $file = $request->file('category_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $old_category_image = $category->category_image;

            //Upload new category image
            $upload = $file->move(public_path($path),$filename);

            if( $upload ){
                 //Delete old category image
                 if( File::exists(public_path($path.$old_category_image)) ){
                    File::delete(public_path($path.$old_category_image));
                 }
                 //Update category info
                 $category->category_name = $request->category_name;
                 $category->category_image = $filename;
                 $category->category_slug = null;
                 $saved = $category->save();

                 if( $saved ){
                    return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('success','<b>'.ucfirst($request->category_name).'</b> Se ha actualizado la categoría.');
                 }else{
                    return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('fail','Hubo un error.');
                 }
            }else{
                return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('fail','Hubo un error al cargar la imagen.');
            }

        }else{
            //Update category Info
            $category->category_name = $request->category_name;
            $category->category_slug = null;
            $saved = $category->save();

            if( $saved ){
                return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('success','<b>'.ucfirst($request->category_name).'</b> La categoría se ha actualizado.');
            }else{
                return redirect()->route('admin.manage-categories.edit-category',['id'=>$category_id])->with('fail','Hubo un error. Intenta nuevamente.');
            }
        }
    }

    public function addSubCategory(Request $request){
        $independent_subcategories = SubCategory::where('is_child_of',0)->get();
        $categories = Category::all();
        $data = [
            'pageTitle'=>'Añadir SubCategorias',
            'categories'=>$categories,
            'subcategories'=>$independent_subcategories
        ];

        return view('back.pages.admin.add-subcategory',$data);
    }

    public function storeSubCategory(Request $request){
        //VALIDATE THE FORM
        $request->validate([
            'parent_category'=>'required|exists:categories,id',
            'subcategory_name'=>'required|min:5|unique:sub_categories,subcategory_name'
        ],[
            'parent_category.required'=>':Attribute es requerido',
            'parent_category.exists'=>':Attribute no existe en la tabla de categorías',
            'subcategory_name.required'=>':Attribute es requerido',
            'subcategory_name.min'=>':Attribute debe contener al menos 5 caracteres',
            'subcategory_name.unique'=>'This :attribute ya existe'
        ]);

        $subcategory = new SubCategory();
        $subcategory->category_id = $request->parent_category;
        $subcategory->subcategory_name = $request->subcategory_name;
        $subcategory->is_child_of = $request->is_child_of;
        $saved = $subcategory->save();

        if( $saved ){
            return redirect()->route('admin.manage-categories.add-subcategory')->with('success','<b>'.ucfirst($request->subcategory_name).'</b> Se ha añadido como subcategoría.');
        }else{
            return redirect()->route('admin.manage-categories.add-subcategory')->with('fail','Hubo un error, intenta nuevamente.');
        }
    }

    public function editSubCategory(Request $request){
        $subcategory_id = $request->id;
        $subcategory = SubCategory::findOrFail($subcategory_id);
        $independent_subcategories = SubCategory::where('is_child_of',0)->get();
        $data = [
            'pageTitle'=>'Editar Sub Categoria',
            'categories'=>Category::all(),
            'subcategory'=>$subcategory,
            'subcategories'=>(!empty($independent_subcategories)) ? $independent_subcategories : []
        ];
        return view('back.pages.admin.edit-subcategory',$data);
    }

    public function updateSubCategory(Request $request){
        $subcategory_id = $request->subcategory_id;
        $subcategory = SubCategory::findOrFail($subcategory_id);

        //VALIDATE THE FORM
        $request->validate([
            'parent_category'=>'required|exists:categories,id',
            'subcategory_name'=>'required|min:5|unique:sub_categories,subcategory_name,'.$subcategory_id,
        ],[
            'parent_category.required'=>':Attribute es requerido',
            'parent_category.exists'=>':Attribute no existe en la tabla de categorías',
            'subcategory_name.required'=>':Attribute es requerido',
            'subcategory_name.min'=>':Attribute debe contener al menos 5 caracteres',
            'subcategory_name.unique'=>'Este :attribute ya existe'
        ]);

        //CHECK IF THIS SUB CATEGORY HAS CHILDREN
        if( $subcategory->children->count() && $request->is_child_of != 0 ){
            return redirect()->route('admin.manage-categories.edit-subcategory',['id'=>$subcategory_id])->with('fail','Esta Sub-Categoría tiene ('.$subcategory->children->count().') Sub-SubCategoría. No puedes cambiar la opción "Sub-SubCategoría" a menos que elimines tus Sub-SubCategorías.



');
        }else{
            //Update category info
            $subcategory->category_id = $request->parent_category;
            $subcategory->subcategory_name = $request->subcategory_name;
            $subcategory->subcategory_slug = null;
            $subcategory->is_child_of = $request->is_child_of;
            $saved = $subcategory->save();

            if( $saved ){
                return redirect()->route('admin.manage-categories.edit-subcategory',['id'=>$subcategory_id])->with('success','<b>'.ucfirst($request->subcategory_name).'</b> sub category has been updated.');
            }else{
                return redirect()->route('admin.manage-categories.edit-subcategory',['id'=>$subcategory_id])->with('fail','Something went wrong.');
            }
        }
    }


}
