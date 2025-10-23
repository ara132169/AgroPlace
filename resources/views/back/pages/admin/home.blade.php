@extends('back.layout.pages-layout')
@section('PageTitle', isset($pageTitle) ? $pageTitle : 'Agro MarketPlace - Administrador')
@section('content')

<div class="card-box pb-10">
    <div class="h5 pd-20 mb-0">Solicitudes de vendedores Verificadas</div>
    <div class="table-responsive">
        <table class="data-table table nowrap">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Nombre de usuario</th>
                    <th>Correo electrónico</th>
                    <th>Teléfono</th>
                    <th>Estatus</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($solicitudesVerificadas as $solicitud)
                    <tr>
                        <td>{{ $solicitud->nombre }}</td>
                        <td>{{ $solicitud->username }}</td>
                        <td>{{ $solicitud->email }}</td>
                        <td>{{ $solicitud->telefono }}</td>
                        <td><span class="badge badge-pill badge-success">Verificado</span></td>
                        <td>
                            <a href="{{ route('admin.solicitudes.verificar', $solicitud->id) }}" title="Verificar">
                                <i class="icon-copy dw dw-edit2"></i>
                            </a>
                            <a href="{{ route('admin.solicitudes.eliminar', $solicitud->id) }}" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?')">
                                <i class="icon-copy dw dw-delete-3"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay solicitudes verificadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> <br>

<div class="card-box pb-10">
    <div class="h5 pd-20 mb-0">Solicitudes de vendedores No Verificadas</div>
    <div class="table-responsive">
        <table class="data-table table nowrap">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Nombre de usuario</th>
                    <th>Correo electrónico</th>
                    <th>Teléfono</th>
                    <th>Estatus</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($solicitudesNoVerificadas as $solicitud)
                    <tr>
                        <td>{{ $solicitud->nombre }}</td>
                        <td>{{ $solicitud->username }}</td>
                        <td>{{ $solicitud->email }}</td>
                        <td>{{ $solicitud->telefono }}</td>
                        <td><span class="badge badge-pill badge-warning">No Verificado</span></td>
                        <td>
                            <a href="{{ route('admin.solicitudes.verificar', $solicitud->id) }}" title="Verificar">
                                <i class="icon-copy dw dw-edit2"></i>
                            </a>
                            <a href="{{ route('admin.solicitudes.eliminar', $solicitud->id) }}" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?')">
                                <i class="icon-copy dw dw-delete-3"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay solicitudes no verificadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div><br>

@endsection
