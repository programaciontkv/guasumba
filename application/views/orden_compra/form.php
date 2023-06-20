
<section class="content-header">
  <h1>
    Ordenes de Compra
  </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $dec = $dec->con_valor;
      $dcc = $dcc->con_valor;
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
                    <td><label>Fecha de Orden:</label></td>
                    <td>
                      <div class="form-group <?php if(form_error('orc_fecha')!=''){ echo 'has-error';}?> ">
                        <input type="hidden" name="orc_codigo" id="orc_codigo" value="<?php echo $orden->orc_codigo;?>">
                        <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $orden->emp_id;?>">
                        <input type="date" class="form-control" name="orc_fecha" id="orc_fecha" value="<?php if(validation_errors()!=''){ echo set_value('orc_fecha');}else{ echo $orden->orc_fecha;}?>" >
                        <?php echo form_error("orc_fecha","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                    <td><label>Fecha de Entrega:</label></td>
                    <td>
                      <div class="form-group <?php if(form_error('orc_fecha_entrega')!=''){ echo 'has-error';}?> ">
                        <input type="date" class="form-control" name="orc_fecha_entrega" id="orc_fecha_entrega" value="<?php if(validation_errors()!=''){ echo set_value('orc_fecha_entrega');}else{ echo $orden->orc_fecha_entrega;}?>" >
                        <?php echo form_error("orc_fecha_entrega","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                    <td><label>Direccion de Entrega:</label></td>
                    <td>
                      <div class="form-group <?php if(form_error('orc_direccion_entrega')!=''){ echo 'has-error';}?> ">
                        <input type="text" class="form-control" name="orc_direccion_entrega" id="orc_direccion_entrega" value="<?php if(validation_errors()!=''){ echo set_value('orc_direccion_entrega');}else{ echo $orden->orc_direccion_entrega;}?>">
                        <?php echo form_error("orc_direccion_entrega","<span class='help-block'>","</span>");?>
                      </div>
                    </td>
                  </tr>
                  <tr>

                    <td><label>Condicion de Pago:</label></td>
                    <td>
                      <div class="form-group <?php if(form_error('orc_condicion_pago')!=''){ echo 'has-error';}?> ">
                       <select id="orc_condicion_pago" name="orc_condicion_pago" class="form-control" >
                        <option value="CONTADO">CONTADO</option>
                        <option value="A 8 DIAS">A 8 DIAS</option>
                        <option value="A 30 DIAS">A 30 DIAS</option>
                        <option value="A 60 DIAS">A 60 DIAS</option>
                        <option value="A 90 DIAS">A 90 DIAS</option>
                        <option value="A 120 DIAS">A 120 DIAS</option>
                        <option value="A 150 DIAS">A 150 DIAS</option>
                      </select>
                      <script type="text/javascript">
                        var pago='<?php echo $orden->orc_condicion_pago?>';
                        orc_condicion_pago.value=pago;
                      </script>
                      <?php echo form_error("orc_condicion_pago","<span class='help-block'>","</span>");?>
                    </div>
                  </td>
                  <td><label>Concepto:</label></td>
                  <td>
                    <div class="form-group <?php if(form_error('orc_concepto')!=''){ echo 'has-error';}?> ">
                      <input type="text" class="form-control" name="orc_concepto" id="orc_concepto" value="<?php if(validation_errors()!=''){ echo set_value('orc_concepto');}else{ echo $orden->orc_concepto;}?>">
                      <?php echo form_error("orc_concepto","<span class='help-block'>","</span>");?>
                    </div>
                  </td> 
                  <td><label>Proveedor:</label></td>
                  <td>
                    <div class="form-group <?php if(form_error('cli_nombre')!=''){ echo 'has-error';}?> ">
                      <input type="text" class="form-control" name="cli_nombre" id="cli_nombre" value="<?php if(validation_errors()!=''){ echo set_value('cli_nombre');}else{ echo $orden->cli_raz_social;}?>" list='list_clientes' onchange="traer_cliente()">
                      <?php echo form_error("cli_nombre","<span class='help-block'>","</span>");?>
                      <input type ="text" id="cli_id" name="cli_id" value="<?php echo $orden->cli_id?>" hidden/>
                    </div>
                  </td>  
                </tr>

              </table>
            </td>
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
                      <th>Cantidad</th>
                      <th>IVA</th>
                      <th>Val.Unit</th>
                      <th>Val.Tot</th>
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
                        <input style="text-align:left;width: 300px; " type="text" class="form-control" id="pro_codigo" name="pro_codigo"  lang="1"   maxlength="16"  list="productos" onchange="load_producto(this.lang)"/>
                      </td>
                      <td>
                        <input style="text-align:left " type ="text" size="40" class="refer form-control"  id="pro_descripcion" name="pro_descripcion"   value="" lang="1" readonly style="width:300px;height:20px;font-size:11px;font-weight:100 "  />
                      </td>
                      <td>
                        <input type ="text" size="7" id="pro_uni" name="pro_uni" lang="1" readonly class="form-control" />
                      </td>

                      <td>
                        <input type ="text" size="7" style="text-align:right" id="orc_det_cant" name="orc_det_cant"  value="" lang="1" onchange="calculo_enc()" class="form-control decimal" />
                      </td>
                      <td>
                        <input type ="text" size="7" style="text-align:right" id="orc_det_iva" name="orc_det_iva" onchange="calculo_enc()" value="" lang="1" class="form-control" readonly/>
                      </td>
                      <td>
                        <input type ="text" size="7" style="text-align:right" id="orc_det_vu" name="orc_det_vu" onchange="calculo_enc()" value="" lang="1" class="form-control decimal" />
                      </td>
                      <td>
                        <input type ="text" size="7"  style="text-align:right" id="orc_det_vt" name="orc_det_vt"  value="" lang="1" onchange="calculo_enc()" class="form-control decimal" readonly/>
                      </td>

                      <td align="center" ><input  type="button" name="add1" id="add1" class="btn btn-primary fa fa-plus" onclick="validar()" lang="1" value='+'/> </td>
                    </tr>

                  </tbody>
                  <tbody id='lista'>
                    <?php
                    $count_det=0;
                    if(!empty($detalle)){
                      $n=0;
                      foreach ($detalle as $det) {
                        $n++;
                    ?>
                    <tr>
                      <td id="item<?php echo $n?>" align="center" lang="<?php echo $n?>">
                        <?php echo $n?>
                        <input type ="hidden"  id="pro_id<?php echo $n?>" name="pro_id<?php echo $n?>" lang="<?php echo $n?>" value="<?php echo $det->mp_id?>"/>
                      </td>
                      <td id="pro_codigo<?php echo $n?>" name="pro_codigo<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_c?></td>
                      <td id="pro_descripcion<?php echo $n?>" name="pro_descripcion<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_d?></td>
                      <td id="pro_uni<?php echo $n?>" name="pro_uni<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_q?></td>

                      <td >
                        <input type ="text" size="7" style="text-align:right" id="orc_det_cant<?php echo $n?>" name="orc_det_cant<?php echo $n?>"  value="<?php echo str_replace(',','',number_format($det->orc_det_cant,$dec))?>" lang="<?php echo $n?>" onchange="calculo()" class="form-control decimal" />
                      </td>
                      <td>
                        <input type ="text" size="7" style="text-align:right" id="orc_det_iva<?php echo $n?>" name="orc_det_iva<?php echo $n?>" onchange="calculo()" value="<?php echo $det->orc_det_iva?>" lang="<?php echo $n?>" class="form-control" readonly/>
                      </td>
                      <td>
                        <input type ="text" size="7" style="text-align:right" id="orc_det_vu<?php echo $n?>" name="orc_det_vu<?php echo $n?>" onchange="calculo()" value="<?php echo str_replace(',','',number_format($det->orc_det_vu,$dec))?>" lang="<?php echo $n?>" class="form-control decimal" />
                      </td>
                      <td>
                        <input type ="text" size="7"  style="text-align:right" id="orc_det_vt<?php echo $n?>" name="orc_det_vt<?php echo $n?>"  value="<?php echo str_replace(',','',number_format($det->orc_det_vt,$dec))?>" lang="<?php echo $n?>" onchange="calculo()" class="form-control decimal" readonly/>
                      </td>
                      <td onclick='elimina_fila(this)' align='center' ><span class='btn btn-danger fa fa-trash'></span></td>
                      
                    </tr>
                    <?php    
                      }
                      $count_det=$n;
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3"><label>Observaciones (250 Caracteres max.):</label></td>
                    </tr>
                    <tr>

                      <td valign="top" rowspan="9" colspan="5">
                        <textarea style="height: 80px; width: 90%;" id="orc_obs" name="orc_obs"   onkeydown="return enter(event)" maxlength="250" ><?php echo $orden->orc_obs ?></textarea>
                      </td>    
                      <td colspan="2" align="right">Subtotal 12%:</td>
                      <td>
                        <input style="text-align:right" type="text" class="form-control" id="orc_sub12" name="orc_sub12" value="<?php echo str_replace(',', '', number_format($orden->orc_sub12, $dec)) ?>" readonly/>

                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" align="right">Subtotal 0%:</td>
                      <td>
                        <input style="text-align:right"  type="text" class="form-control" id="orc_sub0" name="orc_sub0" value="<?php echo str_replace(',', '', number_format($orden->orc_sub0, $dec)) ?>" readonly/>

                      </td>
                    </tr>

                    <tr>
                      <td align="right">Descuento %:</td>
                      <td><input style="text-align:right" size="5" type="text" class="form-control" id="orc_descuento" name="orc_descuento" value="<?php echo str_replace(',', '', number_format($orden->orc_descuento, $dec)) ?>" onchange="calculo()"/>
                      </td>
                      <td><input style="text-align:right" type="text" class="form-control" id="orc_descv" name="orc_descv" value="<?php echo str_replace(',', '', number_format($orden->orc_descv, $dec)) ?>" readonly/>
                      </td>
                    </tr>
                    
                    <tr>
                      <td colspan="2" align="right">IVA 12%:</td>
                      <td><input style="text-align:right" type="text" class="form-control" id="orc_iva" name="orc_iva" value="<?php echo str_replace(',', '', number_format($orden->orc_iva, $dec)) ?>" readonly />
                      </td>
                    </tr> 
                    <tr>
                      <td colspan="2" align="right">Flete:</td>
                      <td><input type="text" class="form-control" id="orc_flete" name="orc_flete" value="<?php echo str_replace(',', '', number_format($orden->orc_flete, $dec)) ?>"  style="text-align:right" onchange="calculo()"/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" align="right">Total:</td>
                      <td><input style="text-align:right;font-size:15px;color:red  " type="text" class="form-control" id="orc_total" name="orc_total" value="<?php echo str_replace(',', '', number_format($orden->orc_total, $dec)) ?>" readonly />

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
    <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $count_det?>">
    <input type="hidden" class="form-control" id="orc_id" name="orc_id" value="<?php echo $orden->orc_id?>">
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
    var dec='<?php echo $dec?>';
    var dcc='<?php echo $dcc?>';
    var base_url='<?php echo base_url();?>';

    function validar_decimal(obj){
      obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
    }

    function traer_cliente(){
      $.ajax({
        beforeSend: function () {
          if ($('#cli_nombre').val().length == 0) {
            alert('Ingrese Proveedor');
            return false;
          }
        },
        url: base_url+"orden_compra/traer_cliente/"+cli_nombre.value,
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

    
    function load_producto(j) {
      vl = $('#pro_codigo').val();
      $.ajax({
        beforeSend: function () {
          if ($('#pro_codigo').val().length == 0) {
            alert('Ingrese un producto');
            return false;
          }
        },
        url: base_url+"orden_compra/load_producto/"+vl+"/"+cli_id.value,
        type: 'JSON',
        dataType: 'JSON',
        success: function (dt) {

          if (dt!='') {
            $('#pro_id').val(dt.pro_id);
            $('#pro_codigo').val(dt.pro_codigo);
            $('#pro_descripcion').val(dt.pro_descripcion);
            $('#pro_uni').val(dt.pro_unidad);
            if (dt.pro_iva =='') {
              $('#orc_det_iva').val('0');
            }else{
              $('#orc_det_iva').val(dt.pro_iva);
            }

            if (dt.val_unit == '') {
              $('#orc_det_vu').val('0.01');
            } else {
              $('#orc_det_vu').val(parseFloat(dt.val_unit).toFixed(dec));
            }
            
            $('#orc_det_cant').focus();
          }else{
            $('#pro_id').val('');
            $('#pro_descripcion').val('');
            $('#pro_codigo').val('');
            $('#pro_uni').val('');
            $('#mov_cantidad').val('');
            $('#orc_det_iva').val('0');
            $('#orc_det_vu').val('0');
            $('#orc_det_vt').val('0');
            $('#pro_codigo').focus();
          }
        }
      });
    }


    function round(value, decimals) {
      return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
    } 

    function calculo_enc() {
    
            uni = $('#orc_det_vu').val();
            cnt = $('#orc_det_cant').val().replace(',', '');
            if(uni==''){
              cnt=0;
            }

            if(cnt==''){
              cnt=0;
            }
            vtp = round(cnt,dcc) * round(uni,dec); 
            $('#orc_det_vt').val(vtp.toFixed(dec));

  }     


    function validar(){
      if ($('#pro_codigo').val().length != 0 && ($('#orc_det_cant').val().length != 0 && parseFloat($('#orc_det_cant').val()) !=0)&& ($('#orc_det_vu').val().length != 0 && parseFloat($('#orc_det_vt').val()) !=0)) {
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
            cant = round($('#orc_det_cant' + n).val(),dcc) + round(orc_det_cant.value,dcc);
            tot = cant * round(orc_det_vt.value,dec);
            $('#orc_det_cant' + n).val(cant.toFixed(dcc));
            $('#orc_det_vu' + n).val(orc_det_vu.value);
            $('#orc_det_vt' + n).val(tot.toFixed(dec));
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
        "<td>"+
        "<input type ='text' class='form-control decimal' size='7' style='text-align:right' id='orc_det_cant"+i+"' name='orc_det_cant"+i+"' lang='"+i+"' value='"+orc_det_cant.value +"' onkeyup='validar_decimal(this)' onchange='calculo()'/>"+
        "</td>"+
        "<td>"+
        "<input type ='text' size='7' style='text-align:right' id='orc_det_iva"+i+"' name='orc_det_iva"+i+"' onchange='calculo()' value='"+orc_det_iva.value+"' lang='"+i+"' class='form-control' readonly/>"+
        "</td>"+
        "<td>"+
        "<input type ='text' size='7' style='text-align:right' id='orc_det_vu"+i+"' name='orc_det_vu"+i+"' onchange='calculo()' value='"+orc_det_vu.value+"' lang='"+i+"' class='form-control decimal' onkeyup='validar_decimal(this)'/>"+
        "</td>"+
        "<td>"+
        "<input type ='text' size='7'  style='text-align:right' id='orc_det_vt"+i+"' name='orc_det_vt"+i+"'  lang='"+i+"' onchange='calculo()' class='form-control decimal' readonly value='"+orc_det_vt.value+"' onkeyup='validar_decimal(this)'/>"+
        "</td>"+
        "<td onclick='elimina_fila(this)' align='center' >"+"<span class='btn btn-danger fa fa-trash'>"+"</span>"+"</td>"+
        "</tr>";
        $('#lista').append(fila);
        $('#count_detalle').val(i);
      }
      pro_codigo.value = '';
      pro_descripcion.value = '';
      pro_id.value = '';
      orc_det_vu.value = '';
      orc_det_vt.value = '';
      pro_uni.value = '';
      orc_det_iva.value = '';
      orc_det_cant.value = '';
      $('#orc_det_cant').css({borderColor: ""});
      $('#pro_codigo').focus();
      calculo();
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
          uni = $('#orc_det_vu' + n).val();
          cnt = $('#orc_det_cant' + n).val().replace(',', '');
          if(cnt==''){
            cnt=0;
          }

          if(uni==''){
            uni=0;
          }
            vtp = round(cnt,dcc) * round(uni,dec); //Valor total parcial
            
            
            $('#orc_det_vt' + n).val(vtp.toFixed(dec));
            ob = $('#orc_det_iva' + n).val();
            val = $('#orc_det_vt' + n).val().replace(',', '');

          }

          if (ob == '12') {
            t12 = (round(t12,dec) * 1 + round(vtp,dec) * 1);
            tiva = ((round(tice,dec) + round(t12,dec)) * 12 / 100);
          }

          if (ob == '0') {
            t0 = (round(t0,dec) * 1 + round(vtp,dec) * 1);
          }
          if (ob == 'EX') {
            tex = (round(tex,dec) * 1 + round(vtp,dec) * 1);
          }
          if (ob == 'NO') {
            tno = (round(tno,dec) * 1 + round(vtp,dec) * 1);
          }

        }
        desc=$('#orc_descuento').val().replace(',', '');
        sub = round(t12,dec) + round(t0,dec) + round(tex,dec) + round(tno,dec);
        tdsc=round(desc,dec)*round(sub,dec)/100;
        sub0 = round(t0,dec) + round(tex,dec) + round(tno,dec);
        fle = $('#orc_flete').val().replace(',', '');
        gtot = (round(sub,dec) * 1 + round(tiva,dec) * 1 - round(tdsc,dec) * 1 + round(fle,dec) * 1);


        $('#orc_sub12').val(t12.toFixed(dec));
        $('#orc_sub0').val(sub0.toFixed(dec));
        $('#orc_descv').val(tdsc.toFixed(dec));
        $('#orc_iva').val(tiva.toFixed(dec));
        $('#orc_total').val(gtot.toFixed(dec));
      }     

    function elimina_fila(obj) {
      var parent = $(obj).parents();
      $(parent[0]).remove();
      calculo();
    }

    function save(){

      if (orc_direccion_entrega.value.length == 0) {
        $("#orc_direccion_entrega").css({borderColor: "red"});
        $("#orc_direccion_entrega").focus();
        return false;
      }else if (orc_concepto.value.length == 0) {
        $("#orc_concepto").css({borderColor: "red"});
        $("#orc_concepto").focus();
        return false;
      }else if (cli_id.value.length == 0 || cli_id.value == 0) {
        $("#cli_nombre").css({borderColor: "red"});
        $("#cli_nombre").focus();
        return false;
      }else if (orc_descuento.value.length == 0 ) {
        $("#orc_descuento").css({borderColor: "red"});
        $("#orc_descuento").focus();
        return false;
      }else if (orc_flete.value.length == 0 ) {
        $("#orc_flete").css({borderColor: "red"});
        $("#orc_flete").focus();
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
        if($('#orc_det_cant'+n).val()!=null){
          if($('#orc_det_cant'+n).val().length==0){
            $("#orc_det_cant"+n).css({borderColor: "red"});
            $("#orc_det_cant"+n).focus();
            return false;
          }
          else if($('#orc_det_vu'+n).val().length==0){
            $("#orc_det_vu"+n).css({borderColor: "red"});
            $("#orc_det_vu"+n).focus();
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
