@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Eventos</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
		<form name="event-select" id="event-select" method="get" action="{{ URL::to('/journeyevents/report/') }}">
			Seleccione el Evento:
			<select name="event_id" id="event_id">
				@foreach($events as $e)
				<option value="{{ $e -> id }}" {{ $e -> id == $event_id?'selected="selected"':'' }}>{{{ $e -> event_name }}}</option>
				@endforeach
			</select>
			<input type="submit" value="Mostrar Evento Seleccionado" class="btn" />
		</form>
	</div>
</div>
@if($event_id != 0)
	<div class="tab-header">Inventarios</div>
	<div class="content-container">
		<div class="table-responsive container" style="width: 100%; padding: 10px;">
			<form method="post" action="{{ Request::Url() }}" name="report-inventory-select-form" id="report-inventory-select-form">
				<table class="table" id="inventores-table">
					<tr>
						<th><input type="checkbox" name="checkall" id="checkall" value="1" /></th>
                                                <th>Mover a</th>
						<th>Inventario</th>
						<th>Tipo</th>
						<th>Etiquetas</th>
						<th>Fecha</th>
					</tr>
					@foreach ($invfull as $i)
					<tr>
						<td><input type="checkbox" name="inventory_id[]" class="iid" value="{{ $i -> id }}" {{ in_array($i -> id, $iids) ? 'checked="checked"':'' }} /></td>
                                                <td><select class="inventory_move">
				@foreach($events as $e)
				<option value="{{ $e -> id }}" {{ $e -> id == $event_id?'selected="selected"':'' }}>{{{ $e -> event_name }}}</option>
				@endforeach
			</select>&nbsp;&nbsp;&nbsp;<button class="btn inventory_move_button" inventory_id="{{ $i->id }}" style="display:none;">Mover</button></td>
						<td>{{{ $i -> name }}}</td>
						<td><select class="inventory_type" inventory_type="{{ $i -> inventory_type }}">
                                                    
                                                    <option value="0"{{ ($i -> inventory_type==0?"selected":"") }}>Parcial</option>
                                                    <option value="1"{{ ($i -> inventory_type==1?"selected":"") }}>Inicial</option>
                                                    <option value="2"{{ ($i -> inventory_type==2?"selected":"") }}>Final</option>
                                                    <option value="3"{{ ($i -> inventory_type==3?"selected":"") }}>Descontar</option>
                                                    </select>&nbsp;&nbsp;&nbsp;<button class="btn inventory_type_button" inventory_id="{{ $i->id }}" style="display:none;">Cambiar</button>
                                                </td>
						<td>{{{ $i -> total_tags }}}</td>
						<td>{{{ $i -> created_at }}}</td>
					</tr>
					@endforeach
				</table>
				<input type="submit" name="sbmtr" value="Mostrar" class="btn" />
                                <input type="hidden" name="move_to_event" value="0" />
                                <input type="hidden" name="new_event" />
                                <input type="hidden" name="change_inventory_type" value="0" />
                                <input type="hidden" name="new_inventory_type" />
			</form>
		</div>
	</div>
	@if ($inventories)
		<div class="tab-header">Reporte</div>
                <button class="btn" id="report-csv-button" style="float:right;">CSV</button>
		<div class="content-container">
			<div class="table-responsive container" style="width: 100%; padding: 10px;">
				<table class="table table-responsive" id="report-table">
					<tr>
						<th>UPC</th>
						<th>Producto</th>
						@foreach ($inventories as $i)
						<th>{{{ $i -> name }}}</th>
						@endforeach
						<th>Diferencia</th>
					</tr>
					@foreach ($products as $p)
					<tr>
						<td>{{{ $p['upc'] }}}</td>
						<td>{{{ $p['product_name'] }}}</td>
						<?php $first = null; $diff = 0; ?>
						@foreach ($inventories as $i)
							<?php if (null === $first) { $first = isset($i -> productarray[$p['upc']]['total'])?$i -> productarray[$p['upc']]['total']:0; $diff = 0; } ?>
							<?php 
								if (isset($i -> productarray[$p['upc']]['total'])) { 
									$diff = $first - $i -> productarray[$p['upc']]['total']; 
								} 
								else $diff = $first;
							?>
							<td>{{ $i -> productarray[$p['upc']]['total'] or '-' }}</td>
						@endforeach
						<td>{{ $diff }}</td>
					</tr>
					@endforeach
				</table>
			</div>
		</div>
	@endif
@endif 
	
	@if(false)
        <table class="table" id="">
            <caption style="font-size: 18px; font-weight: bold;">Eventos</caption>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Evento</th>
                    <th>Descripción</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Activo?</th>
                    <th>creado</th>
                    <th>modificado</th>
                </tr>
            </thead>
        </table>
    </div>
	@endif
@stop

@section('javascripts')
    <script type="text/javascript" src="/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/js/datatables.js"></script>
@stop

@section('scripts')
<script type="text/javascript">
/* <![CTYPE[ */
$(document).ready(function() {
    //$('button.inventory_type_button').hide();
	setActiveMenu('menu_report_eventinventory');
	$('form#event-select').off('submit').submit(function(e) {
		e.preventDefault();
		var o = $(this),
			base = o.attr('action'),
			ev = o.find('#event_id').val();
		window.location.href = '' + base + '/' + ev;
		return false;
	});
	
	// check all inventories
	$('form#report-inventory-select-form input#checkall').off('change').change(function(e) {
		var o = $(this),
			v = o.is(':checked'),
			all = o.parents('form:first').find('input.iid');
		if (v) all.prop('checked', 'checked');
		else all.prop('checked', '').removeProp('checked');
	});
        
        $('#report-csv-button').click(function(){
            var form=$('form#report-inventory-select-form');
            form.attr('action',form.attr('action').replace('report','csv'));
            form.submit();
            form.attr('action',form.attr('action').replace('csv','report'));
        });
        
        $('select.inventory_move').change(function(){
            if('{{ $event_id }}'!=$(this).val())
                $(this).closest("td").find("button").show();
            else
                $(this).closest("td").find("button").hide();
        });
        
        $('select.inventory_type').change(function(){
            if($(this).attr('inventory_type')!=$(this).val())
                $(this).closest("td").find("button").show();
            else
                $(this).closest("td").find("button").hide();
        });
        
        $('button.inventory_move_button').click(function(){
            $(this).closest('form').find("input[name='move_to_event']").val($(this).attr('inventory_id'));
            $(this).closest('form').find("input[name='new_event']").val($(this).closest('td').find('select.inventory_move').val());
        });
        
        $('button.inventory_type_button').click(function(){
            $(this).closest('form').find("input[name='change_inventory_type']").val($(this).attr('inventory_id'));
            $(this).closest('form').find("input[name='new_inventory_type']").val($(this).closest('td').find('select.inventory_type').val());
        });
});
/* ]]> */
</script>
@stop
