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
  if($cheque->chq_secuencial !=''){

  
?>
<table width="100%" style="margin-top:-40px">
  <tr>    
        <td colspan="3" width="100%" >
            <table id="encabezado1" width="100%">
                <tr><td>  </td> 
                <td rowspan="2" width="15%"><img src="<?php echo base_url().'imagenes/'.$logo?>"  width="130px" height="70px"></td>
                 </tr>
            </table>
        </td>
    </tr>
  
</table>
 


<?php
}
?>

<!-- <p align="left"><img src="<?php echo base_url().'imagenes/'.$logo?>" width="250px" height="100px"> </p> -->
  <p align="center" class="titulo_p"><strong>DETALLE DE COBROS</strong>

    
  </p>
  <p style="text-align: right;color: red;" align="right"  class="sub_titulo"><strong style="color:black;">COMPROBANTE DE INGRESO NO.: </strong> 
    <?php echo $cheque->chq_secuencial ?> </p>

  <table  id="tbl_list" class="table table-bordered table-list table-hover" width="100%" >
      <tr>
          <td>
              <strong>Cliente:</strong>  <?php echo ucwords(strtolower($cheque->cli_raz_social)) ?>
          </td>
          
      </tr>
      <tr>
        
          <td>
              <strong>Tipo Pago:</strong> <?php echo ucwords(strtolower($tp)) ?>
          </td>
          <td>
            <?php
            if($cheque->chq_estado_cheque=='3' || $cheque->chq_estado_cheque == '12')
            {
                $letra='style="color:red"';
            }else{
                $letra='';

            }
            ?>
              <strong <?php echo $letra ?> >Estado Cheque: <?php echo $cheque->est_cheque?> </strong> 
          </td>
          
      </tr>
      <tr>
          <td>
              <strong>Numero:</strong> <?php echo $cheque->chq_numero?>
          </td>
          <td>
              <strong>Fecha registro :</strong> <?php echo $cheque->chq_recepcion?>
          </td>
      </tr>
      <tr>
          <td>
              <strong>Banco :</strong> <?php echo  ucwords(strtolower($cheque->pln_descripcion)) ?>
          </td>
            <?php
                if($cheque->chq_estado_cheque=='3' || $cheque->chq_estado_cheque == '12')
                {
            ?>
              <td>
              <strong>Motivo:</strong> <?php echo  $cheque->chq_est_observacion ?>
              </td>
            <?php
                }
            ?>
      </tr>
      <tr>
          <td>
              <strong>Valor $:</strong> <?php echo  str_replace(',','',number_format($cheque->chq_monto,$dec))?>
          </td>
      </tr>
  </table>

                            <table id="detalle" width="100%">
                              <thead>
                                <tr>
                                  <th>Fecha Cobro</th>
                                  <th>Concepto</th>
                                  <th>Factura</th>
                                  <th>Asiento</th>
                                  <th>Valor $</th>
                                </tr>
                              </thead>

                              <tbody>
                                <?php
                                $total=0;
                                $n=0;
                                $grup='';
                                foreach ($pagos as $pag) {
                                  $n++;
                                  
                                ?>
                                    <tr>
                                      <?php
                                      if($grup!=$pag->fecha_pago )
                                      {
                                        ?>
                                        <td><?php echo $pag->fecha_pago?></td>
                                      <?php
                                      }else{
                                        ?>
                                        <td></td>
                                       <?php 
                                      }
                                      ?>
                                        <td><?php echo $pag->concepto?></td>
                                        <td><?php echo $pag->factura?></td>
                                        <td><?php echo $pag->asiento?></td>
                                        <td style="text-align: right;"><?php echo str_replace(',','',number_format($pag->valor,$dec))?></td>
                                        
                                    </tr>
                                <?php
                                  $total+=round($pag->valor,$dec);
                                  $grup=$pag->fecha_pago;
                                }
                                ?>
                                <tr>
                                  <td colspan="3"></td>
                                  <td><strong>Total</strong></td>
                                  <td style="text-align: right;"><strong><?php echo str_replace(',','',number_format($total,$dec))?></strong></td>
                                </tr>
                                
                              </tbody>        
                             
                            </table>
                      

    <style type="text/css">

    *,label{
        font-size: 15px;
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
    .sub_titulo{
        font-size: 18px;
        text-align: center;
        font-family: "calibri", "bold";
    }
    .titulo_p{
        font-size: 19px;
        text-align: center;
        font-family: "calibri", "bold";

    }
    </style>
