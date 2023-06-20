<section class="content">
  <table width="100%" style="margin-top:-40px;margin-left: -10px;margin-right: -10px;">
    <tr>    
        <td colspan="2" width="100%" >
            <table  width="100%">
                <tr><td>  </td> 
                <td rowspan="2" width="15%"><img src="<?php echo base_url().'imagenes/'.$pedido->emp_logo?>"  width="130px" height="70px"></td>
                 </tr>
            </table>
        </td>
    </tr> 
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr >


        <td colspan="2"  >
            <div style="text-align:center;">
                <p class="titulo2"><?php echo utf8_encode( 'PEDIDO DE VENTA')?>  </p>
            </div>
            
        </td>

        
    </tr>

    <tr>
        <td colspan="2" class="sub_titulo" style="text-align: right;"><?php echo utf8_encode(' N°: ')?>
            <label class="sub_titulo"  style="color:red">
                 <?php echo $pedido->ped_num_registro?></td>
            </label>
    </tr>
        
    
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
               

                <tr>
                    <?php
                   $nombre= str_replace ( "Ñ" , "ñ" , $pedido->ped_nom_cliente )
                    ?>
                    <td><strong>  <?php echo utf8_encode('Razón Social:') ?> </strong> <?php echo ucwords(strtoupper($nombre))?></td>
                    
                </tr>    
                <tr>
                    <td><strong>Email: </strong><?php echo strtolower($pedido->ped_email_cliente)?></td>
                    <td><strong> <?php echo utf8_encode('Cédula/RUC:') ?> </strong><?php echo $pedido->ped_ruc_cc_cliente?></td>
                </tr>    
                <tr>
                    <?php
                   $dire= str_replace ( "Ñ" , "ñ" , $pedido->ped_dir_cliente )
                    ?>
                    <td><strong><?php echo utf8_encode('Dirección:') ?> </strong><?php echo utf8_encode( ucwords(strtoupper($dire)))?></td>
                    <td><strong><?php echo utf8_encode('Teléfono:') ?> </strong><?php echo $pedido->ped_tel_cliente?></td>
                </tr>
                <tr>
                     <td > <strong>  <?php echo utf8_encode('Fecha emisión:')?> </strong>  <?php echo $pedido->ped_femision?></td>
                </tr>
                <tr>
                    <td > <strong> Fecha entrega: </strong>  <?php echo $pedido->ped_fentrega?></td>
                 </tr>
        </td>
            </table>
             <br>
            <br>
        </td>
    </tr>  

    <tr>
        <td colspan="2">
            <table id="detalle" width="95%"  class="table table-bordered table-list table-hover table-striped">
                <thead>
                    <tr>
                        <th style="width:70px"> <?php echo utf8_encode('Código') ?> </th>
                        <th colspan="3"> <?php echo utf8_encode('Descripción') ?></th>
                        
                        <?php 
                        if ($etq!='hidden') {
                            ?>
                            <th <?php echo $etq ?> > <?php echo utf8_encode('Imagen') ?></th>
                       <?php }
                        ?>
                        <th style="width:70px">Cantidad</th>
                        <th style="width:70px">V.Unitario</th>
                        <!-- <th style="width:70px">% IVA</th>
                        <th style="width:70px">% Descuento</th> -->
                        <th style="width:70px">V.Total</th>
                    </tr> 
                </thead> 
                <tbody >
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {

                        if ($height=='1') {
                            
                            ?>
                            
                            <?php

                        }

                        ?>
                        
                <tr >
                        <td <?php echo $height ?> style="width:70px"><?php echo $det->det_cod_producto?></td>
                        <td colspan="3"><?php echo $det->det_descripcion?></td>
                        <?php 
                        if ($etq!='hidden') {
                            ?>
                            <td>
                            <?php
                            if ($det->img!='') {   
                             ?>
                             <img  align="center" src="<?php echo base_url().'imagenes/'.$det->img?>" width="50px" height="50px">
                               <?php
                               }
                               ?>
                             </td>
                       <?php }
                        ?>
                        
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
                        <th rowspan="5" <?php echo $colspan ?>></th>
                       
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
                        <td <?php echo $cols ?>><?php echo $pedido->ped_observacion?></td>
                    </tr>
                    
                    
                    
                </tbody>   
            </table>
            <br>
            <br>
            

            
            <br>
            <br>
            <table  width="50%" >
            <tr class="pagos">
                <td class="pagos" colspan="2">
                        <table >
                        <tr>
                        <td><strong>Forma de Pago</strong></td>
                        <td><strong> Valor </strong></td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
            <?php
                        foreach ($cns_pag as $rst_pag) {
                        ?>
                        <tr>
                        <td ><?php echo $rst_pag->fpg_codigo.' - '.ucwords(strtolower($rst_pag->fpg_descripcion_sri))?></td>
                        <td class="numerico">$ <?php echo number_format($rst_pag->pag_cant,$dec)?></td>
                        </tr>
                        <?php    
                        }
                        ?>
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
<script type="text/javascript">
     window.onload = function () {
        
          document.getElementById("tdetalle").style.height = "50px";        
        
      }

    
</script>

<style type="text/css">
    *{
        font-size: 15px;
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


    #encabezado1 td, #encabezado1 th,   #encabezado3 td, #encabezado3 th{
        text-align: left;
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
    .pagos{
        border-top: 1px  solid;
    }
    #firmas{
            margin-left: auto;
            margin-right: auto;
            text-align: left; /* Para que deje el texto dentro de la tabla hacia la izquierda */
            border-collapse: separate;
            border-spacing: 80px 5px;
    }
    .titulo_p{
        font-size: 19px;
        text-align: center;
        font-family: "calibri", "bold";

    }
    .sub_titulo{
        font-size: 18px;
        text-align: center;
        font-family: "calibri", "bold";
    }


</style>


         

