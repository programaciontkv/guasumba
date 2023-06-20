<section >
  <table width="100%">
    <tr>
        <td>
            <table id="login" align="center">
                <tr>
                    <td  >
                        <td>
                            <img src="<?php echo base_url().'imagenes/'.$factura->emp_logo?>"  width="150px" height="70px">
                        
                            </td>
                    </td>
                </tr>    
            </table>
        </td>
        <!-- <td rowspan="2" width="52%">
            <table id="encabezado2" width="100%">
                
                <tr>
                    <td class="titulo" style=" border-collapse: separate;" colspan="2">FACTUrA</td>
                </tr>    
                <tr>
                    <td colspan="2" style="font-size:20px">No. <?php echo $factura->fac_numero?></td>
                </tr>  
                <tr>
                    <td colspan="2"><?php echo utf8_encode('Fecha de Emisión:') ?>
                        <label style="font-weight: normal;">
                            <?php echo $factura->fac_fecha_emision?>
                        </label>
                    </td>
                </tr>    
                <tr>
                    <td colspan="2"><?php echo utf8_encode('Número de Autorización:') ?> </td>
                </tr>    
                <tr>
                    <td colspan="2"><?php echo $factura->fac_autorizacion?></td>
                </tr>    
                <tr>
                    <td colspan="2"> <?php echo utf8_encode('Fecha y hora de autorización:') ?></td>
                </tr>    
                <tr>    
                    <td colspan="2"><?php echo $factura->fac_fec_hora_aut?></td>
                </tr>    
                <tr>
                    <?php 
                    switch ($ambiente->con_valor) {
                      case 0:
                        $amb='';
                        break;
                      case 1:
                        $amb='Pruebas';
                        break;
                      case 2:
                        $amb='Produccion';
                        break;  
                    }
                    ?>
                    <td colspan="2"> 
                         Ambiente:
                        <label style="font-weight: normal;">
                       
                        <?php echo $amb?>
                    </label>
                        </td>
                </tr>    
                   
                <tr>
                    <td>Clave de acceso:</td>
                    
                </tr>
                <tr> 
                    <td colspan="2">
                        <img src="<?php echo base_url();?>barcodes/<?php echo $factura->fac_clave_acceso?>.png" alt="" width="350px" height="70px">
                    </td>
                </tr> 
                <tr>
                    <td colspan="2" style="font-size: 11px; text-align: center"><?php echo $factura->fac_clave_acceso?></td>
                 </tr>    
            </table>
        </td> -->
    </tr>    
    <tr>    
        <td width="48%" valign="bottom">
            <table id="encabezado1" width="100%">
                <tr>
                    <td class="titulo"  colspan="2"><?php echo $factura->emp_nombre?></td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2"><?php echo ucwords(strtolower($factura->emi_nombre))?></td>
                </tr>
                <tr>
                    <td class="titulo"><?php echo $factura->emp_identificacion?></td>
                </tr>    
                <tr >
                  
                   <td colspan="2">
                    <label style="font-weight: normal;">
                       <?php echo trim(ucwords(strtolower($factura->emp_direccion)))?></td>
                   </label>
                    
                </tr>
                <tr>
                    <td colspan="2">
                     <?php echo utf8_encode('Teléfono:') ?> 
                     <label style="font-weight: normal;">
                          <?php echo ucwords(strtolower($factura->emp_telefono))?></td>
                     </label>
                    
                    <th></th>
                </tr> 
                <tr>
                    <td colspan="2">Email: 
                            <label style="font-weight: normal;">
                             <?php echo strtolower($factura->emp_email)?>
                            </label>
                    </td>
                    <th></th>
                </tr> 
                <tr>
                    <td>Clave de acceso:</td>
                    
                </tr> 
                <tr>
                    <td colspan="2" style="font-size: 9px; text-align: center"><?php echo $factura->fac_clave_acceso?></td>
                 </tr>
                <?php 
                if(!empty($factura->emp_contribuyente_especial)){
                ?>
                <tr>
                    <td colspan="2">Contribuyente Especial Nro:</td>
                    <td>
                        <label style="font-weight: normal;">
                            <?php echo $factura->emp_contribuyente_especial?>
                        </label>
                    </td>
                </tr>
                <?php 
                }
                ?>  

                <tr>
                    <td colspan="2">Obligado a llevar contabilidad: <?php echo ucwords(strtolower($factura->emp_obligado_llevar_contabilidad))?></td>
                    <th></th>
                </tr>  
                <tr>
                    <td colspan="2"><?php echo trim($factura->emp_leyenda_sri)?></td>
                </tr>  

                <tr>
                    <td colspan="2"> <?php echo utf8_encode('Emisión:') ?>
                        <label style="font-weight: normal;">
                     Normal
                 </label>
             </td>
                </tr>   
                  
            </table>
        </td>
        

    </tr>
    <tr>
        <td colspan="2" >
            <table id="encabezado3" width="100%">
                <tr>
                    <td><strong>  <?php echo utf8_encode('Razón Social:') ?> </strong> <?php echo ucwords(strtolower($factura->fac_nombre))?></td>
                    
                </tr>    
                <tr>
                    <td><strong>Email: </strong><?php echo strtolower($factura->cli_email)?></td>
                    <td><strong> <?php echo utf8_encode('Cédula/RUC:') ?> </strong><?php echo $factura->fac_identificacion?></td>
                </tr>    
                <tr>
                    <td><strong><?php echo utf8_encode('Dirección:') ?> </strong><?php echo ucwords(strtolower($factura->fac_direccion))?></td>
                    <td><strong><?php echo utf8_encode('Teléfono:') ?> </strong><?php echo $factura->fac_telefono?></td>
                </tr>

            </table>
        </td>
    </tr>      
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td colspan="2">
            <table id="detalle"  class="table table-bordered table-list table-hover table-striped">
                <thead>
                    <tr >
                      
                        <th >Cantidad</th>
                        <th ><?php echo utf8_encode('Códido') ?></th>
                        <th><?php echo utf8_encode('Descripción') ?></th>
                        <th >P.Unitario</th>
                        <th >P.Total</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                    ?>
                    <tr>
                        <td class="numerico"><?php echo number_format($det->cantidad,$dcc)?></td>
                        <td ><?php echo substr($det->pro_codigo, 0, 13)  ?></td>
                        <td><?php echo ucfirst(strtolower( $det->pro_descripcion ))?></td>
                        <td class="numerico"><?php echo number_format($det->pro_precio,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->precio_tot,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tbody>
                    <tr>
                        <td id="inv" colspan="2" rowspan="3" valign="top" >
                            
                        </td>
                        <td colspan="2"><strong>Subtotal 12%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal12,$dec)?></td>
                    </tr>
                   <!--  <tr>
                        <td colspan="2"><strong>Subtotal 0%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal0,$dec)?></td>
                    </tr> -->
                    <!-- <tr>
                        <td colspan="2"><strong>Subtotal no objeto de IVA</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal_no_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Subtotal excento IVA</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal_ex_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Subtotal sin impuestos</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Descuento</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_descuento,$dec)?></td>
                    </tr> -->
                    <tr>
                        <td colspan="2"><strong>IVA 12%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_iva,$dec)?></td>
                    </tr>
                    <!-- <tr>
                        <td colspan="2"><strong>Propina</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_propina,$dec)?></td>
                    </tr> -->
                    <tr>
                        <td colspan="2"><strong>VALOR TOTAL</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_valor,$dec)?></td>
                    </tr>
                    
                    
                </tbody>   
            </table>
        </td>
    </tr>
        
