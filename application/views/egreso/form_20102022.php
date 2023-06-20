
<section class="content-header">
      <h1>
        Egresos a Bodega
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-12">
          <?php 
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
                      <td class="col-sm-6">
                        <table class="table">
                          <tr>
                              <td><label>Fecha de egreso:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('mov_fecha_trans')!=''){ echo 'has-error';}?> ">
                                <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $egreso->emp_id;?>">
                                <input type="hidden" name="emi_id" id="emi_id" value="<?php echo $egreso->emi_id;?>">
                                <input type="text" class="form-control" name="mov_fecha_trans" id="mov_fecha_trans" value="<?php if(validation_errors()!=''){ echo set_value('mov_fecha_trans');}else{ echo $egreso->mov_fecha_trans;}?>" readonly>
                                  <?php echo form_error("mov_fecha_trans","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                              <td><label>Documento/Informacion:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('mov_guia_transporte')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="mov_guia_transporte" id="mov_guia_transporte" value="<?php if(validation_errors()!=''){ echo set_value('mov_guia_transporte');}else{ echo $egreso->mov_guia_transporte;}?>">
                                  <?php echo form_error("mov_guia_transporte","<span class='help-block'>","</span>");?>
                                </div>
                              </td>
                          </tr>
                          <tr>
                              <td><label>Transaccion:</label></td>
                              <td>
                                <div class="form-group ">
                                  <input type="text" class="form-control" name="trs_descripcion" id="trs_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('trs_descripcion');}else{ echo $egreso->trs_descripcion;}?>" readonly>
                                  <?php echo form_error("cli_nombre","<span class='help-block'>","</span>");?>
                                  <input type ="text" id="trs_id" name="trs_id" value="<?php echo $egreso->trs_id?>" hidden/></td>
                                </div>
                              </td> 
                              <td><label>Proveedor:</label></td>
                              <td>
                              <div class="form-group <?php if(form_error('cli_nombre')!=''){ echo 'has-error';}?> ">
                                <input type="text" class="form-control" name="cli_nombre" id="cli_nombre" value="<?php if(validation_errors()!=''){ echo set_value('cli_nombre');}else{ echo $egreso->cli_nombre;}?>" list='list_clientes' onchange="traer_cliente()">
                                  <?php echo form_error("cli_nombre","<span class='help-block'>","</span>");?>
                                  <input type ="text" id="cli_id" name="cli_id" value="<?php echo $egreso->cli_id?>" hidden/></td>
                                </div>
                              </td>   
                            </table>
                          </tr>    
                    </tr>
                    <tr>
                       <td class="col-sm-12">
                          <div class="box-body">
                          <div class="panel panel-default col-sm-12">
                          
                          <table class="table table-bordered table-striped" id="tbl_detalle">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Unidad</th>
                                <th>Inventario</th>
                                <th>Cantidad</th>
                                <th>Cost. Unit</th>
                                <th>Cost. Tot</th>
                                <th></th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td align="center">
                                          <input type ="hidden"  id="pro_id" name="pro_id" lang="1" />
                                        </td>
                                        <td>
                                            <input style="text-align:left " type="text" style="width:  300px;" class="form-control" id="pro_codigo" name="pro_codigo"  lang="1"   maxlength="16"  list="productos" onchange="load_producto(this.lang)"/>
                                        </td>
                                        <td>
                                            <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_descripcion" name="pro_descripcion"   value="" lang="1" readonly style="width:300px;height:20px;font-size:11px;font-weight:100 "  />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" id="pro_uni" name="pro_uni" lang="1" readonly class="form-control" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" id="inventario" name="inventario" lang="1" readonly class="form-control" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="mov_cantidad" name="mov_cantidad"  value="" lang="1" onchange="validar_inventario(this), costo(this, 1)" class="form-control decimal" />
                                        </td>
                                        <td>
                                          <input type ="text" size="7" style="text-align:right" id="mov_cost_unit" name="mov_cost_unit" onchange="costo(this, 1)" value="" lang="1" class="form-control decimal" readonly/>
                                        </td>
                                        <td>
                                          <input type ="text" size="7"  style="text-align:right" id="mov_cost_tot" name="mov_cost_tot"  value="" lang="1" onchange="costo(this, 2)" class="form-control decimal" readonly/>
                                        </td>
                                        
                                        <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar()" lang="1" value='+'/> </td>
                                    </tr>
                                    
                                </tbody>
                                <tbody id='lista'></tbody>
                            <tfoot>
                                <tr>
                                    <th class="totales" colspan="5" align="right">Total:</th>
                                    <th class="totales" id="total"></th>
                                    <th class="totales" id="tu"></th>
                                    <th class="totales" id="tt"></th>
                                </tr>
                                
                              </tfoot>
                          </table>
                          </div>
                          </div>
                          </td>
                    </tr> 
                    
                    
                  </table>
              </div>
                <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="0">
              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="save()">Guardar</button>
                <a href="<?php echo $cancelar?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
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
    foreach ($cns_pro  as $rst_pro) {
        ?>
        <option value="<?php echo $rst_pro->id ?>" label="<?php echo $rst_pro->mp_c . ' ' . $rst_pro->mp_d?>" />
        <?php
    }
    ?>
</datalist>
    <script>
      var dec='<?php echo $dec->con_valor?>';
      var dcc='<?php echo $dcc->con_valor?>';
      var base_url='<?php echo base_url();?>';

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }

      function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#cli_nombre').val().length == 0) {
                            alert('Ingrese Cliente');
                            return false;
                      }
                    },
                    url: base_url+"movimiento/traer_cliente/"+cli_nombre.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#cli_id').val(dt.cli_id);
                          $('#cli_nombre').val(dt.cli_raz_social);
                        }else{
                          alert('Proveedor no existe');
                          $('#cli_id').val('');
                          $('#cli_nombre').val('');
                        } 
                        
                    },
                    error : function(xhr, status) {
                          alert('Proveedor no existe');
                          $('#cli_id').val('');
                          $('#cli_nombre').val('');
                    }
                    });    
            }
            
            function total() {
                var tr = $('#lista').find("tr:last");
                a = tr.find("input").attr("lang");
                i = parseInt(a);
                n = 0;
                sum = 0;
                su = 0;
                st = 0;
                while (n < i) {
                    n++;
                  if($('#mov_cantidad'+n).val()!=null){  
                    if ($('#mov_cantidad' + n).val().length == 0) {
                        can = 0;
                    } else {
                        can = $('#mov_cantidad' + n).val();
                    }
                    if ($('#mov_cost_unit' + n).val().length == 0) {
                        u = 0;
                    } else {
                        u = $('#mov_cost_unit' + n).val();
                    }
                    if ($('#mov_cost_tot' + n).val().length == 0) {
                        t = 0;
                    } else {
                        t = $('#mov_cost_tot' + n).val();
                    }
                    sum = sum + round(can,dcc);
                    su = su + round(u,dec);
                    st = st + round(t,dec);
                  }
                }

                $('#total').html(sum.toFixed(dcc));
                $('#tu').html(su.toFixed(dec));
                $('#tt').html(st.toFixed(dec));
            } 
            
            function seleccion() {
                if ($('#trs_id').val().length == 0) {
                        $('#pro_codigo').attr('readonly', true);
                } else {
                        $('#pro_codigo').attr('readonly', false);
                        if ($('#trs_id').val().substring(0, 1) == 1) {
                            $('#mov_cost_unit').attr('readonly', true);
                            $('#mov_cost_tot').attr('readonly', true);
                        } else {
                            $('#mov_cost_unit').attr('readonly', false);
                            $('#mov_cost_tot').attr('readonly', false);
                        }
                }
            }

            function validar_inventario(obj) {
                if ($('#trs_id').val().substring(0, 1) == 1) {
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
                                if ($('#pro_id' + n).val() == pro_id.value) {
                                    cant = round($('#mov_cantidad' + n).val(),dcc) + round(mov_cantidad.value,dcc);
                                }else{
                                    cant = mov_cantidad.value;   
                                }

                                if (parseFloat($('#inventario').val()) < parseFloat(cant)) {
                                    alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                                    
                                        $('#mov_cantidad').val('');
                                        $('#mov_cantidad').focus();
                                        $('#mov_cantidad').css({borderColor: "red"});
                                        v=1;
                                }
                            }
                        }else{
                          if (parseFloat($('#inventario').val()) < parseFloat($(obj).val())) {
                              alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                              $(obj).val('');
                              $(obj).focus();
                              $(obj).css({borderColor: "red"});
                              costo(obj,1);
                          }
                        }  
                }
            }

            function validar_inventario_det() {
                if ($('#trs_id').val().substring(0, 1) == 1) {
                    var tr = $('#lista').find("tr:last");
                    a = tr.find("input").attr("lang");
                    i = parseInt(a);
                    n = 0;
                    while (n < i) {
                        n++;
                        if($('#mov_cantidad'+n).val()!=null){
                          if (parseFloat($('#inventario'+n).html()) < parseFloat($('#mov_cantidad'+n).val())) {
                              alert('NO SE PUEDE REGISTRAR LA CANTIDAD\n ES MAYOR QUE EL INVENTARIO');
                              $('#mov_cantidad'+n).val('');
                              $('#mov_cantidad'+n).focus();
                              $('#mov_cantidad'+n).css({borderColor: "red"});
                              
                          }
                        }    
                    }  
                }
                costo_detalle(1);
            }

            function costo(obj, x) {
                can = $('#mov_cantidad').val();
                uni = $('#mov_cost_unit').val() * 1;
                tot = $('#mov_cost_tot').val();
                if(can.length==0){
                    can=0;
                }
                if(uni.length==0){
                    uni=0;
                }
                if(tot.length==0){
                    tot=0;
                }
                if (x == 1) {
                    t = round(can,dcc) * round(uni,dec);
                    $('#mov_cost_tot').val(t.toFixed(dec));
                } else {
                    if (can != 0) {
                        t = round(tot,dec) / round(can,dcc);
                    } else {
                        t = 0;
                    }
                    $('#mov_cost_unit').val(t.toFixed(dec));
                }
            }

            function costo_detalle(x) {
              var tr = $('#tbl_detalle').find("tbody tr:last");
                  a = tr.find("input").attr("lang");
                  i = parseInt(a);
                  n = 0;
                  while (n < i) {
                    n++;
                    if($('#mov_cantidad'+n).val()!=null){
                      can = $('#mov_cantidad'+n).val();
                      uni = $('#mov_cost_unit'+n).val() * 1;
                      tot = $('#mov_cost_tot'+n).val();
                      if(can.length==0){
                          can=0;
                      }
                      if(uni.length==0){
                          uni=0;
                      }
                      if(tot.length==0){
                          tot=0;
                      }
                      if (x == 1) {
                          t = round(can,dcc) * round(uni,dec);
                          $('#mov_cost_tot'+n).val(t.toFixed(dec));
                      } else {
                          if (can != 0) {
                              t = round(tot,dec) / round(can,dcc);
                          } else {
                              t = 0;
                          }
                          $('#mov_cost_unit'+n).val(t.toFixed(dec));
                      }
                    }  
                  }  
                  total();
            }

            function load_producto(j) {
                vl = $('#pro_codigo').val();
                $.ajax({
                  beforeSend: function () {
                      if ($('#pro_codigo').val().length == 0) {
                            alert('Ingrese un producto');
                            return false;
                      }
                    },
                    url: base_url+"movimiento/load_producto/"+vl+"/"+emi_id.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {

                      if (dt!='') {
                        $('#pro_id').val(dt.pro_id);
                        $('#pro_codigo').val(dt.pro_codigo);
                        $('#pro_descripcion').val(dt.pro_descripcion);
                        $('#pro_uni').val(dt.pro_unidad);
                        
                        if (dt.cost_unit == '') {
                            $('#mov_cost_unit').val('0.01');
                        } else {
                            $('#mov_cost_unit').val(parseFloat(dt.cost_unit).toFixed(dec));
                        }
                        if (dt.inventario == '') {
                            $('#inventario').val('0');
                        } else {
                            $('#inventario').val(parseFloat(dt.inventario).toFixed(dcc));
                        }
                        $('#mov_cantidad').focus();
                      }else{
                        $('#pro_id').val('');
                        $('#pro_descripcion').val('');
                        $('#pro_codigo').val('');
                        $('#pro_uni').val('');
                        $('#mov_cantidad').val('');
                        $('#mov_cost_unit').val('0');
                        $('#inventario').val('0');
                        $('#pro_codigo').focus();
                      }
                    }
                  });
                }
              

            function round(value, decimals) {
                  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
            } 

            function validar(){
              if ($('#pro_codigo').val().length != 0 && ($('#mov_cantidad').val().length != 0 && parseFloat($('#mov_cantidad').val()) !=0)&& ($('#mov_cost_unit').val().length != 0 && parseFloat($('#mov_cost_tot').val()) !=0)) {
                clonar();
              }
            }

            function clonar() {
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
                        if ($('#pro_id' + n).val() == pro_id.value) {
                            d = 1;
                            cant = round($('#mov_cantidad' + n).val(),dcc) + round(mov_cantidad.value,dcc);
                            tot = cant * round(mov_cost_unit.value,dec);
                            $('#mov_cantidad' + n).val(cant.toFixed(dcc));
                            $('#mov_cost_unit' + n).val(mov_cost_unit.value);
                            $('#mov_cost_tot' + n).val(tot.toFixed(dec));
                        }
                    }
                }
                if (d == 0) {
                    i = j + 1;
                    var fila = "<tr>"+
                                        "<td id='item"+i+"' lang='"+i+"' align='center'>"+
                                          i+
                                          "<input type ='hidden' name='pro_id"+i+"' id='pro_id"+i+"' lang='"+i+"' value='"+pro_id.value+"'/>"+
                                        "</td>"+
                                        "<td id='pro_codigo"+i+"' lang='"+i+"'>"+pro_codigo.value+"</td>"+
                                        "<td id='pro_descripcion"+i+"' lang='"+i+"'>"+pro_descripcion.value+"</td>"+
                                        "<td id='pro_uni"+i+"' lang='"+i+"'>"+pro_uni.value+"</td>"+
                                        "<td id='inventario"+i+"' lang='"+i+"' align='right'>"+inventario.value+"</td>"+
                                        "<td>"+
                                          "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='mov_cantidad"+i+"' name='mov_cantidad"+i+"' lang='"+i+"' onchange='validar_inventario_det()'  value='"+mov_cantidad.value +"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7' style='text-align:right' id='mov_cost_unit"+i+"' name='mov_cost_unit"+i+"' onchange='costo_detalle(1)' value='"+mov_cost_unit.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)' readonly/>"+
                                        "</td>"+
                                        "<td>"+
                                          "<input type ='text' size='7'  style='text-align:right' id='mov_cost_tot"+i+"' name='mov_cost_tot"+i+"'  lang='"+i+"' onchange='costo_detalle(2)' class='form-control decimal' readonly value='"+mov_cost_tot.value+"' onkeyup='validar_decimal(this)'/>"+
                                        "</td>"+
                                        "<td onclick='elimina_fila(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
                                    "</tr>";
                    $('#lista').append(fila);
                    $('#count_detalle').val(i);
                }
                pro_codigo.value = '';
                pro_descripcion.value = '';
                pro_id.value = '';
                mov_cost_unit.value = '';
                mov_cost_tot.value = '';
                pro_uni.value = '';
                inventario.value = '';
                mov_cantidad.value = '';
                $('#mov_cantidad').css({borderColor: ""});
                $('#pro_codigo').focus();
                total();
            }

            function elimina_fila(obj) {
                var parent = $(obj).parents();
                $(parent[0]).remove();
                total();
            }

            function save(){

              if (trs_id.value.length == 0) {
                $("#trs_id").css({borderColor: "red"});
                $("#trs_id").focus();
                return false;
              }else if (cli_nombre.value.length == 0) {
                $("#cli_nombre").css({borderColor: "red"});
                $("#cli_nombre").focus();
                return false;
              }else if (cli_id.value.length == 0) {
                $("#cli_nombre").css({borderColor: "red"});
                $("#cli_nombre").focus();
                return false;
              }else if (count_detalle.value == 0) {
                alert('Ingrese al menos un detalle');
                return false;
              }
              var tr = $('#tbl_detalle').find("tbody tr:last");
                  a = tr.find("input").attr("lang");
                  i = parseInt(a);
                  n = 0;
                  while (n < i) {
                    n++;
                    if($('#mov_cantidad'+n).val()!=null){
                      if($('#mov_cantidad'+n).val().length==0){
                        $("#mov_cantidad"+n).css({borderColor: "red"});
                        $("#mov_cantidad"+n).focus();
                        return false;
                      }
                    }
                  }  

              frm_save.submit();

            }

    </script>
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
        margin-bottom: 3px !important;
        margin-top: 3px !important;
        padding-bottom: 3px !important;
        padding-top: 3px !important;
      }

      .totales{
        font-size: 16px !important;
        text-align: right !important;
      }
    </style>
      <!-- /.row -->
    </section>
