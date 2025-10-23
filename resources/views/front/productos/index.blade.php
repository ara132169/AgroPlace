@extends('front.layout.pages-layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-center">Todos los Productos</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($productos2 as $productoo)
            <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                <a href="">
                    <img src="{{ asset('images/products/' . $productoo->product_image) }}" alt="{{ $productoo->name }}" class="w-full h-48 object-cover">
                </a>
                <div class="p-4">
                    <h2 class="text-lg font-semibold">{{ $productoo->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $productoo->category->category_name ?? 'Sin categoría' }}</p>
                    <p class="mt-2 text-blue-500 font-bold">${{ number_format($productoo->price, 2) }}</p>
                    <a href="" class="inline-block mt-3 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ver detalle</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $productos2->links() }} <!-- Paginación -->
    </div>
</div>
@endsection