</table>

<style type="text/css">
    *,label{
        /*font-size: 10px;*/
       /*  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        /* font-family:"Calibri ligth";*/
       /* font-family: 'Source Sans Pro';*/
        font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
       margin-left:-0.5px;
       margin-right: 5px;
       margin-top: 0px;
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

    #detalle tr,#detalle td {
        border-collapse: collapse;
         font-size: 8px;
          margin-left:5px;
       
    }

    

    #encabezado2 tr,#encabezado2 th, #encabezado2 td {
        font-weight: bold;
        justify-content: right;
         font-size: 11px;


    }

    

    #encabezado1 td, #encabezado1 th{
        text-align: left;
        font-weight: bold;
        font-size: 9px;

    }
    #encabezado3 td, #encabezado3 th{
        text-align: left;
        font-size: 9px;
        
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
    #inv  {
        border: 1px solid;
        border-color: #ffffff;
         background:#ffffff; 
        /*border-right: none;
        border-top: none;
        border-bottom: none;
        border-left: none;*/

    }

    /*#detalle tr:nth-child(2n-1) td ,#detalle tr:nth-child(2n-1) th {
      background: #DFDFDF !important;

    }*/

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
        font-size: 21px;
        font-weight: bold;
    }
    .mensaje {
                        color: #828282;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 15px;
                        justify-content: right;
                        font-weight: bolder;
                     }



</style>


         

