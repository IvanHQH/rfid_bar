@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/mydatatables.css') }}">
    <style>
        td.group {
            text-align: center
        }
        </style>
@stop

@section('content')
    <div class="tab-header">Dashboard</div>
	<div class="table-responsive container">
		<form id="event-select-form" name="event-select-form" method="get" action="{{ URL::to('/dashboard/') }}">
			Seleccione el Evento:
			<select name="event_id" id="event_id">
				@foreach($events as $e)
				<option value="{{ $e -> id }}" {{ $e -> id == $event_id?'selected="selected"':'' }}>{{{ $e -> event_name }}}</option>
				@endforeach
			</select>
			<input type="submit" value="Mostrar Evento Seleccionado" class="btn" />
		</form>
	</div>
    <div class="content-container">
        <div id="my-timeline"></div>
        <div class="divider row"><hr/></div>
        @if (count($reads) > 0)
            <div class="table-responsive container">
                <table class="table" id="reads">
                    <caption>Detalle de entradas y salidas de botellas</caption>
                    <thead>
                        <tr>
                            <th>Acción</th>
                            <th>Producto</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <!-- <th>tag</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reads as $read)
                            <tr>
                                <td>{{$read -> io}}</td>
                                <td>{{$read -> product_name}}</td>
                                <td>{{$read -> date}}</td>
                                <td>{{$read -> time}}</td>
                                <!-- <td>{{$read -> tag}}</td>-->
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
    <script type="text/javascript" src="{{ asset('js/storyjs-embed.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.dataTables.rowGrouping.js') }}"></script>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            createStoryJS({
                type: 'timeline',
                width: '100%',
                height: '600',
                source: '/working_day/{{ $event_id }}',
                embed_id: 'my-timeline',
                start_at_end: true,
                start_zoom_adjust: '3',
                //hash_bookmark: true,
                font: 'DroidSerif-DroidSans',
                //debug: true,
                lang: 'es'
            });
            $('#reads').dataTable({
                "sPaginationType": "bs_full",
                "iDisplayLength":100,
                "bLengthChange":0,
                "aaSorting": [[ 3, "desc" ]],
                /*"columnDefs": [
            { "visible": false, "targets": 2 }
        ],*/
        //"order": [[ 2, 'asc' ]],
        //"displayLength": 25,
        "fnDrawCallback": function ( settings ) {
            
        //console.log(this);
            /*var api = this.oApi;
            var rows = api.rows( {page:'current'} ).nodes();
            //var rows = api._fnGetRowData();
            //console.log(rows);
            var last=null;
 
            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );*/
        },
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
            }).rowGrouping({	iGroupingColumnIndex:2,
							//sGroupBy: "year",
                                                    sGroupingColumnSortDirection: "desc",
							bHideGroupingColumn: true});
            
        });

		$('form#event-select-form').off('submit').submit(function(e) {
			e.preventDefault();
			var o = $(this),
				base = o.attr('action'),
				ev = o.find('#event_id').val();
			window.location.href = '' + base + '/' + ev;
			return false;
		});

    </script>
@stop
