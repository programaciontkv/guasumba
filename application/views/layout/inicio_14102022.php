<?php
// Valores con PHP. Estos podrían venir de una base de datos o de cualquier lugar del servidor
// var_dump($productos_g['etiquetas_p']);
$etiquetas = $productos_g['etiquetas_p'];
$datosProductos = $productos_g['cantidad_p'];
$dataPoints = array();
$dataPo = array();
$dataVentas = array();

$array_num = count($ventas_g['meses']);
$array    = count($ventas_m['totales']);
$array_pro = count($datosProductos);


for ($i = 0; $i < 12; ++$i){
    //print $array[$i];

    $to=$ventas_g['totales'][$i];
	array_push($dataPoints,array("label"=> $ventas_g['meses'][$i], "y"=> $to));

}

for ($i = 0; $i < $array_pro; ++$i){
    
    $ca=$productos_g['cantidad_p'][$i];
	array_push($dataPo,array( "y"=> $ca,"label"=> $productos_g['etiquetas_p'][$i]));
	// array("y" => 7,"label" => "March" ),

}
$interval = intval($ventas_g['total_g']/5);

for ($i = 0; $i < $array; ++$i){
    
    $ca=$ventas_m['totales'][$i];
	array_push($dataVentas,array( "y"=> $ca,"label"=> $ventas_m['fechas'][$i]));
	// array("y" => 7,"label" => "March" ),

}
$interval_2 = intval($ventas_m['total_m']/5);



	
?>


<section class="content">
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
    <!-- ./col -->
    <div class="col-lg-6 col-xs-8">
			        <!-- small box -->
			        <div class="small-box ">
			        	<div class="small-box bg-danger" style="color: black;">
			        		<div class="inner">
			           
			                <h3><?php echo $facturas_m->contador?></h3>
			                <p id="s1"></p>
			            </div>
			        	</div>
			        	
			            
			            <div class="icon">
			                <i class="ion ion-document"></i>
			            </div>
			            
			        </div>
			    </div>
			    <div class="col-lg-6 col-xs-8">
			        <!-- small box -->
			        <div class="small-box ">
			        	
			        	<div class="small-box bg-info" style="color: black;">
			            <div class="inner">
			                <h3><?php echo $facturas_a->contador?></h3>
			                <p id="s2"></p>
			            </div>
			            </div>
			            
			            <div class="icon">
			                <i class="ion ion-document"></i>
			            </div>
			            
			        </div>
			    </div>
    			
			    <div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-red">
			        	<div class="small-box bg-red">
			        		
			            <div class="inner">

			                <h3><?php echo $facturas_g->contador?></h3>
			                <p>Total de Facturas Autorizadas</p>
			            </div>
			            
			            
			            <div class="icon">
			                <i class="ion ion-document"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>factura/nuevo/18" class="small-box-footer">Facturar <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
			    </div>
			    
			    <div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-green">
			            <div class="inner">
			                <h3><?php echo $clientes->contador?></h3>
			                <p>Clientes</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-person-add"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>cliente/5" class="small-box-footer">Ver Clientes y Proveedores <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
    <!-- ./col -->
			    <div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-yellow">
			            <div class="inner">
			                <h3><?php echo $productos->contador?></h3>
			                <p>Productos</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-bag"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>producto_comercial/15" class="small-box-footer">Ver Productos <i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
			    <div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-blue">
			            <div class="inner">
			                <h3><?php echo $productos->contador?></h3>
			                <p>Inventarios</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-podium"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>inventario/30" class="small-box-footer">Ver Inventario<i class="fa fa-arrow-circle-right"></i></a>
			        </div>
			    </div>
			    
    <!-- ./col -->
			    
			    
    <!-- ./col -->
		</div>
		<br>
		<div class="row">
			
            <div class="col-lg-6">
            	<!-- <button  style="float:right;padding:0px;margin: 0px;" onclick="rep_ventas()" class="btn btn-success">Reporte</button> -->
            	<a style="float:right;padding:0px;margin: 0px;" href="<?php echo base_url().'rep_ventas_por_punto/49';?>" class="btn btn-primary" title="Reporte Completo"  href="">Reporte</a>
            	<br>
            	<br>
				<div id="ventas_anual" style="height: 280px; max-width: 100%; margin: 0px auto;" ></div>

            </div>
            <div class="col-lg-6">
            	<!-- <button  style="float:right;padding:0px;margin: 0px;" onclick="rep_prod()" class="btn btn-primary">Reporte</button> -->
            	<a style="float:right;padding:0px;margin: 0px;" href="<?php echo base_url().'rep_ventas_por_producto/50';?>" class="btn btn-primary" title="Reporte Completo"  href="">Reporte</a>
            	<br>
            	<br>
				<div id="productos_top"   style="height: 280px; width: 100%;margin: 0px auto; " ></div>

            </div>
            <div  class="col-lg-12" >
            	

				<!-- <button  style="float:right;padding:0px;margin: 0px;" onclick="rep_ventas()" class="btn btn-success">Reporte</button> -->
            	<a style="float:right;padding:0px;margin: 0px;" href="<?php echo base_url().'rep_ventas_por_punto/49';?>" class="btn btn-primary" title="Reporte Completo"  href="">Reporte</a>
            	<br>
            	<br>
				<div id="ventas_mensual" style="height: 280px; max-width: 100%; margin: 0px auto;" ></div>
            </div>
            <br>
            <!-- <div class="col-lg-6" >
            	<a style="float:right;padding:0px;margin: 0px;" class="btn btn-primary"  href="">Reporte</a>
            	<br>
            	<br>
				<div id="productos"   style="height: 280px; width: 100%;margin: 0px auto; " ></div>
            </div> -->
        </div>
			
		</div>
