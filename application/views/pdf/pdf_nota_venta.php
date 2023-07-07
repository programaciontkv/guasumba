
<section class="content">
  <table width="100%">
    <tr>
        <td width="50%" >
            <table id="login">
                <tr >
                    <td>
                        <td><img src="./imagenes/<?php echo $factura->emp_logo;?>" width="250px" height="90px"></td>
                    </td>
                </tr>    
            </table>
        </td>
        <td rowspan="2" width="50%">
        <table  id="encabezado2" width="100%" cellspacing="5px"   style="border-top: 0.5px solid black; border-bottom: 0.5px solid black ; border-left: 0.5px solid black; border-right: 0.5px solid black ;">
             
                    <!-- <tr><td></td> </tr>
                    <tr><td></td> </tr>
                    <tr><td></td> </tr> -->
                
                <tr >
                    <td rowspan="1" class="titulo" style=" border-collapse: separate;"  colspan="2"><?php echo $tipo ?></td>
                </tr>    
                <tr>
                    <td colspan="2" style="font-size:20px">No. <?php echo $factura->fac_numero?></td>
                </tr>  
                <tr>
                    <td colspan="2"> <b><?php echo 'Fecha de Emisión:' ?> </b>
                        <label style="font-weight: normal;">
                            <?php echo $factura->fac_fecha_emision?>
                        </label>
                    </td>
                </tr>    
               
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="75%" valign="bottom">
            <table id="encabezado1"  width="100%">
                <tr>
                    <td class="titulo"  colspan="2"><?php echo  utf8_decode($factura->emp_nombre)?></td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2"><?php echo ucwords(strtolower($factura->emi_nombre))?></td>
                </tr>
                <tr>
                    <td class="titulo"><?php echo $factura->emp_identificacion?></td>
                </tr>    
                <tr >
                  
                   <td colspan="2" align="left">
                    <label style="font-weight: normal;">
                       <?php echo trim(ucwords(strtolower($factura->emp_direccion)))?>
                   </label>
                    </td>
                </tr>
                 
                <tr>
                    <td >
                      <b><?php echo 'Teléfono:' ?> </b>
                     <label style="font-weight: normal;">
                          <?php echo ucwords(strtolower($factura->emp_telefono))?>

                     </label>
                     </td>
                    <td colspan="2">
                        <b>Email:  </b>
                            <label style="font-weight: normal;">
                             <?php echo strtolower($factura->emp_email)?>
                            </label>
                    </td>
                    <th></th>
                </tr> 

            </table>
        </td>
        <!-- <tr>
            <td><br></td>
        </tr> -->

    </tr>
    <tr >
        <td colspan="2" >
            <table id="encabezado3"  width="80%"  style=" border-top: 0.5px solid black ; border-bottom: 0.5px solid black  ;" >
                <tr>
                <td colspan="2"><strong>  <?php echo utf8_encode ('Razón Social:') ?> </strong> <?php echo strtoupper(strtolower($factura->fac_nombre))?></td>
                <td></td>
                    
                </tr>    
                <tr>

                    <td><strong>Email: </strong><?php echo strtolower($factura->cli_email)?></td>
                    <td><strong> <?php echo 'Cédula/RUC:' ?> </strong><?php echo $factura->fac_identificacion?></td>
                </tr>    
                <tr>
                    <td><strong><?php echo utf8_encode('Dirección:') ?> </strong><?php echo ucwords(strtolower($factura->fac_direccion))?></td>
                    <td><strong><?php echo utf8_encode('Teléfono:') ?> </strong><?php echo $factura->fac_telefono?></td>
                </tr>

            </table>
        </td>
    </tr>      
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td colspan="2">
            <table id="detalle" style="" border="1"  width="100%" style="" class="table table-bordered table-list table-hover table-striped">
                <thead>
                    <tr >
                        <th  width="100px"> <b><?php echo utf8_encode('Código') ?> </b> </th>
                        <th style="width:70px"><b>Cantidad </b> </th>
                        <th width="250px"><b><?php echo utf8_encode('Descripción') ?> </b> </th>
                        <th style="width:70px"><b>Precio Unitario </b> </th>
                        <th style="width:80px"><b>Descuento </b> </th>
                        <th style="width:70px"><b>Precio Total </b> </th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                    ?>
                    <tr >
                        <td width="100px"><?php echo $det->pro_codigo?></td>
                        <td class="numerico"><?php echo number_format($det->cantidad,$dcc)?></td>
                        <td width="250px"><?php echo ucfirst(strtolower($det->pro_descripcion))?></td>
                        <td class="numerico"><?php echo number_format($det->pro_precio,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->pro_descuento,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->precio_tot,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tbody>
                    <tr>
                        <td  colspan="3" rowspan="9" valign="top" style="background: #ffffff !important;">
                            <table id="info" >
                                <tr>
                                    <td style=" background: #ffffff !important;" ><strong> <?php echo 'Información Adicional:' ?></strong></td>
                                </tr>
                                <tr>
                                    <td style=" background: #ffffff !important;" ><strong>Observaciones: </strong> <?php echo ucfirst(strtolower($factura->fac_observaciones))?> </td>
                                </tr>
                                
                            </table>
                        </td>
                        <td colspan="2"><strong>Subtotal <?php echo $t_iva ?>%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal12,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Subtotal 0%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal0,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Subtotal no objeto de IVA</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal_no_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Subtotal excento IVA</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal_ex_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Subtotal sin impuestos</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Descuento</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_descuento,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>IVA <?php echo $t_iva ?>%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Propina</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_propina,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>VALOR TOTAL </strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_valor,$dec)?></td>
                    </tr>
                    
                    
                </tbody>   
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table id="pagos">
                <tr>
                    <td width="50%"><strong>Forma de Pago</strong></td>
                     <td width="50%"><strong>Valor</strong></td>
                </tr>
                    <?php
                    foreach ($cns_pag as $rst_pag) {
                    ?>
                        <tr>
                            <td width="50%"><?php echo $rst_pag->fpg_codigo.' - '.ucwords(strtolower($rst_pag->fpg_descripcion_sri))?></td>
                            <td width="50%" >$ <?php echo number_format($rst_pag->pag_cant,$dec)?></td>
                        </tr>
                    <?php    
                        }
                    ?>
            </table>
        </td>
    </tr>  
    <tr>
        <td>
            <h2>
                <?php echo $leyenda_pie ?>
            </h2>
            
        </td>
    </tr>  
</table>


<style type="text/css">
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
    

    .numerico{
        text-align: right;
    }

    #encabezado3{
      
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

    #detalle tr:nth-child(2n-1) td{
      background-color: #DFDFDF !important;
      background-color: white;
    }

    #detalle tr:nth-child(2n-1) th {
      background-color: #DFDFDF !important;
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
        font-size: 15px;
        font-weight: bold;
    }
.mensaje {
color: #828282;
font-family: Arial, Helvetica, sans-serif;
font-size: 14px;
justify-content: right;
font-weight: bolder;
}



</style>

         

