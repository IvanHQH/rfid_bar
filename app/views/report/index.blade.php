@extends('templates.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/mydatatables.css">
@stop

@section('content')
<div class="tab-header">Reporte General </div>
<div class="content-container">
    <div class="table-responsive container" style="width: 100%; padding: 10px;">
        Año: 
        <select id="year">
            <option value="">Todos</option>
            @foreach($years as $year)
            <option value="{{ $year->year }}">{{ $year->year }}</option>
            @endforeach
        </select>
        Mes: 
        <select id="month">
            <option value="">Todos</option>
            @foreach($months as $month)
            <option value="{{ $month->year.$month->month }}" class="{{ $month->year }}">{{ Month::$months[$month->month] }}</option>
            @endforeach
        </select>
        Evento:
        <select id="event">
            <option value="">Todos</option>
            @foreach($events as $event)
            <option value="{{ $event->id }}" class="{{ $event->month }}">{{ $event->event_name }}</option>
            @endforeach
        </select>
        <span id="group_type_label">
        Por:
        <select id="group_type">
            <option value="Producto">Producto</option>
            <option value="Familia">Familia</option>
            
        </select>
        </span>
        Reporte:
        <select id="report_type">
            <option value="1">Corte</option>
            <option value="2">Hora de venta</option>
            <option value="3">Top 10</option>
            <option value="5">En trafico sin dirección</option>
            <option value="4">Fuera de trafico</option>
        </select>
        <button class="btn btn-sm" style="float: right;" id="get_csv">CSV</button>
        <div class="table-responsive">
            <table class="table" id="reads">
                <caption style="font-size: 18px; font-weight: bold;"></caption>
                <thead>
                    <tr>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        </tr>
                    </tfoot>
            </table>
        </div>
    </div>
</div>
@stop

@section('javascripts')
    <script type="text/javascript" src="/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="/js/datatables.js"></script>
    <script type="text/javascript" src="/js/jquery.chained.js"></script>
    <script type="text/javascript" src="/js/accounting.min.js"></script>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#month").chained("#year");
            $("#event").chained("#month");                   
        
            function rebuildDT(){
                dt.fnDestroy();
                buildDT();
            }
            
            function buildDT(){
                
            $("#reads").find("tfoot").show();
            $("#group_type_label").show();
                $("#reads").find("tbody").empty();
                switch($("#report_type").val()){
                    case "1":
                switch($("#group_type").val()){
                    case "Producto":
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("upc")).append($("<td>").text("Producto")).append($("<td>").text("Familia")).append($("<td>").text("Inv. Inicial")).append($("<td>").text("Inv. Final")).append($("<td>").text("Salidas")).append($("<td>").text("Venta")).append($("<td>").text("Costo")).append($("<td>").text("Total Venta")).append($("<td>").text("Total Costo"));                           
                           vcolumns=[
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
                                        { "mData": 3 },
                                        { "mData": 4 },
					{ "mData": 5 },
                                        {
                                            "mData": 6,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 7,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                                    {
                                            "mData": 8,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 9,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            }
				];
                    break;
                    case "Familia":
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("Familia")).append($("<td>").text("Inv. Inicial")).append($("<td>").text("Inv. Final")).append($("<td>").text("Salidas")).append($("<td>").text("Venta")).append($("<td>").text("Costo")).append($("<td>").text("Total Venta")).append($("<td>").text("Total Costo"));    
                           vcolumns=[
					{ "mData": 0 },
					{ "mData": 1 },
                                        { "mData": 2 },
                                        { "mData": 3 },
                                        {
                                            "mData": 4,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 5,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                                    {
                                            "mData": 6,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 7,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            }
				];
                    break;
            }
            break;
            case "2":
                switch($("#group_type").val()){
                    case "Producto":
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("upc")).append($("<td>").text("Producto")).append($("<td>").text("Familia")).append($("<td>").text("Hora")).append($("<td>").text("Cantidad")).append($("<td>").text("Venta")).append($("<td>").text("Costo")).append($("<td>").text("Total Venta")).append($("<td>").text("Total Costo"));    
                           vcolumns=[
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
					{ 
						"mData": 3,
						"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
								o = $(nTd);
							o.html('').text(sData+":00 Hrs.");
						}
					},
					{ "mData": 4 },
                                {
                                            "mData": 5,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 6,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                                    {
                                            "mData": 7,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 8,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            }
				];
                    break;
                    case "Familia":
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("Familia")).append($("<td>").text("Hora")).append($("<td>").text("Cantidad")).append($("<td>").text("Venta")).append($("<td>").text("Costo")).append($("<td>").text("Total Venta")).append($("<td>").text("Total Costo"));    
                           vcolumns=[
					{ "mData": 0 },
					{ 
						"mData": 1,
						"fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
								o = $(nTd);
							o.html('').text(sData+":00 Hrs.");
						}
					},
					{ "mData": 2 },
                                         {
                                            "mData": 3,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 4,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                                    {
                                            "mData": 5,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 6,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            }
				];
                    break;
            }
            break;
            case "3":
                switch($("#group_type").val()){
                    case "Producto":
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("upc")).append($("<td>").text("Producto")).append($("<td>").text("Familia")).append($("<td>").text("Cantidad")).append($("<td>").text("Venta")).append($("<td>").text("Costo"));    
                           vcolumns=[
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
					{ "mData": 3 },
                                         {
                                            "mData": 4,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 5,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            }
				];
                    break;
                    case "Familia":
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("Familia")).append($("<td>").text("Cantidad")).append($("<td>").text("Venta")).append($("<td>").text("Costo"));
                           vcolumns=[
					{ "mData": 0 },
					{ "mData": 1 },
                                         {
                                            "mData": 2,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 3,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                        }
				];
                    break;
            }
            break;
            case "4":                                                        
            $("#reads").find("tfoot").hide();
            $("#group_type_label").hide();
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("epc")).append($("<td>").text("upc")).append($("<td>").text("Producto")).append($("<td>").text("Familia")).append($("<td>").text("Venta")).append($("<td>").text("Costo"));    
                           vcolumns=[
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
					{ "mData": 3 },
                                        {
                                            "mData": 4,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 5,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            }
				];
                    
            break;
            case "5":
            $("#reads").find("tfoot").hide();
            $("#group_type_label").hide();
                           $("#reads").find("thead").find("tr").empty().append($("<td>").text("epc")).append($("<td>").text("upc")).append($("<td>").text("Producto")).append($("<td>").text("Familia")).append($("<td>").text("Fecha")).append($("<td>").text("Venta")).append($("<td>").text("Costo"));    
                           vcolumns=[
					{ "mData": 0 },
					{ "mData": 1 },
					{ "mData": 2 },
					{ "mData": 3 },
                                        { "mData": 4 },
                                        
                                        
                                        {
                                            "mData": 5,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            },
                                        {
                                            "mData": 6,
                                            "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
							
							$(nTd).text(accounting.formatMoney(sData, { symbol: "$",  format: "%s %v " }));
						}
                                            }
				];
                    
            break;
            }
               
            
               dt = $('#reads').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": '/report/datatable',
                "fnServerParams": function ( aoData ) {
                    
                ajax_extra_param={
                    "year":$("#year").val(),
                    "month":$("#month").val(),
                    "event":$("#event").val(),
                    "group_type":$("#group_type").val(),
                    "report_type":$("#report_type").val()
                };
            $.each((typeof ajax_extra_param === 'undefined'?{}:ajax_extra_param),function(key,value){
            aoData.push({"name":key,"value":value});
            });
        },
                //"sDom": "<'row'<'col-xs-6'T><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                "sPaginationType": "bs_full",
                "fnDrawCallback" : (typeof dataTableDrawCallBack === 'undefined'?function(){
            var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
            $.ajax({
                        type: "GET",
                        url: '{{ URL::to('/report/footer') }}'+"?"+$.param(oParams)+"&year="+$("#year").val()+"&month="+$("#month").val()+"&event="+$("#event").val()+"&group_type="+$("#group_type").val()+"&report_type="+$("#report_type").val(),
                        success: function(data, textStatus, jqXHR) {
                            var footer=$("#reads").find("tfoot").find("tr").empty();
                            $.each(data,function(column,value){
                                var vhtml;
                                switch(column){
                                    case "inv_ini":
                                        if(value!=0)
                                     vhtml=value;
                                 else
                                     vhtml="";
                                     break;
                                case "inv_final":
                                     if(value!=0)
                                     vhtml=value;
                                 else
                                     vhtml="";
                                     break;
                                 case "count":
                                     vhtml=value;
                                     break;
                                     case "sold":
                                         case "cost":
                                         vhtml=accounting.formatMoney(value, { symbol: "$",  format: "%s %v " });
                                     break;
                                     case "family_name":
                                         vhtml="Total";
                                     break;
                                     default:
                                         vhtml="";
                                }
                                footer.append($("<td>").text(vhtml));
                            });
                        },
                        dataType: 'json'
                    });
            
                }:dataTableDrawCallBack),
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
                    "aoColumns": vcolumns
            });
            }
            
            buildDT();
            
            $("#year").change(rebuildDT);
                    $("#month").change(rebuildDT);
                    $("#event").change(rebuildDT);
                    $("#group_type").change(rebuildDT);
                    $("#report_type").change(rebuildDT);

            setActiveMenu('menu_report');
			
                        $("#get_csv").click(function(){
                            var oParams = dt.oApi._fnAjaxParameters( dt.fnSettings() );
                            window.location="/report/csv"+"?"+$.param(oParams)+"&year="+$("#year").val()+"&month="+$("#month").val()+"&event="+$("#event").val()+"&group_type="+$("#group_type").val()+"&report_type="+$("#report_type").val();
                        });
                       
        });
    </script>
@stop