<!-- /.row -->
	</div>
</section>
<script type="text/javascript">
       
function rep_prod(){
	swal("Error!", "Se abrirar.!", "error");
} 

        

window.onload = function () {

	var currentTime = new Date();
	var year = currentTime.getFullYear()
	var mes = currentTime.toLocaleString("es-EC", { month: "long" });

	$('#s1').html('Total de facturas mes '+mes);
	$('#s2').html('Total de facturas año '+ year);

	CanvasJS.addCultureInfo("es",
	    {
	        decimalSeparator: ".",
	        digitGroupSeparator: ",",
	        days: ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"],
	        savePNGText:"GUARDAR PNG",
	        saveJPGText:"GUARDAR JPG",
	        printText:"IMPRIMIR"

	   });

 	var chart = new CanvasJS.Chart("ventas_anual", {
	animationEnabled: true,
	// exportEnabled: true,
	culture:  "es",
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	backgroundColor: "white",

	title:{
		text: "VENTAS  AÑO-"+year,
		FontWeight: "bolder",
		fontFamily: "Calibri",
		fontSize: 25,
	},
	axisY:{
		includeZero: true,
		interval: <?php echo $interval ?>,
		labelFontColor: "black",
		title:"RANGO DE VALORES EN $"
	},

	axisX:{
		labelFontColor: "black",

	},


	data: [{
		
		type: "spline", //change type to bar, line, area, pie, etc
		// indexLabel: "{y}", //Shows y value on all Data Points
		yValueFormatString: "$#,##0",
		// indexLabelFontColor: "black",

		indexLabelPlacement: "inside",
		indexLabelFontFamily:"Calibri", 
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});

chart.render();


var chart_m = new CanvasJS.Chart("ventas_mensual", {
	animationEnabled: true,
	// exportEnabled: true,
	culture:  "es",
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	backgroundColor: "white",

	title:{
		text: "VENTAS  "+mes.toUpperCase(),
		FontWeight: "bolder",
		fontFamily: "Calibri",
		fontSize: 25,
	},
	axisY:{
		includeZero: true,
		interval: <?php echo $interval_2 ?>,
		labelFontColor: "black",
		title:"RANGO DE VALORES EN $"
	},

	axisX:{
		labelFontColor: "black",

	},


	data: [{
		
		type: "spline", //change type to bar, line, area, pie, etc
		// indexLabel: "{y}", //Shows y value on all Data Points
		yValueFormatString: "$#,##0.#0",
		// indexLabelFontColor: "black",

		indexLabelPlacement: "inside",
		indexLabelFontFamily:"Calibri", 
		dataPoints: <?php echo json_encode($dataVentas, JSON_NUMERIC_CHECK); ?>
	}]
});

chart_m.render();



var chart_pt = new CanvasJS.Chart("productos_top", {
	animationEnabled: true,
	// exportEnabled: true,
	culture:  "es",
	title:{
		text: 'TOP PRODUCTOS  ULTIMOS 3 MESES', 
		fontFamily: "Calibri",
		fontWeight: "bold",
		fontSize: 25,
		
        verticalAlign: "top", // "top", "center", "bottom"
        horizontalAlign: "center" // "left", "right", "center"
	},
	axisY: {
		// title: "De los 3 últimos meses",
		includeZero: true,
		// prefix: "$",
		// suffix:  "k"
	},
	data: [{
		type: "bar",
		yValueFormatString: "#,##0",
		indexLabel: "{y}",
		indexLabelPlacement: "inside",
		indexLabelFontColor: "black",
		indexLabelFontFamily:"Calibri",
		dataPoints: <?php echo json_encode($dataPo, JSON_NUMERIC_CHECK); ?>
	}]
});
chart_pt.render();

var chart = new CanvasJS.Chart("productos", {
	animationEnabled: true,
	// exportEnabled: true,
	culture:  "es",
	title:{
		text: 'TOP PRODUCTOS  ULTIMOS 3 MESES', 
		fontFamily: "Calibri",
		fontWeight: "bold",
		fontSize: 25,
		
        verticalAlign: "top", // "top", "center", "bottom"
        horizontalAlign: "center" // "left", "right", "center"
	},
	axisY: {
		// title: "De los 3 últimos meses",
		includeZero: true,
		// prefix: "$",
		// suffix:  "k"
	},
	data: [{
		type: "bar",
		yValueFormatString: "#,##0",
		indexLabel: "{y}",
		indexLabelPlacement: "inside",
		indexLabelFontColor: "black",
		indexLabelFontFamily:"Calibri",
		dataPoints: <?php echo json_encode($dataPo, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render()


 
}

    </script>
