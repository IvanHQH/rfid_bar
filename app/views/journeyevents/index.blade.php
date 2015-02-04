@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Eventos</div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
        <button class="btn btn-sm" data-toggle="modal" data-target="#smwModal" id="add_event">Agregar Evento</button>
        <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>
        <table class="table" id="reads">
            <caption style="font-size: 18px; font-weight: bold;">Eventos</caption>
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>id</th>
                    <th style="width:240px;">Evento</th>
                    <th>Descripción</th>
                    <th style="width:170px;">Inicio</th>
                    <th style="width:170px;">Fin</th>
                    <th>Inv. Inicial</th>
                    <th>Inv. Final</th>
                    <th>Generar Reporte</th>
                    <th>Activo?</th>
                    <th style="width:170px;">creado</th>
                    <th style="width:170px;">modificado</th>
                </tr>
            </thead>
        </table>
    </div>

    <div style="display: none;" id="add-event">
        <form role="form" id="event-form">
            <div class="form-group">
                <label for="event_name">Nombre del Evento:</label>
                <input type="text" class="form-control" id="event_name" name="event[event_name]" placeholder="Nombre del Evento" value="">
            </div>
            <div class="form-group">
                <label for="description">Descripción:</label>
                <textarea id="description" name="event[description]" placeholder="Descripción del evento" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="active">Registrar como Evento Activo?</label>
				<input type="radio" id="active" name="event[active]" value="1" class="" checked="checked" />Si
				<input type="radio" id="active" name="event[active]" value="0" class="" />No
            </div>
        </form>
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
            var dt = $('#reads').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": '/journeyevents/datatable',
                //"sDom": "<'row'<'col-xs-6'T><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
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
                },
				"aoColumns": [
					{ 
						"bSortable": false,
						"bSearchable": false,
						"mData": 0,
						"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							var id = sData,
								o = $(nTd),
								bar = $('<div />').addClass('action-buttons').append(
									$('<span />').addClass('event-id').hide().text(id)
								).append(
									$('<button />').addClass('btn btn-info btn-sm action-edit').append('<span class="glyphicon glyphicon-pencil" />')
								);
							o.html('').append(bar);
						}
					},
                                        { 
						"bSortable": false,
						"bSearchable": false,
						"mData": 0,
						"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							var id = sData,
								o = $(nTd),
								bar = $('<div />').addClass('action-buttons').append(
									$('<span />').addClass('event-id').hide().text(id)
								).append(
									$('<button />').addClass('btn btn-danger btn-sm action-delete').append('<span class="glyphicon glyphicon-remove" />')
								);
                                                                    
							o.html('').append(bar);
						}
					},
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
					{ "mData": 3 },
					{ "mData": 4 },
					{ "mData": 5 },
					{ "mData": 6 },
                                        { 
						"bSortable": false,
						"bSearchable": false,
						"mData": 0,
						"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                                                    if(oData[5]!=null && oData[6]!=null){
							var id = sData,
								o = $(nTd),
								bar = $('<button />').addClass('btn action_generate_report').attr("event_id",id).text("Generar Reporte");
							o.html('').append(bar);
                                                    }
                                                    else
                                                        {
                                                            $(nTd).html('');
                                                        }
						}
					},
                                        { "mData": 7 },
                                        { "mData": 8 },
					{ "mData": 9 }
				]
            });

            setActiveMenu('menu_journeyevents_list');

			function prepareModal(id) {
				var mod = $('#smwModal');
                mod.find('#modalTitle').html('Agregar Evento');
				if (id != 0) {
					mod
						.find('.modal-body').html('Por favor espere...').end()
						.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>').end()
					;
					$.ajax({
						type: 'GET',
						url: '{{ URL::to('/journeyevents/get') }}' + '/' + id,
						success: function(d) {
							$('#smwModal').find('.modal-body').html($('#add-event').html())
									.find('#event_name').val(d.event_name).end()
									.find('#description').val(d.description).end()
									.find('input[name=event\\[active\\]][value='+ d.active +']').prop('checked', 'checked').end()
									.data('id', d.id)
							;
							$('#smwModal').find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-event-btn">Modificar Evento</button>');
						},
						dataType: 'json'
					});
				}
				else {
					mod
						.find('.modal-body').html($('#add-event').html()).end()
						.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary" id="add-event-btn">Agregar Evento</button>')
					;
				}
                mod.off('click', '#add-event-btn').on('click', '#add-event-btn', function() {
                    var data = $('#smwModal #event-form').serialize();
                    $.ajax({
                        type: "POST",
						url: '{{ URL::to('/journeyevents') }}' + (typeof id !== 'undefined'?('/' + id):''),
                        data: data,
                        success: function(data, textStatus, jqXHR) {
                            $('#smwModal').modal('hide');
                            dt.fnDraw();
                        },
                        dataType: 'json'
                    });
                });
			}
			
            $('#add_event').on('click', function() {
				prepareModal(0);
            });
			
			$('#reads').off('click', '.action-edit').on('click', '.action-edit', function(e) {
				var o = $(this),
				id = o.parents('div:first').find('span.event-id').text();
				prepareModal(id);
				$('#smwModal').modal();
			});
			
			$('#reads').off('click', '.action-delete').on('click', '.action-delete', function(e) {
				var o = $(this),
					id = o.parents('div:first').find('span.event-id').text();
				if (!confirm('Desea borrar el Evento?')) {
					return false;
				}

				$.ajax({
					type: "POST",
					url: '{{ URL::to('/journeyevents/delete') }}' + '/' + id,
					success: function(data, textStatus, jqXHR) {
						dt.fnDraw();
					},
					dataType: 'json'
				});
			});
                        
                        $('#reads').off('click', '.action_generate_report').on('click', '.action_generate_report', function(e) {
                                       $.ajax({
					type: "POST",
					url: '{{ URL::to('/journeyevents/generate') }}' + '/' + $(this).attr('event_id'),
					success: function(data, textStatus, jqXHR) {
						if(data.status){
                                                    alert("Reporte Generado con exito");
                                                }else{
                                                    alert("Error al generar el reporte");
                                                }
					},
					dataType: 'json'
				});
                        });
                        
                        $("#get_csv").click(function(){
                        //console.log(dt.oApi._fnAjaxParameters( dt.fnSettings()) );
                            var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
                            window.location="/journeyevents/csv"+"?"+$.param(oParams);
                        });
                       
        });
    </script>
@stop
