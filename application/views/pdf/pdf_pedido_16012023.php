<section class="content">
 <!--  <td><img src="<?php echo base_url().'imagenes/logo_empresa.jpg'?>" width="250px" height="100px"></td>  -->
  <table width="100%">
    <tr>
        <td width="50%">
            <table id="login">
                <tr>
                    <td>
                        <td><img src="<?php echo base_url().'imagenes/'.$pedido->emp_logo?>" width="250px" height="100px"></td>
                    </td>
                </tr>    
            </table>
        </td>
        
        <td width="50%" >
            <table id="encabezado2" >
                    
                <tr >
                    <td class="titulo2" > <?php echo utf8_encode( 'Pedido N°:')?> <?php echo $pedido->ped_num_registro?></td>
                </tr>    
                <tr>
                     <td > <?php echo utf8_encode('Fecha emisión:')?> <?php echo $pedido->ped_femision?></td>
                </tr>
                <tr>
                    <td >Fecha entrega: <?php echo $pedido->ped_fentrega?></td>
                 </tr>
                 
            </table>
        </td>
    </tr>
    <tr>
            <td>
                <table id="encabezado4" width="38%">
                <tr>
                    <td class="titulo2"><?php echo $pedido->emi_nombre?></td>
                </tr>
                <tr>
                    <td class="titulo"><?php echo $pedido->emi_dir_establecimiento_emisor?></td>
                </tr>
                <!-- <tr>
                    <th ><?php echo $pedido->emi_ciudad .' - '. $pedido->emi_pais ?></th>
                </tr> -->
                   
                 <tr>
                    <td class="titulo2"><?php echo $pedido->emp_identificacion?></td>
                </tr> 
                <tr>
                    <td ><?php echo $pedido->emi_telefono?></td>
                </tr>   
            </table>
                
            </td>
            
        </tr>    
    
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Razón Social: <?php echo $pedido->ped_nom_cliente?></th>
                </tr>    
                <tr>
                    <th>Ruc: <?php echo $pedido->ped_ruc_cc_cliente?></th>
                    <th>Direccion: <?php echo $pedido->ped_dir_cliente?></th>
                </tr>    
                <tr>
                     <th>Telefono:<?php echo $pedido->ped_tel_cliente?></th>
                    <th>Vendedor: <?php echo $pedido->vnd_nombre?></th>
                   
                    <th></th>
                </tr>    
            </table>
             <br>
            <br>
        </td>
    </tr>  

    <tr>
        <td colspan="2">
            <table id="detalle" width="100%"  class="table table-bordered table-list table-hover table-striped">
                <thead>
                    <tr>
                        <th style="width:70px">Codigo</th>
                        <th colspan="3">Descripcion</th>
                        <th style="width:70px">Cantidad</th>
                        <th style="width:70px">V.Unitario</th>
                        <!-- <th style="width:70px">% IVA</th>
                        <th style="width:70px">% Descuento</th> -->
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
                        <td style="width:70px"><?php echo $det->det_cod_producto?></td>
                        <td colspan="3"><?php echo $det->det_descripcion?></td>
                        <td class="numerico"><?php echo number_format($det->det_cantidad,$dcc)?></td>
                        <td  class="numerico"><?php echo number_format(doubleval($det->det_vunit),$dec)?></td> 
                        <!-- <td class="numerico"><?php echo number_format(doubleval($det->det_impuesto),$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->det_descuento_porcentaje,$dec)?></td> -->
                        <td class="numerico"><?php echo number_format($det->det_total,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tbody>
                    <tr>
                        <th rowspan="5" colspan="4"></th>
                       
                        <th colspan="2" >DESCUENTO</th>
                        <td class="numerico"><?php echo number_format($pedido->ped_tdescuento,$dec)?></td>
                    </tr>
                    <tr>
                        <!-- <td rowspan="4" colspan="4" valign="top">
                            
                        </td> -->
                        
                      
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
            <br>
            <br>
            

            
            <br>
            <br>
            <table>
            <tr>
                <td>
                        <table id="pagos">
                        <tr>
                        <td><strong>Forma de Pago</strong></td>
                        <td><strong> Valor </strong></td>
                        </tr>
                        <?php
                        foreach ($cns_pag as $rst_pag) {
                        ?>
                        <tr>
                        <td><?php echo $rst_pag->fpg_codigo.' - '.ucwords(strtolower($rst_pag->fpg_descripcion_sri))?></td>
                        <td class="numerico">$ <?php echo number_format($rst_pag->pag_cant,$dec)?></td>
                        </tr>
                        <?php    
                        }
                        ?>
                    </table>
                </td>
            </tr>
            </table>
            <br>
            <br>
            <br>
            <br>

            <table id="firmas" >
                <tr>
                        
                        <th>ELABORADO</th>
                        <th>VTO.BUENO</th>
                        <th >CLIENTE</th>
                    </tr>
                    <tr>
                        <td>___________________</td>
                        <td>___________________</td>
                        <td>___________________</td>
                    </tr>
            </table>

            
        </td>
    </tr>    
</table>

<style type="text/css">
    *{
        font-size: 13px;
        /*font-family:"Calibri ligth";*/
        font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
    }
    .numerico {
        text-align: right;
    }

    #encabezado1, #encabezado3 {

        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
    }
    #encabezado4 {
        text-align: left;
    }

    #encabezado2 tr , #encabezado2 td {
        justify-content: right;

    }

    /*#detalle {
        border: 1px solid;
        border-collapse: collapse;
    }*/

    #encabezado1 td, #encabezado1 th,   #encabezado3 td, #encabezado3 th{
        text-align: left;
    }

    /*#encabezado2 th, #encabezado2 td{
     text-align: center;   
    }*/

    /*#detalle td, #detalle th{
        border: 1px solid;
        
    }*/

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

    #info td, #info th{
        border: none;
    }

    .titulo{
        font-size: 15px;
    }
    .titulo2{
        font-size: 18px;
        font-weight: bold;
    }
    #pagos{
        border-top: 1px  solid;
    }
    #firmas{
            margin-left: auto;
            margin-right: auto;
            text-align: left; /* Para que deje el texto dentro de la tabla hacia la izquierda */
            border-collapse: separate;
            border-spacing: 80px 5px;
    }



</style>


         

