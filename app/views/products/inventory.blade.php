@extends('templates.base')

@section('css')
<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Inventario de Productos</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
        <table class="table" id="reads">
            <caption style="font-size: 18px; font-weight: bold;">Inventario de Productos</caption>
            <thead>
            <tr>
                <th>Producto</th>
                <th>UPC</th>
                <th>Existencias</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
@stop

@section('javascripts')
<script type="text/javascript" src="/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/datatables.js"></script>
@stop

@section('scripts')
<script>
    $(document).ready(function() {
        $('#reads').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": '/products/inventory/datatable',
            "sDom": "<'row'<'col-xs-6'T><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
            "sPaginationType": "bs_full",
            "fnDrawCallback" : (typeof dataTableDrawCallBack === 'undefined'?function(){}:dataTableDrawCallBack),
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros por pagina",
                "sZeroRecords": "No se encontraron registros",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar",
                "oPaginate": {
                    "sFirst":'<i class="glyphicon glyphicon-step-backward"></i>',
                    "sLast":'<i class="glyphicon glyphicon-step-forward"></i>',
                    "sNext":'<i class="glyphicon glyphicon-forward"></i>',
                    "sPrevious":'<i class="glyphicon glyphicon-backward"></i>'
                }
            }
        });
    });
</script>
@stop
