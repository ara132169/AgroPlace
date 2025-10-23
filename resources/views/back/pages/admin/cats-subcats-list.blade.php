@extends('back.layout.pages-layout')
@section('PageTitle',isset($pageTitle)? $pageTitle : 'Agro MarketPlace')
@section('content')

        @livewire('admin-categories-subcategories-list')
@endsection

@push('scripts')
    <script>
         $('table tbody#sortable_categories').sortable({
            cursor:"move",
            update:function(event,ui){
                $(this).children().each(function(index){
                   if( $(this).attr("data-ordering") != (index+1) ){
                      $(this).attr("data-ordering",(index+1)).addClass("updated");
                   }
                });
                var positions = [];
                $(".updated").each(function(){
                   positions.push([$(this).attr("data-index"),$(this).attr("data-ordering")]);
                   $(this).removeClass("updated");
                });
                // alert(positions);
                Livewire.dispatch("updateCategoriesOrdering",[positions]);
            }        
        });

        $(document).on('click','.deleteCategoryBtn', function(e){
            e.preventDefault();
            var category_id = $(this).data('id');
            Swal.fire({
                title:'¿Estas seguro?',
                html:'Eliminarás esta categoría',
                showCloseButton:true,
                showCancelButton:true,
                cancelButtonText:'Cancelar',
                confirmButtonText:'Si, Eliminar',
                cancelButtonColor:'#d33',
                confirmButtonColor:'#3085d6',
                width:300,
                allowOutsideClick:false
            }).then(function(result){
                if(result.value){
                    Livewire.dispatch('deleteCategory',[category_id]);
                }
            });
        });

        $('table tbody#sortable_subcategories').sortable({
            cursor:"move",
            update:function(event, ui){
                $(this).children().each(function(index){
                    if( $(this).attr("data-ordering") != (index+1) ){
                        $(this).attr("data-ordering",(index+1)).addClass("updated");
                    }
                });
                var positions = [];
                $(".updated").each(function(){
                    positions.push([$(this).attr("data-index"),$(this).attr("data-ordering")]);
                    $(this).removeClass("updated");
                });
                Livewire.dispatch("updateSubCategoriesOrdering",[positions]);
            }
        });

        $(document).on('click','.deleteSubCategoryBtn', function(e){
             e.preventDefault();
             var subcategory_id = $(this).data("id");
             var title = $(this).data("title");
             swal.fire({
                title:'¿Estás seguro?',
                html:'Eliminarás esta categoría'+title+'</b>',
                showCloseButton:true,
                showCancelButton:true,
                cancelButtonText:'Cancelar',
                confirmButtonText:'Si, Eliminar',
                cancelButtonColor:'#d33',
                confirmButtonColor:'#3085d6',
                width:300,
                allowOutsideClick:false
             }).then(function(result){
                 if( result.value ){
                    Livewire.dispatch("deleteSubCategory",[subcategory_id]);
                 }
             });
        });

        
 

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush