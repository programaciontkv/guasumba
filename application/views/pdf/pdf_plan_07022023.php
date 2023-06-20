<section class="content" class="page-break">
  <table width="100%">
  	<tr>
        <td colspan="3" width="100%">
            <table  width="100%">
                <tr><td><strong><?php echo $emp->emp_nombre; ?> </strong>  </td> 
                <td rowspan="4" width="20%"><img src="<?php echo base_url().'imagenes/'.$emp->emp_logo?>"  width="130px" height="70px"></td>
                 </tr>
                <tr><td><?php echo $emp->emp_identificacion; ?> </td>  </tr>
                <tr><td><?php echo $emp->emp_ciudad."-".$emp->emp_pais; ?> </td>  </tr>
                <tr><td><?php echo "TELEFONO: " . $emp->emp_telefono ?> </td>  </tr>
              
                
            </table>
        </td>
        <td></td>
        
        
    </tr>
  	<tr>

                    <td class="titulo" style=" border-collapse: separate; text-align: center;" colspan="3" ><?php echo $titulo?> </td>

                </tr>
					<table id="detalle" width="100%" class="table table-bordered table-list table-hover table-striped page-break" >
						<thead>
							<tr>
								<th>Codigo</th>
							<th>Descripcion</th>
							<th>Tipo</th>
								
							</tr>

						</thead>
						
						<tbody>
						 <?php 
						$n=0;
						if(!empty($cuentas)){
							foreach ($cuentas as $cuenta) {
								$n++;
								if($cuenta->pln_tipo==0){
									$tipo='SUMATORIA';
								}else{
									$tipo='MOVIMIENTO';
								}
						?>
							<tr>
								<td><?php echo $cuenta->pln_codigo?></td>
								<td><?php echo $cuenta->pln_descripcion?></td>
								<td><?php echo $tipo?></td>
								
							</tr>
						<?php
							}
						}
						?>
						</tbody>
					</table>
				</table>



<style type="text/css">
	footer .pagenum:before {
      content: counter(page);
}
    *,label{
        font-size: 13px;
       /*  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        /* font-family:"Calibri ligth";*/
       /* font-family: 'Source Sans Pro';*/
        font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
       margin-left: 6px;
       margin-right: 20px;
       justify-content: right;

    }
    
    

    .numerico {
        text-align: right;
    }

    #encabezado3 {
        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
    }

    /*#detalle{
        border-collapse: collapse;
    }*/

    #encabezado2 tr,#encabezado2 th, #encabezado2 td {
        font-weight: bold;
        justify-content: right;

    }

    

    #encabezado1 td, #encabezado1 th{
        text-align: left;
        font-size: 12px;
        font-weight: bold;

    }
    #encabezado3 td, #encabezado3 th{
        text-align: left;
        font-size: 12px;
        
    }

    #detalle td, #detalle th{
        /*border: 1px solid;
        border-color: #ffffff;
         background:#d7d7d7; */
        border-right: 2px solid #d7d7d7 !important;
        border-top: 2px solid #d7d7d7 !important;
        border-bottom: 2px solid #d7d7d7 !important;
        border-left: 2px solid #d7d7d7 !important;

    }

    #detalle tr:nth-child(2n-1) td ,#detalle tr:nth-child(2n-1) th {
      background: #DFDFDF !important;

    }

    #info td, #info th, #info tr{
        border: none;
       
        border-right: 2px solid #ffffff !important;
        border-top: 2px solid #ffffff !important;
        border-bottom: 2px solid #ffffff !important;
        border-left: 2px solid #ffffff !important;

    }

    #info{
        background: white !important;
    }

    #pagos{
        border-top: 1px  solid;
    }

    .titulo{
        font-size: 30px;
        font-weight: bold;
        align-content: center;
    }
    .mensaje {
                        color: #828282;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 14px;
                        justify-content: right;
                        font-weight: bolder;
                     }
    footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      color: black;
    }


</style>



