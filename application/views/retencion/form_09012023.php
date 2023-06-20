
<section class="content-header">
      <h1>
        Retenciones de Compras
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          $dcc=$dcc->con_valor;
          
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
                              <div class="form-group <?php if(form_error('ret_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="ret_fecha_emision" id="ret_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('ret_fecha_emision');}else{ echo $retencion->ret_fecha_emision;}?>">
                                  <?php echo form_error("ret_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $retencion->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo $retencion->emi_id;}?>">
                                <input type="hidden" class="form-control" name="cja_id" id="cja_id" value="<?php if(validation_errors()!=''){ echo set_value('cja_id');}else{ echo $retencion->cja_id;}?>">
                                <input type="hidden" class="form-control" name="reg_id" id="reg_id" value="<?php if(validation_errors()!=''){ echo set_value('reg_id');}else{ echo $retencion->reg_id;}?>">
                                </div>
                              </td>
                              <td><label>Vendedor</label></td>
                              <td>
                                <div class="form-group ">
                                  <select name="vnd_id"  id="vnd_id" class="form-control">
                                    <option value="0">SELECCIONE</option>
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
                                    var vnd='<?php echo $retencion->vnd_id;?>';
                                    vnd_id.value=vnd;
                                  </script>
                                </div>
                              </td>    
                          </tr>
                          <tr>
                              <td><label>Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('ret_num_comp_retiene')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control documento" name="ret_num_comp_retiene" id="ret_num_comp_retiene" value="<?php if(validation_errors()!=''){ echo set_value('ret_num_comp_retiene');}else{ echo $retencion->ret_num_comp_retiene;}?>" onchange="num_factura(this)" maxlength="17">
                                  <?php echo form_error("ret_num_comp_retiene","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          <tr>    
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $retencion->ret_identificacion;}?>" list="list_clientes" onchange="traer_cliente(this)" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $retencion->ret_nombre;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $retencion->cli_id;}?>" >
                              </td>
                          </tr>
                          <tr>
                            <td><label>Direccion:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('direccion_cliente')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="direccion_cliente" id="direccion_cliente" value="<?php if(validation_errors()!=''){ echo set_value('direccion_cliente');}else{ echo $retencion->ret_direccion;}?>" readonly>
                                <?php echo form_error("direccion_cliente","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Telefono:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('telefono_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" value="<?php if(form_error('telefono_cliente')){ echo set_value('telefono_cliente');}else{ echo $retencion->ret_telefono;}?>" readonly>
                                      <?php echo form_error("telefono_cliente","<span class='help-block'>","</span>");?>
                                  
                                  </div>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Email:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('email_cliente')!=''){ echo 'has-error';}?> ">
                                  <input type="email" class="form-control" name="email_cliente" id="email_cliente" value="<?php if(validation_errors()!=''){ echo set_value('email_cliente');}else{ echo $retencion->ret_email;}?>" readonly>
                                  <input type="hidden" class="form-control" name="subtotal" id="subtotal" value="<?php echo $retencion->reg_sbt;?>">
                                  <input type="hidden" class="form-control" name="iva" id="iva" value="<?php echo $retencion->reg_iva12;?>">
                                  <?php echo form_error("email_cliente","<span class='help-block'>","</span>");?>
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
                                <th>Impuesto</th>
                                <th>Ejercicio Fiscal</th>
                                <th>Tipo Impuesto</th>
                                <th>Base Imponible</th>
                                <th>% de Retencion</th>
                                <th>Valor Retenido</th>
                                <th></th>
                              </tr>
                            </thead>

                            <tbody id="lista_encabezado">
                            
                              <?php
                                $cnt_detalle=0;
                                  ?>
                                    <tr>
                                        <td colspan="2">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="dtr_codigo_impuesto" name="dtr_codigo_impuesto"  value="" lang="1"   maxlength="16" list="impuestos" onchange="load_impuesto(this.lang)"  />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="por_descripcion" name="por_descripcion"   value="" lang="1" readonly style="width:300px;" />
                                            <input type="hidden"  id="por_id" name="por_id" lang="1"/>
                                        </td>
                                        <td>
                                          <input type="text" id="dtr_ejercicio_fiscal" name="dtr_ejercicio_fiscal"  value="" lang="1" class="form-control" readonly />
                                        </td>
                                        <td>
                                          <input type="text" id="dtr_tipo_impuesto" name="dtr_tipo_impuesto"  value="" lang="1" class="form-control" readonly />
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="dtr_base_imponible" name="dtr_base_imponible"  value="" lang="1" onchange="calculo_encabezado(this)" class="form-control decimal"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="dtr_procentaje_retencion" name="dtr_procentaje_retencion" onchange="calculo_encabezado(this)" value="" lang="1" class="form-control decimal"/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="dtr_valor" name="dtr_valor"  value="" lang="1" class="form-control decimal" readonly/>
                                        </td>
                                        
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar('#tbl_detalle','0')" lang="1" value='+'/> </td>
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
                                            <td>
                                              <input type="text" class="form-control" size="7" id="dtr_codigo_impuesto<?PHP echo $n ?>" name="dtr_codigo_impuesto<?PHP echo $n ?>" value="<?php echo $rst_det->dtr_codigo_impuesto ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td>
                                                <input type="text" class="form-control" size="7" id="por_descripcion<?PHP echo $n ?>" name="por_descripcion<?PHP echo $n ?>" value="<?php echo $rst_det->por_descripcion ?>" lang="<?PHP echo $n ?>" readonly/>
                                                <input type="hidden" size="7" id="por_id<?PHP echo $n ?>" name="por_id<?PHP echo $n ?>" value="<?php echo $rst_det->por_id ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td>
                                              <input type="text" class="form-control" size="7" id="dtr_ejercicio_fiscal<?PHP echo $n ?>" name="dtr_ejercicio_fiscal<?PHP echo $n ?>" value="<?php echo $rst_det->dtr_ejercicio_fiscal ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td>
                                              <input type="text" class="form-control" size="7" id="dtr_tipo_impuesto<?PHP echo $n ?>" name="dtr_tipo_impuesto<?PHP echo $n ?>" value="<?php echo $rst_det->dtr_tipo_impuesto ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'dtr_base_imponible' . $n ?>" name="<?php echo 'dtr_base_imponible' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->dtr_base_imponible, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" /></td>
                                            <td><input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'dtr_procentaje_retencion' . $n ?>" name="<?php echo 'dtr_procentaje_retencion' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->dtr_procentaje_retencion, $dec)) ?>" lang="<?PHP echo $n ?>" onchange="calculo(this)" /></td>
                                            <td>
                                                <input type ="text" size="7" style="text-align:right" class="form-control decimal" id="<?php echo 'dtr_valor' . $n ?>" name="<?php echo 'dtr_valor' . $n ?>"  value="<?php echo str_replace(',', '', number_format($rst_det->dtr_valor, $dec)) ?>" lang="<?PHP echo $n ?>" readonly/>
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
                                    <th colspan="7" style="text-align:right">Total Valor:</th>
                                    <td><input style="text-align:right;font-size:15px;color:red" size="7" type="text" class="form-control" id="total_valor" name="total_valor" value="<?php echo str_replace(',', '', number_format($retencion->ret_total_valor, $dec)) ?>" readonly />
                                        
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
                                
                <input type="hidden" class="form-control" name="ret_id" value="<?php echo $retencion->ret_id?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>
            </form>
          </div>
         
        </div>
      </div>  
      <!-- /.row -->
