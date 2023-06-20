<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<section class="content-header">
      <h1>
        Registro de Guias de Remisión
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
                        <div class="panel panel-default col-sm-8">
                        <div class="panel panel-heading"><label>Datos Generales</label></div>
                        <table class="table">
                          <tr>
                              <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('rgu_fregistro')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="rgu_fregistro" id="rgu_fregistro" value="<?php if(validation_errors()!=''){ echo set_value('rgu_fregistro');}else{ echo  $guia->rgu_fregistro;}?>">
                                  <?php echo form_error("rgu_fregistro","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo  $guia->emp_id;}?>">
                                <input type="hidden" class="form-control" name="emi_id" id="emi_id" value="<?php if(validation_errors()!=''){ echo set_value('emi_id');}else{ echo   $guia->emi_id;}?>">
                                <input type="hidden" class="form-control" name="cja_id" id="cja_id" value="<?php if(validation_errors()!=''){ echo set_value('cja_id');}else{ echo  $guia->cja_id;}?>">
                                <input type="hidden" class="form-control" name="fac_id" id="fac_id" value="<?php if(validation_errors()!=''){ echo set_value('fac_id');}?>">
                                </div>
                              </td>
                                  
                              <td><label>Factura No:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('reg_num_documento')!=''){ echo 'has-error';}?>">
                                  <input  type="hidden" class="form-control documento" name="reg_num_documento" id="reg_num_documento" value="<?php if(validation_errors()!=''){ echo set_value('reg_num_documento');}?>" onchange="num_factura(this)"  maxlength="17">
                                  <?php echo form_error("reg_num_documento","<span class='help-block'>","</span>");?>
                                  
                                  <div class="row">
                                  <div class="col-xs-3">
                                    <input type="text" class="form-control" id="reg_num_documento0" size="3" maxlength="3" value="" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)" />
                                  </div>
                                  <div class="col-xs-1" style="width: 0.5%;"> <p>-</p> </div> 
                                    <div class="col-xs-3">
                                      <input type="text"  class="form-control" id="reg_num_documento1"  maxlength="3" value="" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 0)" />
                                  </div>
                                  <div class="col-xs-1" style="width: 0.5%;"> <p>-</p> </div> 
                                  <div class="col-xs-4">
                                    <input type="text"  class="form-control" id="reg_num_documento2"  maxlength="9" value="" onkeyup=" this.value = this.value.replace(/[^0-9]/, '')" onchange="completar_ceros(this, 1)" />
                                  </div>
                                  </div>  
                                </div>
                              </td>
                          </tr>
                          <tr>    
                              <td><label>RUC/CI:</label></td>
                              <td >
                                <div class="form-group <?php if(form_error('identificacion')!=''){ echo 'has-error';}?> ">
                                  <input type="text" class="form-control" name="identificacion" id="identificacion" value="<?php if(validation_errors()!=''){ echo set_value('identificacion');}else{ echo $guia->cli_ced_ruc;}?>" readonly>
                                  <?php echo form_error("identificacion","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                            <td><label>Nombre:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php if(validation_errors()!=''){ echo set_value('nombre');}else{ echo $guia->cli_raz_social;}?>" readonly>
                                    <?php echo form_error("nombre","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $guia->cli_id;}?>" >
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
                          <div class="panel panel-default col-sm-10">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Unidad</th>
                                <th>Cant.Guias</th>
                                <th>Cant.Facturado</th>
                                <th>Saldo</th>
                              </tr>
                            </thead>

                               
                                <tbody id="lista">
                                  <?php
                                  if(!empty($cns_det)){
                                  $cnt_detalle=0;
                                  $n=0;
                                  $t_solicitado=0;
                                  $t_entregado=0;
                                  $t_saldo=0;
                                    foreach($cns_det as $rst_det) {
                                        $n++;
                                        ?>
                                        <tr>
                                            <td id="item<?PHP echo $n ?>" name="item<?PHP echo $n ?>" lang="<?PHP echo $n ?>" align="center"><?PHP echo $n ?></td>
                                            <td id="pro_descripcion<?PHP echo $n ?>" name="pro_descripcion<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->mp_c ?></td>
                                            <td id="pro_referencia<?PHP echo $n ?>" name="pro_referencia<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?php echo $rst_det->mp_c ?>
                                                <input type="hidden" size="7" id="pro_id<?PHP echo $n ?>" name="pro_id<?PHP echo $n ?>" value="<?php echo $rst_det->pro_id ?>" lang="<?PHP echo $n ?>"/>
                                            </td>
                                            <td id="unidad<?PHP echo $n ?>" name="unidad<?PHP echo $n ?>" lang="<?PHP echo $n ?>"><?PHP echo $rst_det->mp_q ?></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'solicitado' . $n ?>" name="<?php echo 'solicitado' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->drg_cantidad, $dec)) ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'entregado' . $n ?>" name="<?php echo 'entregado' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->entregado, $dec)) ?>" lang="<?PHP echo $n ?>" readonly/></td>
                                            <td ><input type ="text" size="7"  style="text-align:right" class="form-control decimal" id="<?php echo 'saldo' . $n ?>" name="<?php echo 'saldo' . $n ?>" value="<?php echo str_replace(',', '', number_format($rst_det->saldo, $dec)) ?>" lang="<?PHP echo $n ?>" onkeyup = "this.value = this.value.replace(/[^0-9.]/, '')" onchange="valida_cantidad(this)"/></td>
                                            
                                        </tr>
                                        <?php
                                        $t_solicitado+=$rst_det->drg_cantidad;
                                        $t_entregado+=$rst_det->entregado;
                                        $t_saldo+=$rst_det->saldo;
                                        $cnt_detalle++;
                                    }
                                  }
                                  $t_saldo2=$t_saldo+$t_entregado;
                                ?>
                                </tbody>
                            <tfoot>
                            <tr hidden>
                                <td colspan="4">Total</td>
                                <td ><input type="text" name="t_solicitado" id="t_solicitado" value="<?php echo $t_solicitado?>"></td>
                                <td ><input type="text" name="t_entregado" id="t_entregado" value="<?php echo $t_entregado?>"></td>
                                <td ><input type="text" name="t_saldo" id="t_saldo" value="<?php echo $t_saldo?>"></td>
                                <td ><input type="text" name="t_saldo2" id="t_saldo2" value="<?php echo $t_saldo2?>"></td>
                            </tr>
                        </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                  </table>
              </div>
                                
                <input type="hidden" class="form-control" name="rgu_secuencia_unif" value="<?php echo $guia->rgu_secuencia_unif?>">
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
                <input type="hidden" class="form-control" id="saldo" name="saldo" value="0">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         </div>
      <!-- /.row -->
    </section>



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
      

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
      
      
      function save() {
                        if (reg_num_documento.value.length == 0) {
                            $("#reg_num_documento0").css({borderColor: "red"});
                            $("#reg_num_documento1").css({borderColor: "red"});
                            $("#reg_num_documento2").css({borderColor: "red"});
                            $("#reg_num_documento0").focus();
                            return false;
                        } else if (rgu_fregistro.value.length == 0) {
                            $("#rgu_fregistro").css({borderColor: "red"});
                            $("#rgu_fregistro").focus();
                            return false;
                        } else if (cli_id.value.length == 0) {
                            $("#cli_id").css({borderColor: "red"});
                            $("#cli_id").focus();
                            return false;
                        }

                        var tr = $('#lista').find("tr:last");
                        a = tr.find("input").attr("lang");
                        i = parseInt(a);
                        n = 0;
                        j = 0;
                        k = 0;
                        if(a==null){
                          swal("Error!", "Ingrese Detalle.!", "error");
                          return false;
                        }
                        if (i != 0) {
                            while (n < i) {
                                n++;

                                if ($('#pro_id' + n).val() != null) {
                                    k++;
                                    if ($('#saldo' + n).val().length == 0) {
                                        $('#saldo' + n).css({borderColor: "red"});
                                        $('#saldo' + n).focus();
                                        return false;
                                    } 

                                }
                            }
                        }

                        if (t_saldo.value == 0) {
                            swal("Error!", "No se puede guardar el registro con todas las cantidades en 0!", "error");
                            return false;
                        } 

                     $('#frm_save').submit();   
               }   

        function completar_ceros(obj, v) {
                o = obj.value;
                val = parseFloat(o);
                if (v == 0) {
                    if (val == 0) {
                        swal("", "Número incorrecto", "info"); 
                        $(obj).val('');
                    } else if (val > 0 && val < 10) {
                        txt = '00';
                    } else if (val >= 10 && val < 100) {
                        txt = '0';
                    } else if (val >= 100 && val < 1000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);
                } else {
                    if (val > 0 && val < 10) {
                        txt = '00000000';
                    } else if (val >= 10 && val < 100) {
                        txt = '0000000';
                    } else if (val >= 100 && val < 1000) {
                        txt = '000000';
                    } else if (val >= 1000 && val < 10000) {
                        txt = '00000';
                    } else if (val >= 10000 && val < 100000) {
                        txt = '0000';
                    } else if (val >= 100000 && val < 1000000) {
                        txt = '000';
                    } else if (val >= 1000000 && val < 10000000) {
                        txt = '00';
                    } else if (val >= 10000000 && val < 100000000) {
                        txt = '0';
                    } else if (val >= 100000000 && val < 1000000000) {
                        txt = '';
                    }
                    $(obj).val(txt + val);

                    if (val == 0 || o.length == 0) {
                        Swal.fire("", "Número incorrecto", "info"); 
                        $(obj).val('');
                        return false;
                    }else{
                      val_factura();
                    }
                }
            }

            function val_factura() {
                
                nfac = $('#reg_num_documento0').val()+'-'+$('#reg_num_documento1').val()+'-'+$('#reg_num_documento2').val();
                 dt = nfac.split('-');
                if (nfac.length != 17 || dt[0].length != 3 || dt[1].length != 3 || dt[2].length != 9) {
                    $(obj).val('');
                    $('#reg_num_documento0').val('');
                    $('#reg_num_documento1').val('');
                    $('#reg_num_documento2').val('');
                    $('#reg_id').val('0');
                    $(obj).focus();
                    $(obj).css({borderColor: "red"});
                     Swal.fire("", "No cumple con la estructura ejem: 000-000-000000000", "info");
                } else{
                  $('#reg_num_documento').val(nfac);
                  doc_duplicado();
                }
            } 

            function doc_duplicado(){
              num_doc = $('#reg_num_documento').val();
              tip_doc = $('#reg_tipo_documento').val();
              if (num_doc.length = 17 && cli_id.value.length > 0 && tip_doc != 0) {
                $.ajax({
                      beforeSend: function () {
                      },
                      url: base_url+"reg_guia/doc_duplicado/"+cli_id.value+"/"+num_doc+"/1",
                      type: 'JSON',
                      dataType: 'JSON',
                      success: function (dt) {
                          if(dt!=""){
                            swal("", "EL numero de Documento y el RUC/CI del Proveedor \n Ya existen en el Registro de Facturas", "info");  
                            $('#reg_num_documento').val('');
                            $('#reg_num_documento0').val('');
                            $('#reg_num_documento1').val('');
                            $('#reg_num_documento2').val('');
                          } 
                      }
                    });
              }          
            } 

            function valida_cantidad(obj) {
                j=obj.lang;
                sol=$('#solicitado'+j).val();
                entr=$('#entregado'+j).val();
                sald=$('#saldo'+j).val();
                if(sol.length==0){
                    sol=0;
                }else{
                    sol=parseFloat(sol);
                }
                if(entr.length==0){
                    entr=0;
                }else{
                    entr=parseFloat(entr);
                }
                if(sald.length==0){
                    sald=0;
                }else{
                    sald=parseFloat(sald);
                }

                sum=entr+sald;
                if(sum>sol){
                    swal("Error!", "La suma de saldo y entregado es mayor a la cantidad de la guia!", "error");
                    $('#saldo'+j).val('');
                }

                calculo();
            }

        function calculo(){
                
                var tr = $('#lista').find("tr:last");
                a = tr.find("input").attr("lang");
                i = parseInt(a);
                t_sal=0;
                n = 0;
                        while (n < i) {
                            n++;
                            sal=$('#saldo' + n).val();
                            if(sal.length==0){
                                sal=0;
                            }else{
                                sal=parseFloat(sal);
                            }
                            t_sal+=sal;
                        }
                        t_ent=$('#t_entregado').val();
                        if(t_ent.length==0){
                            t_ent=0;
                        }else{
                            t_ent=parseFloat(t_ent);
                        }
                    t_sal2=t_ent+t_sal;    
                    $('#t_saldo').val(t_sal); 
                    $('#t_saldo2').val(t_sal2);    
            }    
         
      
</script>