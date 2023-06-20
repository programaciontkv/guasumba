<section class="content">
 <table width="100%" >

    <tr>
        <td colspan="2" width="100%" >
            <table class="encabezado5"   width="100%" >
                <tr><td style="font-size:12px"><?php echo $empresa->emp_nombre; ?></td> 
                    <td></td>
                    <td> </td>
                    <td></td>
                    <td></td>
                <td rowspan="6" width="20%">
                    <img src="<?php echo base_url().'imagenes/'.$empresa->emp_logo?>"  width="120px" height="70px">
                </td>
                 </tr>
                <tr><td style="font-size:12px"><?php echo $empresa->emp_identificacion; ?> </td>  </tr>
                <tr><td style="font-size:12px"><?php echo $empresa->emi_ciudad."-".$empresa->emi_pais; ?> </td>  </tr>
                <tr><td style="font-size:12px"><?php echo "TELEFONO: " . $empresa->emi_telefono ?> </td>  </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <table id="encabezado1" width="100%">
                <tr>
                    <th class="titulo_p">INVENTARIO</th>
                </tr>    
                <tr>
                    <td class="sub_titulo"><strong>Al:</strong> <?php echo $fecha?></td>
                </tr>  
                 <tr>
                    <td><br><br></td>
                </tr>    
            </table>
        </td>
    </tr>         
    
    <tr>
        <td colspan="3">   
            <table id="detalle" style="" border="1"  width="100%" style="" class="table table-bordered table-list table-hover table-striped" >
                        <thead>
                            <tr class="encabezado_t">
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $dcc=$dcc->con_valor;
                        $n=0;
                        $inv=0;
                        $tot=0;
                        $inv_a=0;
                        $grup='';
                        $nom_a='';
                        $nom_a1='';
                        $inv_b=0;
                        $inv_b1=0;
                        $grup_b='';
                        $nom_b='';
                        $nom_b1='';
                        if(!empty($inventarios)){
                            foreach ($inventarios as $inventario) {

                                $n++;
                                $inv=round($inventario->ingresos,$dcc)-round($inventario->egresos,$dcc);
                                $tot+=$inv;   
                                $nom_a=$inventario->familia;
                                $nom_b=$inventario->tipo;

                            if($inventario->mp_b!=$grup_b && $n!=1){ 
                                    ?>
                                <tr  class="tr_t2"  >
                                <td colspan="2" ></td>
                                <td >TOTAL  <?php echo $nom_b1?> </td>
                                <td  class="numerico"><?php echo str_replace(',','', number_format($inv_b,$dcc))?></td>
                            </tr>
                        <?php
                            $inv_b=0;
                            }

                            if($inventario->mp_a != $grup && $n!=1 ){
                        ?>
                                <tr class="tr_t1" >
                                    <td colspan="2" ></td>
                                    <td  style="mso-number-format:'@'">TOTAL  <?php echo $nom_a1?> </td>
                                    <td class="numerico"><?php echo str_replace(',','', number_format($inv_a,$dcc))?></td>
                                </tr>
                        <?php
                             $inv_a=0;
                              }
                            
                        if($inventario->mp_a != $grup){
                                    ?>
                                <tr class="tr_familia">
                                    <td colspan="4" style="mso-number-format:'@'"><?php echo $nom_a?></td>
                                </tr>
                                <?php
                             $inv_a=0;
                              }
                        if($inventario->mp_b!=$grup_b ){ 
                                ?>
                                <tr class="tr_tipo <?php echo 'tra_'.$inventario->mp_a?>">
                                    <td  colspan="4" style="mso-number-format:'@'"><?php echo $nom_b?></td>
                                </tr>
                            <?php
                            $inv_b=0;
                            }

                            if(round($inv,$dcc)!=0){    
                        ?>
                            <tr class="tr">
                                <td style="mso-number-format:'@'"><?php echo $inventario->mp_c?></td>
                                <td><?php echo ucwords(strtolower($inventario->mp_d))?></td>
                                <td><?php echo ucwords(strtolower($inventario->mp_q))?></td>
                                <td class="numerico"><?php echo str_replace(',','', number_format($inv,$dcc))?></td>
                            </tr>
                        <?php
                            }
                            
                            $grup=$inventario->mp_a;
                            $grup_b=$inventario->mp_b;
                            $inv_a+=round($inv,$dcc);
                            $nom_a=$inventario->familia;
                            $nom_a1=$inventario->familia;
                            $nom_b1=$inventario->tipo;
                            $inv_b+=round($inv,$dcc);
                            $inv_b1+=round($inv,$dcc);
                            }
                        }
                        if(round($inv_b1,$dcc)!=0){ 
                        ?>
                            <tr  class="tr_t2" >
                                <td colspan="2" ></td>
                                <td >TOTAL  <?php echo $nom_b1?> </td>
                                <td  class="numerico"><?php echo str_replace(',','', number_format($inv_b,$dcc))?></td>
                            </tr>
                        <?php
                        }
                           
                        if(round($inv_a,$dcc)!=0){  
                        ?>
                                <tr class="tr_t1">
                                    <td colspan="2" ></td>
                                    <td  style="mso-number-format:'@'">TOTAL <?php echo $nom_a1?>  </td>
                                    <td class="numerico"><?php echo str_replace(',','', number_format($inv_a,$dcc))?></td>
                                </tr>
                        <?php
                             $inv_a=0;
                              }
                             
                        ?>  
                            <tr class="total">
                                <td colspan="2"></td>
                                <td>Totales</td>    
                                <td class="numerico"><?php echo str_replace(',','', number_format($tot,$dcc))?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>            
<style type="text/css">
    *{
        font-size: 15px;

    }
    

    .numerico {
        text-align: right;
    }


    #encabezado1{
        border: none;
        text-align: center;
    }
    .encabezado3 td,.encabezado3 tr {
        font-size: 11px;
    }


    #detalle td, #detalle th{
        border-collapse: collapse;
        
    }
    .encabezado_t th{
        border-collapse: collapse;
        font-size: 16px;
        font-family: "calibrib";
        
    }


    #detalle td, #detalle th{
        border-color: #ffffff;
        border-right: 1px solid #d7d7d7 !important;
        border-left: 1px solid #d7d7d7 !important;
    }

    #detalle tr:nth-child(2n-1) td {
      background: #DFDFDF !important;

    }

    .titulo{
        font-size: 15px !important;
        font-weight: bolder !important;
    }

    .tr_familia{
        background: #1B4D75 !important;
        color:black; !important;
        font-weight: bolder !important;
    }

    .tr_tipo{
        background: #2A76B5 !important;
        color:#ffffff !important;
        font-weight: bolder !important;
    }

    .total{
        background: #081B38 !important;
        color:#ffffff !important;
        font-weight: bolder !important;
    }
    .tr_t1{
    background: #cddef2 !important;
    color:black !important;
    font-weight: bolder !important;
    height: 15px !important;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}
.tr_t2{
    background: #cddef2 !important;
    color:black !important;
    font-weight: bolder !important;
    height: 15px !important;
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}
.titulo_p{
        font-size: 18px;
        text-align: center;
        font-family: "calibrib";
    }
    .sub_titulo{
        font-size: 17px;
        text-align: center;
    }



</style>



         

