<section class="content">
  <p align="center"><strong>Detalle de Pagos</strong></p>
  <p><strong>Codigo Cliente: </strong><?php echo $cliente->cli_codigo;?></p>
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
                                  $saldo+=$pag->pag_valor;
                                  if($saldo_vencido->pag_fecha_v<=date('Y-m-d')){
                                    $sal_ven=round($saldo_vencido->reg_total,$dec)-round($saldo_vencido->pago,$dec);
                                  }else{
                                    $sal_ven=0;
                                  }
                                  $debito=$pag->pag_valor;
                                  $cerdito=0;
                                ?>
                                    <tr>
                                        <td><?php echo $pag->pag_fecha_v?></td>
                                        <td></td>
                                        <td><?php echo $pag->reg_num_documento?></td>
                                        <td><?php echo $pag->reg_concepto?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($debito,$dec))?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($credito,$dec))?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format(str_replace(',','',$saldo),$dec))?></td>
                                        <td style="text-align: right;">
                                            <?php 
                                            if($n==1){
                                              echo str_replace(',','',number_format($sal_ven,$dec));
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
                                  $credito=$det->ctp_monto;
                                  $saldo=round($saldo,$dec)-round($credito,$dec);
                                  switch ($det->ctp_forma_pago) {
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
                                        <td><?php echo $det->ctp_fecha_pago?></td>
                                        <td><?php echo $fp?></td>
                                        <td><?php echo $det->num_documento?></td>
                                        <td><?php echo $det->ctp_concepto?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($debito,$dec))?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($credito,$dec))?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($saldo,$dec))?></td>
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
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($t_debito,$dec))?></th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($t_credito,$dec))?></th>
                                        <th id="saldo" style="text-align: right;"><?php echo str_replace(',','',number_format($saldo,$dec))?></th>
                                        <th style="text-align: right;"><?php echo str_replace(',','',number_format($sal_ven,$dec))?></th>
                                    </tr>
                              </tfoot>
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
        font-size: 20px;
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