</section>
    
    <datalist id="impuestos">
      <?php 
        if(!empty($cns_impuestos)){
          foreach ($cns_impuestos as $rst_imp) {
      ?>
        <option value="<?php echo $rst_imp->por_id?>"><?php echo $rst_imp->por_codigo .' '.$rst_imp->por_descripcion?></option>
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
            

            function limpiar_retencion(){
                    $('#reg_id').val('0');
                    $('#identificacion').val('');
                    $('#nombre').val('');
                    $('#direccion_cliente').val('');
                    $('#telefono_cliente').val('');
                    $('#email_cliente').val('');
                    $('#lista').html('');
                    $('#cli_id').val('');
                    $('#identificacion').attr('readonly', false);
                    $('#nombre').attr('readonly', true);
                    $('#direccion_cliente').attr('readonly', true);
                    $('#telefono_cliente').attr('readonly', true);
                    $('#email_cliente').attr('readonly', true);
                    a = '"';
                    var tr = "<tr>"+
                                        "<td colspan='2'>"+
                                            "<input style='text-align:left ' type='text' style='width:  150px;' class='form-control' id='dtr_codigo_impuesto' name='dtr_codigo_impuesto'  value='' lang='1'   maxlength='16' list='impuestos' onchange='load_impuesto(this.lang)'  />"+
                                        "</td>"+
                                        "<td>"+
                                            "<input style='text-align:left ' type ='text' size='40' class='refer form-control'  id='por_descripcion' name='por_descripcion'   value='' lang='1' readonly style='width:300px;' />"+
                                            "<input type='hidden'  id='por_id' name='por_id' lang='1'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='dtr_ejercicio_fiscal' name='dtr_ejercicio_fiscal'  value='' lang='1' class='form-control' readonly />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='dtr_tipo_impuesto' name='dtr_tipo_impuesto'  value='' lang='1' class='form-control' readonly />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='dtr_base_imponible' name='dtr_base_imponible'  value='' lang='1' onchange='calculo_encabezado(this)' class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='dtr_procentaje_retencion' name='dtr_procentaje_retencion' onchange='calculo_encabezado(this)' value='' lang='1' class='form-control decimal'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='dtr_valor' name='dtr_valor'  value='' lang='1' class='form-control decimal' readonly/>"+
                                        "</td>"+
                                        "<td align='center' ><input  type='button' name='add1' id='add1' class='btn btn-primary fa fa-plus' onclick='validar("+a+"#tbl_detalle"+a+",0)' lang='1' value='+'/> </td>"+
                                    "</tr>";
                    $('#lista_encabezado').html(tr);
                    $('#total_valor').val(parseFloat('0').toFixed(dec));

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
                    limpiar_retencion();                    
                } else {
                    traer_facturas(obj);
                }
            }

            function traer_facturas(obj) {
              
              $.ajax({
                  beforeSend: function () {
                      if ($('#ret_num_comp_retiene').val().length == 0) {
                            alert('Ingrese una factura');
                            return false;
                      }
                    },
                  url: base_url+"retencion/traer_facturas/"+$('#ret_num_comp_retiene').val()+"/"+emp_id.value,
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
                        alert('No existe Factura');
                        limpiar_retencion();
                    }
                  }
                })
            }        

            function load_factura(vl) {
              $("#myModal").modal('hide');
              
              $.ajax({
                  beforeSend: function () {
                      
                    },
                    url: base_url+"retencion/load_factura/"+vl+"/"+dec+"/"+dcc,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                            if (dt.length != '0') {
                                $('#reg_id').val(dt.reg_id);
                                $('#ret_fecha_emision').val(dt.reg_femision);
                                $('#identificacion').val(dt.cli_ced_ruc);
                                $('#nombre').val(dt.cli_raz_social);
                                $('#direccion_cliente').val(dt.cli_calle_prin);
                                $('#telefono_cliente').val(dt.cli_telefono);
                                $('#email_cliente').val(dt.cli_email);
                                $('#cli_id').val(dt.cli_id);
                                $('#subtotal').val(dt.reg_sbt);
                                $('#iva').val(dt.reg_iva12);
                                calculo();
                            } else {
                                limpiar_retencion();
                            }
                        }
                })
                
            }

            function load_impuesto(j) {
              
                vl = $('#dtr_codigo_impuesto').val();
                $.ajax({
                  beforeSend: function () {
                      if ($('#dtr_codigo_impuesto').val().length == 0) {
                            alert('Ingrese un impuesto');
                            return false;
                      }
                    },
                    url: base_url+"retencion/load_impuesto/"+vl,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                      if (dt!='') {
                        v=0;
                        if(conf_as==0){
                          if(dt.cta_id=='' || dt.cta_id=='0'){
                            alert('El impuesto no tiene una Cuenta Contable asignada')
                            v=1;
                          }  
                        }
                        if(v==0){
                          iva=$('#iva').val();
                          rnt=$('#subtotal').val();
                          ej=$('#ret_fecha_emision').val().split('-');
                          $('#por_descripcion').val(dt.por_descripcion);
                          $('#dtr_codigo_impuesto').val(dt.por_codigo);
                          $('#por_id').val(dt.por_id);
                          $('#dtr_procentaje_retencion').val(dt.por_porcentage);
                          $('#dtr_tipo_impuesto').val(dt.por_siglas);
                          $('#dtr_ejercicio_fiscal').val(ej[0]+'/'+ej[1]);
                          if(dt.por_siglas=='IV'){
                            $('#dtr_base_imponible').val(parseFloat(iva,dec));
                          }else if(dt.por_siglas=='IR'){
                            $('#dtr_base_imponible').val(parseFloat(rnt,dec));
                          }
                        }else{
                          $('#por_descripcion').val('');
                          $('#dtr_codigo_impuesto').val('');
                          $('#por_id').val('');
                          $('#dtr_procentaje_retencion').val('0');
                          $('#dtr_tipo_impuesto').val('');
                          $('#dtr_base_imponible').val('0');
                          $('#dtr_ejercicio_fiscal').val('');
                          $('#por_codigo').focus();
                        }  

                      }else{
                        $('#por_descripcion').val('');
                        $('#dtr_codigo_impuesto').val('');
                        $('#por_id').val('');
                        $('#dtr_procentaje_retencion').val('0');
                        $('#dtr_tipo_impuesto').val('');
                        $('#dtr_base_imponible').val('0');
                        $('#dtr_ejercicio_fiscal').val('');
                        $('#por_codigo').focus();
                      }
                      calculo_encabezado();
                    }
                  });
                
              }

            function validar(table, opc){
              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              
                if($('#dtr_procentaje_retencion').val().length!=0 &&  $('#dtr_base_imponible').val().length!=0 && $('#por_descripcion').val().length!=0){
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
                                    
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                          "<input type ='hidden' name='por_id"+i+"' id='por_id"+i+"' lang='"+i+"' value='"+por_id.value+"'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' id='dtr_codigo_impuesto"+i+"' name='dtr_codigo_impuesto"+i+"' lang='"+i+"' value='"+dtr_codigo_impuesto.value +"' readonly />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' id='por_descripcion"+i+"' name='por_descripcion"+i+"' lang='"+i+"' value='"+por_descripcion.value +"' readonly />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control'  id='dtr_ejercicio_fiscal"+i+"' name='dtr_ejercicio_fiscal"+i+"' lang='"+i+"' value='"+dtr_ejercicio_fiscal.value +"' readonly />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control' id='dtr_tipo_impuesto"+i+"' name='dtr_tipo_impuesto"+i+"' lang='"+i+"' value='"+dtr_tipo_impuesto.value +"' readonly />"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='dtr_base_imponible"+i+"' name='dtr_base_imponible"+i+"' lang='"+i+"' onchange='calculo()'  value='"+dtr_base_imponible.value +"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='dtr_procentaje_retencion"+i+"' name='dtr_procentaje_retencion"+i+"' onchange='calculo()' value='"+dtr_procentaje_retencion.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' />"+
                                        "</td>"+
                                        "<td >"+
                                          "<input type ='text' size='7'  style='text-align:right' id='dtr_valor"+i+"' name='dtr_valor"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+dtr_valor.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                dtr_codigo_impuesto.value = '';
                por_descripcion.value = '';
                por_id.value = '';
                dtr_ejercicio_fiscal.value = '';
                dtr_base_imponible.value = '';
                dtr_procentaje_retencion.value = '';
                dtr_valor.value = '';
                dtr_tipo_impuesto.value = '';
                $('#dtr_codigo_impuesto').focus();
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
                
                
                bimp = $('#dtr_base_imponible').val().replace(',', '');
                porc = $('#dtr_procentaje_retencion').val().replace(',', '');
                if(bimp.length==0){
                  bimp=0;
                }
                if(porc.length==0){
                  porc=0;
                }
                tot = round(bimp,dec) * round(porc,dec)/100;
                $('#dtr_valor').val(tot.toFixed(dec));

                    
            }     


            function calculo(obj) {
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                i = parseInt(a);
                n = 0;
                var tot = 0;

                while (n < i) {
                    n++;
                    if ($('#item' + n).val() == null) {
                        bimp = 0;
                        porc = 0;
                        t=0;
                    } else {
                        bimp = $('#dtr_base_imponible'+n).val().replace(',', '');
                        porc = $('#dtr_procentaje_retencion'+n).val().replace(',', '');
                        t = round(bimp,dec) * round(porc,dec)/100;

                        $('#dtr_valor'+n).val(t.toFixed(dec));

                    }
                    tot+=t;

                }
                
                $('#total_valor').val(tot.toFixed(dec));
            }     


            function save() {
                        if (ret_num_comp_retiene.value.length == 0) {
                            $("#ret_num_comp_retiene").css({borderColor: "red"});
                            $("#ret_num_comp_retiene").focus();
                            return false;
                        } else if (ret_fecha_emision.value.length == 0) {
                            $("#ret_fecha_emision").css({borderColor: "red"});
                            $("#ret_fecha_emision").focus();
                            return false;
                        } else if ($('#vnd_id').val()== '0' || $('#vnd_id').val() == '') {
                            $("#vnd_id").css({borderColor: "red"});
                            $("#vnd_id").focus();
                            return false;
                        } else if (identificacion.value.length == 0) {
                            $("#identificacion").css({borderColor: "red"});
                            $("#identificacion").focus();
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
                                if ($('#dtr_codigo_impuesto' + n).html() != null) {
                                  k++;
                                    if ($('#dtr_codigo_impuesto' + n).val().length == 0) {
                                        $('#dtr_codigo_impuesto' + n).css({borderColor: "red"});
                                        $('#dtr_codigo_impuesto' + n).focus();
                                        return false;
                                    } else if ($('#por_descripcion' + n).val().length == 0) {
                                        $('#por_descripcion' + n).css({borderColor: "red"});
                                        $('#por_descripcion' + n).focus();
                                        return false;
                                    } else if ($('#dtr_base_imponible' + n).val().length == 0) {
                                        $('#dtr_base_imponible' + n).css({borderColor: "red"});
                                        $('#dtr_base_imponible' + n).focus();
                                        return false;
                                    } else if ($('#dtr_procentaje_retencion' + n).val().length == 0) {
                                        $('#dtr_procentaje_retencion' + n).css({borderColor: "red"});
                                        $('#dtr_procentaje_retencion' + n).focus();
                                        return false;
                                    } else if ($('#dtr_valor' + n).val().length == 0) {
                                        $('#dtr_valor' + n).css({borderColor: "red"});
                                        $('#dtr_valor' + n).focus();
                                        return false;
                                    }

                                }
                            }
                        }

                        if(k==0){
                          alert('No se puede Guardar retencion sin detalle');
                          return false;
                        }
                        
                        
                        
                     $('#frm_save').submit();   
               }   
    </script>

