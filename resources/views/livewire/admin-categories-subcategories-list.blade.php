<div>
       <div class="row">
                <div class="col-md-12">
                    <div class="pd-20 card-box mb-30">
                        <div class="clearfix">
                            <div class="pull-left">
                                <div class="h4 text-blue">
                                        Categorías
                                </div>
                            </div>
                            <div class="pull-right">
                                <div class="h4 text-blue">
                                    <a href="{{route('admin.manage-categories.add-category')}}" class="btn btn-primary btn-sm" type="button">
                                        <i class="fa fa-plus"></i>
                                        Añadir categoría
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table  table-borderless table-striped">
                                    <thead class="bg-secondary text-white">
                                        <tr>
                                            <th>Icono de categoría</th>
                                            <th>Nombre de categoría</th>
                                            <th>Número de subcategorías</th>
                                            <th>Acción</th>

                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0" id="sortable_categories">
                                        @forelse($categories as $item)
                                            <tr data-index="{{ $item->id }}" data-ordering="{{ $item->ordering }}">
                                                <td>
                                                    <div class="avatar mr-2">
                                                        <img src="/images/categories/{{ $item->category_image }}" width="50" height="50" alt="">
                                                    </div>
                                                </td>
                                                <td>
                                                {{ $item->category_name }}
                                                </td>
                                                <td>
                                                {{ $item->subcategories->count() }}
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="{{route('admin.manage-categories.edit-category',['id'=>$item->id])}}" class="text-primary">
                                                            <i class="dw dw-edit2">

                                                            </i>
                                                        </a>

                                                        <a href="javascript:;" class="text-danger deleteCategoryBtn" data-id="{{ $item->id }}">
                                            <i class="dw dw-delete-3"></i>
                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <span class="text-danger">No se encontraron categorías!</span>
                                                </td>
                                            </tr>
                                        @endforelse
                                      
                                    </tbody>
                    </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="pd-20 card-box mb-30">
                        <div class="clearfix">
                            <div class="pull-left">
                                <div class="h4 text-blue">
                                        Subcategorías
                                </div>
                            </div>
                            <div class="pull-right">
                                <div class="h4 text-blue">
                                    <a href="{{route('admin.manage-categories.add-subcategory')}}" class="btn btn-primary btn-sm" type="button">
                                        <i class="fa fa-plus"></i>
                                        Añadir Subcategoría
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table  table-borderless table-striped">
                                    <thead class="bg-secondary text-white">
                                        <tr>
                                            
                                            <th>Nombre de Subcategoría</th>
                                            <th>Categoría principal</th>
                                            <th>Sub-Subcategoría</th>
                                            <th>Acción</th>

                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">

                                        @forelse ($subcategories as $item)
                                       
                                        <tr>
                                            <td>
                                                {{ $item->subcategory_name }}
                                            </td>
                                            <td>
                                            {{ $item->parentcategory->category_name ?? 'Sin categoría' }}

                                            </td>
                                            <td>
                                                 @if ( $item->children->count() > 0 )
                                                        <ul class="list-group" id="sortable_child_subcategories">
                                                            @foreach ($item->children as $child)
                                                                <li data-index="{{ $child->id }}" data-ordering="{{ $child->ordering }}" class="d-flex justify-content-between align-items-center">
                                                                    - {{ $child->subcategory_name }}
                                                                    <div>
                                                                        <a href="{{ route('admin.manage-categories.edit-subcategory',['id'=>$child->id]) }}" class="text-primary" data-toggle="tooltip" title="Edit child sub category">Editar</a>
                                                                        |
                                                                        <a href="javascript:;" class="text-danger deleteChildSubCategoryBtn" data-toggle="tooltip" title="Delete child sub category" data-id="{{ $child->id }}" data-title="Child Sub Category">Eliminar</a>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        No se ha asignado
                                                @endif
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="{{route('admin.manage-categories.edit-subcategory',['id'=>$item->id])}}" class="text-primary">
                                                        <i class="dw dw-edit2">

                                                        </i>
                                                    </a>

                                                    <a href="javascript:;" class="text-danger deleteSubCategoryBtn" data-id="{{ $item->id }}" data-title="Sub categoria">
                                                        <i class="dw dw-delete-3">

                                                        </i>
                                                    </a>



                                                </div>
                                            </td>
                                        </tr>

                                        @empty
                                        <tr>
                                            <td>
                                                <span class="text-danger">No se encontraron subcategorías</span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
</table>
                        </div>
                    </div>
                </div>
            </div>

</div>
