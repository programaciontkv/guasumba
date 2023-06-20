<section class="content" class="page-break" style="margin-top:-5px" >
  <table width="100%" class="table table-bordered table-list table-hover table-striped page-break">
    <tr>
        <td>
            <table id="login" align="center">
                <tr>
                    <td  >
                       
                            <img src="<?php echo base_url().'imagenes/'.$factura->emp_logo?>"  width="150px" height="70px">
                            
                    </td>
                </tr>    
            </table>
        </td>

    </tr> 
    </table>  

    <table id="encabezado1" width="150%">
                <tr>
                     <?php
                   $emp_nombre= str_replace ( "Ñ" , "ñ" , $factura->emp_nombre );
                   $emi_nombre= str_replace ( "Ñ" , "ñ" , $factura->emi_nombre);
                    ?>
                    <td class="titulo"  colspan="2"><?php echo ( ucwords(strtolower($emp_nombre)))?></td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2"><?php echo ( ucwords(strtolower($emi_nombre)))?></td>
                </tr>
                <tr>
                    <td class="titulo"><?php echo $factura->emp_identificacion?></td>
                </tr>    
                <tr >
                  
                   <td colspan="2"><label style="font-weight: normal;"><?php echo trim(ucwords(strtolower($factura->emi_dir_establecimiento_emisor)))?>
                   </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><strong><?php echo utf8_encode ('Teléfono:') ?> </strong>
                     <label style="font-weight: normal;">
                          <?php echo ucwords(strtolower($factura->emi_telefono))?>
                     </label>
                     </td>
                    
                    
                </tr> 
                <tr>
                    <td colspan="2"><strong>Email: </strong> <label style="font-weight: normal;"><?php echo strtolower($factura->emi_email)?>
                            </label>
                    </td>
                   
                </tr> 
                <tr>
                    <td><strong>Clave de acceso:</strong> </td>
                    
                </tr> 
                <tr>
                    <td  width="250px" style="font-size: 8px; text-align: center"><?php echo $factura->fac_clave_acceso?></td>
                 </tr>
                <?php 
                if(!empty($factura->emp_contribuyente_especial)){
                ?>
                <tr>
                    <td colspan="2"><strong>Contribuyente Especial Nro: </strong> </td>
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
                    <td colspan="2"><strong>Obligado a llevar contabilidad: </strong>  <?php echo ucwords(strtolower($factura->emp_obligado_llevar_contabilidad))?></td>
                    
                </tr>  
                <tr>
                    <td colspan="2"><?php echo trim($factura->emp_leyenda_sri)?></td>
                </tr>  

                <tr>
                    <td colspan="2"><strong><?php echo ('Emisión:') ?> </strong> 
                        <label style="font-weight: normal;">
                     Normal</label>
                    </td>
                
                </tr> 
                <tr>
                    <td colspan="2"><strong><?php echo ('Factura N°:') ?> </strong> 
                        <label style="font-weight: normal;">
                        <?php echo trim($factura->fac_numero)?>
                       </label>
                </td>
                </tr>  
                  
            </table> 

            <table id="encabezado3"  width="100%" >
                <tr>
                    <?php
                   $nombre= str_replace ( "Ñ" , "ñ" , $factura->fac_nombre )
                    ?>
                    <td><strong><?php echo utf8_encode ('Razón Social:') ?> </strong> <?php echo (ucwords(strtolower($nombre)))?>
                </td>
                    
                </tr>    
                <tr>
                    <td><strong><?php echo utf8_encode ('Cédula/RUC:') ?> </strong><?php echo $factura->fac_identificacion?></td>
                </tr> 
                <tr>
                    <td ><strong>Email:</strong></td>
                </tr>   
                <tr>
                    <td><?php echo strtolower($factura->cli_email);  ?> </td>
                </tr> 
                <tr>
                    <?php
                   $dire= str_replace ( "Ñ" , "ñ" , $factura->fac_direccion )
                    ?>
                    <td><strong><?php echo utf8_encode ('Dirección:') ?> </strong><?php echo ( ucwords(strtolower($dire)))?></td>
                </tr>
                <tr>
                    <?php
                  
                    ?>
                    <td><strong><?php echo utf8_encode ('Teléfono:') ?> </strong><?php echo $factura->fac_telefono?></td>
                </tr>
                    <tr>
                        <td></td>
                    </tr>
            </table>

    <table id="detalle" width="95%" border="0.5" class="table table-bordered table-list table-hover table-striped page-break">
                
                    <tr>
                      
                        <td  ><b>Cantidad </b> </td>
                        <td ><b><?php echo utf8_encode ('Código') ?> </b> </td>
                        <td width="50px"><b><?php echo utf8_encode ('Descripción') ?> </b> </td>
                        <td ><b>P.Unitario </b> </td>
                        <td ><b>P.Total </b> </td>
                </tr>
                  
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                    ?>
                    <tr>
                        <td class="numerico"><?php echo number_format($det->cantidad,$dcc)?></td>
                        <td ><?php echo substr($det->pro_codigo, 0, 11)  ?></td>
                        
                        <?php
                         $descr= str_replace ( "Ñ" , "ñ" , strtoupper ($det->pro_descripcion) );
                        ?>
                        <td width="65px"><?php echo  ((ucfirst( mb_strtolower($descr))))?></td>
                        <td class="numerico"><?php echo number_format($det->pro_precio,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->precio_tot,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tfoot>
                    <tr>
                        <td id="inv" colspan="2" rowspan="3" valign="top" >
                            
                        </td>
                        <td colspan="2"><strong>Subtotal 12%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_subtotal12,$dec)?></td>
                    </tr>                
                    <tr>
                        <td colspan="2"><strong>IVA 12%</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>VALOR TOTAL</strong></td>
                        <td class="numerico"><?php echo number_format($factura->fac_total_valor,$dec)?></td>
                    </tr>
                    
                    
                </tfoot>   
            </table>

            <h4>
                <?php echo '*Este es un documento sin validez tributaria' ?>
            </h4>

</section>

<style type="text/css">
    *,label{
        /*font-size: 10px;*/
       /*  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        /* font-family:"Calibri ligth";*/
       /* font-family: 'Source Sans Pro';*/
        font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
       margin-left:-0.20px;
       margin-right: 5px;
       margin-top: 0px;
       justify-content: right;
       font-size: 10px;

    }
    
    

    .numerico {
        text-align: right;
    }

    #encabezado3 {
        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
    }

    #encabezado3 td {
        width: 20px;
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


         

