<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <h1>
        Factura Móvil <?php echo $titulo?>
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          $ctrl_inv=$ctrl_inv->con_valor;
          $inven=$inven->con_valor;
          $cprec=$cprec->con_valor;
          $cdesc=$cdesc->con_valor;
          $m_pag=$m_pag->con_valor;
          if($inven==0){
            $hid_inv='hidden';
            $col_obs='4';
          }else{
            $hid_inv='hidden';
            $col_obs='3';
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
                 <table class="table col-sm-12" border="0" style="margin-left: -20px;">
                    <tr>
                      <td class="col-sm-6">
                        <table class="table">
                          <tr>
                               <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('fac_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="fac_fecha_emision" id="fac_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('fac_fecha_emision');}else{ echo $factura->fac_fecha_emision;}?>" readonly>
                                  <?php echo form_error("fac_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $factura->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo $factura->emi_id;}?>">
                                <input type="hidden" class="form-control" name="cja_id" id="cja_id" value="<?php if(validation_errors()!=''){ echo set_value('cja_id');}else{ echo $factura->cja_id;}?>">
                                <input type="hidden" class="form-control" name="ped_id" id="ped_id" value="<?php if(validation_errors()!=''){ echo set_value('ped_id');}else{ echo $factura->ped_id;}?>">
                                </div>
                              </td>
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
                            </table>
                          </tr>    
                    </tr>
                    <tr>
                      <td class="col-sm-1" >
                        <div class="box-body">
                        <div class="panel panel-success col-sm-12">
                        <div class="panel panel-heading"><label>Seleccione Cliente</label></div>
                        <table  width="60%">
                          <tr>
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $factura->fac_identificacion;}?>" list="list_clientes" onchange="verificar_cedula(this)" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              
                          </tr>
                          <tr>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $factura->fac_nombre;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $factura->cli_id;}?>" >
                              </td>
                          </tr>
                          <tr>
                            <td><label>Direccion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('direccion_cliente')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" value="<?php if(validation_errors()!=''){ echo set_value('direccion_cliente');}else{ echo $factura->fac_direccion;}?>" readonly>
                                <?php echo form_error("direccion_cliente","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Telefono:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" value="<?php if(validation_errors('telefono_cliente')!=''){ echo set_value('telefono_cliente');}else{ echo $factura->fac_telefono;}?>" readonly>
                                      <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                  
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Email:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('email_cliente');}else{ echo $factura->fac_email;}?>" readonly>
                                  <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                            </tr>
                           <!--  <tr>
                              <td><label>Parroquia:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('cli_parroquia')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="cli_parroquia" id="cli_parroquia" value="<?php if(validation_errors()!=''){ echo set_value('cli_parroquia');}else{ echo $factura->cli_parroquia;}?>" readonly>
                                  <?php echo form_error("cli_parroquia","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr> -->
                            <tr>
                              <td><label>Ciudad:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('cli_ciudad')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="cli_ciudad" id="cli_ciudad" value="<?php if(validation_errors()!=''){ echo set_value('cli_ciudad');}else{ echo $factura->cli_canton;}?>" readonly>
                                  <?php echo form_error("cli_ciudad","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Pais:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('cli_pais')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="cli_pais" id="cli_pais" value="<?php if(validation_errors()!=''){ echo set_value('cli_pais');}else{ echo $factura->cli_pais;}?>" readonly>
                                  <?php echo form_error("cli_pais","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr>
                          </table>
                          </div>
                          </div>
                          </td>
                          
                    </tr>
                    <tr>
                       <td class="col-sm-12" colspan="2">
                          <!-- <div class="box-body"> -->
                          <div class="panel panel-default col-sm-18">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle" >
                            <thead>
                              <tr>
                                <th hidden>Item</th>
                                <th hidden>Codigo</th>
                                <th>Descripcion</th>
                                <th hidden>Unidad</th>
                                <th <?php echo $hid_inv?>>Inventario</th>
                                <th>Solicitado</th>
                                <th>Entregado</th>
                                <th >Cantidad</th>
                                <th >Precio</th>
                                <th>Desc.%</th>
                                <th hidden>IVA</th>
                                <th hidden>ICE%</th>
                                <th hidden>ICE $</th>
                                <th>Val.Total</th>
                              
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
                                            <td hidden id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center" class="itm"><?PHP echo $n ?></td>
                                            <td hidden id="pro_descripcion<?PHP echo $n ?>" name="pro_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_codigo ?></td>
                                            <td id="pro_referencia<?PHP echo $n ?>" size="10" name="pro_referencia<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_descripcion ?>
                                                <input type="hidden"  id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="pro_ids<?PHP echo $n ?>" name="pro_ids<?PHP echo $n ?>" value="<?php echo $rst_det->ids ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden"  id="mov_cost_unit<?PHP echo $n ?>" name="mov_cost_unit<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cost_unit, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden"  id="mov_cost_tot<?PHP echo $n ?>" name="mov_cost_tot<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($cost_tot, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td hidden id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->pro_unidad ?></td>
                                            <td hidden> <?php echo $hid_inv?> >
                                              <input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'inventario' . $n ?>" name="<?php echo 'inventario' . $n ?>" style="text-align:right" value="<?php echo str_replace(',', '', number_format($rst_det->inventario, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), validar_inventario_det(this), costo_det(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  readonly/>
                                            </td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'solicitado' . $n ?>" name="<?php echo 'solicitado' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->solicitado, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), validar_inventario_det(this), costo_det(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'entregado' . $n ?>" name="<?php echo 'entregado' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->entregado, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), validar_inventario_det(this), costo_det(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  readonly/></td>
                                            <td width="250px" ><input  type ="text"  style="text-align:right;width:80px" class="form-control decimal" id="<?php echo 'cantidad' . $n ?>" name="<?php echo 'cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="validar_inventario_det(this), costo_det(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  /></td>
                                            <td ><input  type ="text"  style="text-align:right;width:80px" class="form-control decimal" id="<?php echo 'pro_precio' . $n ?>" name="<?php echo 'pro_precio' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->pro_precio, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" <?php echo $r_precio?> /></td>
                                            <td>
                                                <input  type ="text" size="7" style="text-align:right;width:80px" class="form-control decimal" id="<?php echo 'descuento' . $n ?>" name="<?php echo 'descuento' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuento, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)"  <?php echo $r_descuento?> />
                                            </td>
                                            <td hidden>
                                                <input type ="text"  style="text-align:right" class="form-control decimal" id="<?php echo 'descuent' . $n ?>" name="<?php echo 'descuent' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuent, $dec)) ?>" lang="<?PHP echo $n ?>"  readonly/>
                                            </td>
                                            <td hidden><input type="text" size="8" id="<?php echo 'iva' . $n ?>" name="<?php echo 'iva' . $n ?>" style="text-align:right" class="form-control" value="<?php echo $rst_det->pro_iva ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'ice_p' . $n ?>" name="<?php echo 'ice_p' . $n ?>" size="5" value="<?php echo str_replace(',', '', number_format($rst_det->ice_p, $dec)) ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'ice' . $n ?>" name="<?php echo 'ice' . $n ?>" size="5" class="form-control" value="<?php echo str_replace(',', '', number_format($rst_det->ice, $dec)) ?>" readonly lang="<?php echo $n ?>"/>
                                                <input type="hidden" id="<?php echo 'ice_cod' . $n ?>" name="<?php echo 'ice_cod' . $n ?>" size="5" class="form-control" value="<?php echo $rst_det->ice_cod ?>" lang="<?PHP echo $n ?>"readonly />
                                            </td>
                                            <td>
                                                <input type ="text" size="9" style="text-align:right;width:80px" class="form-control" id="<?php echo 'valor_total' . $n ?>" name="<?php echo 'valor_total' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->precio_tot, $dec)) ?>" readonly lang="<?PHP echo $n ?>"/>
                                                
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
                                    <td><label>Observaciones:</label></td>
                                </tr>
                                <tr>

                                    <td valign="top" rowspan="9" colspan="<?php echo $col_obs?>">
                                      <textarea id="observacion" name="observacion" style="height: 30px; width: 90%;"  onkeydown="return enter(event)"><?php echo $factura->fac_observaciones ?></textarea>
                                    </td>    
                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal12, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal_ex_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal_no_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($factura->fac_subtotal, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Descuento:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_descuento" name="total_descuento" value="<?php echo str_replace(',', '', number_format($factura->fac_total_descuento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total ICE:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_ice" name="total_ice" value="<?php echo str_replace(',', '', number_format($factura->fac_total_ice, $dec)) ?>"  readonly/>
                                    </td>
                                </tr>
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
                                    <td colspan="6" align="right">Total Valor:</td>
                                    <td><input style="text-align:right;font-size:15px;color:red  " type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($factura->fac_total_valor, $dec)) ?>" readonly />
                                        
                                    </td>
                                </tr>
                              </tfoot>

                          </table>
                          <table>
                            <tr>
                              <td class="col-sm-10">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-16">
                          <div class="panel panel-heading"><label>Pagos</label></div>
                          <table class="table table-bordered table-striped" id="tbl_pagos">
                            <thead>
                              <tr>
                                <th>Forma</th>
                               <!--  <th>Documento</th>
                                <th>Pago</th> -->
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
                                <td width="200px">
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
                                foreach($cns_pag as $rst_pag) {
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
                                <td width="200">
                                <!--   <input type="text" id="pag_descripcion<?php echo $m?>" name="pag_descripcion<?php echo $m?>" class="form-control itm" size="35px" lang="<?php echo $m?>" list="list_formas" onchange="traer_forma(this),busqueda_ntscre(this)" value="<?php echo $rst_pag->fpg_descripcion?>"> -->

                                   <div class="form-group" >
                                  <select id="pag_descripcion<?php echo $m?>"  name="pag_descripcion<?php echo $m?>" lang="<?php echo $m?>"  class="form-control itm" onchange="traer_forma(this)">
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
                                  <script>
                                    pag_descripcion<?php echo $m?>.value='<?php echo $rst_pag->fpg_descripcion?>';
                                  </script>
                              </div>


                                  <input type="hidden" id="pag_forma<?php echo $m?>" name="pag_forma<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_forma?>">
                                  <input type="hidden" id="pag_tipo<?php echo $m?>" name="pag_tipo<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_tipo?>">
                                </td>
                                <td hidden ><input type="text" id="pag_documento<?php echo $m?>" name="pag_documento<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" list="list_notas" onchange="load_notas_credito(this)" value="<?php echo $rst_pag->chq_numero?>">
                                  <input type="text" id="id_nota_credito<?php echo $m?>" name="id_nota_credito<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_id_chq?>">
                                  <input type="hidden" id="val_nt_cre<?php echo $m?>" name="val_nt_cre<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>">
                                </td>
                                 <td hidden >
                                  <input  class="form-control" type="text" name="pag_plazo<?php echo $m?>" id="pag_plazo<?php echo $m?>" lang="<?php echo $m?>" value="<?php echo $rst_pag->pag_contado?>">
                                  <!-- <select name="pag_plazo1" id="pag_plazo1" class="form-control" lang="1">
                                    <option value='0'>SELECCIONE</option>
                                  </select> -->
                                </td>
                                <td hidden >
                                  <input   class="form-control" type="text" name="pag_banco<?php echo $m?>" id="pag_banco<?php echo $m?>" lang="<?php echo $m?>" value="0">
                                  <!-- <select name="pag_banco1" id="pag_banco1" class="form-control" lang="1">
                                    <option value='0'>SELECCIONE</option>
                                  </select> -->
                                </td>
                                <td hidden >
                                  <input class="form-control" type="text" name="pag_tarjeta<?php echo $m?>" id="pag_tarjeta<?php echo $m?>" value="0" lang="<?php echo $m?>">
                                  <!-- <select name="pag_tarjeta1" id="pag_tarjeta1" class="form-control" lang="1">
                                    <option value='0'>SELECCIONE</option>
                                  </select> -->
                                </td>
                                <td width="90"><input type="text" id="pag_cantidad<?php echo $m?>" name="pag_cantidad<?php echo $m?>" class="form-control" size="10px" lang="<?php echo $m?>" onchange="calculo_pagos(this)" <?php echo $read_cnt?> value="<?php echo $rst_pag->pag_cant?>"></td>
                                <?php
                     if($m>1){
                      $ocultar='hidden';
                     }else{
                      $ocultar='button';

                     }

                     ?>
                                <?php 
                                  if($m_pag==0){
                                  ?>
                                  <!-- <td align="center" ><span name="add<?php echo $m?>" id="add<?php echo $m?>" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_pagos','1')" lang="<?php echo $m?>"> </span></td> -->
                                  <td  ><input type="<?php echo $ocultar ?>" name="add<?php echo $m?>" id="add<?php echo $m?>" onclick="validar('#tbl_pagos','1')" lang="<?php echo $m?>" class="btn btn-primary " value="Agregar"></td>
                                  <td> <input type="button" lang="<?php echo $m?>" class="btn btn-success " onclick="edit(this)" name="edit<?php echo $m?>" id="edit<?php echo $m?>" value="Editar"  >  </td>
                                  <td onclick="elimina_fila(this, '#tbl_pagos','1')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                <?php
                                  }
                                  $faltante+=$rst_pag->pag_cant;
                                  $cnt_pagos++;
                                }  
                                ?>
                                

                              </tr>
                              <?php
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
                          </table>
                          </div>
                       <!--    </div> -->
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
    <datalist id="list_clientes">
      <?php 
        if(!empty($cns_clientes)){
          foreach ($cns_clientes as $rst_cli) {
      ?>
        <option value="<?php echo $rst_cli->cli_id?>"><?php echo $rst_cli->cli_ced_ruc .' '.$rst_cli->cli_raz_social?></option>
      <?php 
          }
        }
      ?>
    </datalist>
    <datalist id="productos">
      <?php 
        if(!empty($cns_productos)){
          foreach ($cns_productos as $rst_pro) {
      ?>
        <option value="<?php echo $rst_pro->id?>"><?php echo $rst_pro->mp_c .' '.$rst_pro->mp_d?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>
    <datalist id="list_formas">
      <?php 
        if(!empty($formas_pago)){
          foreach ($formas_pago as $fpg) {
      ?>
            <option value="<?php echo $fpg->fpg_id?>"><?php echo $fpg->fpg_descripcion?></option>
      <?php
          }
        }
      ?>
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
       window.onload = function () {
        calculo_pagos();
        var mensaje ='<?php echo $mensaje;?>';
        swal("", mensaje, "info");
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
                                  $('#pag_plazo_aux').val(159);
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
      
      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#identificacion').val().length == 0) {
                            alert('Ingrese dato');
                            $('#nombre').focus();
                            $('#cli_id').val('0');
                            $('#nombre').val('');
                            $('#telefono_cliente').val('');
                            $('#direccion_cliente').val('');
                            $('#cli_ciudad').val('');
                            $('#email_cliente').val('');
                            $('#cli_pais').val('');
                            $('#cli_parroquia').val('');
                            $('#pag_descripcion1').val('');
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
                    url: base_url+"factura/traer_cliente/"+identificacion.value,
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
                          $('#pag_descripcion1').val('');
                          $('#pag_documento1').val('');
                          $('#pag_contado1').val('0');
                          $('#pag_forma1').val('0');
                          $('#pag_cantidad1').val('0');
                          $('#pag_tipo1').val('0');
                          $('#id_nota_credito1').val('0');
                          $('#val_nt_cre1').val('0');
                        }else{
                          alert('Cliente no existe \n Se creará uno nuevo');
                          $('#nombre').focus();
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#cli_ciudad').val('');
                          $('#email_cliente').val('');
                          $('#cli_pais').val('');
                          $('#cli_parroquia').val('');
                          $('#pag_descripcion1').val('');
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
                          alert('Cliente no existe \n Se creará uno nuevo');
                          $('#nombre').focus();
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#cli_ciudad').val('');
                          $('#email_cliente').val('');
                          $('#cli_pais').val('');
                          $('#cli_parroquia').val('');
                          $('#pag_descripcion1').val('');
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
                }
              }else{
                if($('#pag_forma'+a1).val()!='0' &&  parseFloat($('#pag_cantidad'+a1).val())>0 && $('#pag_cantidad'+a1).val().length!=0){

                  clona_fila(table,opc);
                }
              }

            }
            

            // function clona_fila(table,opc) {
                
            //       var tr = $(table).find("tbody tr:last").clone();
            //       tr.find("input,select").attr("name", function () {
            //           var parts = this.id.match(/(\D+)(\d+)$/);
            //           return parts[1] + ++parts[2];
            //       }).attr("id", function () {
            //           var parts = this.id.match(/(\D+)(\d+)$/);
            //           x = ++parts[2];
            //           this.lang = x;
            //           var parent = $(this).parents();
            //           $(parent[1]).css('background-color', 'transparent');
            //           if (parts[1] == 'item') {
            //               this.value = x;
            //           } else if (parts[1] == 'pag_forma') {
            //               this.value = '0';
            //           } else if (parts[1] == 'pag_contado') {
            //               this.value = '0';
            //           } else{
            //               this.value ='';
            //           }
            //           return parts[1] + x;
            //       });
            //       $(table).find("tbody tr:last").after(tr);
            //       if(opc==0){
            //         $('#count_detalle').val(x);
            //       }else{
            //         $('#count_pagos').val(x);
            //         $('#pag_documento' + x).attr('readonly', false);
            //         ultimo_pago(x);
            //       }
                
            // }
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

            
            function elimina_fila(obj, tbl,op) {
                
                  itm = $(tbl + ' .itm').length;
                  if (itm > 1) {
                      var parent = $(obj).parents();
                      $(parent[0]).remove();
                  } else {
                      alert('No puede eliminar todas las filas');
                  }
                  calculo_pagos();
            }

            function elimina_fila_det(obj) {
              itm = $('#tbl_detalle .itm').length;
              if (itm > 1) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
              } else {
                  alert('No puede eliminar todas las filas');
              }
            }

            

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }


           


            function calculo(obj) {

               var pcont="<?php echo $rst_pag->contado?>";

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
                        pic=0;
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
                prop = $('#total_propina').val().replace(',', '');
                gtot = (round(sub,dec) * 1 + round(tiva,dec) * 1 + round(tice,dec) * 1 + round(prop,dec) * 1);
                 
                $('#subtotal12').val(t12.toFixed(dec));
                $('#subtotal0').val(t0.toFixed(dec));
                $('#subtotalex').val(tex.toFixed(dec));
                $('#subtotalno').val(tno.toFixed(dec));
                $('#subtotal').val(sub.toFixed(dec));
                $('#total_descuento').val(tdsc.toFixed(dec));
                $('#total_iva').val(tiva.toFixed(dec));
                $('#total_ice').val(tice.toFixed(dec));
                $('#total_valor').val(gtot.toFixed(dec));
                if(pcont == ''){
                  pag_cantidad1.value = gtot.toFixed(dec);
                }
              
                calculo_pagos();
            }   

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
                         
                         if(dt.fpg_id != 1 ){
                          
                          traer_plazo(obj);
                          traer_banco(obj);
                          traer_tarjeta(obj);
                          busqueda_ntscre(obj);
                          
                           if($('#cli_id').val().length!=0){

                             $("#pagos").modal('show');
                             $("ventana").attr('disabled',true);
                           }
                          
                         }
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
                         alert('Pago es mayor al del documento $: ' + parseFloat(val_nc).toFixed(dec));
                            $('#pag_cantidad' + n).val('0');
                            $('#pag_cantidad' + n).focus();
                            cnt=0;
                      }
                    }
                    pg_ac+=cnt;
                  }
                }
                falt=tv-pg_ac;
                if(falt<0){
                  falt=tv-pg_ac+round($('#pag_cantidad'+n).val(),dec);
                  $('#pag_cantidad'+n).val('0')
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

          // function traer_forma(obj){
          //   n=obj.lang;
          //     $.ajax({
          //           beforeSend: function () {
          //             if ($('#pag_descripcion'+n).val().length == 0) {
          //                   alert('Ingrese Forma de pago');
          //                   return false;
          //             }
          //           },
          //           url: base_url+"factura/traer_forma/"+$('#pag_descripcion'+n).val(),
          //           type: 'JSON',
          //           dataType: 'JSON',
          //           success: function (dt) {
          //               if(dt!=""){
          //                 $('#pag_forma'+n).val(dt.fpg_id);
          //                 $('#pag_descripcion'+n).val(dt.fpg_descripcion);
          //                 $('#pag_contado'+n).html(dt.lista);
          //                 $('#pag_tipo'+n).val(dt.fpg_tipo);
          //               }else{
          //                 alert('Forma de pago no existe');
          //                 $('#pag_forma'+n).val('');
          //                 $('#pag_descripcion'+n).val('');
          //                 $('#pag_tipo'+n).val('0');
          //                 $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
          //               } 
          //              habilitar(obj); 
          //           },
          //           error : function(xhr, status) {
          //                 alert('Forma de pago no existe');
          //                 $('#pag_forma'+n).val('');
          //                 $('#pag_descripcion'+n).val('');
          //                 $('#pag_tipo'+n).val('0');
          //                 $('#pag_contado'+n).html("<option value='0'>SELECCIONE</option>");
          //                 habilitar(obj);
          //           }
          //           });    
          //   } 

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


            // function habilitar(obj) {
            //     if (obj.lang != null) {
            //         s = obj.lang;
            //     } else {
            //         s = obj;
            //     }

            //     if ($('#pag_tipo' + s).val() == '1') {
            //         $('#pag_cantidad' + s).attr('readonly', false);
            //         $('#pag_contado' + s).attr('disabled', false);
            //         $('#pag_contado' + s).focus();
            //     } else if ($('#pag_tipo' + s).val() == '2') {
            //         $('#pag_cantidad' + s).attr('readonly', false);
            //         $('#pag_contado' + s).attr('disabled', true);
            //         $('#pag_cantidad' + s).focus();
            //     } else if ($('#pag_tipo' + s).val() == '3') {
            //         $('#pag_contado' + s).attr('disabled', true);
            //         $('#pag_contado' + s).val('0');
            //         $('#pag_cantidad' + s).attr('readonly', false);
            //         $('#pag_cantidad' + s).focus();
            //     } else if ($('#pag_tipo' + s).val() == '9') {
            //         $('#pag_contado' + s).attr('disabled', false);
            //         $('#pag_cantidad' + s).attr('readonly', false);
            //         $('#pag_contado' + s).focus();
            //     } else if ($('#pag_tipo' + s).val() > '3') {
            //         $('#pag_contado' + s).attr('disabled', true);
            //         $('#pag_contado' + s).val('0');
            //         $('#pag_cantidad' + s).attr('readonly', false);
            //         $('#pag_cantidad' + s).focus();
            //     } else {
            //         $('#pag_contado' + s).attr('disabled', true);
            //         $('#pag_contado' + s).val('0');
            //         $('#pag_cantidad' + s).attr('readonly', true);
            //     }
            // }  

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

            // function busqueda_ntscre(obj) {

            //     if (obj.lang != null) {
            //         s = obj.lang;
            //     } else {
            //         s = obj;
            //     }
            //     nc = obj.value;

            //     ruc_cli = $('#identificacion').val();
            //     if (ruc_cli != '') {
            //         if (nc == 8) {
            //           $.ajax({
            //           beforeSend: function () {
            //             if ($('#pag_descripcion'+n).val().length == 0) {
            //                   alert('Ingrese Forma de pago');
            //                   return false;
            //             }
            //           },
            //           url: base_url+"factura/buscar_notas/"+$('#cli_id').val(),
            //           type: 'JSON',
            //           dataType: 'JSON',
            //           success: function (dt) {
            //                   if (dt != '') {
            //                       $('#list_notas').html(dt.lista);
            //                       $('#pag_documento' + s).focus();
            //                   } else {
            //                       alert('El Cliente no tiene Documentos \n En esta opcion');
            //                       $('#pag_documento' + s).val('');
            //                       $('#id_nota_credito' + s).val('0');
            //                       $('#val_nt_cre' + s).val('');
            //                       $('#pag_descripcion' + s).val('');
            //                       $('#pag_forma' + s).val(0);
            //                       $('#pag_tipo' + s).val(0);
            //                       $('#pag_descripcion' + s).focus();
            //                       $('#pag_cantidad' + s).val('0');
            //                       $('#pag_cantidad' + s).attr('readonly', true);
            //                       $('#pag_documento' + s).attr('readonly', false);
            //                       calculo_pagos($('#pag_cantidad' + s));
            //                   }
            //               },
            //             error : function(xhr, status) {
            //                   alert('El Cliente no tiene Documentos \n En esta opcion');
            //                       $('#pag_documento' + s).val('');
            //                       $('#id_nota_credito' + s).val('0');
            //                       $('#val_nt_cre' + s).val('');
            //                       $('#pag_descripcion' + s).val('');
            //                       $('#pag_forma' + s).val(0);
            //                       $('#pag_tipo' + s).val(0);
            //                       $('#pag_descripcion' + s).focus();
            //                       $('#pag_cantidad' + s).val('0');
            //                       $('#pag_cantidad' + s).attr('readonly', true);
            //                       $('#pag_documento' + s).attr('readonly', false);
            //                       $('#list_notas').html('');
            //                       calculo_pagos($('#pag_cantidad' + s));
            //             }
            //           });  
            //         } else {

            //             $('#pag_documento' + s).attr('readonly', false);
            //             $('#pag_documento' + s).val('');
            //             $('#id_nota_credito' + s).val('0');
            //             $('#val_nt_cre' + s).val('');
            //             $('#list_notas').html('');
            //         }
            //     } else {
            //         alert('Debe elejir un cliente');
            //         $('#pag_descripcion' + s).val('');
            //         $('#pag_forma' + s).val(0);
            //         $('#pag_tipo' + s).val(0);
            //         $('#pag_cantidad' + s).attr('readonly', true);
            //         $('#identificacion').focus();
            //         $('#pag_documento' + s).val('');
            //         $('#id_nota_credito' + s).val('0');
            //         $('#val_nt_cre' + s).val('');
            //         $('#list_notas').html('');
            //     }
            // }

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

            function load_notas_credito(obj) {
              n=obj.lang;
              var vl='';
              if($('#pag_tipo'+n).val()=='8'){
                $('#tbl_pagos .itm').each(function () {
                  if($('#pag_descripcion' + this.lang).val()!=null){
                    pro = $('#id_nota_credito' + this.lang).val();
                    pro2 = $('#pag_documento' + n).val();
                    $('#pag_descripcion' + n).css({borderColor: ""});
                    vl=pro2;
                    if (pro2 == pro) {
                      vl='';
                      alert('Documento ya ingresado');
                      $('#pag_descripcion' + n).val('');
                      $('#pag_forma' + n).val(0);
                      $('#pag_tipo' + n).val(0);
                      $('#pag_documento' + n).val('');
                      $('#id_nota_credito' + n).val('0');
                      $('#val_nt_cre' + n).val('');
                      $('#pag_cantidad' + n).val('0');
                      $('#pag_cantidad' + n).attr('readonly', true);
                        return false;
                    }
                  }
                });
                if(vl!=''){
                $.ajax({
                      beforeSend: function () {
                        if ($('#pag_documento'+n).val().length == 0) {
                              alert('Ingrese numero de documento');
                              return false;
                        }
                      },
                      url: base_url+"factura/load_nota/"+vl,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                        if (dt != '1') {
                            $('#pag_documento' + n).val(dt.chq_numero);
                            $('#pag_cantidad' + n).val(dt.chq_valor);
                            $('#id_nota_credito' + n).val(dt.chq_id);
                            $('#val_nt_cre' + n).val(dt.chq_valor);
                            $('#pag_cantidad' + n).focus();
                            $('#pag_documento' + n).attr('readonly', true);
                            calculo_pagos($('#pag_cantidad' + n));
                        }else {
                            alert('Documento no existe');
                            $('#pag_descripcion' + n).val('');
                            $('#pag_forma' + n).val('0');
                            $('#pag_tipo' + n).val('0');
                            $('#pag_documento' + n).val('');
                            $('#id_nota_credito' + n).val('0');
                            $('#val_nt_cre' + n).val('');
                            $('#pag_cantidad' + n).val(0);
                            $('#pag_cantidad' + n).attr('readonly', true);
                            $('#pag_documento' + n).attr('readonly', false);
                            $('#list_notas').html('');
                            calculo_pagos($('#pag_cantidad' + n));
                        }
                        
                    }
                  }); 
                }else{
                  calculo_pagos($('#pag_cantidad' + n));
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
                            if (parseFloat($('#inventario'+n).val()) < parseFloat($('#cantidad'+n).val())) {
                                alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                                $('#cantidad'+n).val('');
                                $('#cantidad'+n).focus();
                                $('#cantidad'+n).css({borderColor: "red"});
                                
                            }
                          }
                        }   
                        ///solicitado
                        if($('#cantidad'+n).val()!=null){
                            ent=parseFloat($('#entregado'+n).val())+parseFloat($('#cantidad'+n).val());
                            if (parseFloat($('#solicitado'+n).val()) < ent) {
                                alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE LO SOLICITADO');
                                $('#cantidad'+n).val('');
                                $('#cantidad'+n).focus();
                                $('#cantidad'+n).css({borderColor: "red"});
                                
                            }
                          }

                    }  
                
                calculo();
            }

            

            function costo_det(obj) {

                i = obj.lang;
                can = $('#cantidad').val();
                uni = $('#mov_cost_unit').val() * 1;
                tot = $('#mov_cost_tot').val();
                t = parseFloat(can) * parseFloat(uni);
                $('#mov_cost_tot').val(t.toFixed(6));
            } 

            function save() {
                        if (identificacion.value.length == 0) {
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
                        } else if (email_cliente.value.length == 0) {
                            $("#email_cliente").css({borderColor: "red"});
                            $("#email_cliente").focus();
                            return false;
                        } 
                        // else if (cli_parroquia.value.length == 0) {
                        //     $("#cli_parroquia").css({borderColor: "red"});
                        //     $("#cli_parroquia").focus();
                        //     return false;
                        // } 
                        else if (cli_ciudad.value.length == 0) {
                            $("#cli_ciudad").css({borderColor: "red"});
                            $("#cli_ciudad").focus();
                            return false;
                        }
                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        if(a==null){
                          alert("Ingrese Detalle");
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
                                    // } else if ($('#cantidad' + n).val().length == 0 || parseFloat($('#cantidad' + n).val()) == 0) {
                                    } else if ($('#cantidad' + n).val().length == 0 ) {
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
                        if ($('#total_valor').val() > 50 && $('#nombre').val() == 'CONSUMIDOR FINAL') {
                            alert('PARA CONSUMIDOR FINAL EL VALOR TOTAL NO PUDE SER MAYOR $50');
                            return false;
                        }
                        if ($('#vendedor').val() == '0' || $('#vendedor').val() == '') {
                            $('#vendedor').css({borderColor: "red"});
                            $('#vendedor').focus();
                            alert('Vendedor no existe');
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
                                        alert('No cumple con la estructura ejem: 000-000-000000000');
                                        return false;
                                    }
                                }
                                if ($('#pag_tipo' + j).val() == '3' || $('#pag_tipo' + j).val() == '5' || $('#pag_tipo' + j).val() == '8') {
                                    if ($('#pag_documento' + j).val().length == 0) {
                                        $('#pag_documento' + j).val('');
                                        $('#pag_documento' + j).focus();
                                        $('#pag_documento' + j).css({borderColor: "red"});
                                        return false;
                                    }
                                }
                                
                                if ($('#pag_contado' + j).val() == '0' && ($('#pag_contado' + j).attr('disabled') != 'disabled')) {
                                    $('#pag_contado' + j).css({borderColor: "red"});
                                    $('#pag_contado' + j).focus();
                                    return false;
                                }
                            }


                        }

                        
                     $('#frm_save').submit();   
               }   
    </script>

