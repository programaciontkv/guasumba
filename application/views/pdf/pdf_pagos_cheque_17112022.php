<section class="content">
<?php 
  $saldo=round($cheque->chq_monto,$dec)-round($cheque->chq_cobro,$dec);
  switch ($cheque->chq_tipo_doc) {
    case '1': $tp="TARJETA DE CREDITO"; break;
    case '2': $tp="TARJETA DE DEBITO"; break;
    case '3': $tp="CHEQUE A LA FECHA"; break;
    case '4': $tp="EFECTIVO"; break;
    case '5': $tp="CERTIFICADOS"; break;
    case '6': $tp="TRANSFERENCIA"; break;
    case '7': $tp="RETENCION"; break;
    case '8': $tp="NOTA CREDITO"; break;
    case '9': $tp="CREDITO"; break;
    case '10': $tp="CHEQUE POSTFECHADO"; break;
  }
?>
  <p align="center" class="titulo"><strong>Detalle de Pagos</strong></p>
  <p><strong>Cliente:</strong> <?php echo  $cheque->cli_raz_social?></p>
  <p><strong>Tipo Documento:</strong> <?php echo $tp?></p>
  <p><strong>Numero:</strong> <?php echo $cheque->chq_numero?></p>
  <p><strong>Banco:</strong> <?php echo  $cheque->chq_banco?></p>
  <p><strong>Monto:</strong> <?php echo  str_replace(',','',number_format($cheque->chq_monto,$dec))?></p>
  
 
                  
                    
                            <table id="detalle" width="100%">
                              <thead>
                                <tr>
                                  <th>Fecha Pago</th>
                                  <th>Concepto</th>
                                  <th>Factura</th>
                                  <th>Valor</th>
                                </tr>
                              </thead>

                              <tbody>
                                <?php
                                $total=0;
                                $n=0;
                                foreach ($pagos as $pag) {
                                  $n++;
                                  
                                ?>
                                    <tr>
                                        <td><?php echo $pag->fecha_pago?></td>
                                        <td><?php echo $pag->concepto?></td>
                                        <td><?php echo $pag->factura?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($pag->valor,$dec))?></td>
                                        
                                    </tr>
                                <?php
                                  $total+=round($pag->valor,$dec);
                                }
                                ?>
                                <tr>
                                  <td colspan="2"></td>
                                  <td><strong>Total</strong></td>
                                  <td style="text-align: right;"><strong><?php echo str_replace(',','',number_format($total,$dec))?></strong></td>
                                </tr>
                                
                              </tbody>        
                             
                            </table>
                      

    <style type="text/css">

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
    </style>
