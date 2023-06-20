
<section class="content-header">
      <h1>
        Registro Nota de Credito <?php echo $titulo?>
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
          
          if($inven==0){
            $hid_inv='';
            $col_obs='9';
          }else{
            $hid_inv='hidden';
            $col_obs='8';
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
                      <td class="col-sm-12">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        <table class="table">
                          <tr>
                              <td><label>Fecha Registro:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('rnc_fec_registro')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnc_fec_registro" id="rnc_fec_registro" value="<?php if(validation_errors()!=''){ echo set_value('rnc_fec_registro');}else{ echo $nota->rnc_fec_registro;}?>">
                                  <?php echo form_error("rnc_fec_registro","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('rnc_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnc_fecha_emision" id="rnc_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('rnc_fecha_emision');}else{ echo $nota->rnc_fecha_emision;}?>">
                                  <?php echo form_error("rnc_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $nota->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo $emi_id;}?>">
                                <input type="hidden" class="form-control" name="reg_id" id="reg_id" value="<?php if(validation_errors()!=''){ echo set_value('reg_id');}else{ echo $nota->reg_id;}?>">
                                </div>
                              </td>
                          </tr>
                          <tr>
                              <td><label>Fecha Autorizacion:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('rnc_fec_autorizacion')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnc_fec_autorizacion" id="rnc_fec_autorizacion" value="<?php if(validation_errors()!=''){ echo set_value('rnc_fec_autorizacion');}else{ echo $nota->rnc_fec_autorizacion;}?>">
                                  <?php echo form_error("rnc_fec_autorizacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha Caducidad:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('rnc_fec_caducidad')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rnc_fec_caducidad" id="rnc_fec_caducidad" value="<?php if(validation_errors()!=''){ echo set_value('rnc_fec_caducidad');}else{ echo $nota->rnc_fec_caducidad;}?>">
                                  <?php echo form_error("rnc_fec_caducidad","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          <tr>
                              <td><label>Nota Credito No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnc_numero')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control documento" name="rnc_numero" id="rnc_numero" value="<?php if(validation_errors()!=''){ echo set_value('rnc_numero');}else{ echo $nota->rnc_numero;}?>"  maxlength="17" onchange="num_factura(this,1)">
                                  <?php echo form_error("rnc_numero","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Autorizacion No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnc_autorizacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control numerico" name="rnc_autorizacion" id="rnc_autorizacion" value="<?php if(validation_errors()!=''){ echo set_value('rnc_autorizacion');}else{ echo $nota->rnc_autorizacion;}?>" onchange="validar_autorizacion()">
                                  <?php echo form_error("rnc_autorizacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>    
                          <tr>
                              <td><label>Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnc_num_comp_modifica')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control documento" name="rnc_num_comp_modifica" id="rnc_num_comp_modifica" value="<?php if(validation_errors()!=''){ echo set_value('rnc_num_comp_modifica');}else{ echo $nota->rnc_num_comp_modifica;}?>" onchange="num_factura(this,0)" maxlength="17">
                                  <?php echo form_error("rnc_num_comp_modifica","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha Factura:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnc_fecha_emi_comp')!=''){ echo 'has-error';}?> ">
                                  <input type="date" class="form-control" name="rnc_fecha_emi_comp" id="rnc_fecha_emi_comp" value="<?php if(validation_errors()!=''){ echo set_value('rnc_fecha_emi_comp');}else{ echo $nota->rnc_fecha_emi_comp;}?>" readonly>
                                  <?php echo form_error("rnc_fecha_emi_comp","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          <tr>    
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $nota->rnc_identificacion;}?>" list="list_clientes" onchange="traer_cliente(this)" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $nota->rnc_nombre;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $nota->cli_id;}?>" >
                              </td>
                          </tr>
                          
                            <tr>
                              <td><label>Transaccion:</label></td>
                              <td >
                                <div class="form-group">
                                  <select name="trs_id"  id="trs_id" class="form-control">
                                    <option value="6">DEVOLUCION DE COMPRA</option>
                                    <option value="7">ANULACION DE COMPRA</option>
                                    <option value="1">VARIOS</option>
                                  </select>
                                  <script type="text/javascript">
                                    var trs='<?php echo $nota->trs_id;?>';
                                    trs_id.value=trs;
                                  </script>
                                  </div>
                              </td> 
                              <td><label>Motivo:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('rnc_motivo')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="rnc_motivo" id="rnc_motivo" value="<?php if(validation_errors()!=''){ echo set_value('rnc_motivo');}else{ echo $nota->rnc_motivo;}?>">
                                  <?php echo form_error("rnc_motivo","<span class='help-block'>","</span>");?>
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
                                <th>Cant.Fact.</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Desc.%</th>
                                <th>Desc.$</th>
                                <th>IVA</th>
                                <th>Val.Total</th>
                                <th></th>
                                <th></th>
                              </tr>
                            </thead>

                            <tbody id="lista_encabezado">
                            
                              <?php
                              
                              if($nota->reg_id==0){
                                $cnt_detalle=0;
                                    
                                    
                                  ?>
                                    <tr>
                                        <td colspan="2">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="pro_descripcion" name="pro_descripcion"  value="" lang="1"   maxlength="16" list="productos" onchange="load_producto(this.lang)"  readonly/>
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
                                          <input type ="text" size="7" id="inventario" name="inventario" value="" lang="1" readonly class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidadf" name="cantidadf"  value="" lang="1" onchange="calculo_encabezado(this), costo(this)" class="form-control decimal" readonly />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidad" name="cantidad"  value="" lang="1" onchange="calculo_encabezado(this), costo(this)" class="form-control decimal" readonly/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="pro_precio" name="pro_precio" onchange="calculo_encabezado(this)" value="" lang="1" class="form-control decimal" readonly />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuento" name="descuento"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal" readonly/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuent" name="descuent"  value="" lang="1" readonly  class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type="text" id="iva" style="text-align:right" name="iva" size="5" value="" readonly class="form-control decimal"/>
                                        </td>
                                        <td>
                                            <input type ="text" size="9" style="text-align:right" id="valor_total" name="valor_total" value="" lang="1" readonly class="form-control decimal" />
                                            
                                        </td>
                                        
                                    </tr>
                              <?php 
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
                                            <td id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center"><?PHP echo $n ?></td>
                                            <td id="pro_descripcion<?PHP echo $n ?>" name="pro_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_codigo ?></td>
                                            <td id="pro_referencia<?PHP echo $n ?>" name="pro_referencia<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->pro_descripcion ?>
                                                <input type="hidden" size="7" id="pro_ids<?PHP echo $n ?>" name="pro_ids<?PHP echo $n ?>" value="<?php echo $rst_det->ids ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="mov_cost_unit<?PHP echo $n ?>" name="mov_cost_unit<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cost_unit, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="mov_cost_tot<?PHP echo $n ?>" name="mov_cost_tot<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($cost_tot, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->pro_unidad ?></td>
                                            <td id="inventario<?PHP echo $n ?>" name="inventario<?PHP echo $n ?>" lang="<?PHP echo $n ?>" <?php echo $hid_inv?>  style="text-align:right"><?php echo str_replace(',', '', number_format($rst_det->inventario, $dcc)) ?></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidadf' . $n ?>" name="<?php echo 'cantidadf' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidadf, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), costo_det(this)" readonly/></td>
                                            <td hidden><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidadn' . $n ?>" name="<?php echo 'cantidadn' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidadn, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), costo_det(this)" readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidad' . $n ?>" name="<?php echo 'cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), validar_cantfactura(this), costo_det(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  /></td>
                                            
                                            <td><input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'pro_precio' . $n ?>" name="<?php echo 'pro_precio' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->pro_precio, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" /></td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuento' . $n ?>" name="<?php echo 'descuento' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuento, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" />
                                            </td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'descuent' . $n ?>" name="<?php echo 'descuent' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->pro_descuent, $dec)) ?>" lang="<?PHP echo $n ?>"  readonly/>
                                            </td>
                                            <td><input type="text" id="<?php echo 'iva' . $n ?>" name="<?php echo 'iva' . $n ?>" size="5" style="text-align:right" class="form-control" value="<?php echo $rst_det->pro_iva ?>" lang="<?PHP echo $n ?>" readonly /></td>
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

                                    <td valign="top" rowspan="11" colspan="<?php echo $col_obs?>">
                                    </td>    
                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($nota->rnc_subtotal12, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($nota->rnc_subtotal0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($nota->rnc_subtotal_ex_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($nota->rnc_subtotal_no_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($nota->rnc_subtotal, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Descuento:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_descuento" name="total_descuento" value="<?php echo str_replace(',', '', number_format($nota->rnc_total_descuento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total ICE:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_ice" name="total_ice" value="<?php echo str_replace(',', '', number_format($nota->rnc_total_ice, $dec)) ?>"  readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_iva" name="total_iva" value="<?php echo str_replace(',', '', number_format($nota->rnc_total_iva, $dec)) ?>" readonly />
                                    </td>
                                </tr> 
                                <tr>
                                    <td colspan="2" align="right">Total Valor:</td>
                                    <td><input style="text-align:right;font-size:15px;color:red  " type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($nota->nrc_total_valor, $dec)) ?>" readonly />
                                        
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
                                
                <input type="hidden" class="form-control" name="rnc_id" value="<?php echo $nota->rnc_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
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
    <!-- ////modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Facturas</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped">
              <thead>
                  <th>Seleccione</th>
                  <th>Fecha</th>
                  <th>Tipo</th>
                  <th>Numero</th>
                  <th>CI/RUC</th>
                  <th>Cliente</th>
              </thead>
              <tbody id="det_ventas"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>


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

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#identificacion').val().length == 0) {
                            alert('Ingrese dato');
                            $('#identificacion').focus();
                            $('#identificacion').val('');
                            $('#cli_id').val('0');
                            $('#nombre').val('');
                            $('#telefono_cliente').val('');
                            $('#direccion_cliente').val('');
                            $('#email_cliente').val('');
                            return false;
                      }
                    },
                    url: base_url+"nota_credito/traer_cliente/"+identificacion.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#cli_id').val(dt.cli_id);
                          $('#nombre').val(dt.cli_raz_social);
                          $('#telefono_cliente').val(dt.cli_telefono);
                          $('#direccion_cliente').val(dt.cli_calle_prin);
                          $('#email_cliente').val(dt.cli_email);
                          $('#identificacion').val(dt.cli_ced_ruc);
                        }else{
                          alert('Cliente no existe');
                          $('#identificacion').focus();
                          $('#identificacion').val('');
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#email_cliente').val('');
                        } 
                        
                    },
                    error : function(xhr, status) {
                          alert('Cliente no existe');

                          $('#identificacion').focus();
                          $('#identificacion').val('');
                          $('#cli_id').val('0');
                          $('#nombre').val('');
                          $('#telefono_cliente').val('');
                          $('#direccion_cliente').val('');
                          $('#email_cliente').val('');
                    }
                    });    
            }

            function limpiar_nota(){
                    $('#reg_id').val('0');
                    $('#rnc_num_comp_modifica').val('');
                    $('#identificacion').val('');
                    $('#nombre').val('');
                    $('#lista').html('');
                    $('#cli_id').val('');
                    $('#rnc_fecha_emi_comp').val('');
                    a = '"';
                    var tr = "<tr>"+
                                        "<td colspan='2'>"+
                                            "<input style='text-align:left ' type='text' style='width:  150px;' class='form-control' id='pro_descripcion' name='pro_descripcion'  value='' lang='1'   maxlength='16' list='productos' onchange='load_producto(this.lang)'  />"+
                                        "</td>"+
                                        "<td>"+
                                            "<input style='text-align:left ' type ='text' size='40' class='refer form-control'  id='pro_referencia' name='pro_referencia'   value='' lang='1' readonly style='width:300px;' />"+
                                            "<input type='hidden'  id='pro_aux' name='pro_aux' lang='1'/>"+
                                            "<input type='hidden'  id='pro_ids' name='pro_ids' lang='1'/>"+
                                            "<input type='hidden'  id='mov_cost_unit' name='mov_cost_unit'  lang='1'/>"+
                                            "<input type='hidden'  id='mov_cost_tot' name='mov_cost_tot' lang='1'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' id='unidad' name='unidad'  value='' lang='1' readonly class='form-control' />"+
                                        "</td>"+
                                        "<td <?php echo $hid_inv?>>"+
                                          "<input type ='text' size='7' id='inventario' name='inventario' value='' lang='1' readonly class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidadf' name='cantidadf'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidad' name='cantidad'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio' name='pro_precio' onchange='calculo_encabezado(this)' value='' lang='1' class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuento' name='descuento'  value='' lang='1' onchange='calculo_encabezado(this)' class='form-control decimal'"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuent' name='descuent'  value='' lang='1' readonly  class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='iva' style='text-align:right' name='iva' size='5' value='' readonly class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td>"+
                                            "<input type ='text' size='9' style='text-align:right' id='valor_total' name='valor_total' value='' lang='1' readonly class='form-control decimal' />"+
                                        "</td>"+
                                        "<td align='center' ><input  type='button' name='add1' id='add1' class='btn btn-primary fa fa-plus' onclick='validar("+a+"#tbl_detalle"+a+",0)' lang='1' value='+'/> </td>"+
                                    "</tr>";
                    $('#lista_encabezado').html(tr);
                    $('#propina').val(parseFloat('0').toFixed(dec));
                    $('#subtotal12').val(parseFloat('0').toFixed(dec));
                    $('#subtotal0').val(parseFloat('0').toFixed(dec));
                    $('#subtotalno').val(parseFloat('0').toFixed(dec));
                    $('#subtotalex').val(parseFloat('0').toFixed(dec));
                    $('#subtotal').val(parseFloat('0').toFixed(dec));
                    $('#total_descuento').val(parseFloat('0').toFixed(dec));
                    $('#total_ice').val(parseFloat('0').toFixed(dec));
                    $('#total_iva').val(parseFloat('0').toFixed(dec));
                    $('#total_valor').val(parseFloat('0').toFixed(dec));

            }

            function num_factura(obj,op) {
                nfac = obj.value;
                dt = nfac.split('-');
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('reg_id').val('0');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    alert('No cumple con la estructura ejem: 000-000-000000000');
                    limpiar_nota();                    
                } else {
                  if(op==0){
                    traer_facturas(obj);
                  }else{
                    doc_duplicado();
                  }
                }
            }

            function traer_facturas(obj) {
              
              $.ajax({
                  beforeSend: function () {
                      if ($('#rnc_num_comp_modifica').val().length == 0) {
                            alert('Ingrese una factura');
                            return false;
                      }
                    },
                  url: base_url+"reg_nota_credito/traer_facturas/"+$('#rnc_num_comp_modifica').val()+"/"+emp_id.value,
                  type: 'JSON',
                  dataType: 'JSON',
                  success: function (dt) { 
                    i=dt.length;
                    if(i>0){
                        n=0;
                        var tr="";
                        while(n<i){
                            tr+="<tr>"+
                                "<td><input type='checkbox' onclick='load_factura("+dt[n]['reg_id']+")'></td>"+
                                "<td>"+dt[n]['reg_femision']+"</td>"+
                                "<td>"+dt[n]['tdc_descripcion']+"</td>"+
                                "<td>"+dt[n]['reg_num_documento']+"</td>"+
                                "<td>"+dt[n]['cli_ced_ruc']+"</td>"+
                                "<td>"+dt[n]['cli_raz_social']+"</td>"+
                                "</tr>";
                                n++;
                        }
                        $('#det_ventas').html(tr);
                        $("#myModal").modal();
                    }else{
                        alert('Numero no existe en Registro de Facturas');
                        limpiar_nota();
                    }
                  }
                })
            }        

            function load_factura(vl) {
              $("#myModal").modal('hide');
              
              $.ajax({
                  beforeSend: function () {
                      
                    },
                    url: base_url+"reg_nota_credito/load_factura/"+vl+"/"+inven+"/"+ctr_inv+"/"+dec+"/"+dcc+"/"+emi_id.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                            if (dt.length != '0') {
                                $('#reg_id').val(dt.reg_id);
                                $('#rnc_fecha_emi_comp').val(dt.fac_fecha_emision);
                                $('#identificacion').val(dt.cli_ced_ruc);
                                $('#nombre').val(dt.cli_raz_social);
                                $('#cli_id').val(dt.cli_id);
                                $('#subtotal12').val(parseFloat(dt.fac_subtotal12).toFixed(dec));
                                $('#subtotal0').val(parseFloat(dt.fac_subtotal0).toFixed(dec));
                                $('#subtotalno').val(parseFloat(dt.fac_subtotal_no_iva).toFixed(dec));
                                $('#subtotalex').val(parseFloat(dt.fac_subtotal_ex_iva).toFixed(dec));
                                $('#subtotal').val(parseFloat(dt.fac_subtotal).toFixed(dec));
                                $('#total_descuento').val(parseFloat(dt.fac_total_descuento).toFixed(dec));
                                $('#total_ice').val(parseFloat(dt.fac_total_ice).toFixed(dec));
                                $('#total_iva').val(parseFloat(dt.fac_total_iva).toFixed(dec));
                                $('#total_valor').val(parseFloat(dt.fac_total_valor).toFixed(dec));
                                $('#subtotal').val(parseFloat(dt.fac_subtotal).toFixed(dec));
                                $('#lista').html(dt.detalle);
                                $('#count_detalle').val(dt.cnt_detalle);
                                $('#lista_encabezado').html('');
                                doc_duplicado();
                                validar_inventario_det();
                            } else {
                                limpiar_nota();
                            }
                        }
                })
                
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
                    url: base_url+"nota_credito/load_producto/"+vl+"/"+inven+"/"+ctr_inv+"/"+emi_id.value,
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
                        $('#cantidad').val('');
                        $('#cantidadf').val('0');
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
                        if (inven == 0) {
                            if (dt.cost_unit == '') {
                                $('#mov_cost_unit').val('0');
                            } else {
                                $('#mov_cost_unit').val(parseFloat(dt.cost_unit).toFixed(dec));
                            }
                            if (dt.inventario == '') {
                                $('#inventario').val('0');
                            } else {
                                $('#inventario').val(parseFloat(dt.inventario).toFixed(dcc));
                            }
                        }else{
                          $('#mov_cost_unit').val('0');
                          $('#inventario').val('0');
                        }

                        $('#cantidad').focus();
                      }else{
                        $('#pro_descripcion').val('');
                        $('#pro_referencia').val('');
                        $('#cantidad').val('');
                        $('#cantidadf').val('');
                        $('#iva').val('0');
                        $('#pro_aux').val('');
                        $('#pro_ids').val('');
                        $('#pro_precio').val('0');
                        $('#descuento').val('0');
                        if (inven == 0) {
                            $('#mov_cost_unit').val('0');
                            $('#inventario').val('0');
                        }
                        $('#pro_descripcion').focus();
                      }
                      calculo('1');
                    }
                  });
                
              }

            function validar(table, opc){
              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              
                if($('#cantidad').val().length!=0 &&  parseFloat($('#cantidad').val())>0 && $('#pro_precio').val().length!=0 &&  parseFloat($('#pro_precio').val())>0 && $('#descuento').val().length!=0 && $('#pro_descripcion').val().length!=0){
                  clona_detalle(table);
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
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidadf"+i+"' name='cantidadf"+i+"' lang='"+i+"' value='"+cantidadf.value +"' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' lang='"+i+"' onchange='validar_inventario_det()'  value='"+cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
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
                                          "<input type ='text' size='7'  style='text-align:right' id='iva"+i+"' name='iva"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+$('#iva').val()+"' />"+
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
                cantidadf.value = '';
                pro_precio.value = '';
                iva.value = '';
                descuento.value = '';
                descuent.value = '';
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

                while (n < i) {
                    n++;
                   
                        uni = $('#mov_cost_unit').val();
                        cnt = $('#cantidad').val().replace(',', '');
                        if(cnt==''){
                          cnt=0;
                        }
                        pr = $('#pro_precio').val().replace(',', '');
                        d = $('#descuento').val().replace(',', '');
                        vtp = round(cnt,dcc) * round(pr,2); //Valor total parcial
                        vt = (vtp * 1) - (vtp * round(d,dec) / 100);
                        // ic = $('#ice_p').val().replace(',', '');
                        // pic = (round(vt,dec) * round(ic,dec)) / 100;
                        // if(pic.toFixed(2)=='NaN'){
                        //   pic=0;
                        // }
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
                        // ic = $('#ice_p' + n).val().replace(',', '');
                        // pic = (round(vt,dec) * round(ic,dec)) / 100;
                        // if(pic.toFixed(2)=='NaN'){
                          pic=0;
                        // }
                        dsc= (round(vtp,dec) * round(d,dec)) / 100; 
                        if(dsc.toFixed(2)=='NaN'){
                          dsc=0;
                        }  
                        $('#descuent' + n).val(dsc.toFixed(dec));
                        $('#valor_total' + n).val(vt.toFixed(dec));
                        ob = $('#iva' + n).val();
                        val = $('#valor_total' + n).val().replace(',', '');
                        d = $('#descuent' + n).val().replace(',', '');
                        // $('#ice' + n).val(pic.toFixed(dec));
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
                // prop = $('#total_propina').val().replace(',', '');
                // gtot = (round(sub,dec) * 1 + round(tiva,dec) * 1 + round(tice,dec) * 1 + round(prop,dec) * 1);
                gtot = (round(sub,dec) * 1 + round(tiva,dec) * 1 + round(tice,dec) * 1);
                 
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

            function validar_cantfactura(obj){
                        if(reg_id.value!=0){
                          n=obj.lang;
                          cnt=parseFloat($('#cantidad'+n).val())+parseFloat($('#cantidadn'+n).val());
                          if (parseFloat($('#cantidadf'+n).val()) < cnt) {
                            alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE LA CANTIDAD DE LA FACTURA');
                            $('#cantidad'+n).val('');
                            $('#cantidad'+n).focus();
                            $('#cantidad'+n).css({borderColor: "red"});
                          }else{
                            $('#cantidad'+n).css({borderColor: ""});
                          }
                        }
                        validar_inventario_det();
            }

            function save() {
                        if (rnc_fec_registro.value.length == 0) {
                            $("#rnc_fec_registro").css({borderColor: "red"});
                            $("#rnc_fec_registro").focus();
                            return false;
                        } else if (rnc_fecha_emision.value.length == 0) {
                            $("#rnc_fecha_emision").css({borderColor: "red"});
                            $("#rnc_fecha_emision").focus();
                            return false;
                        } else if (rnc_fec_autorizacion.value.length == 0) {
                            $("#rnc_fec_autorizacion").css({borderColor: "red"});
                            $("#rnc_fec_autorizacion").focus();
                            return false;
                        } else if (rnc_fec_caducidad.value.length == 0) {
                            $("#rnc_fec_caducidad").css({borderColor: "red"});
                            $("#rnc_fec_caducidad").focus();
                            return false;
                        } else if (rnc_numero.value.length == 0) {
                            $("#rnc_numero").css({borderColor: "red"});
                            $("#rnc_numero").focus();
                            return false;
                        } else if (rnc_autorizacion.value.length == 0) {
                            $("#rnc_autorizacion").css({borderColor: "red"});
                            $("#rnc_autorizacion").focus();
                            return false;
                        } else if (rnc_num_comp_modifica.value.length == 0) {
                            $("#rnc_num_comp_modifica").css({borderColor: "red"});
                            $("#rnc_num_comp_modifica").focus();
                            return false;
                        } else if (rnc_fecha_emi_comp.value.length == 0) {
                            $("#rnc_fecha_emi_comp").css({borderColor: "red"});
                            $("#rnc_fecha_emi_comp").focus();
                            return false;
                        } else if (identificacion.value.length == 0) {
                            $("#identificacion").css({borderColor: "red"});
                            $("#identificacion").focus();
                            return false;
                        } else if (rnc_motivo.value.length == 0) {
                            $("#rnc_motivo").css({borderColor: "red"});
                            $("#rnc_motivo").focus();
                            return false;
                        }
                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        k = 0;
                        if(a==null){
                          alert("Ingrese Detalle");
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;
                                if ($('#pro_descripcion' + n).html() != null && parseFloat($('#cantidad' + n).val())>0) {
                                  k++;
                                    if ($('#pro_descripcion' + n).html() == 0) {
                                        $('#pro_descripcion' + n).css({borderColor: "red"});
                                        $('#pro_descripcion' + n).focus();
                                        return false;
                                    } else if ($('#cantidad' + n).val().length == 0) {
                                        $('#cantidad' + n).css({borderColor: "red"});
                                        $('#cantidad' + n).focus();
                                        return false;
                                    } else if ($('#descuento' + n).val().length == 0) {
                                        $('#descuento' + n).css({borderColor: "red"});
                                        $('#descuento' + n).focus();
                                        return false;
                                    } else if ($('#pro_precio' + n).val().length == 0 || $('#pro_precio' + n).val() == 0) {
                                        $('#pro_precio' + n).css({borderColor: "red"});
                                        $('#pro_precio' + n).focus();
                                        return false;
                                    }

                                }
                            }
                        }

                        if(k==0){
                          alert('No se puede Guardar Nota de Credito con cantidades en 0');
                          return false;
                        }
                        
                        
                        
                     $('#frm_save').submit();   
               } 

              function validar_autorizacion(){
                var aut = $('#rnc_autorizacion').val();
                if(aut.length!=10 && aut.length!=37 && aut.length!=49 ){
                  alert('El numero de autorizacion debe ser de 10, 37 o 49 digitos');
                  $('#rnc_autorizacion').val('');
                }
              }  

              function doc_duplicado(){
              num_doc = $('#rnc_numero').val();
              if (num_doc.length = 17 && cli_id.value.length > 0) {
                $.ajax({
                      beforeSend: function () {
                      },
                      url: base_url+"reg_nota_credito/doc_duplicado/"+cli_id.value+"/"+num_doc,
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                          if(dt!=""){
                            alert('EL numero de Documento y el RUC/CI del Proveedor \n Ya existen en el Registro de Nota de Credito');   
                            $('#rnc_numero').val('');
                          }
                          calculo(); 
                      }
                    });
              }          
            }

            function validar_inventario_det() {
                
                    var tr = $('#lista').find("tr:last");
                    a = tr.find("input").attr("lang");
                    i = parseInt(a);
                    n = 0;
                    while (n < i) {
                        n++;
                        if($('trs_id').val()!='1' && inven==0 && ($('#pro_ids'+n).val()=='26' || $('#pro_ids'+n).val()=='69') ){
                          if($('#cantidad'+n).val()!=null){
                            if (parseFloat($('#inventario'+n).html()) < parseFloat($('#cantidad'+n).val())) {
                                alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                                $('#cantidad'+n).val('');
                                $('#cantidad'+n).focus();
                                $('#cantidad'+n).css({borderColor: "red"});
                                
                            }
                          }
                        }      
                    }  
                
                calculo();
            }
    </script>

