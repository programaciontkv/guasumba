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
                    <th class="titulo">R.U.C: </th>
                    <th class="titulo"><?php echo $guia->emp_identificacion?></th>
                </tr>
                <tr>
                    <th class="titulo" colspan="2">GUIA DE REMISION</th>
                </tr>    
                <tr>
                    <th colspan="2">No. <?php echo $guia->gui_numero?></th>
                </tr>    
                <tr>
                    <th colspan="2">NUMERO DE AUTORIZACION</th>
                </tr>    
                <tr>
                    <th colspan="2"><?php echo $guia->gui_autorizacion?></th>
                </tr>    
                <tr>
                    <th>FECHA Y HORA DE AUTORIZACION</th>
                    <th><?php echo $guia->gui_fec_hora_aut?></th>
                </tr>    
                <tr>
                    <?php 
                    switch ($ambiente->con_valor) {
                      case 0:
                        $amb='';
                        break;
                      case 1:
                        $amb='PRUEBAS';
                        break;
                      case 2:
                        $amb='PRODUCCION';
                        break;  
                    }
                    ?>
                    <th>AMBIENTE</th>
                    <th><?php echo $amb?></th>
                </tr>    
                <tr>
                    <th>EMISION</th>
                    <th>NORMAL</th>
                </tr>    
                <tr>
                    <th>CLAVE DE ACCESO</th>
                </tr>
                <tr> 
                    <td colspan="2">
                        <img src="<?php echo base_url();?>barcodes/<?php echo $guia->gui_clave_acceso?>.png" alt="" width="350px" height="40px">
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
            <table id="encabezado1" width="98%">
                <tr>
                    <th colspan="2" class="titulo"><?php echo $guia->emp_nombre?></th>
                </tr>    
                <tr>
                    <th>Direccion Matriz:</th>
                    <th><?php echo $guia->emp_direccion?></th>
                </tr>
                <tr>
                    <th>Direccion Sucursal:</th>
                    <th><?php echo $guia->emi_dir_establecimiento_emisor?></th>
                </tr>
                <?php 
                if(!empty($factura->emp_contribuyente_especial)){
                ?>
                <tr>
                    <th colspan="2">Cotribuyente Especial Nro:</th>
                    <th><?php echo $guia->emp_contribuyente_especial?></th>
                </tr>  
                <?php 
                }
                ?>      
                <tr>
                    <th colspan="2">OBLIGADO A LLEVAR CONTABILIDAD:</th>
                    <th><?php echo $guia->emp_obligado_llevar_contabilidad?></th>
                </tr>    
            </table>
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Identificacion Transportista: <?php echo $guia->gui_identificacion_transp?></th>
                </tr>    
                <tr>
                    <th>Razón Social / Nombres y Apellidos: <?php echo $guia->tra_razon_social?></th>
                </tr>    
                <tr>
                    <th>Placa: <?php echo $guia->tra_placa?></th>
                </tr>    
                <tr>
                    <th>Punto de Partida: <?php echo $guia->gui_punto_partida?></th>
                </tr>    
                <tr>
                    <th>Punto de Partida: <?php echo $guia->gui_punto_partida?></th>
                </tr>    
                <tr>
                    <th>Fecha Inicio Transporte: <?php echo $guia->gui_fecha_inicio?></th>
                    <th>Fecha Fin Transporte: <?php echo $guia->gui_fecha_fin?></th>
                </tr> 
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Comprobante de Venta: FACTURA <?php echo $guia->gui_num_comprobante?></th>
                    <th>Fecha de emision: <?php echo $guia->gui_fecha_emision?></th>
                </tr>   
                <tr>
                   <th>Numero de Autorizacion: <?php echo $guia->gui_aut_comp?></th> 
                </tr> 
                <tr>
                   <th>Motivo Traslado: <?php echo $guia->gui_motivo_traslado?></th> 
                </tr>
                <tr>
                   <th>Destino (Punto de llegada): <?php echo $guia->gui_destino?></th> 
                </tr>
                <tr>
                    <th>Identificacion (Destinatario): <?php echo $guia->gui_identificacion?></th>
                </tr>
                <tr>    
                    <th>Razon Social / Nombres y Apellidos: <?php echo $guia->gui_nombre?></th>
                </tr>    
                <tr>    
                    <th>Documento Aduanero: <?php echo $guia->gui_doc_aduanero?></th>
                </tr>    
                <tr>    
                    <th>Codigo Establecimiento Destino: <?php echo $guia->gui_cod_establecimiento?></th>
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
                        <th>Descripcion</th>
                        <th>Cod.Principal</th>
                        <th>Cod.Auxiliar</th>
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
                        <td><?php echo $det->pro_descripcion?></td>
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
                                    <th>Informacion Adicional</th>
                                </tr>
                                <tr>
                                    <td>Direccion</td>
                                    <td><?php echo $guia->cli_calle_prin?></td>
                                </tr>
                                <tr>
                                    <td>Telefono</td>
                                    <td><?php echo $guia->cli_telefono?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo $guia->cli_email?></td>
                                </tr>
                                <tr>
                                    <td>Observaciones</td>
                                    <td><?php echo $guia->gui_observacion?></td>
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


         

