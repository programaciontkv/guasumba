<section class="content">
  <!-- <td><img src="<?php echo base_url().'imagenes/logo_empresa.jpg'?>" width="250px" height="100px"></td> -->
 <table width="100%">
    <tr>
        <td>
            <table id="login">
                <tr>
                    <td>
                        
                    </td>
                </tr>    
            </table>
        </td>
        <td rowspan="2" width="52%">
            <table id="encabezado2" width="100%">
                
                <tr>
                    <td class="titulo" style=" border-collapse: separate;" colspan="2">COMPROBANTE DE RETENCION</td>
                </tr>    
                <tr>
                    <td colspan="2" style="font-size:20px">No. <?php echo $retencion->rgr_numero?></td>
                </tr>  
                <tr>
                    <td colspan="2"><?php echo utf8_encode('Fecha de Emisión:') ?>
                        <label style="font-weight: normal;">
                            <?php echo $retencion->rgr_fecha_emision?>
                        </label>
                    </td>
                </tr>    
                <tr>
                    <td colspan="2"><?php echo utf8_encode('Número de Autorización:') ?> </td>
                </tr>    
                <tr>
                    <td colspan="2"><?php echo $retencion->rgr_autorizacion?></td>
                </tr>    
                 
            </table>
        </td>
    </tr> 
    <tr>    
        <td width="48%" valign="bottom">
            <table id="encabezado1" width="100%">
                <tr>
                    <td class="titulo"  colspan="2"><?php echo $retencion->cli_raz_social?></td>
                </tr>
                
                <tr>
                    <td class="titulo"><?php echo $retencion->cli_ced_ruc?></td>
                </tr>    
                <tr >
                  
                   <td colspan="2">
                    <label style="font-weight: normal;">
                       <?php echo trim(ucwords(strtolower($retencion->cli_calle_prin)))?></td>
                   </label>
                    
                </tr>
                <tr>
                    <td colspan="2">
                     <?php echo utf8_encode('Teléfono:') ?> 
                     <label style="font-weight: normal;">
                          <?php echo ucwords(strtolower($retencion->cli_telefono))?></td>
                     </label>
                    
                    <th></th>
                </tr> 
                <tr>
                    <td colspan="2">Email: 
                            <label style="font-weight: normal;">
                             <?php echo strtolower($retencion->cli_email)?>
                            </label>
                    </td>
                    <th></th>
                </tr> 
               
                  
            </table>
        </td>
        <tr>
            <td><br></td>
        </tr>

    </tr>  
      
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%" border="1">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Numero</th>
                        <th>Ejercicio Fiscal</th>
                        <th style="width:70px">Base Imponible para la Retencion</th>
                        <th style="width:70px">Impuesto</th>
                        <th style="width:70px">Porcentaje de Retencion</th>
                        <th style="width:70px">Valor Retenido</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                        if($det->drr_tipo_impuesto='IVA'){
                            $imp='IVA';
                        } else if($det->drr_tipo_impuesto='IR'){
                            $imp='RENTA';
                        } else if($det->drr_tipo_impuesto='IC'){
                            $imp='ICE';
                        }
                    ?>
                    <tr>
                        <td>FACTURA</td>
                        <td><?php echo $retencion->rgr_num_comp_retiene?></td>
                        <td><?php echo $det->drr_ejercicio_fiscal?></td>
                        <td class="numerico"><?php echo number_format($det->drr_base_imponible,$dcc)?></td>
                        <td><?php echo $imp?></td>
                        <td class="numerico"><?php echo number_format($det->drr_procentaje_retencion,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->drr_valor,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                
            </table>
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


         

