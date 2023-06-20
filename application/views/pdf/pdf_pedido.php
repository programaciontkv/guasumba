<section class="content" class="page-break" style="margin-top:-20px;margin-left: 20px;margin-right: -20px;">
  <table width="100%" class="encabezado_p">
    <tr>
        <td colspan="2" width="100%" >
            <table class="encabezado5"   width="100%" >
                <tr><td style="font-size:12px"><?php echo $pedido->emp_nombre; ?></td> 
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                <td rowspan="6" width="20%"><img src="<?php echo base_url().'imagenes/'.$pedido->emp_logo?>"  width="120px" height="70px"></td>
                 </tr>
                <tr><td style="font-size:12px"><?php echo $pedido->emp_identificacion; ?> </td>  </tr>
                <tr><td style="font-size:12px"><?php echo $pedido->emp_ciudad."-".$pedido->emp_pais; ?> </td>  </tr>
                <tr><td style="font-size:12px"><?php echo "TELEFONO: " . $pedido->emp_telefono ?> </td>  </tr>
            </table>
        </td>
    </tr>
    <tr >
        <td colspan="2"  >
            <div style="text-align:center;">
                <p class="titulo_p"><strong class="titulo_p"><?php echo ( 'PEDIDO DE VENTA')?> </strong>   </p>
            </div> 
        </td>
    </tr>
    <tr>
        <td colspan="2" class="sub_titulo" style="text-align: right;"><?php echo (' N°: ')?>
            <label   style="color:red">
                <strong class="sub_titulo">
                  <?php echo $pedido->ped_num_registro?>  
                </strong>
            </label>
            </td>
    </tr>
        
    
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%" >
               

                <tr>
                    <?php
                   $nombre= str_replace ( "Ñ" , "ñ" , $pedido->ped_nom_cliente )
                    ?>
                    <td width="120px"> <b ><?php echo utf8_encode ('Cliente:') ?> </b>  </td>
                    <td width="320px"><?php echo ucwords(strtoupper($nombre))?></td>
                    <td width="100px" ></td>

                    <td><strong> <?php echo utf8_encode ('Fecha emisión:')?> </strong> 
                     <?php echo $pedido->ped_femision?></td> 
                    
                </tr> 
                <tr>
                    <td width="120px"><strong> <?php echo utf8_encode ('Cédula/RUC:') ?> </strong></td>
                      <td width="100px"><?php echo $pedido->ped_ruc_cc_cliente?></td>
                      <td width="100px" ></td>
                      <td ><strong> Fecha entrega: </strong>
                       <?php echo $pedido->ped_fentrega?></td>
                   

                </tr>   
                <tr >
                    <td width="120px"><strong> <?php echo ('Email:') ?> </strong> </td>
                     <td width="100px">   <?php echo strtolower($pedido->ped_email_cliente)?></td>
                      <td width="100px" ></td>
                     <td></td>
                </tr>    
                <tr>
                    <?php
                   $dire= str_replace ( "Ñ" , "ñ" , $pedido->ped_dir_cliente )
                    ?>
                    <td width="120px"><strong> <?php echo utf8_encode ('Teléfono:') ?> </strong></td>
                     <td width="120px">    <?php echo $pedido->ped_tel_cliente?></td>
                      <td width="100px" ></td>
                    
                </tr>
                <tr>
                    <td  width="120px"><strong> <?php echo utf8_encode ('Dirección:') ?> </strong></td>
                     <td colspan="3">   <?php echo ( ucwords(strtoupper($dire)))?></td> 
                   
                </tr> 
                
                
                 </table>
        </td>
            
              <br>
            <br> 
       
    </tr>  
</table>
    
            <table id="detalle" width="100%"   class="table table-bordered table-list table-hover table-striped page-break" >
                <thead class="sub_titulo">
                    <tr>
                        <th style="width:120px"> <strong><?php echo utf8_encode('Código') ?> </strong>  </th>
                        <th colspan="3"> <strong><?php echo utf8_encode('Descripción') ?> </strong> </th>
                        
                        <?php 
                        if ($etq!='hidden') {
                            ?>
                            <th <?php echo $etq ?> style="<?php echo $width ?>" > <strong><?php echo ('Imagen') ?> </strong>  </th>
                       <?php }
                        ?>
                        <th style="width:70px"> <strong>Cantidad</strong> </th>
                        <th style="width:100px"><strong>V.Unitario $</strong>  </th>
                        <!-- <th style="width:70px">% IVA</th>
                        <th style="width:70px">% Descuento</th> -->
                        <th style="width:80px"> <strong>V.Total $</strong> </th>
                    </tr> 
                </thead> 
                <tbody >
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                        ?>
                        
                <tr >
                        <td  style="width:120px;<?php echo $height ?>"><?php echo $det->det_cod_producto?></td>
                        <td colspan="3"><?php echo $det->det_descripcion?></td>
                        <?php 
                        if ($etq!='hidden') {
                            ?>
                            <td style="<?php echo $width ?>">
                            <?php
                            if ($det->img!='') {   
                             ?>
                             <div style="margin: 0 auto;"> 
                                <img  src="<?php echo base_url().'imagenes/'.$det->img?>" width="50px" height="40px">
                            </div>


                           
                             
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
            <table  width="50%" >
            <tr class="pagos">
                <td class="pagos" colspan="2">
                        <table >
                        <tr>
                        <td width="200px"><strong>Forma de Pago</strong></td>
                        <td><strong> Valor </strong></td>
                        </tr>
                        
                    </table>
                </td>
            </tr>
            <?php
                        foreach ($cns_pag as $rst_pag) {
                        ?>
                        <tr>
                        <td width="200px" ><?php echo $rst_pag->fpg_codigo.' - '.ucwords(strtolower($rst_pag->fpg_descripcion_sri))?></td>
                        <td class="numerico">$ <?php echo number_format($rst_pag->pag_cant,$dec)?></td>
                        </tr>
                        <?php    
                        }
                        ?>
            </table>

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
            

            
          
</section>


<style type="text/css">
    
    *,label{
        font-size: 14px;
       /*  font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        /* font-family:"Calibri ligth";*/
       /* font-family: 'Source Sans Pro';*/
        font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
       margin-left: 6px;
       margin-right: 20px;
       justify-content: right;
/*       font-family: "calibri", "normal";*/

    }
    .pagos{
        border-top: 1px  solid;
    }
    .datos{
        font-family: "calibri", "bold";
    }
    .encabezado5{
        font-size: 9px;
       border-spacing: -1px;
    }
    .encabezado_p{
         border-spacing: 0px;
         padding: -1px;
    }
    #encabezado3{
        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
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
    th, td {
        padding-top: -5px;
        padding-bottom: 2px;
        padding-left: 3px;
        padding-right: 4px;
        }


</style>



         

