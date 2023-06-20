<section class="content">
  <table width="100%">
    <tr>
        <td>
            <table id="login">
                <tr>
                    <td>
                        <td><img src="<?php echo base_url().'imagenes/'.$factura->emp_logo?>" width="250px" height="100px"></td>
                    </td>
                </tr>    
            </table>
        </td>
        <td rowspan="2" width="52%">
            <table id="encabezado2" width="100%">
                <tr>
                    <th class="titulo">R.U.C: </th>
                    <th class="titulo"><?php echo $factura->emp_identificacion?></th>
                </tr>
                <tr>
                    <th class="titulo" colspan="2">FACTURA</th>
                </tr>    
                <tr>
                    <th colspan="2">No. <?php echo $factura->fac_numero?></th>
                </tr>    
                <tr>
                    <th colspan="2">NUMERO DE AUTORIZACION</th>
                </tr>    
                <tr>
                    <th colspan="2"><?php echo $factura->fac_autorizacion?></th>
                </tr>    
                <tr>
                    <th>FECHA Y HORA DE AUTORIZACION</th>
                    <th><?php echo $factura->fac_fec_hora_aut?></th>
                </tr>    
                <tr>
                    <?php 
                    switch ($ambiente->con_valor) {
                      case 0:
                        $amb='';
                        break;
                      case 1:
                        $amb='PRUEBAS';
                        break;
                      case 2:
                        $amb='PRODUCCION';
                        break;  
                    }
                    ?>
                    <th>AMBIENTE</th>
                    <th><?php echo $amb?></th>
                </tr>    
                <tr>
                    <th>EMISION</th>
                    <th>NORMAL</th>
                </tr>    
                <tr>
                    <th>CLAVE DE ACCESO</th>
                    
                </tr>
                <tr> 
                    <td colspan="2">
                        <img src="<?php echo base_url();?>barcodes/<?php echo $factura->fac_clave_acceso?>.png" alt="" width="350px" height="40px">
                    </td>
                </tr> 
                <tr>
                    <th colspan="2" style="font-size: 11px; text-align: center"><?php echo $factura->fac_clave_acceso?></th>
                 </tr>    
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="48%" valign="bottom">
            <table id="encabezado1" width="98%">
                <tr>
                    <th class="titulo" colspan="2"><?php echo $factura->emp_nombre?></th>
                </tr>    
                <tr>
                    <th>Direccion Matriz:</th>
                    <th><?php echo $factura->emp_direccion?></th>
                </tr>
                <tr>
                    <th>Direccion Sucursal:</th>
                    <th><?php echo $factura->emi_dir_establecimiento_emisor?></th>
                </tr>
                <?php 
                if(!empty($factura->emp_contribuyente_especial)){
                ?>
                <tr>
                    <th colspan="2">Cotribuyente Especial Nro:</th>
                    <th><?php echo $factura->emp_contribuyente_especial?></th>
                </tr>
                <?php 
                }
                ?>    
                <tr>
                    <th colspan="2">OBLIGADO A LLEVAR CONTABILIDAD:</th>
                    <th><?php echo $factura->emp_obligado_llevar_contabilidad?></th>
                </tr>    
            </table>
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Razon Social / Nombres y Apellidos: <?php echo $factura->fac_nombre?></th>
                    <th>Identificacion: <?php echo $factura->fac_identificacion?></th>
                </tr>    
                <tr>
                    <th>Fecha de emision: <?php echo $factura->fac_fecha_emision?></th>
                    <th>Guia de Remision:</th>
                </tr>    
                <tr>
                    <th>Direccion:<?php echo $factura->fac_direccion?></th>
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
                        <th>Cod.Principal</th>
                        <th style="width:70px">Cantidad</th>
                        <th>Descripcion</th>
                        <th style="width:70px">P.U</th>
                        <th style="width:70px">Descuento</th>
                        <th style="width:70px">Precio Total</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                    ?>
                    <tr>
                        <td><?php echo $det->pro_codigo?></td>
                        <td class="numerico"><?php echo number_format($det->cantidad,$dcc)?></td>
                        <td><?php echo $det->pro_descripcion?></td>
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
                        <td id="info" colspan="3" rowspan="8" valign="top">
                            <table>
                                <tr>
                                    <th>Informacion Adicional</th>
                                </tr>
                                <tr>
                                    <td>Direccion</td>
                                    <td><?php echo $factura->fac_direccion?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo strtolower($factura->cli_email)?></td>
                                </tr>
                            </table>
                        </td>
                        <th colspan="2">SUBT 12%</th>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal12,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT IVA 0%</th>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal0,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT EX IVA</th>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal_ex_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT NO-OBJ IVA</th>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal_no_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT SIN IMPUESTOS</th>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">DESCUENTO</th>
                        <td class="numerico"><?php echo number_format($factura->fac_total_descuento,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">IVA 12%</th>
                        <td class="numerico"><?php echo number_format($factura->fac_total_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">VALOR TOTAL</th>
                        <td class="numerico"><?php echo number_format($factura->fac_total_valor,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <table>
                                <tr>
                                    <th>Forma de Pago</th>
                                    <th>Valor</th>
                                </tr>
                                <?php
                                foreach ($cns_pag as $rst_pag) {
                                    if ($rst_pag->fpg_tipo==1) {
                                        $pagoforma = "Pago con Tarjeta de Credito";
                                    }else if ($rst_pag->fpg_tipo==2) {
                                        $pagoforma = "Pago con tarjeta de Debito";
                                    }else if ($rst_pag->fpg_tipo==3 || $rst_pag->fpg_tipo==6 ||  $rst_pag->fpg_tipo==9) {
                                        $pagoforma = "20-Otros con utilizacion del sistema financiero ";
                                    }else {
                                        $pagoforma = "01-Sin utilizacion del sistema financiero";
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $pagoforma?></th>
                                        <td class="numerico">$ <?php echo number_format($rst_pag->pag_cant,$dec)?></td>
                                    </tr>
                                <?php    
                                }
                                ?>
                                
                            </table>
                        </td>
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

    #encabezado1,#encabezado2, #encabezado3 {
        border: 1px solid;
        text-align: left;
    }

    #detalle {
        border: 1px solid;
        border-collapse: collapse;
    }

    #encabezado1 td, #encabezado1 th, #encabezado2 td, #encabezado2 th, #encabezado3 td, #encabezado3 th{
        text-align: left;
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


         

