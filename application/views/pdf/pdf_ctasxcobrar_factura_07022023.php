<section class="content">
  <p align="center"><strong>Detalle de Pagos</strong></p>
<!--   <p><strong>Codigo Cliente: </strong><?php echo $cliente->cli_codigo;?></p> -->
  <p><strong>Nombre Cliente: </strong><?php echo $cliente->cli_raz_social;?></p>
                  
                    
                            <table id="detalle" width="100%">
                              <thead>
                                <tr>
                                  <th>Fecha</th>
                                  <th>Forma de Pago</th>
                                  <th>Documento</th>
                                  <th>Concepto</th>
                                  <th>Debito</th>
                                  <th>Credito</th>
                                  <th>Saldo</th>
                                  <th>Saldo Vencido</th>
                                </tr>
                              </thead>

                              <tbody>
                                <?php
                                $saldo=0;
                                $sal_ven=0;
                                $t_debito=0;
                                $t_credito=0;
                                $n=0;
                                foreach ($cns_pag as $pag) {
                                  $n++;
                                  $saldo+=$pag->pag_cant;
                                  if($saldo_vencido->pag_fecha_v<date('Y-m-d')){
                                    $sal_ven=round($saldo_vencido->fac_total_valor,$dec)-round($saldo_vencido->pago,$dec);
                                  }else{
                                    $sal_ven=0;
                                  }
                                  $debito=$pag->pag_cant;
                                  $cerdito=0;
                                ?>
                                    <tr>
                                        <td><?php echo $pag->pag_fecha_v?></td>
                                        <td></td>
                                        <td><?php echo $pag->fac_numero?></td>
                                        <td>FACTURACION EN VENTAS</td>
                                        <td style="text-align: right;"><?php echo number_format(str_replace(',','',$debito),$dec)?></td>
                                        <td style="text-align: right;"><?php echo number_format($credito,$dec)?></td>
                                        <td style="text-align: right;"><?php echo number_format(str_replace(',','',$saldo),$dec)?></td>
                                        <td style="text-align: right;">
                                            <?php 
                                            if($n==1){
                                              echo number_format(str_replace(',','',$sal_ven),$dec);
                                            }
                                            
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                $t_debito+=$debito;
                                $t_credito+=$credito;
                                }
                                ?>
                                <?php
                                
                                foreach ($cns_det as $det) {
                                  
                                  $debito=0;
                                  $credito=$det->cta_monto;
                                  $saldo=round($saldo,$dec)-round($credito,$dec);
                                  switch ($det->cta_forma_pago) {
                                    case '1': $fp="TARJETA DE CREDITO"; break;
                                    case '2': $fp="TARJETA DE DEBITO"; break;
                                    case '3': $fp="CHEQUE"; break;
                                    case '4': $fp="EFECTIVO"; break;
                                    case '5': $fp="CERTIFICADOS"; break;
                                    case '6': $fp="TRANSFERENCIA"; break;
                                    case '7': $fp="RETENCION"; break;
                                    case '8': $fp="NOTA CREDITO"; break;
                                    case '9': $fp="CREDITO"; break;
                                    case '10': $fp="CRUCE DE CUENTAS"; break;
                                  }

                                ?>
                                    <tr>
                                        <td><?php echo $det->cta_fecha_pago?></td>
                                        <td><?php echo $fp?></td>
                                        <td><?php echo $det->num_documento?></td>
                                        <td><?php echo $det->cta_concepto?></td>
                                        <td style="text-align: right;"><?php echo number_format($debito,$dec)?></td>
                                        <td style="text-align: right;"><?php echo number_format(str_replace(',','',$credito),$dec)?></td>
                                        <td style="text-align: right;"><?php echo number_format(str_replace(',','',$saldo),$dec)?></td>
                                        <td style="text-align: right;"><?php echo number_format(0,$dec)?></td>
                                    </tr>
                                <?php
                                $t_debito+=$debito;
                                $t_credito+=$credito;
                                }
                                ?>    
                              </tbody>        
                              <tfoot>
                                <tr>
                                        <th colspan="3"></th>
                                        <th>Total</th>
                                        <th style="text-align: right;"><?php echo number_format(str_replace(',','',$t_debito),$dec)?></th>
                                        <th style="text-align: right;"><?php echo number_format(str_replace(',','',$t_credito),$dec)?></th>
                                        <th id="saldo" style="text-align: right;"><?php echo number_format(str_replace(',','',$saldo),$dec)?></th>
                                        <th style="text-align: right;"><?php echo number_format(str_replace(',','',$sal_ven),$dec)?></th>
                                    </tr>
                              </tfoot>
                            </table>
                      

    <style type="text/css">
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
