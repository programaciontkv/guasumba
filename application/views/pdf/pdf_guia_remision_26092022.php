<section class="content">
  <table width="100%">
    <tr>
        <td>
            <table id="login">
                <tr>
                    <td><img src="<?php echo base_url().'imagenes/'.$guia->emp_logo?>" width="250px" height="100px"></td>
                </tr>    
            </table>
        </td>
        <td rowspan="2" width="52%">
            <table id="encabezado2" width="100%">
                
                <tr>
                    <th class="titulo" style=" border-collapse: separate;" colspan="2">GUIA DE REMISION</th>
                </tr>    
                <tr>
                    <th colspan="2" style="font-size:20px">No. <?php echo $guia->gui_numero?></th>
                </tr>  
                <tr>
                    <td><strong> <?php echo utf8_encode('Fecha de Emisión:') ?> </strong><?php echo $guia->gui_fecha_emision?></td>
                </tr>    
                <tr>
                    <th colspan="2"><?php echo utf8_encode('Número de Autorización:') ?> </th>
                </tr>    
                <tr>
                    <th colspan="2"><?php echo $guia->gui_autorizacion?></th>
                </tr>    
                <tr>
                    <th> <?php echo utf8_encode('Fecha y hora de autorización:') ?></th>
                </tr>    
                <tr>    
                    <td><?php echo $guia->gui_fec_hora_aut?></td>
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
                    <td><strong>Ambiente: </strong><?php echo $amb?></td>
                </tr>    
                <tr>
                    <td><strong> <?php echo utf8_encode('Emisión:') ?> </strong>Normal</td>
                </tr>    
                <tr>
                    <th>Clave de acceso:</th>
                    
                </tr>
                <tr> 
                    <td colspan="2">
                        <img src="<?php echo base_url();?>barcodes/<?php echo $guia->gui_clave_acceso?>.png" alt="" width="350px" height="70px">
                    </td>
                </tr> 
                <tr>
                    <th colspan="2" style="font-size: 11px; text-align: center"><?php echo $guia->gui_clave_acceso?></th>
                 </tr>    
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="48%" valign="bottom">
            <table id="encabezado1" width="100%">
                <tr>
                    <th class="titulo" colspan="2"><?php echo $guia->emp_nombre?></th>
                </tr>
                <tr>
                    <td class="titulo" colspan="2"><?php echo ucwords(strtolower($guia->emi_nombre))?></td>
                </tr>
                <tr>
                    <th class="titulo"><?php echo $guia->emp_identificacion?></th>
                </tr>    
                <tr >
                  
                   <td colspan="2"><?php echo trim(ucwords(strtolower($guia->emp_direccion)))?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong><?php echo utf8_encode('Teléfono:')?> </strong> <?php echo ucwords(strtolower($guia->emp_telefono))?></td>
                    <th></th>
                </tr> 
                <tr>
                    <td colspan="2"><strong>Email: </strong> <?php echo strtolower($guia->emp_email)?></td>
                    <th></th>
                </tr> 
                <?php 
                if(!empty($guia->emp_contribuyente_especial)){
                ?>
                <tr>
                    <th colspan="2">Cotribuyente Especial Nro:</th>
                    <td><?php echo $guia->emp_contribuyente_especial?></td>
                </tr>
                <?php 
                }
                ?>  

                <tr>
                    <td colspan="2"><strong>Obligado a llevar contabilidad: </strong> <?php echo ucwords(strtolower($guia->emp_obligado_llevar_contabilidad))?></td>
                    <th></th>
                </tr>  
                <tr>
                    <th colspan="2"><?php echo trim($guia->emp_leyenda_sri)?></th>
                </tr>    
                  
            </table>
        </td>
        <tr>
            <td><br><br></td>
        </tr>

    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <td><strong><?php echo utf8_encode('Razón Social Transportista:')?></strong> <?php echo ucwords(strtolower($guia->tra_razon_social))?></td>
                </tr>    
                <tr>
                    <td><strong>Placa:</strong> <?php echo $guia->tra_placa?></td>
                    <td><strong><?php echo utf8_encode('Cédula/RUC')?> Transportista:</strong> <?php echo $guia->gui_identificacion_transp?></td>
                </tr>    
                <tr>
                    <td><strong>Punto de Partida:</strong> <?php echo ucwords(strtolower($guia->gui_punto_partida))?></td>
                </tr>    
                <tr>
                    <td><strong>Fecha Inicio Transporte:</strong> <?php echo $guia->gui_fecha_inicio?></th>
                    <td><strong>Fecha Fin Transporte:</strong> <?php echo $guia->gui_fecha_fin?></td>
                </tr> 
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <td><strong>Comprobante de Venta:</strong> FACTURA <?php echo $guia->gui_num_comprobante?></td>
                    <td><strong>Fecha de <?php echo utf8_encode('emisión')?>:</strong> <?php echo $guia->gui_fecha_emision?></td>
                </tr>   
                <tr>
                   <td><strong><?php echo utf8_encode('Número de Autorización:')?></strong> <?php echo $guia->gui_aut_comp?></td> 
                </tr> 
                <tr>
                   <td><strong>Motivo Traslado:</strong> <?php echo ucwords(strtolower($guia->gui_motivo_traslado))?></td> 
                
                   
                </tr>
               
                <tr>    
                    <td><strong><?php echo utf8_encode('Razón Social (Destinatario):')?></strong> <?php echo ucwords(strtolower($guia->gui_nombre))?></td>
                </tr>
                <tr>
                    <td><strong>Email: </strong><?php echo strtolower($guia->cli_email)?></td>
                    <td><strong> <?php echo utf8_encode('Cédula/RUC:') ?> </strong><?php echo $guia->gui_identificacion?></td>
                </tr>    
                <tr>
                    <td><strong>Destino (Punto de llegada):</strong> <?php echo ucwords(strtolower($guia->gui_destino))?></td> 
                    <td><strong><?php echo utf8_encode('Teléfono:')?> </strong><?php echo $guia->cli_telefono?></td>
                </tr>    
                <tr>    
                    <td><strong>Documento Aduanero:</strong> <?php echo $guia->gui_doc_aduanero?></th>
                  
                    <td><strong><?php echo utf8_encode('Código')?> Establecimiento Destino:</strong> <?php echo $guia->gui_cod_establecimiento?></td>
                </tr>    
            </table>
        </td>
    </tr>      
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%">
                <thead>
                    <tr>
                        <th style="width:70px">Cantidad</th>
                        <th><?php echo utf8_encode('Descripción')?></th>
                        <th><?php echo utf8_encode('Código')?> Principal</th>
                        <th><?php echo utf8_encode('Código')?> Auxiliar</th>
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
                        <td><?php echo ucwords(strtolower($det->pro_descripcion))?></td>
                        <td><?php echo $det->pro_codigo?></td>
                        <td><?php echo $det->pro_codigo_aux?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tbody>
                    <tr>
                        <td id="info" colspan="4" valign="top">
                            <table>
                                <tr>
                                    <th><?php echo utf8_encode('Información')?> Adicional</th>
                                </tr>
                                <tr>
                                    <td><strong><?php echo utf8_encode('Dirección')?></strong></td>
                                    <td><?php echo ucwords(strtolower($guia->cli_calle_prin))?></td>
                                </tr>
                                <tr>
                                    <td><strong>Observaciones</strong></td>
                                    <td><?php echo ucfirst(strtolower($guia->gui_observacion))?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>   
            </table>
        </td>
    </tr>    
</table>

<style type="text/css">
    *{
        font-size: 14px;
       /* font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        font-family: 'Source Sans Pro';


       
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

    

    #encabezado1 td, #encabezado1 th, #encabezado2 td, #encabezado2 th, #encabezado3 td, #encabezado3 th{
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
        font-size: 15px;
    }



</style>


         

