<?php
// Valores con PHP. Estos podrían venir de una base de datos o de cualquier lugar del servidor
// var_dump($productos_g['etiquetas_p']);
$etiquetas = $productos_g['etiquetas_p'];
$datosProductos = $productos_g['cantidad_p'];
$dataPoints = array();
$dataPo = array();

$array_num = count($ventas_g['meses']);
$array_pro = count($datosProductos);
for ($i = 0; $i < $array_num; ++$i){
    //print $array[$i];

    $to=$ventas_g['totales'][$i];
	array_push($dataPoints,array("label"=> $ventas_g['meses'][$i], "y"=> $to));

}

for ($i = 0; $i < $array_pro; ++$i){
    
    $ca=$productos_g['cantidad_p'][$i];
	array_push($dataPo,array( "y"=> $ca,"label"=> $productos_g['etiquetas_p'][$i]));
	// array("y" => 7,"label" => "March" ),

}




	
?>
<section class="content">
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
    <!-- ./col -->
    			<div class="col-lg-3 col-xs-8">
			        <!-- small box -->
			        <div class="small-box bg-red">
			            <div class="inner">
			                <h3><?php echo $facturas->contador?></h3>
			                <p>Facturas</p>
			            </div>
			            <div class="icon">
			                <i class="ion ion-document"></i>
			            </div>
			            <a href="<?php echo base_url(); ?>factura/nuevo/18" class="small-box-footer">Facturar <i class="fa fa-arrow-circle-right"></i></a>
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
			
            <div class="col-lg-5">
			<div id="chartContainer" style="height: 270px; width: 100%;" ></div>

            </div>
            <div class="col-lg-7">
			<div id="productos" style="height: 280px; width: 100%;" ></div>

            </div>
            </div>
			
		</div>
<!-- /.row -->
	</div>
</section>
<script type="text/javascript">
        // // Obtener una referencia al elemento canvas del DOM
        // const $grafica = document.querySelector("#grafica");
  
        // // Pasaamos las etiquetas desde PHP
        // const etiquetas = <?php echo json_encode($etiquetas) ?>;
        // // Podemos tener varios conjuntos de datos. Comencemos con uno
        // const datosVentas2020 = {
        //     label: "Productos mas vendidos",
        //     // Pasar los datos igualmente desde PHP
        //     data: <?php echo json_encode($datosProductos) ?>,
        //     backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de fondo
        //     borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
        //     borderWidth: 1, // Ancho del borde
        // };
        // new Chart($grafica, {
        //     type: 'line', // Tipo de gráfica
        //     data: {
        //         labels: etiquetas,
        //         datasets: [
        //             datosVentas2020,
        //             // Aquí más datos...
        //         ]
        //     },
        //     options: {
        //         scales: {
        //             yAxes: [{
        //                 ticks: {
        //                     beginAtZero: true
        //                 }
        //             }],
        //         },
        //     }
        // });

        

window.onload = function () {

	var currentTime = new Date();
	var year = currentTime.getFullYear()

	CanvasJS.addCultureInfo("es",
	    {
	        decimalSeparator: ".",
	        digitGroupSeparator: ",",
	        days: ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"],
	        savePNGText:"GUARDAR PNG",
	        saveJPGText:"GUARDAR JPG",
	        printText:"IMPRIMIR"

	   });

 	var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	culture:  "es",
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "TOTAL DE VENTAS "+year,
		FontWeight: "bolder",
		fontFamily: "Calibri",
		fontSize: 25,
	},
	axisY:{
		includeZero: true
	},
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		// indexLabel: "{y}", //Shows y value on all Data Points
		yValueFormatString: "$#,##0",
		indexLabelFontColor: "#5A5757",
		indexLabelPlacement: "outside",
		indexLabelFontColor: "white",  
		indexLabelFontFamily:"Calibri", 
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();





var chart = new CanvasJS.Chart("productos", {
	animationEnabled: true,
	exportEnabled: true,
	culture:  "es",
	title:{
		text: "PRODUCTOS MAS VENDIDOS ",
		fontFamily: "Calibri",
		fontWeight: "bold",
		fontSize: 25,
	},
	axisY: {
		title: "De los 3 últimos meses",
		includeZero: true,
		// prefix: "$",
		// suffix:  "k"
	},
	data: [{
		type: "bar",
		yValueFormatString: "#,##0",
		indexLabel: "{y}",
		indexLabelPlacement: "inside",
		indexLabelFontColor: "white",
		indexLabelFontFamily:"Calibri",
		dataPoints: <?php echo json_encode($dataPo, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}

    </script>
