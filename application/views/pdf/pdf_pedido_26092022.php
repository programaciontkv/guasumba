<section class="content">
  <!-- <td><img src="<?php echo base_url().'imagenes/logo_empresa.jpg'?>" width="250px" height="100px"></td> -->
  <table width="100%">
    <tr>
        
        <td colspan="2" width="100%">
            <table id="encabezado2" width="100%">
                <tr>
                    <th class="titulo"><?php echo $pedido->emi_nombre?></th>
                </tr>
                <tr>
                    <th class="titulo"><?php echo $pedido->emi_dir_establecimiento_emisor?></th>
                </tr>
                <tr>
                    <th ><?php echo $pedido->emi_ciudad .' - '. $pedido->emi_pais ?></th>
                </tr>
                <tr>
                    <th ><?php echo $pedido->emi_telefono?></th>
                </tr>   
                 <tr>
                    <th ><?php echo $pedido->emp_identificacion?></th>
                </tr>    
                <tr>
                    <th style="text-align: right;">Proforma N°: <?php echo $pedido->ped_num_registro?></th>
                </tr>    
                 
            </table>
        </td>
    </tr>    
    
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Cliente: <?php echo $pedido->ped_nom_cliente?></th>
                    <th>Fecha: <?php echo $pedido->ped_femision?></th>
                </tr>    
                <tr>
                    <th>Direccion: <?php echo $pedido->ped_dir_cliente?></th>
                    <th>Vendedor: <?php echo $pedido->vnd_nombre?></th>
                </tr>    
                <tr>
                    <th>Telefono:<?php echo $pedido->ped_tel_cliente?></th>
                    <th></th>
                </tr>    
            </table>
        </td>
    </tr>      
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th style="width:70px">Cantidad</th>
                        <th style="width:70px">V.Unitario</th>
                        <th style="width:70px">% IVA</th>
                        <th style="width:70px">% Descuento</th>
                        <th style="width:70px">V.Total</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                    ?>
                    <tr>
                        <td><?php echo $det->det_cod_producto?></td>
                        <td><?php echo $det->det_descripcion?></td>
                        <td class="numerico"><?php echo number_format($det->det_cantidad,$dcc)?></td>
                        <td class="numerico"><?php echo number_format(doubleval($det->det_vunit),$dec)?></td> 
                        <td class="numerico"><?php echo number_format(doubleval($det->det_impuesto),$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->det_descuento_porcentaje,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->det_total,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tbody>
                    <tr>
                        <th>ELABORADO</th>
                        <th>VTO.BUENO</th>
                        <th colspan="2">CLIENTE</th>
                        <th colspan="2">DESCUENTO</th>
                        <td class="numerico"><?php echo number_format($pedido->ped_tdescuento,$dec)?></td>
                    </tr>
                    <tr>
                        <td rowspan="4" valign="top">
                            
                        </td>
                        <td rowspan="4" valign="top">
                            
                        </td>
                        <td colspan="2" rowspan="4" valign="top">
                            
                        </td>
                        <th colspan="2">TARIFA 0%</th>
                        <td class="numerico"><?php echo number_format($pedido->ped_sbt0,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">TARIFA 12%</th>
                        <td class="numerico"><?php echo number_format($pedido->ped_sbt12,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">IVA 12%</th>
                        <td class="numerico"><?php echo number_format($pedido->ped_iva12,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <td class="numerico"><?php echo number_format($pedido->ped_total,$dec)?></td>
                    </tr>
                    <tr>
                        <th>OBSERVACIONES:</th>
                        <td colspan="6"><?php echo $pedido->ped_observacion?></td>
                    </tr>
                    
                    
                </tbody>   
            </table>
        </td>
    </tr>    
</table>

<style type="text/css">
    *{
        font-size: 13px;
    }
    .numerico {
        text-align: right;
    }

    #encabezado1, #encabezado3 {
        border: 1px solid;
        text-align: left;
    }

    #encabezado2 {
        border: 0px;
        text-align: left;
    }

    #detalle {
        border: 1px solid;
        border-collapse: collapse;
    }

    #encabezado1 td, #encabezado1 th,   #encabezado3 td, #encabezado3 th{
        text-align: left;
    }

    #encabezado2 th, #encabezado2 td{
     text-align: center;   
    }

    #detalle td, #detalle th{
        border: 1px solid;
        
    }

    #info td, #info th{
        border: none;
    }

    .titulo{
        font-size: 15px;
    }



</style>


         

