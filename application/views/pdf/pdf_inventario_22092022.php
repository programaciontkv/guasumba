
<section class="content">
 <table width="100%">
    <tr>
        
        <td colspan="3" width="100%">
            <table id="encabezado1" width="100%">
                <tr>
                    <td rowspan="4" width="100px;">
                        <img src="<?php echo base_url().'imagenes/'.$empresa->emp_logo?>" width="150px" height="80px">
                    </td>
                    <th class="titulo" width="80%"><?php echo $empresa->emi_nombre?></th>
                </tr>
                <tr>
                    <th><?php echo $empresa->emi_dir_establecimiento_emisor?></th>
                </tr>
                <tr>
                    <th><?php echo $empresa->emi_ciudad .' - '. $empresa->emi_pais ?></th>
                </tr>
                 <tr>
                    <th><?php echo $empresa->emp_identificacion?></th>
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
                    <th class="titulo">INVENTARIO</th>
                </tr>    
                <tr>
                    <td><strong>Al:</strong> <?php echo $fecha?></td>
                </tr>  
                 <tr>
                    <td><br><br></td>
                </tr>    
            </table>
        </td>
    </tr>         
    
    <tr>
        <td colspan="3">   
            <table id="detalle" class="table table-bordered table-list table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
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
                        $inv_b=0;
                        $grup_b='';
                        $nom_b='';
                        if(!empty($inventarios)){
                            foreach ($inventarios as $inventario) {
                                $n++;
                                $inv=round($inventario->ingresos,$dcc)-round($inventario->egresos,$dcc);
                                $tot+=$inv;   
                        if($tip=='1'){        
                            if($inventario->mp_b!=$grup_b && $n!=1){
                                if(round($inv_b,$dcc)!=0){  
                        ?>
                                <tr class="tr_tipo <?php echo 'tra_'.$grup?>">
                                    <td style="mso-number-format:'@'"><?php echo ucwords(strtolower($nom_b))?></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td class="numerico"><?php echo str_replace(',','', number_format($inv_b,$dcc))?></td>
                                </tr>
                        <?php
                                }
                            $inv_b=0;
                            }
                        }

                        if($fam=='1'){           

                            if($inventario->mp_a!=$grup && $n!=1){
                                if(round($inv_a,$dcc)!=0){  
                        ?>
                                <tr class="tr_familia">
                                    <td style="mso-number-format:'@'"><?php echo ucwords(strtolower($nom_a))?></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td class="numerico"><?php echo str_replace(',','', number_format($inv_a,$dcc))?></td>
                                </tr>
                        <?php
                                }
                            $inv_a=0;
                            }
                        }    
                        if($det=='1'){
                            if(round($inv,$dcc)!=0){    
                        
                        ?>
                            <tr class="tr <?php echo 'b_'.$inventario->mp_b ?> <?php echo 'a_'.$inventario->mp_a ?>  ">
                                <td style="mso-number-format:'@'"><?php echo $inventario->mp_c?></td>
                                <td><?php echo ucwords(strtolower($inventario->mp_d))?></td>
                                <td><?php echo ucwords(strtolower($inventario->mp_q))?></td>
                                <td class="numerico"><?php echo str_replace(',','', number_format($inv,$dcc))?></td>
                            </tr>
                        <?php
                            }
                        }    
                            $grup=$inventario->mp_a;
                            $grup_b=$inventario->mp_b;
                            $inv_a+=round($inv,$dcc);
                            $nom_a=$inventario->familia;
                            $inv_b+=round($inv,$dcc);
                            $nom_b=$inventario->tipo;
                            }
                        }
                        if($tip=='1'){        
                            if(round($inv_b,$dcc)!=0){  
                            ?>
                                <tr class="tr_tipo <?php echo 'tra_'.$grup?>">
                                    <td style="mso-number-format:'@'"><?php echo ucwords(strtolower($nom_b))?></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td class="numerico"><?php echo str_replace(',','', number_format($inv_b,$dcc))?></td>
                                </tr>
                            <?php
                            }
                        }
                        if($fam=='1'){            
                            if(round($inv_a,$dcc)!=0){      
                            ?>  
                                <tr class="tr_familia">
                                    <td style="mso-number-format:'@'"><?php echo ucwords(strtolower($nom_a))?></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td class="numerico"><?php echo str_replace(',','', number_format($inv_a,$dcc))?></td>
                                </tr>
                            <?php
                            }  
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
        font-size: 14px;
       /* font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;*/
        font-family: 'Source Sans Pro';


       
    }
    

    .numerico {
        text-align: right;
    }


    #encabezado1{
        border: none;
        text-align: center;
    }


    #detalle td, #detalle th{
        border-collapse: collapse;
        
    }

    .titulo{
        font-size: 15px !important;
        font-weight: bolder !important;
    }

    .tr_familia{
        background: #1B4D75 !important;
        color:#ffffff !important;
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



</style>


         

