
<section class="content-header">
      <h1>
        Registro de Facturas <?php echo $titulo?>
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          $ctrl_inv=$ctrl_inv->con_valor;
          if($retencion==0){
            $read_ret='';
            $hid_ret='';
            $sms_ret='';
          }else{
            $read_ret='readonly';
            $hid_ret="style='display:none;'";
            $sms_ret='*Apertura de Retencion no se pueden modificar los valores monetarios';
          }
          if($conf_as==1){
            $col_obs='7';
            $hidden_as='hidden';
          }else{
            $col_obs='8';
            $hidden_as='';
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
                 <table class="table col-sm-10" border="0">
                    
                    <tr>
                      <td class="col-sm-3">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                          <table class="table">
                          <tr>
                              <td><label>Fecha Registro:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('reg_fregistro')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="reg_fregistro" id="reg_fregistro" value="<?php if(validation_errors()!=''){ echo set_value('reg_fregistro');}else{ echo $factura->reg_fregistro;}?>">
                                  <?php echo form_error("reg_fregistro","<span class='help-block'>","</span>");?>
                                </div>
                              </td>  
                          </tr>    
                          <tr>
                              <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('reg_femision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="reg_femision" id="reg_femision" value="<?php if(validation_errors()!=''){ echo set_value('reg_femision');}else{ echo $factura->reg_femision;}?>" <?php echo $read_ret ?>>
                                  <?php echo form_error("reg_femision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $factura->emp_id;}?>">
                                </div>
                              </td>
                          </tr> 
                          <tr>
                                <td><label>Fecha Autorizacion:</label></td>
                                <td>
                                  <div class="form-group <?php if(form_error('reg_fautorizacion')!=''){ echo 'has-error';}?> ">
                                  <input type="date" id="reg_fautorizacion" name="reg_fautorizacion" class="form-control itm" size="25px" value="<?php if(validation_errors()!=''){ echo set_value('reg_fautorizacion');}else{ echo $factura->reg_fautorizacion;}?>">
                                  <?php echo form_error("reg_fautorizacion","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                          </tr>
                          
                          <tr>
                            <td><label>Tipo de Documento</label></td>
                              <td>
                                <div class="form-group ">
                                  <select name="reg_tipo_documento"  id="reg_tipo_documento" class="form-control" onchange="doc_duplicado()">
                                    <option value="0">SELECCIONE</option>
                                     <?php
                                    if(!empty($tipo_documentos)){
                                      foreach ($tipo_documentos as $tp_dc) {
                                    ?>
                                    <option value="<?php echo $tp_dc->tdc_id?>"><?php echo $tp_dc->tdc_codigo .' - '. $tp_dc->tdc_descripcion?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <script type="text/javascript">
                                    var tipodoc='<?php echo $factura->reg_tipo_documento;?>';
                                    reg_tipo_documento.value=tipodoc;
                                  </script>
                                  <?php echo form_error("reg_tipo_documento","<span class='help-block'>","</span>");?>
                                </div>
                              </td>    
                          </tr>
                          <tr>
                            <td><label>Sustento</label></td>
                              <td>
                                <div class="form-group ">
                                  <select name="reg_sustento"  id="reg_sustento" class="form-control">
                                    <option value="0">SELECCIONE</option>
                                     <?php
                                    if(!empty($cns_sustento)){
                                      foreach ($cns_sustento as $rst_sust) {
                                    ?>
                                    <option value="<?php echo $rst_sust->sus_id?>"><?php echo $rst_sust->sus_codigo .' - '. $rst_sust->sus_descripcion?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <script type="text/javascript">
                                    var sust='<?php echo $factura->reg_sustento;?>';
                                    reg_sustento.value=sust;
                                  </script>
                                  <?php echo form_error("reg_sustento","<span class='help-block'>","</span>");?>
                                </div>
                              </td>    
                          </tr>
                          <tr>
                            <td><label>Numero de Documento:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('reg_num_documento')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control documento" name="reg_num_documento" id="reg_num_documento" value="<?php if(validation_errors()!=''){ echo set_value('reg_num_documento');}else{ echo $factura->reg_num_documento;}?>" onchange="num_factura(this)" maxlength="17" <?php echo $read_ret ?>>
                                    <?php echo form_error("reg_num_documento","<span class='help-block'>","</span>");?>
                                
                                </div>
                                
                              </td>
                          </tr>
                          <tr>
                            <td><label>Numero de Autorizacion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('reg_num_autorizacion')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control numerico" name="reg_num_autorizacion" id="reg_num_autorizacion" value="<?php if(validation_errors()!=''){ echo set_value('reg_num_autorizacion');}else{ echo $factura->reg_num_autorizacion;}?>" onchange="validar_autorizacion()" maxlength="49">
                                    <?php echo form_error("reg_num_autorizacion","<span class='help-block'>","</span>");?>
                                
                                </div>
                              </td>
                          </tr>
                          
                          
                          </table>
                        
                        </div>
                        </div>
                      </td>
                      <td class="col-sm-3">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          <table class="table">
                            <tbody>
                             
                            <tr>
                              <td><label>Tipo Proveedor</label></td>
                                <td>
                                  <div class="form-group ">
                                    <select name="reg_tpcliente"  id="reg_tpcliente" class="form-control">
                                      <option value="0">SELECCIONE</option>
                                      <option value="LOCAL">LOCAL</option>
                                      <option value="EXTRANJERO">EXTRANJERO</option>
                                    </select>
                                    <script type="text/javascript">
                                      var tprov='<?php echo $factura->reg_tpcliente;?>';
                                      reg_tpcliente.value=tprov;
                                    </script>
                                    <?php echo form_error("reg_tpcliente","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>    
                            </tr>
                            <tr>
                                <td><label>Proveedor RUC/CI:</label></td>
                                <td >
                                  <div class="form-group <?php if(form_error('reg_ruc_cliente')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="reg_ruc_cliente" id="reg_ruc_cliente" value="<?php if(validation_errors()!=''){ echo set_value('reg_ruc_cliente');}else{ echo $factura->cli_ced_ruc;}?>" list="list_clientes" onchange="traer_cliente(this)" <?php echo $read_ret ?>>
                                    <?php echo form_error("reg_ruc_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Proveedor Razon Social:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $factura->cli_raz_social;}?>" <?php echo $read_ret ?>>
                                      <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                  
                                  <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $factura->cli_id;}?>" >
                                  </div>
                                  
                                </td>
                            </tr>
                            <tr>
                              <td><label>Direccion:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('direccion_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" value="<?php if(validation_errors()!=''){ echo set_value('direccion_cliente');}else{ echo $factura->cli_calle_prin;}?>" <?php echo $read_ret ?>>
                                  <?php echo form_error("direccion_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td><label>Telefono:</label></td>
                                <td >
                                  <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                                    <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" value="<?php if(form_error('telefono_cliente')){ echo set_value('telefono_cliente');}else{ echo $factura->cli_telefono;}?>" <?php echo $read_ret ?>>
                                        <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                    
                                    </div>
                                </td>
                              </tr>
                              <tr>
                                <td><label>Email:</label></td>
                                <td >
                                  <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                                    <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('email_cliente');}else{ echo $factura->cli_email;}?>" <?php echo $read_ret ?>>
                                    <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
                                    </div>
                                </td> 
                              </tr>
                                                         
                              </tbody>
                          </table>
                          </div>
                          </div>
                      </td>
                      <td class="col-sm-3">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          <table class="table">
                            <tbody>
                            <tr>
                              <td><label>Concepto:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('reg_concepto')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="reg_concepto" id="reg_concepto" value="<?php if(validation_errors()!=''){ echo set_value('reg_concepto');}else{ echo $factura->reg_concepto;}?>">
                                  <?php echo form_error("reg_concepto","<span class='help-block'>","</span>");?>
                                  </div>
                              </td>
                            </tr>
                            <tr>
                                <td><label>#Importacion:</label></td>
                                <td>
                                  <div class="form-group <?php if(form_error('reg_importe')!=''){ echo 'has-error';}?> ">
                                  <input type="text" id="reg_importe" name="reg_importe" class="form-control itm" size="25px" value="<?php if(validation_errors()!=''){ echo set_value('reg_importe');}else{ echo $factura->reg_importe;}?>">
                                  <?php echo form_error("reg_importe","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td><label>Pais Origen:</label></td>
                                <td>
                                <div class="form-group ">
                                  <select name="reg_pais_importe"  id="reg_pais_importe" class="form-control">
                                    <option value="0">SELECCIONE</option>
                                     <?php
                                    if(!empty($paises)){
                                      foreach ($paises as $pais) {
                                    ?>
                                    <option value="<?php echo $pais->pai_id?>"><?php echo $pais->pai_codigo .' - '. $pais->pai_descripcion?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <script type="text/javascript">
                                    var pais='<?php echo $factura->reg_pais_importe;?>';
                                    reg_pais_importe.value=pais;
                                  </script>
                                  <?php echo form_error("reg_pais_importe","<span class='help-block'>","</span>");?>
                                </div>
                              </td>    
                          </tr>
                          <tr>
                                <td><label>Fecha de Vencimiento Pago:</label></td>
                                <td>
                                  <div class="form-group <?php if(form_error('reg_fcaducidad')!=''){ echo 'has-error';}?> ">
                                  <input type="date" id="reg_fcaducidad" name="reg_fcaducidad" class="form-control itm" size="25px" value="<?php if(validation_errors()!=''){ echo set_value('reg_fcaducidad');}else{ echo $factura->reg_fcaducidad;}?>">
                                  <?php echo form_error("reg_fcaducidad","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                          </tr>   
                          <tr>
                                <td><label>Tipo Pago:</label></td>
                                <td>
                                <div class="form-group ">
                                  <select name="reg_tipo_pago"  id="reg_tipo_pago" class="form-control">
                                    <option value="0">SELECCIONE</option>
                                    <option value='01'>01 - PAGO A RESIDENTE</option>
                                    <option value='02'>02 - PAGO A NO RESIDENTE</option>
                                  </select>
                                  <script type="text/javascript">
                                    var tip_pago='<?php echo $factura->reg_tipo_pago;?>';
                                    reg_tipo_pago.value=tip_pago;
                                  </script>
                                  <?php echo form_error("reg_tipo_pago","<span class='help-block'>","</span>");?>
                                </div>
                            </td>    
                          </tr>
                          <tr>
                                <td><label>Forma de Pago:</label></td>
                                <td>
                                <div class="form-group ">
                                  <select name="reg_forma_pago"  id="reg_forma_pago" class="form-control">
                                    <option value="0">SELECCIONE</option>
                                     <?php
                                    if(!empty($formas_pago)){
                                      foreach ($formas_pago as $forma) {
                                    ?>
                                    <option value="<?php echo $forma->fpg_id?>"><?php echo $forma->fpg_codigo .' - '. $forma->fpg_descripcion?></option>
                                    <?php
                                      }
                                    }
                                  ?>
                                  </select>
                                  <script type="text/javascript">
                                    var forma='<?php echo $factura->reg_forma_pago;?>';
                                    reg_forma_pago.value=forma;
                                  </script>
                                  <?php echo form_error("reg_forma_pago","<span class='help-block'>","</span>");?>
                                </div>
                            </td>    
                          </tr>

                            </tbody>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr>
                    <tr>
                       <td class="col-sm-10" colspan="3">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th <?php echo $hidden_as?>>Cta.Contable</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Unidad</th>
                                <th>Cant.</th>
                                <th>Precio</th>
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

                            <tbody <?php echo $hid_ret?>>
                            
                                <?php
                                $cnt_detalle=0;
                                $verifica_cuenta=0;
                                   ?>
                                    <tr>
                                      <td align="center" ><input  type="button" name="nuevo" id="nuevo" class="btn btn-success" onclick="habilitar(0)" lang="1" value='NUEVO'/> </td>
                                       
                                        <td <?php echo $hidden_as?>>
                                            <input style="text-align:left " type="text" size="40" class="form-control" id="reg_codigo_cta" name="reg_codigo_cta"  value="" lang="1"   maxlength="14" list="list_cuentas" onchange="load_cuenta(this,0)"/>
                                            <input type="hidden" name="pln_id" id="pln_id" lang="1" value="0">
                                        </td>
                                        <td>
                                            <input style="text-align:left " type="text" size="40" class="form-control" id="pro_descripcion" name="pro_descripcion"  value="" lang="1"   maxlength="16" list="productos" onchange="load_producto(this.lang)"  />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_referencia" name="pro_referencia"   value="" lang="1" readonly style="width:300px;" />
                                            <input type="hidden"  id="pro_aux" name="pro_aux" lang="1"/>
                                            <!-- <input type="hidden"  id="pro_ids" name="pro_ids" lang="1"/> -->
                                        </td>
                                        
                                        <td>
                                          <select id="unidad" name="unidad" class="form-control" disabled>
                                            <option value=''>SELECCIONE</option>
                                            <option value='KG'>kg</option>
                                            <option value='LB'>lb</option>
                                            <option value='GR'>gr</option>
                                            <option value='LITRO'>litro</option>
                                            <option value='GALON'>galon</option>
                                            <option value='M'>m</option>
                                            <option value='CM'>cm</option>
                                            <option value='FT'>ft</option>
                                            <option value='IN'>in</option>
                                            <option value='UNIDAD'>UNIDAD</option>
                                            <option value='MILLAR'>MILLAR</option>
                                            <option value='ROLLO'>rollo</option>
                                          </select>
                                          <!-- <input type ="text" size="7" id="unidad" name="unidad"  value="" lang="1" readonly class="form-control" /> -->
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidad" name="cantidad"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="pro_precio" name="pro_precio" onchange="calculo_encabezado(this)" value="" lang="1" class="form-control decimal"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuento" name="descuento"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuent" name="descuent"  value="" lang="1" readonly  class="form-control decimal" />
                                        </td>
                                        <td>
                                          <select name="iva" id="iva" class="form-control">
                                            <option value="12">12</option>
                                            <option value="0">0</option>
                                            <option value="NO">NO OBJETO</option>
                                            <option value="EX">EXCENTO</option>
                                          </select>
                                        </td>
                                        <td hidden><input type="text" name="ice_p" id="ice_p" size="5" value="0" readonly lang="1" /></td>
                                        <td hidden><input type="text" name="ice" id="ice" size="5" value="0" readonly lang="1"/>
                                            <input type=""  name="ice_cod" id="ice_cod" size="5" value="0" readonly lang="1"/>
                                        </td>
                                        <td>
                                            <input type ="text" size="9" style="text-align:right" id="valor_total" name="valor_total" value="" lang="1" readonly class="form-control decimal" />
                                            
                                        </td>
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar(0)" lang="1" value='+'/> </td>
                                        
                                    </tr>
                                </tbody>        
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        ?>
                                        <tr>
                                            <td id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center"><?PHP echo $n ?></td>
                                          
                                            <td <?php echo $hidden_as?>>
                                              <input style="text-align:left " type="text" size="40" class="form-control" id="reg_codigo_cta<?PHP echo $n ?>" name="reg_codigo_cta<?PHP echo $n ?>"  value="<?PHP echo $rst_det->reg_codigo_cta ?>" lang="<?PHP echo $n ?>"   maxlength="14" list="list_cuentas" onchange="load_cuenta(this,1)"/>
                                              <input type="hidden" name="pln_id<?PHP echo $n ?>" id="pln_id<?PHP echo $n ?>" lang="<?PHP echo $n ?>" value="<?PHP echo $rst_det->pln_id ?>">
                                            </td>
                                            <td id="pro_descripcion<?PHP echo $n ?>" name="pro_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_codigo ?></td>
                                            <td id="pro_referencia<?PHP echo $n ?>" name="pro_referencia<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_descripcion ?>
                                                <input type="hidden" size="7" id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->pro_unidad ?></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidad' . $n ?>" name="<?php echo 'cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')" <?php echo $read_ret ?> /></td>
                                            <td><input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'pro_precio' . $n ?>" name="<?php echo 'pro_precio' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->pro_precio, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" <?php echo $read_ret ?>/></td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuento' . $n ?>" name="<?php echo 'descuento' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuento, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)"  <?php echo $read_ret ?>/>
                                            </td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuent' . $n ?>" name="<?php echo 'descuent' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuent, $dec)) ?>" lang="<?PHP echo $n ?>"  readonly/>
                                            </td>
                                            
                                            <td>
                                              <?php 
                                              if($read_ret==''){
                                              ?>
                                              <select name="<?php echo 'iva' . $n ?>" id="<?php echo 'iva' . $n ?>" onchange='calculo()' class="form-control">
                                                <option value="12">12</option>
                                                <option value="0">0</option>
                                                <option value="NO">NO OBJETO</option>
                                                <option value="EX">EXCENTO</option>
                                              </select>
                                              <script type="text/javascript">
                                                var iv='<?php echo $rst_det->pro_iva;?>';
                                                <?php echo 'iva' . $n ?>.value=iv;
                                              </script>
                                              <?php
                                              }else{
                                              ?>  
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'iva' . $n ?>" name="<?php echo 'iva' . $n ?>"  value="<?php echo $rst_det->pro_iva ?>" lang="<?PHP echo $n ?>"  readonly/>
                                              <?php
                                              }
                                              ?>
                                            </td>
                                            <td hidden><input type="text" id="<?php echo 'ice_p' . $n ?>" name="<?php echo 'ice_p' . $n ?>" size="5" value="<?php echo str_replace(',', '', number_format($rst_det->ice_p, $dec)) ?>" lang="<?PHP echo $n ?>" readonly /></td>
                                            <td hidden><input type="text" id="<?php echo 'ice' . $n ?>" name="<?php echo 'ice' . $n ?>" size="5" class="form-control" value="<?php echo str_replace(',', '', number_format($rst_det->ice, $dec)) ?>" readonly lang="<?php echo $n ?>"/>
                                                <input type="hidden" id="<?php echo 'ice_cod' . $n ?>" name="<?php echo 'ice_cod' . $n ?>" size="5" class="form-control" value="<?php echo $rst_det->ice_cod ?>" lang="<?PHP echo $n ?>"readonly />
                                            </td>
                                            <td>
                                                <input type ="text" size="9" style="text-align:right" class="form-control" id="<?php echo 'valor_total' . $n ?>" name="<?php echo 'valor_total' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->precio_tot, $dec)) ?>" readonly lang="<?PHP echo $n ?>"/>
                                                
                                            </td>
                                            <td onclick="elimina_fila_det(this)" align="center" <?php echo $hid_ret?>><span class="btn btn-danger fa fa-trash"></span></td>
                                        </tr>
                                        <?php
                                        $cnt_detalle++;
                                    }
                                  }
                                ?>
                                </tbody>
                            <tfoot>
                                <tr>

                                    <td valign="top" rowspan="11" colspan="<?php echo $col_obs?>">
                                    </td>    
                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt12, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt_excento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt_noiva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($factura->reg_sbt, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Descuento:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_descuento" name="total_descuento" value="<?php echo str_replace(',', '', number_format($factura->reg_tdescuento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total ICE:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_ice" name="total_ice" value="<?php echo str_replace(',', '', number_format($factura->reg_ice, $dec)) ?>"  readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_iva" name="total_iva" value="<?php echo str_replace(',', '', number_format($factura->reg_iva12, $dec)) ?>" readonly />
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" align="right">Propina:</td>
                                    <td><input type="text" class="form-control" id="total_propina" name="total_propina" value="<?php echo str_replace(',', '', number_format($factura->reg_propina, $dec)) ?>"  style="text-align:right" onchange="calculo()" <?php echo $read_ret?> />
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Valor:</td>
                                    <td><input style="text-align:right;font-size:15px;color:red  " type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($factura->reg_total, $dec)) ?>" readonly />
                                        
                                    </td>
                                </tr>
                                <tr>
                                  <td colspan="<?php echo $col_obs?>" style="color:red;">
                                  <?php echo $sms_ret?>  
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
                                
                <input type="hidden" class="form-control" name="reg_id" value="<?php echo $factura->reg_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
                <input type="hidden" class="form-control" id="verifica_cuenta" name="verifica_cuenta" value="<?php echo $verifica_cuenta?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <div class="modal fade" id="modal_productos">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear producto</h4>
              </div>
              <div class="modal-body">
                <table class="table" width="50%">
                  <tr>
                    <td>Categoria</td>
                    <td >
                        <select name="pro_ids" id="pro_ids" class="form-control" onchange="load_familias()">
                          <option value="0">SELECCIONE</option>
                          <?php 
                          if(!empty($categorias)){
                            foreach ($categorias as $rst_ct) {
                          ?>
                           <option value="<?php echo $rst_ct->cat_id?>"><?php echo strtoupper($rst_ct->cat_descripcion)?></option>
                           <?php
                            }
                          }
                          ?>
                        </select>
                      </td> 
                  </tr>
                  <tr>  
                    <td>Familia</td>
                    <td>
                      <select name="mp_a" id="mp_a" class="form-control" onchange='load_tipos(),load_codigo()'>
                        <option value="0">SELECCIONE</option>
                        <?php 
                        if(!empty($tipos)){
                          foreach ($tipos as $rst_tp) {
                        ?>
                        <option value="<?php echo $rst_tp->tps_id?>"><?php echo $rst_tp->tps_nombre?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                  <tr>    
                    <td>Tipo</td>
                    <td>
                        <select name="mp_b" id="mp_b" onchange="load_codigo()" class="form-control">
                          <option value="0">SELECCIONE</option>
                          <?php 
                          if(!empty($familias)){
                            foreach ($familias as $rst_fm) {
                          ?>
                          <option value="<?php echo $rst_fm->tps_id?>"><?php echo $rst_fm->tps_nombre?></option>
                          <?php
                            }
                          }
                          ?>
                        </select>
                      </td>
                  </tr>
                  <tr>  
                    <td>Codigo</td>
                    <td><input type="text" id="codigo" name="codigo" class="form-control" size="15px" lang="1" readonly onchange="validar_codigo()">
                      </td> 
                  </tr> 
                  <tr>
                    <td>Descripcion</td>
                    <td><input type="text" id="descripcion" name="descripcion" class="form-control" size="15px" lang="1">
                    </td>
                  </tr>
                  <tr>
                    <td>Unidad</td>  
                    <td>
                        <select id="unidad_pr" name="unidad_pr" class="form-control">
                           <option value=''>SELECCIONE</option>
                          <option value='KG'>kg</option>
                          <option value='LB'>lb</option>
                          <option value='GR'>gr</option>
                          <option value='LITRO'>litro</option>
                          <option value='GALON'>galon</option>
                          <option value='M'>m</option>
                          <option value='CM'>cm</option>
                          <option value='FT'>ft</option>
                          <option value='IN'>in</option>
                          <option value='UNIDAD'>UNIDAD</option>
                          <option value='MILLAR'>MILLAR</option>
                          <option value='ROLLO'>rollo</option>
                        </select>
                    </td>
                  </tr>
                  <tr>  
                    <td>Precio</td>
                    <td><input type="text" id="precio" name="precio" class="form-control decimal" size="15px" lang="1" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')">
                    </td>
                  </tr>
                  <tr>  
                    <td>Iva</td>
                    <td>
                      <select name="iva_pr" id="iva_pr" class="form-control">
                        <option value="12">12</option>
                        <option value="0">0</option>
                        <option value="NO">NO OBJETO</option>
                        <option value="EX">EXCENTO</option>
                      </select>
                    </td>
                  </tr>
                  
                </table>
              </div>
              <div class="modal-footer"  >
                <div style="float:right">
                  <button type="button" class="btn btn-success pull-left btn-md" onclick="nuevo_producto()">Agregar</button>
                </div>
              </div>
            </div>
          </div>
      </div>

    </section>
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

    <datalist id="list_cuentas">
      <?php 
        if(!empty($cns_cuentas)){
          foreach ($cns_cuentas as $rst_cta) {
      ?>
        <option value="<?php echo $rst_cta->pln_codigo?>"><?php echo $rst_cta->pln_codigo .' '.$rst_cta->pln_descripcion?></option>
      <?php 
          }
        }
      ?>
  
    </datalist>
    

    <style type="text/css">
     
      .panel{
        margin: 0.5px !important;
        /*margin-top: 0px !important;*/
        padding: 0.5px !important;
        /*padding-top: 0px !important;*/

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
        margin-right: 0px !important;
        margin-left:  0px !important;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }
      
      .btn-success{
        width: 35px !important;
        font-size: 10px;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }

      .fa-plus{
        width: 20px !important;
        padding-right: 0px !important;
        padding-left:  0px !important;
      }

      .sel{
        width: 100px !important;
        
      }
    </style>
    <script >

      var base_url='<?php echo base_url();?>';
      var dec='<?php echo $dec;?>';
      var dcc='<?php echo $dcc;?>';
      var valida_asiento='<?php echo $valida_asiento;?>';
      var conf_as='<?php echo $conf_as;?>';

      window.onload = function () {
        if(valida_asiento==1){
          swal("", "No se puede crear Documento \nRevise Configuracion de cuentas", "info");          
        }
      }

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#reg_ruc_cliente').val().length == 0) {
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
                            return false;
                      }
                    },
                    url: base_url+"reg_factura/traer_cliente/"+reg_ruc_cliente.value,
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
                          $('#reg_ruc_cliente').val(dt.cli_ced_ruc);
                          $('#cli_pais').val(dt.cli_pais);
                          $('#cli_parroquia').val(dt.cli_parroquia);
                          doc_duplicado();
                        }else{
                          alert('Proveedor no existe');
                          $('#nombre').focus();
                          $('#reg_ruc_cliente').val('');
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#cli_ciudad').val('');
                          $('#email_cliente').val('');
                          $('#cli_pais').val('');
                          $('#cli_parroquia').val('');
                        } 
                        
                    },
                    error : function(xhr, status) {
                          alert('Proveedor no existe');
                          $('#nombre').focus();
                          $('#reg_ruc_cliente').val('');
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#cli_ciudad').val('');
                          $('#email_cliente').val('');
                          $('#cli_pais').val('');
                          $('#cli_parroquia').val('');
                    }
                    });    
            }

            function doc_duplicado(){
              num_doc = $('#reg_num_documento').val();
              tip_doc = $('#reg_tipo_documento').val();
              if (num_doc.length = 17 && cli_id.value.length > 0 && tip_doc != 0) {
                $.ajax({
                      beforeSend: function () {
                      },
                      url: base_url+"reg_factura/doc_duplicado/"+cli_id.value+"/"+num_doc+"/"+tip_doc,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                          if(dt!=""){
                            alert('EL numero de Documento y el RUC/CI del Proveedor \n Ya existen en el Registro de Facturas');   
                            $('#reg_num_documento').val('');
                          } 
                      }
                    });
              }          
            }

            function validar_autorizacion(){
              var aut = $('#reg_num_autorizacion').val();
              if(aut.length!=10 && aut.length!=37 && aut.length!=49 ){
                alert('El numero de autorizacion debe ser de 10, 37 o 49 digitos');
                $('#reg_num_autorizacion').val('');
              }

            }

            function validar(opc){
              
                if($('#cantidad').val().length!=0 &&  parseFloat($('#cantidad').val())>0 && $('#pro_precio').val().length!=0 &&  parseFloat($('#pro_precio').val())>0 && $('#descuento').val().length!=0 && $('#pro_descripcion').val().length!=0){
                  clona_detalle();
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
                      } else{
                          this.value ='';
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
                            $('#iva' + n).val(iva.value);
                            
                        }
                    }
                }
                                    
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+

                                          "<input type ='hidden' name='pro_aux"+i+"' id='pro_aux"+i+"' lang='"+i+"' value='"+pro_aux.value+"'/>"+
                                        "</td>"+
                                        
                                        "<td <?php echo $hidden_as?>>"+
                                            "<input style='text-align:left ' type='text' size='40' class='form-control' id='reg_codigo_cta"+i+"' name='reg_codigo_cta"+i+"'  value='"+reg_codigo_cta.value+"' lang='"+i+"'  maxlength='14' list='list_cuentas' onchange='load_cuenta(this,1)'/>"+
                                            "<input type='hidden' name='pln_id"+i+"' id='pln_id"+i+"' lang='"+i+"' value='"+pln_id.value+"'>"+
                                        "</td>"+
                                        "<td id='pro_descripcion"+i+"' lang='"+i+"'>"+pro_descripcion.value+"</td>"+
                                        "<td id='pro_referencia"+i+"' lang='"+i+"'>"+pro_referencia.value+"</td>"+
                                        "<td id='unidad"+i+"' lang='"+i+"'>"+unidad.value+"</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' lang='"+i+"'  value='"+cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio"+i+"' name='pro_precio"+i+"' onchange='calculo()' value='"+pro_precio.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuento"+i+"' name='descuento"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' value='"+descuento.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuent"+i+"' name='descuent"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+descuent.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<select name='iva"+i+"' id='iva"+i+"' onchange='calculo()' class='form-control'>"+
                                            "<option value='12'>12</option>"+
                                            "<option value='0'>0</option>"+
                                            "<option value='NO'>NO OBJETO</option>"+
                                            "<option value='EX'>EXCENTO</option>"+
                                          "</select>"+
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
                    $('#iva' + i).val(iva.value);
                }
         
                reg_codigo_cta.value='';
                pln_id.value='0';
                pro_referencia.value = '';
                pro_descripcion.value = '';
                pro_aux.value = '';
                unidad.value = '';
                cantidad.value = '';
                pro_precio.value = '';
                iva.value = '12';
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
                      alert('No puede eliminar todas las filas');
                  }
                  calculo_pagos();
            }

            function elimina_fila_det(obj) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
            }

            

            function load_producto(j) {
                vl = $('#pro_descripcion').val();
                $.ajax({
                  beforeSend: function () {
                      if ($('#pro_descripcion').val().length == 0) {
                            alert('Ingrese un producto');
                            return false;
                      }
                    },
                    url: base_url+"reg_factura/load_producto/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') {
                        $('#pro_cat').val(dt.pro_cat);
                        $('#mp_a').val(dt.mp_a);
                        $('#mp_b').val(dt.mp_b);
                        $('#pro_descripcion').val(dt.pro_codigo);
                        $('#pro_referencia').val(dt.pro_descripcion);
                        if(dt.pro_iva==''){
                          $('#iva').val('12');
                        }else{
                          $('#iva').val(dt.pro_iva);
                        }
                        $('#pro_aux').val(dt.pro_id);
                        $('#pro_ids').val(dt.ids);
                        $('#cantidad').val('');
                        $('#unidad').val(dt.pro_unidad);
                       
                        if (dt.pro_precio== '') {
                            $('#pro_precio').val(0);
                            
                        } else {
                            $('#pro_precio').val(parseFloat(dt.pro_precio).toFixed(dec));
                        }

                        if (dt.pro_descuento == '') {
                            $('#descuento').val(0);
                        } else {
                            $('#descuento').val(parseFloat(dt.pro_descuento).toFixed(dec));
                        }

                        if (dt.ice_p== '') {
                            $('#ice').val('0');
                            $('#ice_p').val('0');
                        } else {
                            $('#ice').val('0');
                            $('#ice_p').val(parseFloat(dt.ice_p).toFixed(dec));

                        }

                        if (dt.ice_cod == '') {
                            $('#ice_cod').val('0');
                        } else {
                            $('#ice_cod').val(dt.ice_cod);
                        }

                        $('#cantidad').focus();
                      }else{
                        $('#pro_descripcion').val('');
                        $('#pro_referencia').val('');
                        $('#cantidad').val('');
                        $('#iva').val('0');
                        $('#pro_aux').val('');
                        $('#pro_ids').val('');
                        $('#pro_precio').val('0');
                        $('#descuento').val('0');
                        
                        $('#ice').val('0');
                        $('#ice_p').val('0');
                        $('#ice_cod').val('0');
                        $('#pro_descripcion').focus();
                      }
                      calculo('1');
                    }
                  });
                
              }

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }


            function calculo_encabezado() {
                
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
            }     

            function save() {


                        if ($("#reg_tipo_documento").val() == '0') {
                            $("#reg_tipo_documento").css({borderColor: "red"});
                            $("#reg_tipo_documento").focus();
                            return false;
                        } else if ($("#reg_sustento").val() == '0') {
                            $("#reg_sustento").css({borderColor: "red"});
                            $("#reg_sustento").focus();
                            return false;
                        } else if ($("#reg_sustento").val() == '0') {
                            $("#reg_sustento").css({borderColor: "red"});
                            $("#reg_sustento").focus();
                            return false;
                        } else if ($("#reg_tipo_pago").val() == '0') {
                            $("#reg_tipo_pago").css({borderColor: "red"});
                            $("#reg_tipo_pago").focus();
                            return false;
                        } else if (reg_forma_pago.value.length == 0) {
                            $("#reg_forma_pago").css({borderColor: "red"});
                            $("#reg_forma_pago").focus();
                            return false;
                        } else if (reg_num_documento.value.length == 0) {
                            $("#reg_num_documento").css({borderColor: "red"});
                            $("#reg_num_documento").focus();
                            return false;
                        } else if (reg_num_autorizacion.value.length == 0) {
                            $("#reg_num_autorizacion").css({borderColor: "red"});
                            $("#reg_num_autorizacion").focus();
                            return false;
                        } else if ($("#reg_tpcliente").val() == '0') {
                            $("#reg_tpcliente").css({borderColor: "red"});
                            $("#reg_tpcliente").focus();
                            return false;
                        } else if (reg_ruc_cliente.value.length == 0) {
                            $("#reg_ruc_cliente").css({borderColor: "red"});
                            $("#reg_ruc_cliente").focus();
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
                        } else if (reg_concepto.value.length == 0) {
                            $("#reg_concepto").css({borderColor: "red"});
                            $("#reg_concepto").focus();
                            return false;
                        } 
                        var ast=0;
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
                                    }else if ($('#reg_codigo_cta' + n).val().length == 0) {
                                        ast++;
                                    }

                                }
                            }
                          $('#verifica_cuenta').val(ast);  
                        }

                        if(ast>0 && conf_as==0){
                          var r=confirm("El detalle de factura no esta completo \ny no se creará el Asiento Contable \n¿Esta Seguro de Guardar?");
                          if(r==false){
                            return false;
                          }
                        }
                        
                     $('#frm_save').submit();   
               } 

      function habilitar(op){
          $('#pro_ids').val('0');
          $('#mp_a').val('0');
          $('#mp_b').val('0');
          $('#codigo').val('');
          $('#descripcion').val('');
          $('#unidad_pr').val('');
          $('#precio').val('0');
          $('#iva_pr').val('12');
          $("#modal_productos").modal('show');
        
      }     

      function load_familias(){
            uri=base_url+'reg_factura/traer_familias/'+$('#pro_ids').val();
            $.ajax({
                    url: uri,
                    type: 'POST',
                    success: function(dt){
                      $('#mp_a').html(dt);
                      load_codigo();
                    } 
              });
      }

      function validar_codigo(){
        vl = $('#codigo').val();
                $.ajax({
                  beforeSend: function () {
                      if ($('#codigo').val().length == 0) {
                            alert('Ingrese un codigo');
                            return false;
                      }
                    },
                    url: base_url+"reg_factura/load_producto/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') {
                        alert('Ya existe codigo');
                        $('#pro_descripcion').val('');
                      }
                    }
                  });
      }

      function nuevo_producto(){
        
        var data={
          "ids":$('#pro_ids').val(),
          'mp_a':$('#mp_a').val(),
          "mp_b": $('#mp_b').val(),
          "mp_c": $('#codigo').val(),
          "mp_d": $('#descripcion').val(),
          "mp_q": $('#unidad_pr').val(),
          "mp_e": $('#precio').val(),
          "mp_h": $('#iva_pr').val()
          
        }
                $.ajax({
                  beforeSend: function () {
                      if ($("#pro_ids").val() == '0') {
                        $("#pro_ids").css({borderColor: "red"});
                        $("#pro_ids").focus();
                        return false;
                      } else if ($("#mp_a").val() == '0') {
                        $("#mp_a").css({borderColor: "red"});
                        $("#mp_a").focus();
                        return false;
                      } else if ($("#mp_b").val() == '0') {
                        $("#mp_b").css({borderColor: "red"});
                        $("#mp_b").focus();
                        return false;
                      } else if ($("#codigo").val().length == 0) {
                        $("#descripcion").css({borderColor: "red"});
                        $("#descripcion").focus();
                        return false;
                      } else if ($("#descripcion").val().length == 0) {
                        $("#descripcion").css({borderColor: "red"});
                        $("#descripcion").focus();
                        return false;
                      } else if ($("#unidad_pr").val().length == 0) {
                        $("#unidad_pr").css({borderColor: "red"});
                        $("#unidad_pr").focus();
                        return false;
                      } else if ($("#precio").val().length == 0 && parseFloat($('#precio').val())==0) {
                        $("#precio").css({borderColor: "red"});
                        $("#precio").focus();
                        return false;
                      }
                    },
                    url: base_url+"reg_factura/nuevo_producto/",
                    type: 'POST',
                    data: {data:data},
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt.id!='0') {
                        $('#pro_descripcion').val(dt.id);
                        $('#productos').html(dt.lista);
                        $("#modal_productos").modal('hide');
                        load_producto();
                      }else{
                        alert('Error al guardar producto')
                      }
                    },
                    error : function(xhr, status, error) {
                      alert(error);
                    }

                  });
      }    

      function num_factura(obj) {
                nfac = obj.value;
                dt = nfac.split('-');
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('reg_id').val('0');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    alert('No cumple con la estructura ejem: 000-000-000000000');
                } else{
                  doc_duplicado();
                }
            } 

      function load_cuenta(obj,opc) {
              var uri = base_url+'reg_factura/traer_cuenta/'+ $(obj).val();
              j=obj.lang;
              $.ajax({
                  url: uri, //this is your uri
                  type: 'GET', //this is your method
                  dataType: 'json',
                  success: function (response) {
                    if(opc==0){
                      $("#pln_id").val(response['pln_id']);  
                    }else{
                      $("#pln_id"+j).val(response['pln_id']);
                    }  
                      
                  },
                  error : function(xhr, status) {
                      alert('No existe Cuenta');
                      if(opc==0){
                        $("#pln_id").val('0');
                        $("#reg_codigo_cta").val('');
                      }else{
                        $("#pln_id"+j).val('0');
                        $("#reg_codigo_cta"+j).val('');
                      }
                        
                  }
              });
          }        

    function load_tipos(){
      uri=base_url+'reg_factura/traer_tipos/'+$('#pro_ids').val();
            $.ajax({
                    url: uri,
                    type: 'POST',
                    success: function(dt){
                       $('#mp_b').html(dt);
                       load_codigo();
                    } 
              });
       
    }

    function load_codigo(){
              var id1 =  $('#mp_a').val();
              var id2 =  $('#mp_b').val();

              if (id1 != 0 && id2 != 0) {
              $.ajax({
                    url: base_url+"reg_factura/traer_codigo/"+id1+"/"+id2,
                    type: 'POST',
                    success: function(dt){

                    dat = dt.split("&&");
                    if (dat[0] != 1) {
                        $('#codigo').val(dat[0]);
                        $('#codigo').attr('readonly', true);
                    } 

                }
              });

            }else{
              
                $('#codigo').val('');
            }
          }      
    </script>

