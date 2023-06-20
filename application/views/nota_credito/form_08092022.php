
<section class="content-header">
      <h1>
        Nota de Credito <?php echo $titulo?>
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
                        <div class="panel panel-heading"><label>Datos Generales</label></div>
                        <table class="table">
                          <tr>
                               <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('ncr_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="ncr_fecha_emision" id="ncr_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('ncr_fecha_emision');}else{ echo  $nota->ncr_fecha_emision;}?>">
                                  <?php echo form_error("ncr_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo  $nota->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo   $nota->emi_id;}?>">
                                <input type="hidden" class="form-control" name="cja_id" id="cja_id" value="<?php if(validation_errors()!=''){ echo set_value('cja_id');}else{ echo  $nota->cja_id;}?>">
                                <input type="hidden" class="form-control" name="fac_id" id="fac_id" value="<?php if(validation_errors()!=''){ echo set_value('fac_id');}else{ echo  $nota->fac_id;}?>">
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
                                    var vnd='<?php echo $nota->vnd_id;?>';
                                    vnd_id.value=vnd;
                                  </script>
                                </div>
                              </td>    
                          </tr>
                          <tr>
                              <td><label>Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ncr_num_comp_modifica')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ncr_num_comp_modifica" id="ncr_num_comp_modifica" value="<?php if(validation_errors()!=''){ echo set_value('ncr_num_comp_modifica') ;}else{ echo  $nota->ncr_num_comp_modifica;}?>" onchange="num_factura(this)" maxlength="17">
                                  <?php echo form_error("ncr_num_comp_modifica","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Fecha Factura:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ncr_fecha_emi_comp')!=''){ echo 'has-error';}?> ">
                                  <input type="date" class="form-control" name="ncr_fecha_emi_comp" id="ncr_fecha_emi_comp" value="<?php if(validation_errors()!=''){ echo set_value('ncr_fecha_emi_comp');}else{ echo   $nota->ncr_fecha_emi_comp;}?>" readonly>
                                  <?php echo form_error("ncr_fecha_emi_comp","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          <tr>    
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $nota->ncr_identificacion;}?>" list="list_clientes" onchange="traer_cliente(this)" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $nota->ncr_nombre;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $nota->cli_id;}?>" >
                              </td>
                          </tr>
                          <tr>
                            <td><label>Direccion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('direccion_cliente')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" value="<?php if(validation_errors()!=''){ echo set_value('direccion_cliente');}else{ echo $nota->ncr_direccion;}?>" readonly>
                                <?php echo form_error("direccion_cliente","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Telefono:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" value="<?php if(form_error('telefono_cliente')){ echo set_value('telefono_cliente');}else{ echo $nota->nrc_telefono;}?>" readonly>
                                      <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                  
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Email:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('email_cliente');}else{ echo $nota->ncr_email;}?>" readonly>
                                  <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
                                  </div>
                              </td> 
                            </tr>
                            <tr>
                              <td><label>Transaccion:</label></td>
                              <td >
                                <div class="form-group">
                                  <select name="trs_id"  id="trs_id" class="form-control" onchange="ocultar(),cam_motivo()">
                                    <option value="0">SELECCIONE</option>
                                    <option value="12">DEVOLUCION DE PRODUCTO</option>
                                    <option value="13">ANULACION DE FACTURA</option>
                                    <option value="1">DESCUENTO A VENTAS</option>
                                  </select>
                                  <script type="text/javascript">
                                    var trs='<?php echo $nota->trs_id;?>';
                                    trs_id.value=trs;
                                  </script>
                                  </div>
                              </td> 
                              <td><label>Motivo:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ncr_motivo')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="ncr_motivo" id="ncr_motivo" value="<?php if(validation_errors()!=''){ echo set_value('ncr_motivo');}else{ echo $nota->ncr_motivo;}?>">
                                  <?php echo form_error("ncr_motivo","<span class='help-block'>","</span>");?>
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
                                <th class="td1">Codigo</th>
                                <th>Descripcion</th>
                                <th class="td1">Unidad</th>
                                
                                <th class="td1">Cant.Fact.</th>
                                <th>Cantidad</th>
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

                            <tbody id="lista_encabezado">
                            
                              <?php
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
                              if($nota->fac_id==0){
                                $cnt_detalle=0;
                                    
                                    
                                  ?>
                                    <tr>
                                        <td colspan="2" class="td1">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="pro_descripcion" name="pro_descripcion"  value="" lang="1"   maxlength="16" list="productos" onchange="load_producto(this.lang)"  />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_referencia" name="pro_referencia"   value="" lang="1" readonly style="width:300px;" />
                                            <input type="hidden"  id="pro_aux" name="pro_aux" lang="1"/>
                                            <input type="hidden"  id="pro_ids" name="pro_ids" lang="1"/>
                                            <input type="hidden"  id="mov_cost_unit" name="mov_cost_unit"  lang="1"/>
                                            <input type="hidden"  id="mov_cost_tot" name="mov_cost_tot" lang="1"/>
                                        </td>
                                        <td class="td1">
                                          <input type ="text" size="7" id="unidad" name="unidad"  value="" lang="1" readonly class="form-control" />
                                        </td>
                                        
                                        <td class="td1">
                                          <input type ="text" size="7"  style="text-align:right" id="cantidadf" name="cantidadf"  value="" lang="1" onchange="calculo_encabezado(this), costo(this)" class="form-control decimal" readonly />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="cantidad" name="cantidad"  value="" lang="1" onchange="calculo_encabezado(this), costo(this)" class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="pro_precio" name="pro_precio" onchange="calculo_encabezado(this)" value="0" lang="1" class="form-control decimal" <?php echo $r_precio?>/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuento" name="descuento"  value="0" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal" <?php echo $r_descuento?>/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="descuent" name="descuent"  value="0" lang="1" readonly  class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type="text" id="iva" style="text-align:right" name="iva" size="5" value="0" readonly class="form-control decimal"/>
                                        </td>
                                        <td hidden><input type="text" name="ice_p" id="ice_p" size="5" value="0" readonly lang="1" /></td>
                                        <td hidden><input type="text" name="ice" id="ice" size="5" value="0" readonly lang="1"/>
                                            <input type=""  name="ice_cod" id="ice_cod" size="5" value="0" readonly lang="1"/>
                                        </td>
                                        <td>
                                            <input type ="text" size="9" style="text-align:right" id="valor_total" name="valor_total" value="" lang="1" readonly class="form-control decimal" />
                                            
                                        </td>
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>

                                        <!-- <td align="center" ><span name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1"> </span></td>
                                        <td onclick="elimina_fila(this, '#tbl_detalle','0')" align="center" ><span class="btn btn-danger fa fa-trash"></span></td> -->
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
                                            <td class="td1">
                                              <input type ="text" size="10" class="form-control" id="<?php echo 'pro_descripcion' . $n ?>" name="<?php echo 'pro_descripcion' . $n ?>" value="<?php echo $rst_det->pro_codigo ?>" lang="<?PHP echo $n ?>" readonly/>
                                            </td>
                                            <td>
                                              <input type ="text" size="10" class="form-control" id="<?php echo 'pro_referencia' . $n ?>" name="<?php echo 'pro_referencia' . $n ?>" value="<?php echo $rst_det->pro_descripcion ?>" lang="<?PHP echo $n ?>" readonly/>
                                                <input type="hidden" size="7" id="pro_aux<?PHP echo $n ?>" name="pro_aux<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="mov_cost_unit<?PHP echo $n ?>" name="mov_cost_unit<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cost_unit, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                                <input type="hidden" size="7" id="mov_cost_tot<?PHP echo $n ?>" name="mov_cost_tot<?PHP echo $n ?>" value="<?php echo str_replace(',', '', number_format($cost_tot, $dec)) ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td class="td1" id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->pro_unidad ?></td>
                                           
                                            <td class="td1"><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidadf' . $n ?>" name="<?php echo 'cantidadf' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidadf, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), costo_det(this)" readonly/></td>
                                            <td hidden><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidadn' . $n ?>" name="<?php echo 'cantidadn' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidadn, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), costo_det(this)" readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'cantidad' . $n ?>" name="<?php echo 'cantidad' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this), validar_cantfactura(this), costo_det(this)" onkeyup="this.value = this.value.replace(/[^0-9.]/, '')"  /></td>
                                            
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

                                    <td id="valores" valign="top" rowspan="11" colspan="<?php echo $col_obs?>">
                                    </td>    
                                    <td colspan="2" align="right">Subtotal 12%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal12" name="subtotal12" value="<?php echo str_replace(',', '', number_format($nota->ncr_subtotal12, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr>
                                  <?php 
                                  $total0=$nota->ncr_subtotal0+$nota->ncr_subtotal_ex_iva+$nota->ncr_subtotal_no_iva;
                                  ?>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal_1" name="subtotal_1" value="<?php echo str_replace(',', '', number_format($total0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>

                                <tr hidden>
                                    <td colspan="2" align="right">Subtotal 0%:</td>
                                    <td>
                                        <input style="text-align:right" type="text" class="form-control" id="subtotal0" name="subtotal0" value="<?php echo str_replace(',', '', number_format($nota->ncr_subtotal0, $dec)) ?>" readonly/>
                                        
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Subtotal Excento de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalex" name="subtotalex" value="<?php echo str_replace(',', '', number_format($nota->ncr_subtotal_ex_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Subtotal no objeto de Iva:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotalno" name="subtotalno" value="<?php echo str_replace(',', '', number_format($nota->ncr_subtotal_no_iva, $dec)) ?>" readonly/>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="2" align="right">Subtotal sin Impuestos:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="subtotal" name="subtotal" value="<?php echo str_replace(',', '', number_format($nota->ncr_subtotal, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total Descuento:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_descuento" name="total_descuento" value="<?php echo str_replace(',', '', number_format($nota->ncr_total_descuento, $dec)) ?>" readonly/>
                                    </td>
                                </tr>
                                <tr hidden>
                                    <td colspan="2" align="right">Total ICE:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_ice" name="total_ice" value="<?php echo str_replace(',', '', number_format($nota->ncr_total_ice, $dec)) ?>"  readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Total IVA:</td>
                                    <td><input style="text-align:right" type="text" class="form-control" id="total_iva" name="total_iva" value="<?php echo str_replace(',', '', number_format($nota->ncr_total_iva, $dec)) ?>" readonly />
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
                                
                <input type="hidden" class="form-control" name="ncr_id" value="<?php echo $nota->ncr_id?>">
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
                    $('#fac_id').val('0');
                    $('#identificacion').val('');
                    $('#nombre').val('');
                    $('#direccion_cliente').val('');
                    $('#telefono_cliente').val('');
                    $('#email_cliente').val('');
                    $('#lista').html('');
                    $('#cli_id').val('');
                    $('#ncr_fecha_emi_comp').attr('readonly', false);
                    $('#ncr_fecha_emi_comp').val('');
                    $('#identificacion').attr('readonly', false);
                    $('#nombre').attr('readonly', true);
                    $('#direccion_cliente').attr('readonly', true);
                    $('#telefono_cliente').attr('readonly', true);
                    $('#email_cliente').attr('readonly', true);
                    a = '"';
                    var tr = "<tr>"+
                                        "<td colspan='2' class='td1'>"+
                                            "<input style='text-align:left ' type='text' style='width:  150px;' class='form-control' id='pro_descripcion' name='pro_descripcion'  value='' lang='1'   maxlength='16' list='productos' onchange='load_producto(this.lang)'  />"+
                                        "</td>"+
                                        "<td>"+
                                            "<input style='text-align:left ' type ='text' size='40' class='refer form-control'  id='pro_referencia' name='pro_referencia'   value='' lang='1' style='width:300px;' />"+
                                            "<input type='hidden'  id='pro_aux' name='pro_aux' lang='1'/>"+
                                            "<input type='hidden'  id='pro_ids' name='pro_ids' lang='1'/>"+
                                            "<input type='hidden'  id='mov_cost_unit' name='mov_cost_unit'  lang='1'/>"+
                                            "<input type='hidden'  id='mov_cost_tot' name='mov_cost_tot' lang='1'/>"+
                                        "</td>"+
                                        "<td class='td1'>"+
                                          "<input type ='text' size='7' id='unidad' name='unidad'  value='' lang='1' readonly class='form-control' />"+
                                        "</td>"+
                                        
                                        "<td class='td1'>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidadf' name='cantidadf'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidad' name='cantidad'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio' name='pro_precio' onchange='calculo_encabezado(this)' value='' lang='1' class='form-control decimal' <?php echo $r_precio?>/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuento' name='descuento'  value='' lang='1' onchange='calculo_encabezado(this)' class='form-control decimal' <?php echo $r_descuento?>/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuent' name='descuent'  value='' lang='1' readonly  class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='iva' style='text-align:right' name='iva' size='5' value='' readonly class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td hidden><input type='text' name='ice_p' id='ice_p' size='5' value='0' readonly lang='1' /></td>"+
                                        "<td hidden><input type='text' name='ice' id='ice' size='5' value='0' readonly lang='1'/>"+
                                            "<input type=''  name='ice_cod' id='ice_cod' size='5' value='0' readonly lang='1'/>"+
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
            function cam_motivo(){

         var combo = document.getElementById("trs_id");

         if($('#trs_id').val() != 0 ){
          var motivo = combo.options[combo.selectedIndex].text;
         $('#ncr_motivo').val(motivo);
         }else{
         $('#ncr_motivo').val('');

         }
       


      }

            function ocultar() {
                
                if ($('#trs_id').val() == '1') {
                  a = '"';
                  $('#lista_encabezado').html('');
                  $('#lista').html('');
                                

                    var tr = "<tr>"+
                                        "<td colspan='2' class='td1' hidden>"+
                                            "<input style='text-align:left ' type='text' style='width:  150px;' class='form-control' id='pro_descripcion' name='pro_descripcion'  value='0' lang='1'   maxlength='16' list='productos' onchange='load_producto(this.lang)'  />"+
                                        "</td>"+
                                        "<td colspan='2'>"+
                                            "<input style='text-align:left ' type ='text' size='40' class='refer form-control'  id='pro_referencia' name='pro_referencia'   value='' lang='1' style='width:300px;' />"+
                                            "<input type='hidden'  id='pro_aux' name='pro_aux' lang='1' value='0'/>"+
                                            "<input type='hidden'  id='pro_ids' name='pro_ids' lang='1' value='0'/>"+
                                            "<input type='hidden'  id='mov_cost_unit' name='mov_cost_unit'  lang='1' value='0'/>"+
                                            "<input type='hidden'  id='mov_cost_tot' name='mov_cost_tot' lang='1' value='0'/>"+
                                        "</td>"+
                                        "<td class='td1' hidden>"+
                                          "<input type ='text' size='7' id='unidad' name='unidad'  value='' lang='1' readonly class='form-control' />"+
                                        "</td>"+
                                        
                                        "<td class='td1' hidden>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidadf' name='cantidadf'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidad' name='cantidad'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio' name='pro_precio' onchange='calculo_encabezado(this)' value='' lang='1' class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuento' name='descuento'  value='' lang='1' onchange='calculo_encabezado(this)' class='form-control decimal' <?php echo $r_descuento?>/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuent' name='descuent'  value='' lang='1' readonly  class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='iva' style='text-align:right' name='iva' size='5' value='0' class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td hidden><input type='text' name='ice_p' id='ice_p' size='5' value='0' readonly lang='1' /></td>"+
                                        "<td hidden><input type='text' name='ice' id='ice' size='5' value='0' readonly lang='1'/>"+
                                            "<input type=''  name='ice_cod' id='ice_cod' size='5' value='0' readonly lang='1'/>"+
                                        "</td>"+
                                        "<td>"+
                                            "<input type ='text' size='9' style='text-align:right' id='valor_total' name='valor_total' value='' lang='1' readonly class='form-control decimal' />"+
                                        "</td>"+
                                        "<td align='center' ><input  type='button' name='add1' id='add1' class='btn btn-primary fa fa-plus' onclick='validar("+a+"#tbl_detalle"+a+",0)' lang='1' value='+'/> </td>"+
                                    "</tr>";
                    $('.td1').hide();  
                    $('#valores').attr('colspan');
                    var clp=$('#valores').attr('colspan')-3;
                    $('#valores').attr('colspan',clp);
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
                }else{
                  $('#lista_encabezado').html('');
                  $('#lista').html('');
                    a = '"';
                    var tr = "<tr>"+
                                        "<td colspan='2' class='td1'>"+
                                            "<input style='text-align:left ' type='text' style='width:  150px;' class='form-control' id='pro_descripcion' name='pro_descripcion'  value='' lang='1'   maxlength='16' list='productos' onchange='load_producto(this.lang)'  />"+
                                        "</td>"+
                                        "<td>"+
                                            "<input style='text-align:left ' type ='text' size='40' class='refer form-control'  id='pro_referencia' name='pro_referencia'   value='' lang='1' style='width:300px;' />"+
                                            "<input type='hidden'  id='pro_aux' name='pro_aux' lang='1'/>"+
                                            "<input type='hidden'  id='pro_ids' name='pro_ids' lang='1'/>"+
                                            "<input type='hidden'  id='mov_cost_unit' name='mov_cost_unit'  lang='1'/>"+
                                            "<input type='hidden'  id='mov_cost_tot' name='mov_cost_tot' lang='1'/>"+
                                        "</td>"+
                                        "<td class='td1'>"+
                                          "<input type ='text' size='7' id='unidad' name='unidad'  value='' lang='1' readonly class='form-control' />"+
                                        "</td>"+
                                        
                                        "<td class='td1'>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidadf' name='cantidadf'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='cantidad' name='cantidad'  value='' lang='1' onchange='calculo_encabezado(this), costo(this)' class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio' name='pro_precio' onchange='calculo_encabezado(this)' value='' lang='1' class='form-control decimal' <?php echo $r_precio?>/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuento' name='descuento'  value='' lang='1' onchange='calculo_encabezado(this)' class='form-control decimal' <?php echo $r_descuento?>/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='descuent' name='descuent'  value='' lang='1' readonly  class='form-control decimal' />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='iva' style='text-align:right' name='iva' size='5' value='' readonly class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td hidden><input type='text' name='ice_p' id='ice_p' size='5' value='0' readonly lang='1' /></td>"+
                                        "<td hidden><input type='text' name='ice' id='ice' size='5' value='0' readonly lang='1'/>"+
                                            "<input type=''  name='ice_cod' id='ice_cod' size='5' value='0' readonly lang='1'/>"+
                                        "</td>"+
                                        "<td>"+
                                            "<input type ='text' size='9' style='text-align:right' id='valor_total' name='valor_total' value='' lang='1' readonly class='form-control decimal' />"+
                                        "</td>"+
                                        "<td align='center' ><input  type='button' name='add1' id='add1' class='btn btn-primary fa fa-plus' onclick='validar("+a+"#tbl_detalle"+a+",0)' lang='1' value='+'/> </td>"+
                                    "</tr>";
                    $('.td1').show();  
                    $('#valores').attr('colspan');
                    var clp=($('#valores').attr('colspan')*1)+3;
                    $('#valores').attr('colspan',clp);
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
                    
            }

            function num_factura(obj) {
                nfac = obj.value;
                dt = nfac.split('-');
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('fac_id').val('0');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                    alert('No cumple con la estructura ejem: 000-000-000000000');
                    limpiar_nota();                    
                } else {
                    traer_facturas(obj);
                }
            }

            function traer_facturas(obj) {
              $.ajax({
                  beforeSend: function () {
                      if ($('#ncr_num_comp_modifica').val().length == 0) {
                            alert('Ingrese una factura');
                            return false;
                      }
                    },
                  url: base_url+"nota_credito/traer_facturas/"+$('#ncr_num_comp_modifica').val()+"/"+emi_id.value,
                  type: 'JSON',
                  dataType: 'JSON',
                  success: function (dt) { 
                    i=dt.length;
                    if(i>0){
                        n=0;
                         load_factura(dt[n]['fac_id']);
                        // var tr="";
                        // while(n<i){
                        //     tr+="<tr>"+
                        //         "<td><input type='checkbox' onclick='load_factura("+dt[n]['fac_id']+")'></td>"+
                        //         "<td>"+dt[n]['fac_fecha_emision']+"</td>"+
                        //         "<td>FACTURA</td>"+
                        //         "<td>"+dt[n]['fac_numero']+"</td>"+
                        //         "<td>"+dt[n]['cli_ced_ruc']+"</td>"+
                        //         "<td>"+dt[n]['cli_raz_social']+"</td>"+
                        //         "</tr>";
                        //         n++;
                        // }
                        // $('#det_ventas').html(tr);
                        // $("#myModal").modal();
                    }else{
                        alert('No existe Factura \nSe creara Nota de Credito sin Factura');
                        limpiar_nota();
                    }
                  }
                })
            }        

            function load_factura(vl) {
              //$("#myModal").modal('hide');
              
              $.ajax({
                  beforeSend: function () {
                      
                    },
                    url: base_url+"nota_credito/load_factura/"+vl+"/"+inven+"/"+ctr_inv+"/"+dec+"/"+dcc,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                            if (dt.length != '0') {
                              var total_0= dt.fac_subtotal0+dt.fac_subtotal_no_iva+dt.fac_subtotal_ex_iva;

                                $('#fac_id').val(dt.fac_id);
                                $('#ncr_fecha_emi_comp').val(dt.fac_fecha_emision);
                                $('#identificacion').val(dt.cli_ced_ruc);
                                $('#nombre').val(dt.cli_raz_social);
                                $('#direccion_cliente').val(dt.cli_calle_prin);
                                $('#telefono_cliente').val(dt.cli_telefono);
                                $('#email_cliente').val(dt.cli_email);
                                $('#cli_id').val(dt.cli_id);
                                $('#identificacion').attr('readonly', true);
                                $('#nombre').attr('readonly', true);
                                $('#direccion_cliente').attr('readonly', true);
                                $('#telefono_cliente').attr('readonly', true);
                                $('#email_cliente').attr('readonly', true);
                                $('#subtotal12').val(parseFloat(dt.fac_subtotal12).toFixed(dec));
                                $('#subtotal0').val(parseFloat(dt.fac_subtotal0).toFixed(dec));
                                $('#subtotal_1').val(parseFloat(total_0).toFixed(dec));
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
                                $('.td1').show(); 
                                calculo();
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

                        // if (dt.ice_p== '') {
                            $('#ice').val('0');
                            $('#ice_p').val('0');
                        // } else {
                            // $('#ice').val('0');
                            // $('#ice_p').val(parseFloat(dt.ice_p).toFixed(dec));

                        // }

                        // if (dt.ice_cod == '') {
                            $('#ice_cod').val('0');
                        // } else {
                        //     $('#ice_cod').val(dt.ice_cod);
                        // }

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
                        $('#ice').val('0');
                        $('#ice_p').val('0');
                        $('#ice_cod').val('0');
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
                  if($("#trs_id").val()!=1){
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
                    if($('#trs_id').val()!=1){
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                          "<input type ='hidden' name='pro_aux"+i+"' id='pro_aux"+i+"' lang='"+i+"' value='"+pro_aux.value+"'/>"+
                                          "<input type ='hidden' name='pro_ids"+i+"' id='pro_ids"+i+"' lang='"+i+"' value='"+pro_ids.value+"'/>"+
                                          "<input type ='hidden' name='mov_cost_unit"+i+"' id='mov_cost_unit"+i+"' lang='"+i+"' value='"+mov_cost_unit.value+"'/>"+
                                          "<input type ='hidden' name='mov_cost_tot"+i+"' id='mov_cost_tot"+i+"' lang='"+i+"' value='"+mov_cost_tot.value+"'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' size='10' id='pro_descripcion"+i+"' name='pro_descripcion"+i+"' lang='"+i+"' value='"+pro_descripcion.value +"' readonly/>"+"</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' size='10' id='pro_referencia"+i+"' name='pro_referencia"+i+"' lang='"+i+"' value='"+pro_referencia.value +"' readonly/>"+"</td>"+
                                        "<td id='unidad"+i+"' lang='"+i+"'>"+unidad.value+"</td>"+
                                       
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidadf"+i+"' name='cantidadf"+i+"' lang='"+i+"' value='"+cantidadf.value +"' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' lang='"+i+"' onchange='calculo()'  value='"+cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
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
                    }else{
                      var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                          "<input type ='hidden' name='pro_aux"+i+"' id='pro_aux"+i+"' lang='"+i+"' value='"+pro_aux.value+"'/>"+
                                          "<input type ='hidden' name='pro_ids"+i+"' id='pro_ids"+i+"' lang='"+i+"' value='"+pro_ids.value+"'/>"+
                                          "<input type ='hidden' name='mov_cost_unit"+i+"' id='mov_cost_unit"+i+"' lang='"+i+"' value='"+mov_cost_unit.value+"'/>"+
                                          "<input type ='hidden' name='mov_cost_tot"+i+"' id='mov_cost_tot"+i+"' lang='"+i+"' value='"+mov_cost_tot.value+"'/>"+
                                        "</td>"+
                                        "<td hidden>"+
                                          "<input type ='text' class='form-control' size='10' id='pro_descripcion"+i+"' name='pro_descripcion"+i+"' lang='"+i+"' value='"+pro_descripcion.value +"' readonly/>"+"</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' size='10' id='pro_referencia"+i+"' name='pro_referencia"+i+"' lang='"+i+"' value='"+pro_referencia.value.toUpperCase() +"' readonly/>"+"</td>"+
                                        "<td hidden id='unidad"+i+"' lang='"+i+"'>"+unidad.value+"</td>"+
                                        
                                        "<td hidden >"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidadf"+i+"' name='cantidadf"+i+"' lang='"+i+"' value='"+cantidadf.value +"' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='cantidad"+i+"' name='cantidad"+i+"' lang='"+i+"' onchange='calculo()'  value='"+cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='pro_precio"+i+"' name='pro_precio"+i+"' onchange='calculo()' value='"+pro_precio.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)'/>"+
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
                    }                
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                pro_referencia.value = '';
                if($("#trs_id").val()==1){
                  pro_descripcion.value = '0';
                }else{
                  pro_descripcion.value = '';
                }
                
                pro_aux.value = '0';
                pro_ids.value = '0';
                mov_cost_unit.value = '0';
                mov_cost_tot.value = '0';
                unidad.value = '';
                inventario.value = '';
                cantidad.value = '';
                cantidadf.value = '';
                pro_precio.value = '';
                iva.value = '0';
                descuento.value = '0';
                descuent.value = '0';
                ice.value = '0';
                ice_cod.value = '0';
                ice_p.value = '0';
                valor_total.value = '0';
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
                $('#subtotal_1').val(sub1.toFixed(dec));
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
                        if(fac_id.value!=0){
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
            }

            function save() {
                        if (ncr_num_comp_modifica.value.length == 0) {
                            $("#ncr_num_comp_modifica").css({borderColor: "red"});
                            $("#ncr_num_comp_modifica").focus();
                            return false;
                        } else if (ncr_fecha_emi_comp.value.length == 0) {
                            $("#ncr_fecha_emi_comp").css({borderColor: "red"});
                            $("#ncr_fecha_emi_comp").focus();
                            return false;
                        } else if (identificacion.value.length == 0) {
                            $("#identificacion").css({borderColor: "red"});
                            $("#identificacion").focus();
                            return false;
                        } else if (ncr_motivo.value.length == 0) {
                            $("#ncr_motivo").css({borderColor: "red"});
                            $("#ncr_motivo").focus();
                            return false;
                        }else if ($('#trs_id').val() == 0 ){
                           $("#trs_id").css({borderColor: "red"});
                            $("#trs_id").focus();
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
                                if ($('#pro_descripcion' + n).val() != null && parseFloat($('#cantidad' + n).val())>0) {
                                  k++;
                                    if ($('#pro_descripcion' + n).val().length == 0) {
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
                        
                        if ($('#vnd_id').val() == 0 || $('#vnd_id').val() == '') {
                            $('#vnd_id').css({borderColor: "red"});
                            $('#vnd_id').focus();
                            return false;
                        }
                        
                     $('#frm_save').submit();   
               }   
    </script>

