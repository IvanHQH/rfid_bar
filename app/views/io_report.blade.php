@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.css') }}">
@stop

@section('content')
<div class="tab-header">Reporte de Entradas y Salidas de Botellas</div>
<div class="content-container">
    <div id="my-timeline"></div>
	<div class="table-responsive container">
            <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>
		<form id="event-select-form" name="event-select-form" method="get" action="{{ URL::to('/reports/') }}">
			Seleccione el Evento:
			<select name="event_id" id="event_id">
				@foreach($events as $e)
				<option value="{{ $e -> id }}" {{ $e -> id == $event_id?'selected="selected"':'' }}>{{{ $e -> event_name }}}</option>
				@endforeach
			</select>
			<input type="submit" value="Mostrar Evento Seleccionado" class="btn" />
		</form>
	</div>
    <div class="divider row"><hr/></div>
    @if (count($reads) > 0)
    <div class="table-responsive container">
        <table class="table" id="reads">
            <caption>Detalle de entradas y salidas de botellas</caption>
            <thead>
            <tr>
                <th>Evento</th>
                <th>Producto</th>
                <th>UPC</th>
                <th>Etiqueta</th>
                <th>Fecha y Hora</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reads as $read)
            <tr>
                <td>{{$read -> io}}</td>
                <td>{{$read -> product_name}}</td>
                <td>{{$read -> upc}}</td>
                <td>{{$read -> tag}}</td>
                <td>{{$read -> created_at}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
    Por el momento no hay lecturas para este día.
    @endif
</div>
@stop

@section('javascripts')
    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datatables.js') }}"></script>
@stop

@section('scripts')
<script>
    $(document).ready(function() {
		setActiveMenu('menu_report_io');
       var dt =  $('#reads').dataTable({
                "sPaginationType": "bs_full",
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
			
		$('form#event-select-form').off('submit').submit(function(e) {
			e.preventDefault();
			var o = $(this),
				base = o.attr('action'),
				ev = o.find('#event_id').val();
			window.location.href = '' + base + '/' + ev;
			return false;
		});
	$("#get_csv").click(function(){
                        //console.log(dt.oApi._fnAjaxParameters( dt.fnSettings()) );
                            var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
                            window.location="/csv/reports/"+$('#event-select-form').val()+"?"+$.param(oParams);
                        });
    });
</script>
@stop
