<section class="content">
  <table width="100%">
    <tr>
        
        <td colspan="3" width="100%">
            <table id="encabezado1" width="100%">
                <tr>
                    <td rowspan="4" width="20%">
                        <img src="<?php echo base_url().'imagenes/'.$ingreso->emp_logo?>"  width="250px" height="100px">
                    </td>
                   <!--  <th class="titulo" style="text-align:left;" width="80%"><?php echo $ingreso->emi_nombre?></th> -->
                </tr>
                <!-- <tr>
                    <th><?php echo $ingreso->emi_dir_establecimiento_emisor?></th>
                </tr>
                <tr>
                    <th><?php echo $ingreso->emi_ciudad .' - '. $ingreso->emi_pais ?></th>
                </tr>
                 <tr>
                    <th><?php echo $ingreso->emp_identificacion?></th>
                </tr>  -->   
                <tr>
                    <td><br></td>
                </tr>    
                <tr>
                    <th></th>                    
                    <th class="titulo" style="text-align: right;"><?php echo utf8_encode('Ingreso N°: ')?>
                        <label style="color:red">
                             <?php echo $ingreso->mov_documento?></th>
                        </label>
                       
                </tr>
                <tr>
                    <th></th>
                    <td style="text-align: right;"><strong> <?php echo utf8_encode('Fecha de Emisión:') ?> </strong> <?php echo $ingreso->mov_fecha_trans?></td>
                </tr>     
                <tr>
                    <td><br><br></td>
                </tr>  
            </table>
        </td>
    </tr> 
    <tr>
        <td colspan="3">
            <table id="encabezado1" width="100%">
                <tr>
                    <th class="titulo">Ingresos a Bodega</th>
                </tr>    
                <tr>
                    <td><br><br></td>
                </tr> 
            </table>
        </td>
    </tr>         
    
    <tr>
        <td colspan="3">
            <table id="encabezado2" width="100%">
                <tr>
                    <td><strong>Proveedor:</strong> <?php echo $ingreso->cli_raz_social?></td>
                </tr> 
                <tr>
                    <td><strong><?php echo utf8_encode('Documento/Información:') ?></strong> <?php echo $ingreso->mov_guia_transporte?></td>
                </tr>    
                <tr>
                    <td><strong> <?php echo utf8_encode('Transacción:') ?> </strong> <?php echo $ingreso->trs_descripcion?></td>
                </tr> 
                
            </table>
        </td>
    </tr>  
    <tr>
        <td><br></td>
    </tr>     
    <tr>
        <td colspan="3">
            <table id="detalle" width="100%">
                <thead>
                    <tr>
                        <th style="width:50px"> <?php echo utf8_encode('Código') ?></th>
                        <th><?php echo utf8_encode('Descripción') ?></th>
                        <th style="width:70px">Unidad</th>
                        <th style="width:70px">Cantidad</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    $total=0;
                    foreach ($cns_det as $det) {

                    ?>
                    <tr>
                        <td style="width:50px" ><?php echo $det->mp_c?></td>
                        <td><?php echo $det->mp_d?></td>
                        <td><?php echo $det->mp_q?></td>
                        <td class="numerico"><?php echo number_format($det->mov_cantidad,$dcc)?></td>
                        
                    </tr>
                    
                    <?php
                        $total+=round($det->mov_cantidad,$dcc);
                    } 
                    ?> 
                    <tr>
                        <th colspan="2"></th>
                        <th>Total</th>
                        <th  class="numerico"><?php echo number_format($total,$dcc)?></th>
                    </tr>
                </tbody> 
                
            </table>
        </td>
    </tr>    
</table>

<style type="text/css">
    *{
        font-size: 12px;
        /*font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
        text-align: left;
    }
    .numerico {
        text-align: right;
    }

    #encabezado1{
        border: none;
        text-align: center;
    }

    #encabezado2{
        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
    }

    

    #detalle td, #detalle th{
        border-color: #ffffff;
        border-right: 1px solid #d7d7d7 !important;
        border-left: 1px solid #d7d7d7 !important;
    }

    #detalle tr:nth-child(2n-1) td {
      background: #DFDFDF !important;

    }

    .titulo,label{
        font-size: 15px;
        font-weight: bolder;
        text-align: center;
    }

</style>


         

