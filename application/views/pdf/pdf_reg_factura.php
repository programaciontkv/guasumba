<section class="content">
    <?php
    $sec = $factura->reg_id;
    if ($sec >= 0 && $sec < 10) {
                $tx = '0000000';
            } else if ($sec >= 10 && $sec < 100) {
                $tx = '000000';
            } else if ($sec >= 100 && $sec < 1000) {
                $tx = '00000';
            } else if ($sec >= 1000 && $sec < 10000) {
                $tx = '0000';
            } else if ($sec >= 10000 && $sec < 100000) {
                $tx = '000';
            } else if ($sec >= 100000 && $sec < 1000000) {
                $tx = '00';
            } else if ($sec >= 1000000 && $sec < 10000000) {
                $tx = '0';
            } else if ($sec >= 10000000 && $sec < 100000000) {
                $tx = '';
            }
            $secuencial = $tx . $sec;
            //$retencion_model= new Reg_retencion_model();
    ?>

  
  <table width="110%" style="margin-top:-30px">
   <tr>    
        <td colspan="2"  >
            <table id="encabezado1" width="100%" style="margin-right: 0px;">
                <tr><td>  </td> <td>  </td> 
                <td  width="15%"><img src="<?php echo base_url().'imagenes/'.$factura->emp_logo?>"  width="130px" height="70px"></td>
                 </tr>
            </table>
        </td>
    </tr> 
    <tr >
      
       
        <th width="130%" class="titulo_p" style="text-align: center;">COMPROBANTES DE REGISTRO</th>

        
    </tr>

    <tr>
        <td>
              
          </td>
          
        <td  class="sub_titulo" ><?php echo utf8_encode('N°: ')?>
                        <label class="sub_titulo"  style="color:red">
                             <?php echo $secuencial?>
                        </label>
          </td>
          
    </tr>
    <tr >
        <td width="500%"  >
            <table  id="encabezado3" width="130%">    
                <tr>
                    <td width="60px" >
                    <strong><?php echo utf8_encode('FECHA:') ?> </strong>
                        
                    </td>
                    <td>
                        <label  style="font-weight: normal;">
                            <?php echo $factura->reg_femision?>
                        </label>
                    </td>
                    
                   
                </tr>    
                <tr >
                    <td width="60px" > <strong><?php echo utf8_encode('FACTURA:') ?> </strong>
                    
                     </td>
                    <td><label class="digito"  style="font-weight: normal;">
                        <?php echo $factura->reg_num_documento?>
                    </label></td>
                     
                </tr>  
                <tr>
                    <td width="60px" > <strong><?php echo utf8_encode('PROVEEDOR:') ?> </strong>
                    
                     </td>
                     <td> <label style="font-weight: normal;">
                        <?php echo $factura->cli_raz_social?>
                    </label> </td>
                    
                </tr>  
                <tr>
                    <?php
                if(!empty($ret)){
            
                ?>
                    <td > <strong> <?php echo utf8_encode('RETENCION:') ?>  </strong>
                    <label class="digito"><?php echo $ret->ret_numero?> </label></td>
                <?php 
                }
                ?>
                <td width="60px"   class="digito" > <strong style="color: black;"> <?php echo utf8_encode('ASIENTO:') ?> </strong>
                     </td>
                     <td class="digito"><label><?php echo $asientos->con_asiento?> </label>   </td>
                </tr>
                           
            </table>
        </td>

    </tr>
    
   <tr>
       <td></td>
   </tr>
    <tr>
       <td></td>
   </tr>
    
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th >Codigo</th>
                        <th>Cuenta</th>
                        <th >Concepto</th>
                        <th >Debe</th>
                        <th >Haber</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    $cuentas = Array();
                    $asiento_model = new asiento_model();
                    $plan_cuentas_model = new Plan_cuentas_model();
                    $cns_cuentas = $asiento_model->asiento_reg_fac_detalle($factura->reg_id);

                        foreach($cns_cuentas as $rst_cuentas) {
                        if (!empty($rst_cuentas->con_concepto_debe)) {
                        array_push($cuentas, $rst_cuentas->con_concepto_debe . '&' . $rst_cuentas->con_id . '&0');
                        }

                        if (!empty($rst_cuentas->con_concepto_haber)) {
                        array_push($cuentas, $rst_cuentas->con_concepto_haber . '&' . $rst_cuentas->con_id . '&1');
                        }
                        }
                        //Eliminar Duplicados del Array
        $n = 0;
        $j = 1;
        $td = 0;
        $th = 0;

         while ($n < count($cuentas)) {
            $cta = explode('&', $cuentas[$n]);
            $rst_cuentas1 = $plan_cuentas_model->lista_un_plan_cuentas_codigo($cta[0]);
            $vdebe=0;
            $vhaber=0;
            if ($cta[2] == 0) {
                $rst_v = $asiento_model->listar_asientos_debe($asientos->con_asiento, $cta[0], $cta[1]);
                    $vdebe=$rst_v->con_valor_debe;
                    $vhaber = 0;
            } else {
                $rst_v = $asiento_model->listar_asientos_haber($asientos->con_asiento, $cta[0], $cta[1]);
                    $vdebe = 0;
                    $vhaber=$rst_v->con_valor_haber;
            }
            $n++;
            $j++;
            $td+= round($vdebe, $dec);
            $th+= round($vhaber, $dec);
        
                    ?>
                    <tr>
                         <td><?php echo $n?></td>
                        <td><?php echo $rst_cuentas1->pln_codigo?></td>
                        <td ><?php echo $rst_cuentas1->pln_descripcion?></td>
                        <td><?php echo $rst_v->con_concepto?></td>
                        <td class="numerico"><?php echo number_format($vdebe,$dec)?></td>
                        <td class="numerico"><?php echo number_format($vhaber,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 

                    <tr>
                        <td class="numerico" colspan="4">TOTAL</td>
                        <td class="numerico"><?php echo  number_format($td,$dec)?></td>
                        <td class="numerico"><?php echo number_format($th,$dec) ?></td>
                    </tr>
                </tbody> 
                
                   
            </table>
           
                    
               
        </td>
       
</table>
<center>
    <table>
    </tr>
        <tr>
        <td>_______________________________</td>
        <td>_______________________________</td>
        <td>_______________________________</td>

        </tr>
        <tr>
        <td>AUTORIZADO</td>
        <td>CONTADOR</td>
        <td>CONTABILIZADO</td>
        </tr> 
</table>
</center>


<style type="text/css">
    *,label{
        font-size: 15px;
       font-family: "calibri", "nromal";
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
    /*#encabezado3 tr {
        border-top: 1px solid;
        border-bottom: 1px solid;
        text-align: left;
    }*/

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

.sub_titulo{
        font-size: 16px;
        text-align: center;
        font-family: "calibri", "bold";
    }
    .titulo_p{
        font-size: 19px;
        text-align: center;
        font-family: "calibri", "bold";

    }
    .digito{
        color: red;
    }
th, td {
        padding-top: -5px;
        padding-bottom: 2px;
        padding-left: 3px;
        padding-right: 4px;
        }
</style>

