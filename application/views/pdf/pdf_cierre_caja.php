<?php
    $dec=$dec->con_valor;
?>
<section class="content">
  <table width="100%">
    <tr>
        <td>
            <table id="login">
                    
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="50%" valign="bottom">
            <table id="encabezado1" width="50%">
                <tr>
                    <td colspan="4"><img src="<?php echo base_url().'imagenes/'.$cierre->emp_logo?>" width="200px" height="80px"></td>
                </tr>
               <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><?php echo $cierre->cie_secuencial?></th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Fecha:</th>
                    <td><?php echo $cierre->cie_fecha?></td>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Hora:</th>
                    <td><?php echo $cierre->cie_hora?></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="2">Usuario:</th>
                    <td><?php echo $cierre->usu_person?></td>
                </tr>
                <tr>
                    <th colspan="2">Almacen:</th>
                    <td><?php echo $cierre->emi_nombre?></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="2">Facturas Emitidas:</th>
                    <td class="numerico"><?php echo $cierre->cie_fac_emitidas?></td>
                </tr>
                <tr>
                    <th colspan="2">Productos Facturados:</th>
                    <td class="numerico"><?php echo $cierre->cie_productos_facturados?></td>
                </tr>
                 <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="2">Subtotal:</th>
                    <td class="numerico"><?php echo number_format($cierre->cie_subtotal,$dec)?></td>
                </tr>
                <tr>
                    <th colspan="2">Descuento:</th>
                    <td class="numerico"><?php echo number_format($cierre->cie_descuento,$dec)?></td>
                </tr>
                <tr>
                    <th colspan="2">IVA:</th>
                    <td class="numerico"><?php echo number_format($cierre->cie_iva,$dec)?></td>
                </tr>
                <tr>
                    <th colspan="2">Total:</th>
                    <td class="numerico"><?php echo number_format($cierre->cie_total_facturas,$dec)?></td>
                </tr>
                 <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <th colspan="2">Total Facturas:</th>
                    <td class="numerico"><?php echo number_format($cierre->cie_total_facturas,$dec)?></td>
                </tr>
                <tr>
                    <th colspan="2">Total Notas Credito:</th>
                    <td class="numerico"><?php echo number_format($cierre->cie_total_notas_credito,$dec)?></td>
                </tr>
                <tr>
                    <?php
                    $tot_caja=round($cierre->cie_total_facturas,$dec)-round($cierre->cie_total_notas_credito,$dec);
                    ?>
                    <th colspan="2">Total en Caja:</th>
                    <td class="numerico"><?php echo number_format($tot_caja,$dec)?></td>
                </tr>
            </table>
        </td>
        
                
    </tr>
    <tr>
        <td width="50%" valign="bottom">
            <table id="detalle" width="50%">
                <thead>
                    <tr>
                        <th colspan="2">Formas de Pago</th>
                    </tr>
                    <tr>
                        <th>Tipo Pago</th>
                        <th style="width:70px">Valor</th>
                    </tr> 
                </thead> 
                <tbody>
                    
                    <tr>
                        <td>1 Tarjeta de Credito</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_tarjeta_credito,$dec)?></td>
                    </tr>
                    <tr>
                        <td>2 Tarjeta de Debito</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_tarjeta_debito,$dec)?></td>
                    </tr>
                    <tr>
                        <td>3 Cheque</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_cheque,$dec)?></td>
                    </tr>
                    <tr>
                        <td>4 Efectivo</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_efectivo,$dec)?></td>
                    </tr>
                    <tr>
                        <td>5 Certificados</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_certificados,$dec)?></td>
                    </tr>
                    <tr>
                        <td>6 Transferencias</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_bonos,$dec)?></td>
                    </tr>
                    <tr>
                        <td>7 Retencion</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_retencion,$dec)?></td>
                    </tr>
                    <tr>
                        <td>8 Nota Credito</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_not_credito,$dec)?></td>
                    </tr>
                    <tr>
                        <td>9 Credito</td>
                        <td class="numerico"><?php echo number_format($cierre->cie_total_credito,$dec)?></td>
                    </tr>
                    <tr>
                        <?php
                        $total_det=round($cierre->cie_total_tarjeta_credito,$dec)+round($cierre->cie_total_tarjeta_debito,$dec)+round($cierre->cie_total_cheque,$dec)+round($cierre->cie_total_efectivo,$dec)+round($cierre->cie_total_certificados,$dec)+round($cierre->cie_total_bonos,$dec)+round($cierre->cie_total_retencion,$dec)+round($cierre->cie_total_not_credito,$dec)+round($cierre->cie_total_credito,$dec);
                        ?>
                        <th>Total en Caja</th>
                        <td class="numerico"><?php echo number_format($total_det,$dec)?></td>
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
        text-align: right !important;
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


         

