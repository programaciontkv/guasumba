<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <!-- <h1>
        Factura 
      </h1> -->
</section>
<section class="content">
      <div class="row" id ='ventana'>
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          $ctrl_inv=$ctrl_inv->con_valor;
          $inven=$inven->con_valor;
          $cprec=$cprec->con_valor;
          $cdesc=$cdesc->con_valor;
          $m_pag=$m_pag->con_valor;
          $m_prec=$m_prec->con_valor;
          if($inven==0){
            $hid_inv='';
            $col_obs='8';
          }else{
            $hid_inv='hidden';
            $col_obs='7';
          }

          if($this->session->flashdata('error')){
            ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <p><i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error')?></p>
            </div>
            <?php
          }
          ?>
          <div class="box box-primary">
            <form id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body" >
                 <table class="table col-sm-12" border="0">
                    <tr>
                      <td class="col-sm-5">
                        
                      </td>  
                    </tr>
                    <tr>
                      <td class="col-sm-2">
                        <div class="box-body">
                        <div class="panel panel-success col-sm-12">
                         
                           
                        <div class="panel panel-heading "><label>Seleccione Cliente</label></div>

                         <br>
                          <div class="row" >
                               <div class="col-sm-4">
                                 <input style="width:105px" type="button" class="btn btn-primary" onclick="verificar_cedula('9999999999999')"  name="" value="Consumidor final ">

                              </div>

                              <div class="col-sm-4">
                                <input style="width:105px" type="button" class="btn btn-success" onclick="datos()"  name="" value="Con Datos">
                              </div>

                              <div class="col-sm-4">
                                <input style="width:105px" type="button" class="btn btn-info" onclick="pas()"  name="" value="Extranjero">
                              </div>
                            </div>
                             <br>

                        <table class="table">
                         
                        <!--  list="list_clientes" -->
                          <tr>
                              <td><label id="tipo_cliente">RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $factura->fac_identificacion;}?>"  onchange="buscar_clientes(this)">
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td >
                              <td hidden><label>Pasaporte:</label>
                                <input type="checkbox" name="pasaporte" id="pasaporte" onclick="extra()">
                                <input type="hidden"  name="pas_aux" id="pas_aux" value="0">
                            </td>
                          </tr>
                          <tr>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $factura->fac_nombre;}?>" >
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $factura->cli_id;}?>" >
                              </td>
                          </tr>
                          <tr>
                            <td><label>Direccion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('direccion_cliente')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" value="<?php if(validation_errors()!=''){ echo set_value('direccion_cliente');}else{ echo $factura->fac_direccion;}?>">
                                <?php echo form_error("direccion_cliente","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Telefono:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" value="<?php if(validation_errors('telefono_cliente')!=''){ echo set_value('telefono_cliente');}else{ echo $factura->fac_telefono;}?>">
                                      <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                  
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Email:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('email_cliente');}else{ echo $factura->fac_email;}?>">
                                  <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                            </tr>
                            <tr>
                              <!-- <td><label>Parroquia:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('cli_parroquia')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="cli_parroquia" id="cli_parroquia" value="<?php if(validation_errors()!=''){ echo set_value('cli_parroquia');}else{ echo $factura->cli_parroquia;}?>">
                                  <?php echo form_error("cli_parroquia","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> -->
                             <tr>
                              <td><label>Ciudad:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('cli_ciudad')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="cli_ciudad" id="cli_ciudad" value="<?php if(validation_errors()!=''){ echo set_value('cli_ciudad');}else{ echo $factura->cli_canton;}?>">
                                  <?php echo form_error("cli_ciudad","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Pais:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('cli_pais')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="cli_pais" id="cli_pais" value="<?php if(validation_errors()!=''){ echo set_value('cli_pais');}else{ echo $factura->cli_pais;}?>">
                                  <?php echo form_error("cli_pais","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr> 
                          </table>
                          </div>
                          </div>
                          </td>
                          <td class="col-sm-10">
                            <table class="table ">
                          <tr>
                            <td>
                                  <tr>
                                   <td><label>Fecha Emision:</label></td>
                                  <td>
                                      <div class="form-group <?php if(form_error('fac_fecha_emision')!=''){ echo 'has-error';}?> ">
                                        <input type="date" class="form-control" name="fac_fecha_emision" id="fac_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('fac_fecha_emision');}else{ echo $factura->fac_fecha_emision;}?>" readonly >
                                          <?php echo form_error("fac_fecha_emision","<span class='help-block'>","</span>");?>
                                      </div>
                                        <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $factura->emp_id;}?>">
                                        <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo $factura->emi_id;}?>">
                                        <input type="hidden" class="form-control" name="cja_id" id="cja_id" value="<?php if(validation_errors()!=''){ echo set_value('cja_id');}else{ echo $factura->cja_id;}?>">
                                        <input type="hidden" class="form-control" name="ped_id" id="ped_id" value="<?php if(validation_errors()!=''){ echo set_value('ped_id');}else{ echo $factura->ped_id;}?>">
                                  </td>
                                      
                                
                              </tr>
                            </td>

                            <td>
                            <tr>
                                <td><label>Vendedor</label></td>
                                  <td>
                                      <div class="form-group ">
                                      <select name="vnd_id"  id="vnd_id" class="form-control">
                                        <option value="">SELECCIONE</option>
                                         <?php
                                        if(!empty($vendedores)){
                                          foreach ($vendedores as $vendedor) {
                                        ?>
                                        <option value="<?php echo $vendedor->vnd_id?>"><?php echo $vendedor->vnd_nombre?></option>
                                        <?php
                                          }
                                        }
                                      ?>
                                      </select>
                                      <script type="text/javascript">
                                        var vnd='<?php echo $factura->vnd_id;?>';
                                        vnd_id.value=vnd;
                                      </script>
                                    </div>
                                  </td>
                              </tr>
                              
                            </td>
                            
                          </tr>
                        </table>  
                          <div class="box-body">
                          <div class="panel panel-default col-sm-16">
                          <div class="panel panel-heading"><label>Pagos</label></div>
                          <table class="table table-bordered table-striped" id="tbl_pagos">
                            <thead>
                              <tr>
                                <th>Forma</th>
                                <!-- <th>Documento</th>
                                <th>Plazo</th>
                                <th>Banco</th>
                                <th>Tarjeta</th> -->
                                <th>Cantidad</th>
                                <th></th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $faltante=0;
                              $cnt_pagos=1;
                              if ($cns_pag == '') {
                              ?>
                              <tr>
                                <td width="200">
                                 <!--  <input type="text" > -->
                                  
                                  <div class="form-group" >
                                    <select id="pag_descripcion1"  name="pag_descripcion1" lang="1"  class="form-control itm" onchange="traer_forma(this)">
                              <option value="">SELECCIONE</option>

                              <?php 
                              if(!empty($formas_pago)){
                              foreach ($formas_pago as $fpg) {
                              ?>
                              <option value="<?php echo $fpg->fpg_descripcion?>"><?php echo $fpg->fpg_descripcion?></option>
                              <?php
                              }
                              }
                              ?>
                                  </select>
                                    
                                  </div>
                                 
                                  <input type="hidden" id="pag_forma1" name="pag_forma1" class="form-control" size="10px" lang="1">
                                  <input type="hidden" id="pag_tipo1" name="pag_tipo1" class="form-control" size="10px" lang="1">
                                </td>
                                <td hidden >

                                  <input  type="text" id="pag_documento1" name="pag_documento1" class="form-control" size="15px" lang="1" >
                                  <input type="text" id="id_nota_credito1" name="id_nota_credito1" class="form-control" size="10px" lang="1" value="0">
                                  <input type="text" id="val_nt_cre1" name="val_nt_cre1" class="form-control" size="10px" lang="1" value="0">
                                </td>

                                <td hidden >
                                  <input  class="form-control" type="text" name="pag_plazo1" id="pag_plazo1" lang="1" value="0">
                                  <!-- <select name="pag_plazo1" id="pag_plazo1" class="form-control" lang="1">
                                    <option value='0'>SELECCIONE</option>
                                  </select> -->
                                </td>
                                <td hidden >
                                  <input   class="form-control" type="text" name="pag_banco1" id="pag_banco1" lang="1" value="0">
                                  <!-- <select name="pag_banco1" id="pag_banco1" class="form-control" lang="1">
                                    <option value='0'>SELECCIONE</option>
                                  </select> -->
                                </td>
                                <td hidden >
                                  <input class="form-control" type="text" name="pag_tarjeta1" id="pag_tarjeta1" value="0" lang="1">
                                  <!-- <select name="pag_tarjeta1" id="pag_tarjeta1" class="form-control" lang="1">
                                    <option value='0'>SELECCIONE</option>
                                  </select> -->
                                </td>
                                
                                  
                                <td><input type="text" id="pag_cantidad1" name="pag_cantidad1" class="form-control" size="10px" lang="1" onchange="calculo_pagos(this)" onkeyup='validar_decimal(this)'></td>
                                <?php 
                                  if($m_pag==0){
                                  ?>
            <td >
              <input type="button" name="add1" id="add1" onclick="validar('#tbl_pagos','1')" lang="1" class="btn btn-primary " value="Agregar">
              <!-- <span  class="btn btn-primary fa fa-plus" onclick="validar('#tbl_pagos','1')" lang="1"> </span> -->

            </td>
                                   
              <td> 
                <input type="button" lang="1" class="btn btn-success "  name="" onclick="edit(this)" name="edit1" id="edit1" value="Editar"  >  </td>

                                  <td onclick="elimina_fila(this,'#tbl_pagos','1')" align="center" ><span class="btn btn-danger fa fa-trash"> </span></td>
                                <?php 
                                  }
                                }else{ 
                                $m=0;
                                $cnt_pagos=0;
                                $faltante=0; 
                                $fal=0; 
                                foreach($cns_pag as $rst_pag){ 
                                  $m++;
                                  $y=$m-1;
                                  if ($rst_pag->pag_tipo == '1') {
                                      $read_cnt='';
                                      $dis_contado='';
                                  } else if ($rst_pag->pag_tipo == '2') {
                                      $read_cnt='';
                                      $dis_contado='disabled';
                                  } else if ($rst_pag->pag_tipo == '3') {
                                      $read_cnt='';
                                      $dis_contado='disabled';
                                  } else if ($rst_pag->pag_tipo == '9') {
                                      $read_cnt='';
                                      $dis_contado='';
                                  } else if ($rst_pag->pag_tipo > '3') {
                                      $read_cnt='';
                                      $dis_contado='disabled';
                                  } else {
                                      $read_cnt='readonly';
                                      $dis_contado='disabled';
                                  }
                                ?>
                                <tr>
                                <td>
                                  <input type="text"  id="pag_descripcion<?php echo $m?>" name="pag_descripcion<?php echo $m?>" class="form-control itm" size="35px" lang="<?php echo $m?>" list="list_formas" onchange="traer_forma(this),busqueda_ntscre(this)" value="<?php echo $rst_pag->fpg_descripcion?>">
                                  <select>
                                    
                                  </select>
                                  <input type="hidden" id="pag_forma<?php echo $m?>" name="pag_forma<?php echo $m?>" class="form-control" size="10px" lang="1" value="<?php echo $rst_pag->pag_forma?>">
                                  <input type="hidden" id="pag_tipo<?php echo $m?>" name="pag_tipo<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_tipo?>">
                                </td>
                                <td><input type="text" id="pag_documento<?php echo $m?>" name="pag_documento<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" list="list_notas" onchange="load_notas_credito(this)" value="<?php echo $rst_pag->chq_numero?>">
                                  <input type="hidden" id="id_nota_credito<?php echo $m?>" name="id_nota_credito<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_id_chq?>">
                                  <input type="hidden" id="val_nt_cre<?php echo $m?>" name="val_nt_cre<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>">
                                </td>
                                <td>
                                  <select name="pag_contado<?php echo $m?>" id="pag_contado<?php echo $m?>" class="form-control" lang="1" <?php echo $dis_contado?>>
                                    <option value='0'>SELECCIONE</option>
                                  </select>
                                  <script>

                                    var pcont="<?php echo $rst_pag->contado?>";
                                    pag_contado<?php echo $m?>.innerHTML=pcont;  
                                    pag_contado<?php echo $m?>.value='<?php echo $rst_pag->pag_contado?>';
                                  </script>
                                </td>
                                <td><input type="text" id="pag_cantidad<?php echo $m?>" name="pag_cantidad<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" onchange="calculo_pagos(this)" <?php echo $read_cnt?> value="<?php echo str_replace(',', '', number_format($rst_pag->pag_cant, $dec))?>"></td>
                                <?php 
                                  if($m_pag==0){
                                  ?>
                                  <td align="center" ><span name="add<?php echo $m?>" id="add<?php echo $m?>" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_pagos','1')" lang="<?php echo $m?>"> </span></td>
                                  <td onclick="elimina_fila(this, '#tbl_pagos','1')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                              </tr>
                              <?php
                                  }
                                  $fal+=round($rst_pag->pag_cant,$dec);
                                  $cnt_pagos++;
                                } 
                                $faltante=round($factura->fac_total_valor,$dec)-round($fal,$dec);
                              }   
                                ?>
                            </tbody>
                            <tfoot>
                            <tr>
                              <td colspan="1"><label>Faltante</label></td>
                              <td><input type="text" id="faltante" name="faltante" class="form-control" size="10px" readonly value='<?php echo str_replace(',', '', number_format($faltante,2))?>'></td>
                            </tr>
                            </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr>
                    <tr>
                       <td class="col-sm-16" colspan="2">
                          <?php
                          if($m_prec==0){
                            $hid_prec="";
                          }else{
                            $hid_prec="display:none;";
                          }
                          ?>
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Unidad</th>
                                <th <?php echo $hid_inv?>>Inventario</th>
                                <th>Cantidad</th>
                                <th>Precio<select id="fprecio" style="width:50px; <?php echo $hid_prec?>" onchange="load_precios()" >
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select></th>
                                <th>Desc.%</th>
                                <th>Desc.$</th>
                                <th>IVA</th>
                                <th hidden>ICE%</th>
                                <th hidden>ICE $</th>
                                <th>Val.Total</th>
                                <th></th>
                                <th></th>
                              </tr>
                            </thead>

                            <tbody>
                            
                                <?php
                                $cnt_detalle=0;
                                    if($cprec==0){
                                      $r_precio='readonly';
                                    }else{
                                      $r_precio='';
                                    }
                                    if($cdesc==0){
                                      $r_descuento='readonly';
                                    }else{
                                      $r_descuento='';
                                    }
                                    
                                  ?>
                                    <tr>
                                        <td colspan="2">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="pro_descripcion" name="pro_descripcion"  value="" lang="1"    list="productos" onchange="load_producto(this.lang)"  />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_referencia" name="pro_referencia"   value="" lang="1" readonly style="width:300px;" />
                                            <input type="hidden"  id="pro_aux" name="pro_aux" lang="1"/>
                                            <input type="hidden"  id="pro_ids" name="pro_ids" lang="1"/>
                                            <input type="hidden"  id="mov_cost_unit" name="mov_cost_unit"  lang="1"/>
                                            <input type="hidden"  id="mov_cost_tot" name="mov_cost_tot" lang="1"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7" id="unidad" name="unidad"  value="" lang="1" readonly class="form-control" />
                                        </td>
                                        <td <?php echo $hid_inv?>>
                                          <input type ="text" size="7" style="text-align:right" id="inventario" name="inventario" value="" lang="1" readonly class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidad" name="cantidad"  value="" lang="1" onchange="calculo_encabezado(this), validar_inventario(this), costo(this)" class="form-control decimal" />
                                        </td>
                                        <td width="70px">
                                          <input type ="text" size="7" style="text-align:right" id="pro_precio" name="pro_precio" onchange="calculo_encabezado(this)" value="" lang="1" class="form-control decimal" <?php echo $r_precio?>/>
                                        </td>
                                        <td width="70px">
                                          <input type ="text" size="7"  style="text-align:right" id="descuento" name="descuento"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal" <?php echo $r_descuento?>/>
                                        </td>
                                        <td width="70px">
                                          <input type ="text" size="7"  style="text-align:right" id="descuent" name="descuent"  value="" lang="1" readonly  class="form-control decimal" />
                                        </td>
                                        <td width="70px">
                                          <input type="text" id="iva" style="text-align:right" name="iva" size="5" value="" readonly class="form-control decimal"/>
                                        </td>
                                        <td hidden><input type="text" name="ice_p" id="ice_p" size="5" value="0" readonly lang="1" /></td>
                                        <td hidden><input type="text" name="ice" id="ice" size="5" value="0" readonly lang="1"/>
                                            <input type=""  name="ice_cod" id="ice_cod" size="5" value="0" readonly lang="1"/>
                                        </td>
                                        <td width="100px">
                                            <input type ="text" size="9" style="text-align:right" id="valor_total" name="valor_total" value="" lang="1" readonly class="form-control decimal" />
                                            
                                        </td>
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>

                                        <!-- <td align="center" ><span name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1"> </span></td>
                                        <td onclick="elimina_fila(this, '#tbl_detalle','0')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td> -->
                                    </tr>
                                </tbody>        
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        $cost_tot=round($rst_det->cost_unit*$rst_det->cantidad,$dec);
                                        ?>
                                        <tr>
                                            <td id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center"><?PHP echo $n ?></td>
                                            <td id="pro_descripcion<?PHP echo $n ?>" name="pro_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_codigo ?></td>
                                            <td id="pro_referencia<?PHP echo $n ?>" name="pro_referencia<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_descripcion ?>
                                                <input type="hidden" size="7" id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="mov_cost_unit<?PHP echo $n ?>" name="mov_cost_unit<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cost_unit, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="mov_cost_tot<?PHP echo $n ?>" name="mov_cost_tot<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($cost_tot, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->pro_unidad ?></td>
                                            <td id="inventario<?PHP echo $n ?>" name="inventario<?PHP echo $n ?>" lang="<?PHP echo $n ?>" <?php echo $hid_inv?>  style="text-align:right"><?php echo str_replace(',', '', number_format($rst_det->inventario, $dcc)) ?></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidad' . $n ?>" name="<?php echo 'cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), validar_inventario_det(this), costo_det(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  /></td>
                                            <td><input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'pro_precio' . $n ?>" name="<?php echo 'pro_precio' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->pro_precio, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" <?php echo $r_precio?> /></td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuento' . $n ?>" name="<?php echo 'descuento' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuento, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)"  <?php echo $r_descuento?> />
                                            </td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuent' . $n ?>" name="<?php echo 'descuent' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuent, $dec)) ?>" lang="<?PHP echo $n ?>"  readonly/>
                                            </td>
                                            <td><input type="text" id="<?php echo 'iva' . $n ?>" name="<?php echo 'iva' . $n ?>" size="5" style="text-align:right" class="form-control" value="<?php echo $rst_det->pro_iva ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'ice_p' . $n ?>" name="<?php echo 'ice_p' . $n ?>" size="5" value="<?php echo str_replace(',', '', number_format($rst_det->ice_p, $dec)) ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'ice' . $n ?>" name="<?php echo 'ice' . $n ?>" size="5" class="form-control" value="<?php echo str_replace(',', '', number_format($rst_det->ice, $dec)) ?>" readonly lang="<?php echo $n ?>"/>
                                                <input type="hidden" id="<?php echo 'ice_cod' . $n ?>" name="<?php echo 'ice_cod' . $n ?>" size="5" class="form-control" value="<?php echo $rst_det->ice_cod ?>" lang="<?PHP echo $n ?>"readonly />
                                            </td>
                                            <td>
                                                <input type ="text" size="9" style="text-align:right" class="form-control" id="<?php echo 'valor_total' . $n ?>" name="<?php echo 'valor_total' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->precio_tot, $dec)) ?>" readonly lang="<?PHP echo $n ?>"/>
                                                
                                            </td>
                                            <td onclick="elimina_fila_det(this)" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                        </tr>
                                        <?php
                                        $cnt_detalle++;
                                    }
                                  }
                                ?>
                                </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><label>Observaciones (250 Caracteres max.):</label></td>
                                </tr>
                                <tr>

                                    <td valign="top" rowspan="9" colspan="<?php echo $col_obs?>">
                                      <textarea style="height: 80px; width: 90%;" id="observacion" name="observacion"   onkeydown="return enter(event)" maxlength="250" ><?php echo $factura->fac_observaciones ?></textarea>
                                    </td>    
                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal12, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>

                                   <?php 
                                   $total0= floatval($factura->fac_subtotal0)  + floatval($factura->fac_subtotal_ex_iva) + floatval($factura->fac_subtotal_no_iva)
                                  ?>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal_1" name="subtotal0" value="<?php echo str_replace(',', '', number_format($total0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>

                                <tr hidden >
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr hidden >
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal_ex_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal_no_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr >
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Descuento:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_descuento" name="total_descuento" value="<?php echo str_replace(',', '', number_format($factura->fac_total_descuento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="2" align="right">Total ICE:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_ice" name="total_ice" value="<?php echo str_replace(',', '', number_format($factura->fac_total_ice, $dec)) ?>"  readonly/>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_iva" name="total_iva" value="<?php echo str_replace(',', '', number_format($factura->fac_total_iva, $dec)) ?>" readonly />
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" align="right">Propina:</td>
                                    <td><input type="text" class="form-control" id="total_propina" name="total_propina" value="<?php echo str_replace(',', '', number_format($factura->fac_total_propina, $dec)) ?>"  style="text-align:right" onchange="calculo()"/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Valor:</td>
                                    <td><input style="text-align:right;font-size:15px;color:red  " type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($factura->fac_total_valor, $dec)) ?>" readonly />
                                        
                                    </td>
                                </tr>
                              </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                  </table>
              </div>
                                
                <input type="hidden" class="form-control" name="fac_id" value="<?php echo $factura->fac_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
                <input type="hidden" class="form-control" id="count_pagos" name="count_pagos" value="<?php echo $cnt_pagos?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>

    <div class="modal fade" id="pagos">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title">Formas de pago</h4>
              </div>
              <div class="modal-body">
                <table class="table">
                  <tr>
                    <th>Documento</th>
                    <th>Plazo</th>
                    <th>Banco</th>
                    <th>Tarjeta</th>
                   
                  </tr>
                  <tr id="det_pagos">
                   
                      <td width="190"><input type="text" id="pag_documento_aux" name="pag_documento_aux" class="form-control" size="15px" lang="1" list="list_notas" onchange="load_notas_credito(this)">
                      <input type="hidden" value="0" id="id_nota_credito_aux" name="id_nota_credito_aux" class="form-control" size="10px" lang="1">
                      <input type="hidden" value="0" id="val_nt_cre_aux" name="val_nt_cre_aux" class="form-control" size="10px" lang="1">
                      <input type="hidden" value="0" id="pag_cantidad_aux" name="pag_cantidad_aux" class="form-control" size="10px" lang="1">
                       </td> 
                       <td width="190">
                        <select name="pag_plazo_aux" id="pag_plazo_aux" class="form-control" lang="1">
                        <option value='0'>SELECCIONE</option>
                        </select>
                        </td>
                        <td width="190">
                        <select name="pag_banco_aux" id="pag_banco_aux" class="form-control" lang="1">
                        <option value='0'>SELECCIONE</option>
                        </select>
                        </td>
                       
                        <td width="190">
                        <select name="pag_tarjeta_aux" id="pag_tarjeta_aux" class="form-control" lang="1">
                        <option value='0'>SELECCIONE</option>
                        </select>
                        </td>
                       
                  </tr>
                </table>
              </div>

               <div class="modal-footer"  >
                <div style="float:right">
                  <button type="button" class="btn btn-success pull-left" onclick="llevar_pagos()" >Agregar</button>
                <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button> -->
                </div>
                

              </div>
              
            </div>
          </div>
</div>
<div class="modal fade" id="clientes">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="limpiar_cliente()" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="text-align:center;">Clientes y proveedores</h4>
              </div>
              <div class="modal-body">
                <table class="table table-bordered table-striped" id="det_clientes">
                 
                  
                </table>
              </div>

               <div class="modal-footer"  >
                <div style="float:right">
               
                <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button> -->
                </div>
                

              </div>
              
            </div>
          </div>
</div>
<div class="modal fade" id="notas">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="limpiar_notas()" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="text-align:center;">Notas de credito </h4>
              </div>
              <div class="modal-body">
                <table class="table table-bordered table-striped" id="det_notas">
                 
                  
                </table>
              </div>

               <div class="modal-footer"  >
                <div style="float:right">
               
                <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button> -->
                </div>
                

              </div>
              
            </div>
          </div>
</div>
    <!-- <datalist id="list_clientes">
      <?php 
        if(!empty($cns_clientes)){
          foreach ($cns_clientes as $rst_cli) {
      ?>
        <option value="<?php echo $rst_cli->cli_id?>"><?php echo $rst_cli->cli_ced_ruc .' '.$rst_cli->cli_raz_social?></option>
      <?php 
          }
        }
      ?>
    </datalist> -->
    <datalist id="productos">
       
      <?php 
        if(!empty($cns_productos)){
          foreach ($cns_productos as $rst_pro) {
      ?>
        <option value="<?php echo $rst_pro->mp_d?>"><?php echo $rst_pro->mp_c .' '.$rst_pro->mp_d?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>
    <datalist id="list_formas">

      
    </datalist>
    <datalist id="list_notas">
    </datalist>

    <style type="text/css">
      .panel{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }

      div{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }
      div .panel-heading{
        margin-bottom: 4px !important;
        margin-top: 4px !important;
        padding-bottom: 4px !important;
        padding-top: 4px !important;
      }
      
      .form-control{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
        height:28px !important;
      }

      td{
        margin-bottom: 1px !important;
        margin-top: 1px !important;
        padding-bottom: 1px !important;
        padding-top: 1px !important;
      }
    </style>
    <script >

      var base_url='<?php echo base_url();?>';
      var inven='<?php echo $inven;?>';
      var ctr_inv='<?php echo $ctrl_inv;?>';
      var dec='<?php echo $dec;?>';
      var dcc='<?php echo $dcc;?>';
      var m_pag='<?php echo $m_pag;?>';
      var objeto ;

      function edit(obj) {

      if(obj==1){
        n=1;
      }else{
        n=obj.lang;
      }
      

      if ( ($('#pag_tipo'+n).val()) == 8) {
       $("#notas").modal('show');
      }else{
         traer_forma_2(obj);
      }

      
      


  



      }

      function pas(){
      if ($('#pasaporte').prop('checked') == true) {
                    $('#pasaporte').attr('checked', false);
                    // $('#tipo_cliente').html('RUC/CI:');
                }else{
                  $('#pasaporte').attr('checked', true);
                  
                  $('#tipo_cliente').html('Pasaporte');
                  limpiar_cliente();

                   $('#identificacion').focus();
                  if($('#identificacion').val()=='9999999999999'){
                    $('#identificacion').focus();
                    $('#identificacion').val('');
                    $('#cli_id').val('0');
                    $('#nombre').val('');
                    $('#telefono_cliente').val('');
                    $('#direccion_cliente').val('');
                    // $('#cli_ciudad').val('Quito');
                    $('#email_cliente').val('');
                    // $('#cli_pais').val('Ecuador');
                    $('#cli_parroquia').val('');
                    $('#pag_descripcion1').val('EFECTIVO');
                    $('#pag_documento1').val('');
                    $('#pag_contado1').val('0');
                    $('#pag_forma1').val('0');
                    $('#pag_cantidad1').val('0');
                    $('#pag_tipo1').val('0');
                    $('#id_nota_credito1').val('0');
                    $('#val_nt_cre1').val('0');
                    }
                }
      }
      function datos(){
            $('#identificacion').focus();
            $('#tipo_cliente').html('RUC/CI:');
            limpiar_cliente();

        if ($('#pasaporte').prop('checked') == true) {
                    $('#pasaporte').attr('checked', false);
                }

      }
      function limpiar_cliente(){
        $('#identificacion').val('');
            $('#cli_id').val('0');
            $('#nombre').val('');
            $('#telefono_cliente').val('');
            $('#direccion_cliente').val('');
            // $('#cli_ciudad').val('Quito');
            $('#email_cliente').val('');
            // $('#cli_pais').val('Ecuador');
            $('#cli_parroquia').val('');
            $('#pag_descripcion1').val('EFECTIVO');
            $('#pag_documento1').val('');
            $('#pag_contado1').val('0');
            $('#pag_forma1').val('0');
            $('#pag_cantidad1').val('0');
            $('#pag_tipo1').val('0');
            $('#id_nota_credito1').val('0');
            $('#val_nt_cre1').val('0');
      }     
      function llevar_pagos(){
        n=objeto.lang;
        

        if( $('#pag_documento_aux').val().length == 0 && $('#pag_plazo_aux').val() == 0 && $('#pag_banco_aux').val() == 0 && $('#pag_tarjeta_aux').val()== 0 ){

          //alert("Debe seleccionar por lo menos un elemento");
          swal("Error!", "Debe seleccionar por lo menos un elemento.!", "error");
        }
        else{
          


            if($('#pag_tipo'+n).val()==8){
            $('#pag_cantidad'+n).val($('#pag_cantidad_aux').val());
            }
            $('#pag_documento'+n).val($('#pag_documento_aux').val());
            $('#pag_plazo'+n).val($('#pag_plazo_aux').val());
            $('#pag_banco'+n).val($('#pag_banco_aux').val());
            $('#pag_tarjeta'+n).val($('#pag_tarjeta_aux').val());
            $('#id_nota_credito'+n).val($('#id_nota_credito_aux').val());
            $('#val_nt_cre'+n).val($('#val_nt_cre_aux').val());

            $("#pagos").modal('hide');
            calculo_pagos();
        }

       
      }

      function extra(){

        if ($('#pasaporte').prop('checked') == true) {
                  $('#pas_aux').val(1);
                  $('#cli_pais').val('');
                  $('#cli_ciudad').val('');
                }else{
                  $('#pas_aux').val(0);
                  $('#cli_pais').val('Ecuador');
                  $('#cli_ciudad').val('Quito');
                }

      }

      function buscar_clientes(){

        $.ajax({
                    beforeSend: function () {


                      // if ($('#identificacion').val().length == 0) {
                      //       alert('Ingrese dato');
                           
                      //       return false;
                      // }
                    },
                    url: base_url+"factura/buscar_cliente/"+identificacion.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {

                        if(dt!=""){

                        $('#det_clientes').html(dt.lista);
                        $("#clientes").modal('show');
                        }else{
                          verificar_cedula($('#identificacion').val());
                        }
                      },
                  error : function(xhr, status) {
                    
                          //alert('Cliente no existe \n Se creará uno nuevo');
                          $('#identificacion').focus();
                           verificar_cedula($('#identificacion').val());
                    }
                    }); 
      }

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }


            function traer_cliente(valor){

              var id =valor;
              $.ajax({
                    beforeSend: function () {
                      if ($('#identificacion').val().length == 0) {
                            //alert('Ingrese dato');
                            $('#nombre').focus();
                            $('#cli_id').val('0');
                            $('#nombre').val('');
                            $('#telefono_cliente').val('');
                            $('#direccion_cliente').val('');
                            $('#cli_ciudad').val('');
                            $('#email_cliente').val('');
                            $('#cli_pais').val('');
                            $('#cli_parroquia').val('');
                            $('#pag_descripcion1').val('EFECTIVO');
                            $('#pag_documento1').val('');
                            $('#pag_contado1').val('0');
                            $('#pag_cantidad1').val('0');
                            $('#pag_forma1').val('0');
                            $('#pag_tipo1').val('0');
                            $('#id_nota_credito1').val('0');
                            $('#val_nt_cre1').val('0');
                            return false;
                      }
                    },
                    url: base_url+"factura/traer_cliente/"+id,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){

                          $('#cli_id').val(dt.cli_id);
                          $('#nombre').val(dt.cli_raz_social);
                          $('#telefono_cliente').val(dt.cli_telefono);
                          $('#direccion_cliente').val(dt.cli_calle_prin);
                          $('#cli_ciudad').val(dt.cli_canton);
                          $('#email_cliente').val(dt.cli_email);
                          $('#identificacion').val(dt.cli_ced_ruc);
                          $('#cli_pais').val(dt.cli_pais);
                          $('#cli_parroquia').val(dt.cli_parroquia);
                          $('#pag_descripcion1').val('EFECTIVO');
                          $('#pag_documento1').val('');
                          $('#pag_contado1').val('0');
                          $('#pag_forma1').val('0');
                          $('#pag_cantidad1').val('0');
                          $('#pag_tipo1').val('0');
                          $('#id_nota_credito1').val('0');
                          $('#val_nt_cre1').val('0');
                          traer_forma(1);
                            $("#clientes").modal('hide');
                         

                        }else{
                          
                          //alert('Cliente no existe \n Se creará uno nuevo');
                          swal("", "¡Cliente no existe. Se creará uno nuevo!", "info");
                          $('#nombre').focus();
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                         // $('#cli_ciudad').val('Quito');
                          $('#email_cliente').val('');
                         // $('#cli_pais').val('Ecuador');
                          $('#cli_parroquia').val('');
                          $('#pag_descripcion1').val('EFECTIVO');
                          $('#pag_documento1').val('');
                          $('#pag_contado1').val('0');
                          $('#pag_forma1').val('0');
                          $('#pag_cantidad1').val('0');
                          $('#pag_tipo1').val('0');
                          $('#id_nota_credito1').val('0');
                          $('#val_nt_cre1').val('0');
                        } 


                        
                    },
                    error : function(xhr, status) {
                          //alert('Cliente  no existe \n Se creará uno nuevo');
                          //  swal("", "¡Cliente no existe. Se creará uno nuevo!", "info");
                          // $('#nombre').focus();
                          // $('#cli_id').val('0');
                          // $('#nombre').val('');
                          // $('#telefono_cliente').val('');
                          // $('#direccion_cliente').val('');
                          // //$('#cli_ciudad').val('Quito');
                          // $('#email_cliente').val('');
                          // ///$('#cli_pais').val('Ecuador');
                          // $('#cli_parroquia').val('');
                          // $('#pag_descripcion1').val('EFECTIVO');
                          // $('#pag_contado1').val('0');
                          // $('#pag_forma1').val('0');
                          // $('#pag_cantidad1').val('0');
                          // $('#pag_tipo1').val('0');
                          // $('#id_nota_credito1').val('0');
                          // $('#val_nt_cre1').val('0');

                          var dato =  $('#identificacion').val();
                          consulta_api(dato);
                           //traer_forma(1);
                    }
                    });    

            }
            function consulta_api(dato){
        var id = dato;
        $.ajax({
              beforeSend: function () {
                if ($('#identificacion').val().length == 0) {
                      //alert('Ingrese dato');
                      swal("Error!", "Ingrese dato.!", "error");
                      
                      $('#nombre').focus();
                      $('#cli_id').val('0');
                      $('#nombre').val('');
                      $('#telefono_cliente').val('');
                      $('#direccion_cliente').val('');
                      $('#cli_ciudad').val('Quito');
                      $('#email_cliente').val('');
                      $('#cli_pais').val('Ecuador');
                      $('#cli_parroquia').val('');
                      $('#pag_descripcion1').val('EFECTIVO');
                      $('#pag_documento1').val('');
                      $('#pag_contado1').val('0');
                      $('#pag_cantidad1').val('0');
                      $('#pag_forma1').val('0');
                      $('#pag_tipo1').val('0');
                      $('#id_nota_credito1').val('0');
                      $('#val_nt_cre1').val('0');
                      return false;
                }
              },
              url: base_url+"factura/consulta_api/"+id,
              type: 'JSON',
              dataType: 'JSON',
              success: function (dt) {
                  if(dt!=""){
                    $('#cli_id').val(0);
                    $('#nombre').val(dt.cli_raz_social);
                    $('#telefono_cliente').val('');
                    $('#direccion_cliente').val('');
                    $('#cli_ciudad').val('Quito');
                    $('#email_cliente').val('');
                    $('#identificacion').val(dt.cli_ced_ruc);
                    $('#cli_pais').val('Ecuador');
                    $('#cli_parroquia').val(dt.cli_parroquia);
                    $('#pag_descripcion1').val('EFECTIVO');
                    $('#pag_documento1').val('');
                    $('#pag_contado1').val('0');
                    $('#pag_forma1').val('0');
                    $('#pag_cantidad1').val('0');
                    $('#pag_tipo1').val('0');
                    $('#id_nota_credito1').val('0');
                    $('#val_nt_cre1').val('0');
                    traer_forma(1);
                    
                     $("#clientes").modal('hide');
                  }else{
                    //alert('Cliente no existe \n Se creará uno nuevo');
                     swal("Error!", "Cliente no existe. Se creará uno nuevo.!", "error");
                      $('#nombre').focus();
                      $('#cli_id').val('0');
                      $('#nombre').val('');
                      $('#telefono_cliente').val('');
                      $('#direccion_cliente').val('');
                      //$('#cli_ciudad').val('Quito');
                      $('#email_cliente').val('');
                      ///$('#cli_pais').val('Ecuador');
                      $('#cli_parroquia').val('');
                      $('#pag_descripcion1').val('EFECTIVO');
                      $('#pag_contado1').val('0');
                      $('#pag_forma1').val('0');
                      $('#pag_cantidad1').val('0');
                      $('#pag_tipo1').val('0');
                      $('#id_nota_credito1').val('0');
                      $('#val_nt_cre1').val('0');
                  } 
                  
              },
              error : function(xhr, status) {
                   swal("Error!", "Cliente no existe. Se creará uno nuevo.!", "error");
                      $('#nombre').focus();
                      $('#cli_id').val('0');
                      $('#nombre').val('');
                      $('#telefono_cliente').val('');
                      $('#direccion_cliente').val('');
                      //$('#cli_ciudad').val('Quito');
                      $('#email_cliente').val('');
                      ///$('#cli_pais').val('Ecuador');
                      $('#cli_parroquia').val('');
                      $('#pag_descripcion1').val('EFECTIVO');
                      $('#pag_contado1').val('0');
                      $('#pag_forma1').val('0');
                      $('#pag_cantidad1').val('0');
                      $('#pag_tipo1').val('0');
                      $('#id_nota_credito1').val('0');
                      $('#val_nt_cre1').val('0');
              }
              });    
         
      }

            function validar(table, opc){

              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              if(opc==0){
                if($('#cantidad').val().length!=0 &&  parseFloat($('#cantidad').val())>0 && $('#pro_precio').val().length!=0 &&  parseFloat($('#pro_precio').val())>0 && $('#descuento').val().length!=0 && $('#pro_descripcion').val().length!=0){

                  clona_detalle(table);
                }else{
                  if( ($('#cantidad'+a1).val().length!=0) || $('#cantidad'+a1).val()==0) {
                            $('#cantidad'+a1).css({borderColor: "red"});
                            $('#cantidad'+a1).focus();
                  }
                }
              }else{
                if($('#pag_forma'+a1).val()!='0' &&  parseFloat($('#pag_cantidad'+a1).val())>0 && $('#pag_cantidad'+a1).val().length!=0){

                  clona_fila(table,opc);
                }
                else{
                  if($('#pag_forma'+a1).val()=='0'){
                     $('#pag_forma'+a1).css({borderColor: "red"});
                     $('#pag_forma'+a1).focus();
                 }
                 else if( ($('#pag_cantidad'+a1).val().length!=0) || $('#pag_cantidad'+a1).val()==0) {
                            $('#pag_cantidad'+a1).css({borderColor: "red"});
                            $('#pag_cantidad'+a1).focus();
                  }
                }
              }

            }
            

            function clona_fila(table,opc) {

                
                  var tr = $(table).find("tbody tr:last").clone();
                  tr.find("input,select").attr("name", function () {
                      var parts = this.id.match(/(\D+)(\d+)$/);
                      return parts[1] + ++parts[2];
                  }).attr("id", function () {
                      var parts = this.id.match(/(\D+)(\d+)$/);
                      x = ++parts[2];
                      this.lang = x;
                      var parent = $(this).parents();
                      $(parent[1]).css('background-color', 'transparent');
                      if (parts[1] == 'item') {
                          this.value = x;
                      } else if (parts[1] == 'pag_forma') {
                          this.value = '0';
                      } else if (parts[1] == 'pag_contado') {
                          this.value = '0';
                      } else if (parts[1] == 'pag_descripcion') {
                          this.value = '';
                      }else if (parts[1] == 'edit') {
                          this.lang = x;
                      }else if(parts[1] == 'add'){
                       
                       this.type="hidden";
                      }
                      else{
                          this.value ='0';
                      }
                      return parts[1] + x;
                  });
                  $(table).find("tbody tr:last").after(tr);
                  if(opc==0){
                    $('#count_detalle').val(x);
                  }else{
                    $('#count_pagos').val(x);
                    $('#pag_documento' + x).attr('readonly', false);
                    ultimo_pago(x);
                  }
                
            }

            function clona_detalle(table,opc) {
                d = 0;
                n = 0;
                ap = '"';
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                if(a==null){
                    j=0;
                }else{
                    j=parseInt(a);
                }
                if (j > 0) {
                    while (n < j) {
                        n++;
                        if ($('#pro_aux' + n).val() == pro_aux.value) {
                            d = 1;
                            cant = round($('#cantidad' + n).val(),dcc) + round(cantidad.value,dcc);
                            $('#cantidad' + n).val(cant.toFixed(dcc));
                            $('#pro_precio' + n).val(pro_precio.value);
                            $('#descuento' + n).val(descuento.value);
                            $('#inventario' + n).html(inventario.value);
                            
                        }
                    }
                }
                var cprec='<?php echo $cprec?>';
                var cdesc='<?php echo $cdesc?>';
                if(cprec==0){
                  r_precio='readonly';
                }else{
                  r_precio='';
                }
                if(cdesc==0){
                  r_descuento='readonly';
                }else{
                  r_descuento='';
                }
                                    
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                          "<input type ='hidden' name='pro_aux"+i+"' id='pro_aux"+i+"' lang='"+i+"' value='"+pro_aux.value+"'/>"+
                                          "<input type ='hidden' name='pro_ids"+i+"' id='pro_ids"+i+"' lang='"+i+"' value='"+pro_ids.value+"'/>"+
                                          "<input type ='hidden' name='mov_cost_unit"+i+"' id='mov_cost_unit"+i+"' lang='"+i+"' value='"+mov_cost_unit.value+"'/>"+
                                          "<input type ='hidden' name='mov_cost_tot"+i+"' id='mov_cost_tot"+i+"' lang='"+i+"' value='"+mov_cost_tot.value+"'/>"+
                                        "</td>"+
                                        "<td id='pro_descripcion"+i+"' lang='"+i+"'>"+pro_descripcion.value+"</td>"+
                                        "<td id='pro_referencia"+i+"' lang='"+i+"'>"+pro_referencia.value+"</td>"+
                                        "<td id='unidad"+i+"' lang='"+i+"'>"+unidad.value+"</td>"+
                                        "<td id='inventario"+i+"' lang='"+i+"' align='right'>"+inventario.value+"</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' lang='"+i+"' onchange='validar_inventario_det()'  value='"+cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio"+i+"' name='pro_precio"+i+"' onchange='calculo()' value='"+pro_precio.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' "+r_precio+" />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuento"+i+"' name='descuento"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' value='"+descuento.value+"' onkeyup='validar_decimal(this)' "+r_descuento+"/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuent"+i+"' name='descuent"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+descuent.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='iva"+i+"' name='iva"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+$('#iva').val()+"' />"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='ice_p"+i+"' name='ice_p"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+ice_p.value+"' onkeyup='validar_decimal(this)'/>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='ice_cod"+i+"' name='ice_cod"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+ice_cod.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='ice"+i+"' name='ice"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+ice.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td >"+
                                          "<input type ='text' size='7'  style='text-align:right' id='valor_total"+i+"' name='valor_total"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+valor_total.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                pro_referencia.value = '';
                pro_descripcion.value = '';
                pro_aux.value = '';
                pro_ids.value = '';
                mov_cost_unit.value = '';
                mov_cost_tot.value = '';
                unidad.value = '';
                inventario.value = '';
                cantidad.value = '';
                pro_precio.value = '';
                iva.value = '';
                descuento.value = '';
                descuent.value = '';
                ice.value = '';
                ice_cod.value = '';
                ice_p.value = '';
                valor_total.value = '';
                $('#cantidad').css({borderColor: ""});
                $('#pro_descripcion').focus();
                calculo();
                
            }

            function elimina_fila(obj, tbl,op) {
                
                  itm = $(tbl + ' .itm').length;
                
                  if (itm > 1) {
                      var parent = $(obj).parents();
                      $(parent[0]).remove();
                  } else {
                      //alert('No puede eliminar todas las filas');
                      swal("Advertencia!", "No puede eliminar todas las filas.!", "warning");
                  }
                  calculo_pagos();
            }

            function elimina_fila_det(obj) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
            }

            
            //algoritmo digito verificado CC//
            function verificar_cedula(cedula) {
              
              //   if(obj != '9999999999'){
              //     i = obj.value.trim().length;
              //     c = obj.value.trim();
              // }else{
                
                
                c =cedula;
                i = 13;
                
                if (cedula=='9999999999999') {
                  $('#identificacion').val('9999999999999');
                }
              // }

                //  i = obj.value.trim().length;
                // c = obj.value.trim();

        var s = 0;
        if ($('#pasaporte').prop('checked') == false) {


          if($('#identificacion').val().length<10 || verificar_entero(cedula) != true || $('#identificacion').val().length>13 )
            {
              
             // alert('RUC/CC incorrecto');
                 swal("Error!", "Cedula/RUC incorrecto.!", "error");
             
              limpiar_cliente();
            }

              else{

                          if($('#identificacion').val().length==13 || $('#identificacion').val().length==10){
                            if (!isNaN(c)) {
                                n = 0;
                                while (n < 9) {
                                    r = n % 2;
                                    if (r == 0) {
                                        m = 2;
                                    } else {
                                        m = 1;
                                    }
                                    ml = (c.substr(n, 1) * 1) * m;

                                    if (ml > 9) {
                                        ml = (ml.toString().substr(0, 1) * 1) + (ml.toString().substr(1, 1) * 1);
                                    }
                                    s += ml;
                                    n++;
                                }
                                d = s % 10;
                                if (d == 0) {
                                    t = 0;
                                } else {
                                    t = 10 - d;
                                }
                                if (t.toString() == c.substr(9, 1) && (i==10 ||i==13)) {
                                    traer_cliente(cedula);
                                } else {
                                    //alert('RUC/CC incorrecto');
                                       swal("Error!", "Cedula/RUC incorrecto.!", "error");
                                  limpiar_cliente();

                                }
                            } else {
                                 traer_cliente(cedula);
                            }
                          }else{
                              traer_cliente(cedula);
                          }
                  } 

              }

                else {
                 
                    traer_cliente(cedula);
                }
              
               

            }
            

function verificar_entero(cedula) {
   cedula = parseInt(cedula);

  
    if(Number.isInteger(cedula)) {

      return true;
    }else{

      return false;
    }
}


            function load_producto(j) {
              if(m_pag==1 && $('#pag_forma1').val().length==0){
                //alert('Seleccione una forma de pago');
                swal("Error!", "Seleccione una forma de pago.!", "error");
                $('#pro_descripcion').val('');
              }else{
                vl = $('#pro_descripcion').val();
                if(m_pag==1){
                  fpag=$('#pag_forma1').val();
                }else{
                  fpag=0;
                }
                $.ajax({
                  beforeSend: function () {
                      if ($('#pro_descripcion').val().length == 0) {
                            //alert('Ingrese un producto');
                       swal("Error!", "Ingrese un producto.!", "error");


                            return false;
                      }
                    },
                    url: base_url+"factura/load_producto/"+vl+"/"+inven+"/"+ctr_inv+"/"+fpag+"/"+emi_id.value+"/0",
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') {
                        $('#pro_descripcion').val(dt.pro_codigo);
                        $('#pro_referencia').val(dt.pro_descripcion);
                        if(dt.pro_iva==''){
                          $('#iva').val('12');
                        }else{
                          $('#iva').val(dt.pro_iva);
                        }
                        $('#pro_aux').val(dt.pro_id);
                        $('#pro_ids').val(dt.ids);
                        
                        $('#unidad').val(dt.pro_unidad);
                       
                        if($('#fprecio').val()=='1'){
                            if (dt.pro_precio == null) {
                                $('#pro_precio').val('0');
                              } else {
                                $('#pro_precio').val(parseFloat(dt.pro_precio).toFixed(dec));
                              }
                            }else{
                              if (dt.pro_precio2 == null) {
                                $('#pro_precio').val('0');
                              } else {
                                $('#pro_precio').val(parseFloat(dt.pro_precio2).toFixed(dec));
                              }
                        }

                        if (dt.pro_descuento == '') {
                            $('#descuento').val(0);
                        } else {
                            $('#descuento').val(parseFloat(dt.pro_descuento).toFixed(dec));
                        }
                        if (inven == 0) {
                            if (dt.cost_unit == '') {
                                $('#mov_cost_unit').val('0');
                            } else {
                                $('#mov_cost_unit').val(parseFloat(dt.cost_unit).toFixed(dec));
                            }
                            if (dt.inventario == '') {
                                $('#inventario').val('0');
                                //swal("Error!", "NO SE PUEDE AGREGAR EL PRODUCTO. NO TIENE INVENTARIO.!", "error");
                            } else {
                                $('#inventario').val(parseFloat(dt.inventario).toFixed(dcc));
                                 var dat = parseFloat(dt.inventario)/parseFloat(dt.inventario);
                                $('#cantidad').val(dat);
                                 validar('#tbl_detalle','0');
                            }
                           
                        }else{
                          $('#mov_cost_unit').val('0');
                          $('#inventario').val('0');
                          $('#cantidad').val(0);
                           
                        }
                         

                        // if (dt.ice_p== '') {
                            $('#ice').val('0');
                            $('#ice_p').val('0');
                        // } else {
                        //     $('#ice').val('0');
                        //     $('#ice_p').val(parseFloat(dt.ice_p).toFixed(dec));

                        // }

                        // if (dt.ice_cod == '') {
                            $('#ice_cod').val('0');
                        // } else {
                        //     $('#ice_cod').val(dt.ice_cod);
                        // }

                        //$('#cantidad').focus();
                       
                      }else{
                        $('#pro_descripcion').val('');
                        $('#pro_referencia').val('');
                        $('#cantidad').val('');
                        $('#iva').val('0');
                        $('#pro_aux').val('');
                        $('#pro_ids').val('');
                        $('#pro_precio').val('0');
                        $('#descuento').val('0');
                        if (inven == 0) {
                            $('#mov_cost_unit').val('0');
                            $('#inventario').val('0');
                        }
                        $('#ice').val('0');
                        $('#ice_p').val('0');
                        $('#ice_cod').val('0');
                        $('#pro_descripcion').focus();
                      }
                      calculo('1');
                    }
                  });
                }
              }

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }


            function calculo_encabezado() {pag_forma
                
                n = 0;
                var t12 = 0;
                var t0 = 0;
                var tex = 0;
                var tno = 0;
                var tdsc = 0;
                var tiva = 0;
                var gtot = 0;
                var tice = 0;
                var tib = 0;
                var sub = 0;
                var prop=0;

                while (n < i) {
                    n++;
                    // if ($('#item' + n).val() == null) {
                    //     ob = 0;
                    //     val = 0;
                    //     val2 = 0;
                    //     d = 0;
                    //     cnt = 0;
                    //     pr = 0;
                    //     d = 0;
                    //     vtp = 0;
                    //     vt = 0;
                    //     ic = 0;
                    //     ib = 0;
                    //     dsc= 0;
                    //     uni=0;
                    // } else {
                        uni = $('#mov_cost_unit').val();
                        cnt = $('#cantidad').val().replace(',', '');
                        if(cnt==''){
                          cnt=0;
                        }
                        pr = $('#pro_precio').val().replace(',', '');
                        d = $('#descuento').val().replace(',', '');
                        vtp = round(cnt,dcc) * round(pr,2); //Valor total parcial
                        vt = (vtp * 1) - (vtp * round(d,dec) / 100);
                        ic = $('#ice_p').val().replace(',', '');
                        pic = (round(vt,dec) * round(ic,dec)) / 100;
                        if(pic.toFixed(2)=='NaN'){
                          pic=0;
                        }
                        dsc= (round(vtp,dec) * round(d,dec)) / 100; 
                        if(dsc.toFixed(2)=='NaN'){
                          dsc=0;
                        }  
                        $('#descuent').val(dsc.toFixed(dec));
                        $('#valor_total').val(vt.toFixed(dec));
                        ob = $('#iva').val();
                        val = $('#valor_total').val().replace(',', '');
                        d = $('#descuent').val().replace(',', '');
                        $('#ice').val(pic.toFixed(dec));
                        ctot = round(cnt,dec) * round(uni,dec);
                        $('#mov_cost_tot').val(ctot.toFixed(6));

                    }
            }     


            function calculo(obj) {
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                i = parseInt(a);
                n = 0;
                var t12 = 0;
                var t0 = 0;
                var tex = 0;
                var tno = 0;
                var tdsc = 0;
                var tiva = 0;
                var gtot = 0;
                var tice = 0;
                var tib = 0;
                var sub = 0;
                var prop=0;

                while (n < i) {
                    n++;
                    if ($('#item' + n).val() == null) {
                        ob = 0;
                        val = 0;
                        val2 = 0;
                        d = 0;
                        cnt = 0;
                        pr = 0;
                        d = 0;
                        vtp = 0;
                        vt = 0;
                        ic = 0;
                        ib = 0;
                        dsc= 0;
                        uni=0;
                    } else {
                        uni = $('#mov_cost_unit' + n).val();
                        cnt = $('#cantidad' + n).val().replace(',', '');
                        if(cnt==''){
                          cnt=0;
                        }
                        pr = $('#pro_precio' + n).val().replace(',', '');
                        d = $('#descuento' + n).val().replace(',', '');
                        vtp = round(cnt,dcc) * round(pr,2); //Valor total parcial
                        vt = (vtp * 1) - (vtp * round(d,dec) / 100);
                        ic = $('#ice_p' + n).val().replace(',', '');
                        pic = (round(vt,dec) * round(ic,dec)) / 100;
                        if(pic.toFixed(2)=='NaN'){
                          pic=0;
                        }
                        dsc= (round(vtp,dec) * round(d,dec)) / 100; 
                        if(dsc.toFixed(2)=='NaN'){
                          dsc=0;
                        }  
                        $('#descuent' + n).val(dsc.toFixed(dec));
                        $('#valor_total' + n).val(vt.toFixed(dec));
                        ob = $('#iva' + n).val();
                        val = $('#valor_total' + n).val().replace(',', '');
                        d = $('#descuent' + n).val().replace(',', '');
                        $('#ice' + n).val(pic.toFixed(dec));
                        ctot = round(cnt,dec) * round(uni,dec);
                        $('#mov_cost_tot' + n).val(ctot.toFixed(6));

                    }

                    tdsc = (round(tdsc,dec) * 1) + (round(d,dec) * 1);
                    tice = (round(tice,dec) * 1) + (round(pic,dec) * 1);

                    if (ob == '14') {
                        t12 = (round(t12,dec) * 1 + round(vt,dec) * 1);
                        tiva = ((round(tice,dec) + round(t12,dec)) * 14 / 100);
                    }

                    if (ob == '12') {
                        t12 = (round(t12,dec) * 1 + round(vt,dec) * 1);
                        tiva = ((round(tice,dec) + round(t12,dec)) * 12 / 100);
                    }
                    if (ob == '0') {
                        t0 = (round(t0,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'EX') {
                        tex = (round(tex,dec) * 1 + round(vt,dec) * 1);
                    }
                    if (ob == 'NO') {
                        tno = (round(tno,dec) * 1 + round(vt,dec) * 1);
                    }

                }

                sub = round(t12,dec) + round(t0,dec) + round(tex,dec) + round(tno,dec);
                sub1 = round(t0,dec) + round(tex,dec) + round(tno,dec);
                prop = $('#total_propina').val().replace(',', '');
                gtot = (round(sub,dec) * 1 + round(tiva,dec) * 1 + round(tice,dec) * 1 + round(prop,dec) * 1);

                 
                $('#subtotal12').val(t12.toFixed(dec));
                $('#subtotal0').val(t0.toFixed(dec));
                $('#subtotalex').val(tex.toFixed(dec));
                $('#subtotalno').val(tno.toFixed(dec));
                $('#subtotal_1').val(sub1.toFixed(dec));

                $('#subtotal').val(sub.toFixed(dec));
                $('#total_descuento').val(tdsc.toFixed(dec));
                $('#total_iva').val(tiva.toFixed(dec));
                $('#total_ice').val(tice.toFixed(dec));
                $('#total_valor').val(gtot.toFixed(dec));
                pag_cantidad1.value = gtot.toFixed(dec);
                calculo_pagos();
            }     

            function calculo_pagos(obj){
              if(obj!=null){
                l=obj.lang;
              }
              tv=round($('#total_valor').val(),dec);
              var tr = $('#tbl_pagos').find("tbody tr:last");
              var a = tr.find("input").attr("lang");
              i = parseInt(a);
              var n = 0;
              var pg_ac=0;
                while(n<i){
                  n++;
                  if($('#pag_forma'+n).val()!=null){
                    cnt=$('#pag_cantidad'+n).val();

                    if(cnt.length==0){
                      cnt=0;
                    }else{
                      cnt=round(cnt,dec);
                    }
                    ///verificar valor nota credito
                    if($('#pag_tipo'+n).val()==8){
                      val_nc=$('#val_nt_cre'+n).val();
                      if(val_nc.length==0){
                        val_nc=0;
                      }else{
                        val_nc=round(val_nc,dec);
                      }

                      if(cnt>val_nc){
                         //alert('Pago es mayor al del documento $: ' + parseFloat(val_nc).toFixed(dec));
                         swal("Error!", "Pago es mayor al del documento $:"+ parseFloat(val_nc).toFixed(dec), "error");
                            // $('#pag_descripcion' + n).val('');
                            // $('#pag_cantidad' + n).val('0');
                            // $('#pag_cantidad' + n).focus();
                            cnt=0;
                      }
                    }

                    pg_ac+=cnt;
                  }
                }
                falt=parseFloat (tv)- parseFloat(round (pg_ac,dec));
                
                
                //falt= round(falt,dec);
                

                if(falt<0){
                  falt=tv-pg_ac+round($('#pag_cantidad'+l).val(),dec);

                  $('#pag_cantidad'+l).val('0')
                  $('#faltante').val(falt.toFixed(dec));   
                }else{
                  $('#faltante').val(falt.toFixed(dec));  
                }
                
            }

            function ultimo_pago(x){
              flt=$('#faltante').val();
              $('#pag_cantidad'+x).val(flt);
              $('#faltante').val('0'); 
            }

          function traer_forma(obj){

            if(obj==1){
              n=1;
            }else{
              n=obj.lang;
            }
            objeto=obj;


              $.ajax({
                    beforeSend: function () {
                      if ($('#pag_descripcion'+n).val().length == 0) {
                            //alert('Ingrese Forma de pago');
                             swal("Error!", "Ingrese Forma de pago.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"factura/traer_forma/"+$('#pag_descripcion'+n).val(),
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {

                        if(dt!=""){
                          ///////limpiar campos ocultos
                          $('#pag_forma'+n).val('');
                          $('#pag_descripcion'+n).val('');
                          $('#pag_tipo'+n).val('0');
                          $('#pag_documento'+n).val('');
                          $('#id_nota_credito'+n).val('0');
                          $('#pag_banco'+n).val('0');
                          $('#pag_tarjeta'+n).val('0');
                          $('#pag_plazo'+n).val('0');

                         if(dt.fpg_id != 1 && dt.fpg_id != 8 ){
                          ////limpiar campos auxiliares
                          $('#pag_documento_aux').attr('readonly', false);
                            $('#pag_documento_aux').val('');
                            $('#pag_plazo_aux').val('0');
                            $('#pag_banco_aux').val('0');
                            $('#id_nota_credito_aux').val('0');
                            $('#val_nt_cre_aux').val('0');
                            $('#pag_cantidad_aux').val('0');
                           if($('#cli_id').val().length!=0){
                             $("#pagos").modal('show');
                             $("ventana").attr('disabled',true);
                           }
                          
                         }

                         if(dt.fpg_id == 8 ){
                            $("#notas").modal('show');
                         }


                          $('#pag_forma'+n).val(dt.fpg_id);
                          $('#pag_descripcion'+n).val(dt.fpg_descripcion);
                          $('#pag_tipo'+n).val(dt.fpg_tipo);

                          traer_plazo(obj);
                          traer_banco(obj);
                          traer_tarjeta(obj);
                          busqueda_ntscre(obj);

                        }else{

                          ///alert('Forma de pago no existe');
                            swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_forma'+n).val('');
                          $('#pag_descripcion'+n).val('');
                          $('#pag_tipo'+n).val('0');

                          // $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
                        } 
                       habilitar(obj); 
                    },
                    error : function(xhr, status) {
                          //alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_forma'+n).val('');
                          $('#pag_descripcion'+n).val('');
                          $('#pag_tipo'+n).val('0');
                          // $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
                          habilitar(obj);
                    }
                    });    
            } 
            function traer_forma_2(obj){

            if(obj==1){
              n=1;
            }else{
              n=obj.lang;
            }
            objeto=obj;


              $.ajax({
                    beforeSend: function () {
                      if ($('#pag_descripcion'+n).val().length == 0) {
                            ///alert('Ingrese Forma de pago');
                            swal("Error!", "Ingrese Forma de pago.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"factura/traer_forma/"+$('#pag_descripcion'+n).val(),
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {

                        if(dt!=""){
                          ///////limpiar campos ocultos
                          // $('#pag_forma'+n).val('');
                          // $('#pag_descripcion'+n).val('');
                          // $('#pag_tipo'+n).val('0');
                          // $('#pag_documento'+n).val('');
                          // $('#id_nota_credito'+n).val('0');
                          // $('#pag_banco'+n).val('0');
                          // $('#pag_tarjeta'+n).val('0');
                          // $('#pag_plazo'+n).val('0');

                         if(dt.fpg_id != 1 ){
                          ////limpiar campos auxiliares
                          // $('#pag_documento_aux').attr('readonly', false);
                          //   $('#pag_documento_aux').val('');
                          //   $('#pag_plazo_aux').val('0');
                          //   $('#pag_banco_aux').val('0');
                          //   $('#id_nota_credito_aux').val('0');
                          //   $('#val_nt_cre_aux').val('0');
                          //   $('#pag_cantidad_aux').val('0');

                          traer_plazo(obj);
                          traer_banco(obj);
                          traer_tarjeta(obj);
                          busqueda_ntscre(obj);
                          

                           if($('#cli_id').val().length!=0){

                             $("#pagos").modal('show');
                             $("ventana").attr('disabled',true);
                           }
                          
                         }


                          // $('#pag_forma'+n).val(dt.fpg_id);
                          // $('#pag_descripcion'+n).val(dt.fpg_descripcion);
                          // $('#pag_tipo'+n).val(dt.fpg_tipo);

                          

                        }else{

                          //alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_forma'+n).val('');
                          $('#pag_descripcion'+n).val('');
                          $('#pag_tipo'+n).val('0');

                          // $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
                        } 
                       habilitar(obj); 


                        var cant  = $('#pag_cantidad'+n).val();
                        var docu  = $('#pag_documento'+n).val();
                       
                       
                        var id    = $('#id_nota_credito'+n).val();
                        var val_n = $('#val_nt_cre'+n).val();
                        $('#pag_cantidad_aux').val(cant);
                        $('#pag_documento_aux').val(docu);
                        $('#id_nota_credito_aux').val(id);
                        $('#val_nt_cre_aux').val(val_n);

                    },
                    error : function(xhr, status) {
                          //alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_forma'+n).val('');
                          $('#pag_descripcion'+n).val('');
                          $('#pag_tipo'+n).val('0');
                          // $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
                          habilitar(obj);
                    }
                    });    
            }

            function traer_plazo(obj){
            if(obj==1){
              n=1;
            }else{
              n=obj.lang;
            }
              $.ajax({
                    beforeSend: function () {
                      if ($('#pag_descripcion'+n).val().length == 0) {
                           // alert('Ingrese Forma de pago');
                             swal("Error!", "Ingrese Forma de pago.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"factura/traer_plazo/"+$('#pag_descripcion'+n).val(),
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){

                          
                          if($('#pag_plazo_aux').html(dt.lista)){
                            
                            if($('#pag_plazo'+n).val() != 0){
                              var plazo = $('#pag_plazo'+n).val();
                              $('#pag_plazo_aux').val(plazo);
                            }else{
                              
                              if($('#pag_tipo'+n).val() == 1){
                                  $('#pag_plazo_aux').val(164);
                                  }
                            }

                          }
                           
                           
                        }else{
                          //alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");

                          $('#pag_plazo_aux').html("<option value='0'>SELECCIONE</option>");
                        } 
                       habilitar(obj); 
                    },
                    error : function(xhr, status) {
                         
                          $('#pag_plazo_aux').html("<option value='0'>SELECCIONE</option>");
                          habilitar(obj);
                    }
                    });    
            }
            function traer_tarjeta(obj){
            if(obj==1){
              n=1;
            }else{
              n=obj.lang;
            }
              $.ajax({
                    beforeSend: function () {
                      if ($('#pag_descripcion'+n).val().length == 0) {
                            //alert('Ingrese Forma de pago');
                             swal("Error!", "Ingrese Forma de pago.!", "error");

                            return false;
                      }
                    },
                    url: base_url+"factura/traer_tarjeta/"+$('#pag_descripcion'+n).val(),
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          // $('#pag_tarjeta_aux').html(dt.lista);

                          if($('#pag_tarjeta_aux').html(dt.lista)){
                            var plazo = $('#pag_tarjeta'+n).val();
                           $('#pag_tarjeta_aux').val(plazo);

                          }


                        }else{
                          //alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_tarjeta_aux').html("<option value='0'>SELECCIONE</option>");
                        } 
                       habilitar(obj); 
                    },
                    error : function(xhr, status) {
                         
                          $('#pag_tarjeta'+n).html("<option value='0'>SELECCIONE</option>");
                          habilitar(obj);
                    }
                    });    
            } 
            function traer_banco(obj){
            if(obj==1){
              n=1;
            }else{
              n=obj.lang;
            }
              $.ajax({
                    beforeSend: function () {
                      if ($('#pag_descripcion'+n).val().length == 0) {
                            //alert('Ingrese Forma de pago');
                             swal("Error!", "Ingrese Forma de pago.!", "error");
                            return false;
                      }
                    },
                    url: base_url+"factura/traer_banco/"+$('#pag_descripcion'+n).val(),
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {

                        if(dt!=""){
                          $('#pag_banco_aux').html(dt.lista);
                          var banco = $('#pag_banco'+n).val();
                        $('#pag_banco_aux').val(banco);
                        }else{
                          ///alert('Forma de pago no existe');
                          swal("Error!", "Forma de pago no existe.!", "error");
                          $('#pag_banco'+n).html("<option value='0'>SELECCIONE</option>");
                        } 
                       habilitar(obj); 
                    },
                    error : function(xhr, status) {
                         
                          $('#pag_banco_aux').html("<option value='0'>SELECCIONE</option>");
                          habilitar(obj);
                    }
                    });    
            }


            function habilitar(obj) {
                if (obj.lang != null) {
                    s = obj.lang;
                } else {
                    s = obj;
                }
                

                if ($('#pag_tipo' + s).val() == '1') {
                    $('#pag_cantidad'+s).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', false);
                    $('#pag_banco_aux').attr('disabled', false);
                    $('#pag_tarjeta_aux').attr('disabled', false);
                    $('#pag_banco_aux').focus();
                    
                } 
                else if ($('#pag_tipo' + s).val() == '2') {
                    $('#pag_cantidad'+n).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', true);
                    $('#pag_banco_aux').attr('disabled', false);
                    $('#pag_tarjeta_aux').attr('disabled', false);
                    $('#pag_banco_aux').focus();
                } 
                else if ($('#pag_tipo' + s).val() == '3') {
                    
                    $('#pag_cantidad'+s).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', true);
                    $('#pag_banco_aux').attr('disabled', false);
                    $('#pag_tarjeta_aux').attr('disabled', true);
                    $('#pag_banco_aux').focus();

                } else if ($('#pag_tipo' + s).val() == '9') {
                    $('#pag_cantidad'+s).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', false);
                    $('#pag_banco_aux').attr('disabled', true);
                    $('#pag_tarjeta_aux').attr('disabled', true);
                    $('#pag_plazo_aux').focus();
                } 
                else if ($('#pag_tipo'+s).val() == '6') {
                    $('#pag_cantidad'+s).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', true);
                    $('#pag_banco_aux').attr('disabled', false);
                    $('#pag_tarjeta_aux').attr('disabled', true);
                    $('#pag_plazo_aux').focus();
                }
                else if ($('#pag_tipo'+s).val() == '8') {
                    $('#pag_cantidad'+s).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', true);
                    $('#pag_banco_aux').attr('disabled', true);
                    $('#pag_tarjeta_aux').attr('disabled', true);
                }

                else if ($('#pag_tipo'+s).val() =='7') {
                    $('#pag_cantidad'+s).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', false);
                    $('#pag_banco_aux').attr('disabled', true);
                    $('#pag_tarjeta_aux').attr('disabled', true);
                    
                } else if ($('#pag_tipo'+s).val() > '3') {
                    $('#pag_cantidad'+s).attr('readonly', false);
                    $('#pag_plazo_aux').attr('disabled', true);
                    $('#pag_banco_aux').attr('disabled', true);
                    $('#pag_tarjeta_aux').attr('disabled', true);
                    
                }

                // else {
                //     $('#pag_contado' + s).attr('disabled', true);
                //     $('#pag_contado' + s).val('0');
                //     $('#pag_cantidad' + s).attr('readonly', true);
                // }
            }  

            function busqueda_ntscre(obj) {

                if (obj.lang != null) {
                    s = obj.lang;
                } else {
                    s = obj;
                }

                nc = obj.value;
                ruc_cli = $('#identificacion').val();
                if (ruc_cli != '') {
                    //if (nc == 8) {
                     
                    if ($('#pag_tipo' + s).val() == 8) {
                      
                      $.ajax({
                      beforeSend: function () {
                        if ($('#pag_descripcion'+n).val().length == 0) {
                              //alert('Ingrese Forma de pago');
                                swal("Error!", "Ingrese Forma de pago.!", "error");
                              return false;
                        }
                      },
                      url: base_url+"factura/buscar_notas/"+$('#cli_id').val(),
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                    
                              if (dt != '') {
                                  //$('#list_notas').html(dt.lista);
                                  $('#det_notas').html(dt.lista);

                                  
                                  //$('#pag_documento_aux').focus();
                              } else {
                                  ///alert('El Cliente no tiene Documentos \n En esta opcion');
                                   swal("Error!", "El Cliente no tiene Documentos. En esta opcion.!", "error");
                                  // $('#pag_documento' + s).val('');
                                  $('#id_nota_credito' + s).val('0');
                                  $('#val_nt_cre' + s).val('');
                                  $('#pag_descripcion' + s).val('');
                                  $('#pag_forma' + s).val(0);
                                  $('#pag_tipo' + s).val(0);
                                  $('#pag_descripcion' + s).focus();
                                  $('#pag_cantidad' + s).val('0');
                                  $('#pag_cantidad' + s).attr('readonly', true);
                                  $('#pag_documento' + s).attr('readonly', false);
                                  //$("#pagos").modal('hide');
                                   $("#notas").modal('hide');
                                  calculo_pagos($('#pag_cantidad' + s));
                              }
                          },
                        error : function(xhr, status) {
                              //alert('El Cliente no tiene Documentos \n En esta opcion');
                               swal("Error!", "El Cliente no tiene Documentos. En esta opcion.!", "error");
                                  $('#pag_documento' + s).val('');
                                  $('#id_nota_credito' + s).val('0');
                                  $('#val_nt_cre' + s).val('');
                                  $('#pag_descripcion' + s).val('');
                                  $('#pag_forma' + s).val(0);
                                  $('#pag_tipo' + s).val(0);
                                  $('#pag_descripcion' + s).focus();
                                  $('#pag_cantidad' + s).val('0');
                                  $('#pag_cantidad' + s).attr('readonly', true);
                                  $('#pag_documento' + s).attr('readonly', false);
                                  $('#list_notas').html('');
                                   $("#pagos").modal('hide');
                                    $("#notas").modal('hide');
                                  calculo_pagos($('#pag_cantidad' + s));
                        }
                      });  
                    } else {
                      
                        $('#pag_documento_aux').attr('readonly', false);
                        $('#pag_documento_aux').val('');
                        $('#id_nota_credito' + s).val('0');
                        $('#val_nt_cre' + s).val('');
                        $('#list_notas').html('');
                    }
                } else {

                    
                    //alert('Debe elejir un cliente');
                     swal("Error!", "Debe elejir un cliente.!", "error");
                    $('#pag_descripcion' + s).val('');
                    $('#pag_forma' + s).val(0);
                    $('#pag_tipo' + s).val(0);
                    $('#pag_cantidad' + s).attr('readonly', true);
                    $('#identificacion').focus();
                    $('#pag_documento' + s).val('');
                    $('#id_nota_credito' + s).val('0');
                    $('#val_nt_cre' + s).val('');
                    $('#list_notas').html('');

                }
            }

            // function load_notas_credito(obj) {
            //   n=objeto.lang;
            //   var vl='';
            //   if($('#pag_tipo'+n).val()=='8'){

            //     $('#tbl_pagos .itm').each(function () {
            //       if($('#pag_descripcion' + this.lang).val()!=null){
            //         pro = $('#id_nota_credito' + this.lang).val();
            //         pro2 = $('#pag_documento_aux').val();
            //         $('#pag_descripcion' + n).css({borderColor: ""});
            //         vl=pro2;
            //         if (pro2 == pro) {
            //           vl='';
            //           //alert('Documento ya ingresado');
            //           swal("Error!", "Documento ya ingresado.!", "error");
            //           $('#pag_descripcion' + n).val('');
            //           $('#pag_forma' + n).val(0);
            //           $('#pag_tipo' + n).val(0);
            //           $('#pag_documento' + n).val('');
            //           $('#id_nota_credito' + n).val('0');
            //           $('#val_nt_cre' + n).val('');
            //           $('#pag_cantidad' + n).val('0');
            //           $('#pag_cantidad' + n).attr('readonly', true);
            //             return false;
            //         }
            //       }
            //     });
            //     if(vl!=''){
            //     $.ajax({
            //           beforeSend: function () {
            //             if ($('#pag_documento_aux').val().length == 0) {
            //                   ///alert('Ingrese numero de documento');
            //                   swal("Error!", "Ingrese numero de documento.!", "error");
            //                   return false;
            //             }
            //           },
            //           url: base_url+"factura/load_nota/"+vl,
            //           type: 'JSON',
            //           dataType: 'JSON',
            //           success: function (dt) {
            //             if (dt != '1') {
            //                 $('#pag_documento_aux').val(dt.chq_numero);
            //                 $('#pag_cantidad_aux').val(dt.chq_valor);
            //                 $('#id_nota_credito_aux').val(dt.chq_id);
            //                 $('#val_nt_cre_aux').val(dt.chq_valor);
            //                 $('#pag_cantidad_aux').focus();
            //                 $('#pag_documento_aux').attr('readonly', true);
            //                 calculo_pagos($('#pag_cantidad'+n));
            //             }else {
            //                 //alert('Documento no existe');
            //                  swal("Error!", "Documento no existe.!", "error");
            //                 $('#pag_descripcion').val('');
            //                 $('#pag_forma').val('0');
            //                 $('#pag_tipo').val('0');
            //                 $('#pag_documento_aux').val('');
            //                 $('#id_nota_credito_aux').val('0');
            //                 $('#val_nt_cre_aux').val('');
            //                 $('#pag_cantidad_aux').val(0);
            //                 $('#pag_cantidad_aux').attr('readonly', true);
            //                 $('#pag_documento_aux').attr('readonly', false);
            //                 $('#list_notas').html('');
            //                 calculo_pagos($('#pag_cantidad'+n));
            //             }
                        
            //         }
            //       }); 
            //     }else{
            //       calculo_pagos($('#pag_cantidad' + n));
            //     }   
            //   }

            // } 
            function load_notas_credito(dato) {
              n=objeto.lang;
              var vl='';

              if($('#pag_tipo'+n).val()=='8'){

                $('#tbl_pagos .itm').each(function () {
                  if($('#pag_descripcion' + n).val()!=null){
                    pro = $('#id_nota_credito'+n).val();
                    pro2 = dato;
                    $('#pag_descripcion' + n).css({borderColor: ""});
                    vl=pro2;
                    if (pro2 == pro) {
                      vl='';
                      //alert('Documento ya ingresado');
                      swal("Error!", "Documento ya ingresado.!", "error");
                      $('#pag_descripcion' + n).val('');
                      $('#pag_forma' + n).val(0);
                      $('#pag_tipo' + n).val(0);
                      $('#pag_documento' + n).val('');
                      $('#id_nota_credito' + n).val('0');
                      $('#val_nt_cre' + n).val('');
                      $('#pag_cantidad' + n).val('0');
                      $('#pag_cantidad' + n).attr('readonly', true);
                       $("#notas").modal('hide');
                        return false;
                    }
                  }
                });
                if(vl!=''){
                $.ajax({
                      // beforeSend: function () {
                      //   if ($('#pag_documento_aux').val().length == 0) {
                      //         ///alert('Ingrese numero de documento');
                      //         swal("Error!", "Ingrese numero de documento.!", "error");
                      //         return false;
                      //   }
                      // },
                      url: base_url+"factura/load_nota/"+vl,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                        if (dt != '1') {
                            $('#pag_documento_aux').val(dt.chq_numero);
                            $('#pag_cantidad_aux').val(dt.chq_valor);
                            $('#id_nota_credito_aux').val(dt.chq_id);
                            $('#val_nt_cre_aux').val(dt.chq_valor);
                            $('#pag_cantidad_aux').focus();
                            $('#pag_documento_aux').attr('readonly', true);
                            llevar_pagos();
                             $("#notas").modal('hide');

                            calculo_pagos($('#pag_cantidad'+n));
                        }else {
                            //alert('Documento no existe');
                             swal("Error!", "Documento no existe.!", "error");
                            $('#pag_descripcion').val('');
                            $('#pag_forma').val('0');
                            $('#pag_tipo').val('0');
                            $('#pag_documento_aux').val('');
                            $('#id_nota_credito_aux').val('0');
                            $('#val_nt_cre_aux').val('');
                            $('#pag_cantidad_aux').val(0);
                            $('#pag_cantidad_aux').attr('readonly', true);
                            $('#pag_documento_aux').attr('readonly', false);
                            $('#list_notas').html('');
                            calculo_pagos($('#pag_cantidad'+n));
                        }
                        
                    }
                  }); 
                }else{
                  calculo_pagos($('#pag_cantidad' + n));
                }   
              }

            } 

            // function validar_inventario(obj) {
            //     if (inven == 0) {
            //        var cant=0;
            //             var tr = $('#lista').find("tr:last");
            //             var a = tr.find("input").attr("lang");
                        
            //             if(a==null){
            //                 j=0;
            //             }else{
            //                 j=parseInt(a);
            //             }
            //             if (j > 0) {
            //                 n=0;
            //                 while (n < j) {
            //                     n++;
            //                     if ($('#pro_aux' + n).val() == pro_aux.value) {
            //                         cant = round($('#cantidad' + n).val(),dcc) + round(cantidad.value,dcc);
            //                     }else{
            //                         cant = cantidad.value;   
            //                     }

            //                     if (parseFloat($('#inventario').val()) < parseFloat(cant)) {
            //                         alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                                    
            //                             $('#cantidad').val('');
            //                             $('#cantidad').focus();
            //                             $('#cantidad').css({borderColor: "red"});
            //                             v=1;
            //                     }
            //                 }
            //             }else{
            //               if (parseFloat($('#inventario').val()) < parseFloat($(obj).val())) {
            //                   alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
            //                   $(obj).val('');
            //                   $(obj).focus();
            //                   $(obj).css({borderColor: "red"});
            //                   costo(obj,1);
            //               }
            //             }
            //     }
            // }

            function validar_inventario(obj) {
                if (inven == 0) {
                   var cant=0;
                        var tr = $('#lista').find("tr:last");
                        var a = tr.find("input").attr("lang");
                        
                        if(a==null){
                            j=0;
                        }else{
                            j=parseInt(a);
                        }
                        if (j > 0) {
                            n=0;
                            while (n < j) {
                                n++;
                                if ($('#pro_aux' + n).val() == pro_aux.value) {
                                    cant = round($('#cantidad' + n).val(),dcc) + round(cantidad.value,dcc);
                                }else{
                                    cant = cantidad.value;   
                                }
                                if(inven==0 && ($('#pro_ids').val()=='26' || $('#pro_ids').val()=='69') ){  
                                  if (parseFloat($('#inventario').val()) < parseFloat(cant)) {
                                      ///alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                                       swal("Error!", "NO SE PUEDE REGISTRAR LA CANTIDAD. ES MAYOR QUE EL INVENTARIO.!", "error");
                                      
                                          $('#cantidad').val('');
                                          $('#cantidad').focus();
                                          $('#cantidad').css({borderColor: "red"});
                                          v=1;
                                  }
                                }  
                            }
                        }else{
                          if(inven==0 && ($('#pro_ids').val()=='26' || $('#pro_ids').val()=='69') ){  
                            if (parseFloat($('#inventario').val()) < parseFloat($(obj).val())) {
                                //alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                                swal("Error!", "NO SE PUEDE REGISTRAR LA CANTIDAD. ES MAYOR QUE EL INVENTARIO.!", "error");
                                $(obj).val('');
                                $(obj).focus();
                                $(obj).css({borderColor: "red"});
                                costo(obj,1);
                            }
                          }  
                        }
                }
            }

            function validar_inventario_det() {
                
                    var tr = $('#lista').find("tr:last");
                    a = tr.find("input").attr("lang");
                    i = parseInt(a);
                    n = 0;
                    while (n < i) {
                        n++;
                        if(inven==0 && ($('#pro_ids'+n).val()=='26' || $('#pro_ids'+n).val()=='69') ){
                          if($('#cantidad'+n).val()!=null){
                            if (parseFloat($('#inventario'+n).html()) < parseFloat($('#cantidad'+n).val())) {
                                //alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                                swal("Error!", "NO SE PUEDE REGISTRAR LA CANTIDAD. ES MAYOR QUE EL INVENTARIO.!", "error");
                                $('#cantidad'+n).val('');
                                $('#cantidad'+n).focus();
                                $('#cantidad'+n).css({borderColor: "red"});
                                
                            }
                          }
                        }      
                    }  
                
                calculo();
            }

            function costo(obj) {
                i = obj.lang;
                can = $('#cantidad').val();
                uni = $('#mov_cost_unit').val() * 1;
                tot = $('#mov_cost_tot').val();
                t = parseFloat(can) * parseFloat(uni);
                $('#mov_cost_tot').val(t.toFixed(6));
            }

            function costo_det(obj) {
                i = obj.lang;
                can = $('#cantidad').val();
                uni = $('#mov_cost_unit').val() * 1;
                tot = $('#mov_cost_tot').val();
                t = parseFloat(can) * parseFloat(uni);
                $('#mov_cost_tot').val(t.toFixed(6));
            } 
            function validarEmail(valor) {
                if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
               return true;
            } else {
              return false;
            }
            }

            function save() {
                        if (vnd_id.value.length == 0) {
                            $("#vnd_id").css({borderColor: "red"});
                            $("#vnd_id").focus();
                            return false;
                        } else if (identificacion.value.length == 0) {
                            $("#identificacion").css({borderColor: "red"});
                            $("#identificacion").focus();
                            return false;
                        } else if (nombre.value.length == 0) {
                            $("#nombre").css({borderColor: "red"});
                            $("#nombre").focus();
                            return false;
                        } else if (direccion_cliente.value.length == 0) {
                            $("#direccion_cliente").css({borderColor: "red"});
                            $("#direccion_cliente").focus();
                            return false;
                        } else if (telefono_cliente.value.length == 0) {
                            $("#telefono_cliente").css({borderColor: "red"});
                            $("#telefono_cliente").focus();
                            return false;
                        } else if (email_cliente.value.length == 0 || validarEmail(email_cliente.value) == false ) {
                            $("#email_cliente").css({borderColor: "red"});
                            $("#email_cliente").focus();
                            return false;
                         } 
                        //else if (cli_parroquia.value.length == 0) {
                        //     $("#cli_parroquia").css({borderColor: "red"});
                        //     $("#cli_parroquia").focus();
                        //     return false;} 
                        // else if (cli_ciudad.value.length == 0) {
                        //     $("#cli_ciudad").css({borderColor: "red"});
                        //     $("#cli_ciudad").focus();
                        //     return false;
                        // }
                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        if(a==null){
                          //alert("Ingrese Detalle");
                          swal("Error!", "Ingrese Detalle.!", "error");
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;
                                if ($('#pro_descripcion' + n).html() != null) {
                                    if ($('#pro_descripcion' + n).html().length == 0) {
                                        $('#pro_descripcion' + n).css({borderColor: "red"});
                                        $('#pro_descripcion' + n).focus();
                                        return false;
                                    } else if ($('#cantidad' + n).val().length == 0 || parseFloat($('#cantidad' + n).val()) == 0) {
                                        $('#cantidad' + n).css({borderColor: "red"});
                                        $('#cantidad' + n).focus();
                                        return false;
                                    } else if ($('#descuento' + n).val().length == 0) {
                                        $('#descuento' + n).css({borderColor: "red"});
                                        $('#descuento' + n).focus();
                                        return false;
                                    } else if ($('#pro_precio' + n).val().length == 0 || parseFloat($('#pro_precio' + n).val()) == 0) {
                                        $('#pro_precio' + n).css({borderColor: "red"});
                                        $('#pro_precio' + n).focus();
                                        return false;
                                    }

                                }
                            }
                        }
                        if ($('#total_valor').val() > 200 && $('#nombre').val() == 'CONSUMIDOR FINAL') {
                            ///alert('PARA CONSUMIDOR FINAL EL VALOR TOTAL NO PUDE SER MAYOR $200');
                            swal("Error!", "PARA CONSUMIDOR FINAL EL VALOR TOTAL NO PUDE SER MAYOR $200.!", "error");
                            return false;
                        }
                        if ($('#faltante').val() !=0 ) {
                            //alert('PARA CONSUMIDOR FINAL EL VALOR TOTAL NO PUDE SER MAYOR $200');
                             swal("Error!", "Existe un faltante.!", "error");
                            $('#faltante').css({borderColor: "red"});
                            $('#faltante').focus();
                            return false;
                        }
                        if ($('#ped_vendedor').val() == '0' || $('#ped_vendedor').val() == '') {
                            $('#ped_vendedor').css({borderColor: "red"});
                            $('#ped_vendedor').focus();
                           //alert('Vendedor no existe');
                            swal("Error!", "Vendedor no existe.!", "error");
                            return false;
                        }
                        if ($('#vendedor').val() == '0' || $('#vendedor').val() == '') {
                            $('#vendedor').css({borderColor: "red"});
                            $('#vendedor').focus();
                            //alert('Vendedor no existe');
                            swal("Error!", "VENDEDOR NO EXISTE.!", "error");
                            return false;
                        }

                        var tr2 = $('#tbl_pagos').find("tbody tr:last");
                        a2 = tr2.find("input").attr("lang");
                        i2 = parseInt(a2);
                        j = 0;
                        while (j < i2) {
                            j++;
                          if ($('#pag_cantidad' + j).val() !=null) {  
                                if ($('#pag_descripcion' + j).val().length == 0) {
                                    $('#pag_descripcion' + j).css({borderColor: "red"});
                                    $('#pag_descripcion' + j).focus();
                                    return false;
                                }
                                if ($('#pag_cantidad' + j).val() == 0) {
                                    $('#pag_cantidad' + j).css({borderColor: "red"});
                                    $('#pag_cantidad' + j).focus();
                                    return false;
                                }
                                if ($('#pag_tipo' + j).val() == '7' && $('#pag_documento' + j).val().length == 0) {
                                    $('#pag_documento' + j).focus();
                                    $('#pag_documento' + j).css({borderColor: "red"});
                                    return false;
                                }
                                if ($('#pag_tipo' + j).val() == '7' || $('#pag_tipo' + j).val() == '8') {

                                    dt = $('#pag_documento' + j).val().split('-');
                                    if ($('#pag_documento' + j).val().length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                                        $('#pag_documento' + j).val('');
                                        $('#pag_documento' + j).focus();
                                        $('#pag_documento' + j).css({borderColor: "red"});
                                        ///alert('No cumple con la estructura ejem: 000-000-000000000');
                                        swal("Error!", "No cumple con la estructura ejem: 000-000-000000000.!", "error");
                                        return false;
                                    }
                                }
                                if ($('#pag_tipo' + j).val() == '3' || $('#pag_tipo' + j).val() == '5' || $('#pag_tipo' + j).val() == '8') {
                                    if ($('#pag_documento' + j).val().length == 0) {
                                        $('#pag_documento' + j).val('');
                                        $('#pag_documento' + j).focus();
                                        $('#pag_documento' + j).css({borderColor: "red"});
                                        swal("Error!", "Documento requerido en la forma de pago.!", "error");
                                        $('#pag_descripcion' + j).css({borderColor: "red"});
                                        return false;
                                    }
                                }
                                
                                if ($('#pag_contado' + j).val() == '0' && ($('#pag_contado' + j).attr('disabled') != 'disabled')) {
                                    $('#pag_contado' + j).css({borderColor: "red"});
                                    $('#pag_contado' + j).focus();
                                    return false;
                                }
                                if ($('#pag_tipo' + j).val() == '9' ) {
                                if ($('#pag_plazo' + j).val() == '0' && ($('#pag_plazo' + j).attr('disabled') != 'disabled')) {
                                    $('#pag_descripcion' + j).css({borderColor: "red"});
                                    $('#pag_descripcion' + j).focus();
                                      swal("Error!", "Seleccione un plazo.!", "error");
                                    return false;
                                }
                                }
                            }


                        }

                        
                     $('#frm_save').submit();   
               }

            function load_precios(){

                // var tr = $('#lista').find("tr:last");
                // a = tr.find("input").attr("lang");
                // i = parseInt(a);
                        
                // if(i==null){
                //     j=0;
                // }else{
                //     j=parseInt(a);
                // }
                // var precio1=0;
                // var precio2=0; 
                // if (j > 0) {
                //     n=0;
                //     while (n < j) {
                //       n++;
                        
                      if ($('#pro_aux').val() !=null) {
                        vl=$('#pro_aux').val();

                        $.ajax({
                          url: base_url+"factura/load_producto/"+vl+"/"+inven+"/"+ctr_inv+"/"+fpag+"/"+emi_id.value+"/"+n,
                          type: 'JSON',
                          dataType: 'JSON',
                          success: function (dt) {
                            if($('#fprecio').val()=='1'){
                              if (dt.pro_precio==null) {
                                $('#pro_precio').val('0');
                              } else {
                                $('#pro_precio').val(parseFloat(dt.pro_precio).toFixed(dec));
                              }
                            }else{
                              if (dt.pro_precio2==null) {
                                $('#pro_precio').val('0');
                              } else {
                                  $('#pro_precio').val(parseFloat(dt.pro_precio2).toFixed(dec));
                              }
                            }
                            calculo();
                          }
                        });    
                      }
                //     }
                // }
            }
            
    </script>

