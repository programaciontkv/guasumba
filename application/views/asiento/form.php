
<section class="content-header">
      <h1>
        Asiento Contable
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
          $dec=$dec->con_valor;
          
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
                        <table class="table">
                          <tr>
                               <td><label>Fecha Emision:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('con_fecha_emision')!=''){ echo 'has-error';}?> ">
                                <input type="date" class="form-control" name="con_fecha_emision" id="con_fecha_emision" value="<?php if(validation_errors()!=''){ echo set_value('con_fecha_emision');}else{ echo $asiento->con_fecha_emision;}?>">
                                  <?php echo form_error("con_fecha_emision","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="emp_id" id="emp_id" value="<?php if(validation_errors()!=''){ echo set_value('emp_id');}else{ echo $asiento->emp_id;}?>">
                                <input type="hidden" class="form-control" name="doc_id" id="doc_id" value="<?php if(validation_errors()!=''){ echo set_value('doc_id');}else{ echo $asiento->doc_id;}?>">
                                <input type="hidden" class="form-control" name="mod_id" id="mod_id" value="<?php if(validation_errors()!=''){ echo set_value('mod_id');}else{ echo $asiento->mod_id;}?>">
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php if(validation_errors()!=''){ echo set_value('cli_id');}else{ echo $asiento->cli_id;}?>">
                                </div>
                              </td>
                          
                            <td><label>Concepto:</label></td>
                            <td >
                              <div class="form-group <?php if(form_error('con_concepto')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="con_concepto" id="con_concepto" value="<?php if(validation_errors()!=''){ echo set_value('con_concepto');}else{ echo $asiento->con_concepto;}?>" >
                                <?php echo form_error("con_concepto","<span class='help-block'>","</span>");?>
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
                                <th>Documento</th>
                                <th>Cuenta Debe</th>
                                <th>Descripcion Debe</th>
                                <th>Valor Debe</th>
                                <th>Cuenta Haber</th>
                                <th>Descripcion Haber</th>
                                <th>Valor Haber</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>

                            <tbody id="lista_encabezado">
                            
                              <?php
                                $cnt_detalle=0;
                                $t_debe=0;
                                $t_haber=0;
                                  ?>
                                    <tr>
                                        <td colspan="2">
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="con_documento" name="con_documento"  value="" lang="1"/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="con_concepto_debe" name="con_concepto_debe"  value="" lang="1" size="45"  list="list_cuentas"  onchange='traer_cuenta(this,0,0)'/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_descripcion_debe" name="con_descripcion_debe"  value="" lang="1" class="form-control" size="30" readonly/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_valor_debe" name="con_valor_debe" size="10" value="0" lang="1" class="form-control decimal" style="text-align:right"  onkeyup='validar_decimal(this)'/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="con_concepto_haber" name="con_concepto_haber"   value="" lang="1" size="45" list="list_cuentas"  onchange='traer_cuenta(this,1,0)'/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_descripcion_haber" name="con_descripcion_haber"  value="" lang="1" class="form-control" size="30" readonly/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_valor_haber" name="con_valor_haber" size="10" value="0" lang="1" class="form-control decimal" style="text-align:right"  onkeyup='validar_decimal(this)'/>
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
                                        <td id="item<?php echo $n ?>" name="item<?php echo $n ?>" lang="<?php echo $n ?>" align="center"><?php echo $n ?></td>
                                        <td>
                                            <input style="text-align:left " type="text" style="width:  150px;" class="form-control" id="con_documento<?php echo $n ?>" name="con_documento<?php echo $n ?>"  value="<?php echo $rst_det->con_documento ?>" lang="<?php echo $n ?>"/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="con_concepto_debe<?php echo $n ?>" name="con_concepto_debe<?php echo $n ?>"   value="<?php echo $rst_det->con_concepto_debe ?>" lang="<?php echo $n ?>" size="45" list="list_cuentas" onchange='traer_cuenta(this,0,1)'/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_descripcion_debe<?php echo $n ?>" name="con_descripcion_debe<?php echo $n ?>"  value="<?php echo $rst_det->con_descripcion_debe?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_valor_debe<?php echo $n ?>" name="con_valor_debe<?php echo $n ?>" size="10" value="<?php echo str_replace(',', '', number_format($rst_det->con_valor_debe, $dec)) ?>" lang="<?php echo $n ?>" class="form-control decimal" style="text-align:right"  onkeyup='validar_decimal(this)' onchange="calculo()"/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" class="refer form-control"  id="con_concepto_haber<?php echo $n ?>" name="con_concepto_haber<?php echo $n ?>" value="<?php echo $rst_det->con_concepto_haber ?>" lang="1" size="45" list="list_cuentas" onchange='traer_cuenta(this,1,1)'/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_descripcion_haber<?php echo $n ?>" name="con_descripcion_haber<?php echo $n ?>" value="<?php echo $rst_det->con_descripcion_haber?>" lang="<?php echo $n ?>" class="form-control" size="30" readonly/>
                                        </td>
                                        <td>
                                          <input type="text" id="con_valor_haber<?php echo $n ?>" name="con_valor_haber<?php echo $n ?>" size="10" value="<?php echo str_replace(',', '', number_format($rst_det->con_valor_haber, $dec)) ?>" lang="<?php echo $n ?>" class="form-control decimal" style="text-align:right"  onkeyup='validar_decimal(this)' onchange="calculo()"/>
                                        </td>
                                           
                                        <td onclick="elimina_fila_det(this)" align="center" ><span class="btn btn-danger fa fa-trash"></span></td>
                                      </tr>
                                        <?php
                                        $cnt_detalle++;
                                        $t_debe+=round($rst_det->con_valor_debe,$dec);
                                        $t_haber+=round($rst_det->con_valor_haber,$dec);
                                    }
                                  }
                                ?>
                                </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align:right">Total:</th>
                                    <th colspan="3"></th>
                                    <th><input style="text-align:right;font-size:15px;color:red" size="10" type="text" class="form-control" id="total_debe" name="total_debe" value="<?php echo str_replace(',', '', number_format($t_debe, $dec)) ?>" readonly />
                                        
                                    </th>
                                    <th colspan="2"></th>
                                    <th><input style="text-align:right;font-size:15px;color:red" size="10" type="text" class="form-control" id="total_haber" name="total_haber" value="<?php echo str_replace(',', '', number_format($t_haber, $dec)) ?>" readonly />
                                        
                                    </th>
                                    <th></th>
                                </tr>
                              </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                </table>
              </div>
                                
              <input type="hidden" class="form-control" name="con_asiento" value="<?php echo $asiento->con_asiento?>">
              <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $cnt_detalle?>">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo base_url().'asiento/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    
    <datalist id="list_cuentas">
      <?php 
        if(!empty($cuentas)){
          foreach ($cuentas as $cuenta) {
      ?>
        <option value="<?php echo $cuenta->pln_codigo?>"><?php echo $cuenta->pln_codigo .' '.$cuenta->pln_descripcion?></option>
      <?php 
          }
        }
      ?>
  
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
      var dec='<?php echo $dec;?>';

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }
            


            function validar(table, opc){
              var tr1 = $(table).find("tbody tr:last");
              var a1 = tr1.find("input").attr("lang");
              
                if(($('#con_concepto_debe').val().length!=0 && $('#con_valor_debe').val().length!=0) ||  ($('#con_concepto_haber').val().length!=0 && $('#con_valor_haber').val().length!=0)){
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
                                        "<td id='item"+i+"' name='item"+i+"' lang='"+i+"' align='center'>"+i+"</td>"+
                                        "<td>"+
                                          "<input style='text-align:left ' type='text' style='width:  150px;' class='form-control' id='con_documento"+i+"' name='con_documento"+i+"'  value='"+con_documento.value+"' lang='"+i+"'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input style='text-align:left ' type ='text' class='refer form-control'  id='con_concepto_debe"+i+"' name='con_concepto_debe"+i+"'   value='"+con_concepto_debe.value+"' lang='"+i+"' size='45' list='list_cuentas' onchange='traer_cuenta(this,0,1)'/>"+
                                        "</td>"+
                                        "<td>"+
                                        "<input type='text' id='con_descripcion_debe"+i+"' name='con_descripcion_debe"+i+"'  value='"+con_descripcion_debe.value+"' lang='"+i+"' class='form-control' size='30' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='con_valor_debe"+i+"' name='con_valor_debe"+i+"' size='10' value='"+con_valor_debe.value+"' lang='"+i+"' class='form-control decimal' style='text-align:right'  onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input style='text-align:left ' type ='text' class='refer form-control'  id='con_concepto_haber"+i+"' name='con_concepto_haber"+i+"' value='"+con_concepto_haber.value+"' lang='1' size='45' list='list_cuentas'  onchange='traer_cuenta(this,1,1)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type='text' id='con_descripcion_haber"+i+"' name='con_descripcion_haber"+i+"' value='"+con_descripcion_haber.value+"' lang='"+i+"' class='form-control' size='30' readonly/>"+
                                        "</td>"+
                                        "<td >"+
                                          "<input type='text' id='con_valor_haber"+i+"' name='con_valor_haber"+i+"' size='10' value='"+con_valor_haber.value+"' lang='"+i+"' class='form-control decimal' style='text-align:right' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila_det(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                con_documento.value = '';
                con_concepto_debe.value = '';
                con_descripcion_debe.value = '';
                con_valor_debe.value = '0';
                con_concepto_haber.value = '';
                con_descripcion_haber.value = '';
                con_valor_haber.value = '0';
                $('#con_documento').focus();
                calculo();
                
            }

            function elimina_fila_det(obj) {
                  var parent = $(obj).parents();
                  $(parent[0]).remove();
                  calculo();
            }
 
            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            }


            function calculo(obj) {
                var tr = $('#lista').find("tr:last");
                var a = tr.find("input").attr("lang");
                i = parseInt(a);
                n = 0;
                var tot = 0;
                tdebe = 0;
                thaber = 0;
                while (n < i) {
                    n++;
                    if ($('#item' + n).val() != null) {
                      tdebe+= round($('#con_valor_debe'+n).val().replace(',', ''),dec);
                      thaber+= round($('#con_valor_haber'+n).val().replace(',', ''),dec);
                    }
                }
                
                $('#total_debe').val(parseFloat(tdebe).toFixed(dec));
                $('#total_haber').val(parseFloat(thaber).toFixed(dec));
            }     

            function traer_cuenta(obj, opc,tipo) {
              var uri = base_url+'asiento/traer_cuenta/'+ $(obj).val();
              j=obj.lang;
              $.ajax({
                  url: uri, //this is your uri
                  type: 'GET', //this is your method
                  dataType: 'json',
                  success: function (response) {
                    if(tipo==0){
                      if(opc==0){
                        $("#con_descripcion_debe").val(response['pln_descripcion']);
                      }else{
                        $("#con_descripcion_haber").val(response['pln_descripcion']);
                      }
                    }else{
                      if(opc==0){
                        $("#con_descripcion_debe"+j).val(response['pln_descripcion']);
                      }else{
                        $("#con_descripcion_haber"+j).val(response['pln_descripcion']);
                      }
                    }  
                  },
                  error : function(xhr, status) {
                      alert('No existe Cuenta');
                      if(tipo==0){
                        if(opc==0){
                          $("#con_descripcion_debe").val('');
                        }else{
                          $("#con_descripcion_haber").val('');
                        }
                      }else{
                        if(opc==0){
                          $("#con_descripcion_debe"+j).val('');
                        }else{
                          $("#con_descripcion_haber"+j).val('');
                        }
                      }  
                  }
              });
          } 

            function save() {
                        if (con_fecha_emision.value.length == 0) {
                            $("#con_fecha_emision").css({borderColor: "red"});
                            $("#con_fecha_emision").focus();
                            return false;
                        } else if (con_concepto.value.length == 0) {
                            $("#con_concepto").css({borderColor: "red"});
                            $("#con_concepto").focus();
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
                                if ($('#con_documento' + n).html() != null) {
                                  k++;
                                    if ($('#con_documento' + n).val().length == 0) {
                                        $('#con_documento' + n).css({borderColor: "red"});
                                        $('#con_documento' + n).focus();
                                        return false;
                                    } else if ($('#con_concepto_debe' + n).val().length == 0 && $('#con_concepto_haber' + n).val().length == 0) {
                                        alert("Ingrese una cuenta en el debe o haber");
                                        return false;
                                    }  else if ($('#con_valor_debe' + n).val().length == 0) {
                                        $('#con_valor_debe' + n).css({borderColor: "red"});
                                        $('#con_valor_debe' + n).focus();
                                        return false;
                                    }else if ($('#con_valor_haber' + n).val().length == 0) {
                                        $('#con_valor_haber' + n).css({borderColor: "red"});
                                        $('#con_valor_haber' + n).focus();
                                        return false;
                                    }

                                }
                            }
                        }

                        if(parseFloat($('#total_debe').val())!=parseFloat($('#total_haber').val())){
                          alert('Los Totales del Debe y Haber tienen que ser iguales');
                          return false;
                        }

                        if(k==0){
                          alert('No se puede Guardar asiento sin detalle');
                          return false;
                        }
                        
                     $('#frm_save').submit();   
               }   
    </script>

