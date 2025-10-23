<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Flasher\Toastr\Laravel\FlasherToastrServiceProvider;
use App\Models\SubCategory;




class AdminCategoriesSubcategoriesList extends Component
{
    protected $listeners = [
        'updateCategoriesOrdering',
        'deleteCategory',
        'deleteSubCategory'
        
    ];

    public function updateCategoriesOrdering($positions){
        foreach($positions as $position){
            $index = $position[0];
            $newPosition = $position[1];
            Category::where('id',$index)->update([
                'ordering'=>$newPosition
            ]);

           toastr()->addSuccess('success','Se ha actualizado el orden de las categorías.');
        }
    }

    public function deleteCategory($category_id){
        $category = Category::findOrFail($category_id);
        $path = 'images/categories/';
        $category_image = $category->category_image;

        //CHECK IF THIS CATEGORY HAS SUBCATEGORIES
        /*if( $category->subcategories->count() > 0 ){
            //Check if on of these subcategories has related product(s)

            //Delete sub categories
            foreach( $category->subcategories as $subcategory ){
                $subcategory = SubCategory::findOrFail($subcategory->id);
                $subcategory->delete();
            }
        }*/

        if( File::exists(public_path($path.$category_image))){
            File::delete($path.$category_image);
        }

        $delete = $category->delete();

        if($delete){
            toastr()->addSuccess('La categoría se ha eliminado correctamente.');
        }else{
            toastr()->error('error','Hubo un error, intenta nuevamente.');
        }
    }

    public function deleteSubCategory($subcategory_id){
        $subcategory = SubCategory::findOrFail($subcategory_id);
 
        //When this sub category has child sub categories
        if( $subcategory->children->count() > 0 ){
           //Check if there is/are product(s) related to one of child sub categories
 
           //If no product(s) related to child sub categories, delete them
           foreach( $subcategory->children as $child ){
              SubCategory::where('id',$child->id)->delete();
           }
 
           //Delete Sub Category
           $subcategory->delete();
           toastr()->addSuccess('La Subcategoría se ha eliminado correctamente.');
 
        }else{
            //Check if this sub category has product(s) related to it
            
 
            //Delete sub category
            $subcategory->delete();
            toastr()->addSuccess('La Subcategoría se ha eliminado correctamente.');
        }
     }

    public function render()
    {
        return view('livewire.admin-categories-subcategories-list',[
            'categories'=>Category::orderBy('ordering','asc')->get(),
            'subcategories'=>SubCategory::where('is_child_of',0)->orderBy('ordering','asc')->get()

        ]);
    }
}
