
<section class="content-header">
      <h1>
        Bancos y Cajas
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-6">
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
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off">
              <div class="box-body">

                
                <div class="form-group <?php if(form_error('byc_referencia')!=''){ echo 'has-error';}?> ">
                  <label>Referencia:</label>
                  <input type="text" class="form-control" name="byc_referencia" value="<?php if(validation_errors()!=''){ echo set_value('byc_referencia');}else{ echo $banco_caja->byc_referencia;}?>">
                  <?php echo form_error("byc_referencia","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('byc_num_cuenta')!=''){ echo 'has-error';}?> ">
                  <label>Cuenta:</label>
                  <input type="text" class="form-control" name="byc_num_cuenta" value="<?php if(validation_errors()!=''){ echo set_value('byc_num_cuenta');}else{ echo $banco_caja->byc_num_cuenta;}?>">
                  <?php echo form_error("byc_num_cuenta","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('byc_tipo')!=''){ echo 'has-error';}?> ">
                  <label>Tipo:</label>
                  <?php
                        if(validation_errors() != " "){
                              $tipo=$banco_caja->byc_tipo;
                            }else{
                              $tipo=set_value('byc_tipo');
                        }
                     ?>   
                  <select name="byc_tipo" id="byc_tipo" class="form-control" onchange="mostrar()">
                    <option value="">SELECCIONE</option>
                    <option value="0">BANCO</option>
                    <option value="1">CAJA</option>
                    <option value="2">CAJA CHICA</option>
                  </select>
                 
                 
                </div>  
                <div class="form-group <?php if(form_error('byc_tipo_cuenta')!=''){ echo 'has-error';}?> ">
                  <label>Tipo Cuenta:</label>

                  <?php
                        if(validation_errors() != " "){
                              $tipo_cuenta=$banco_caja->byc_tipo_cuenta;
                            }else{
                              $tipo_cuenta=set_value('byc_tipo_cuenta');
                        }
                     ?>
                   <select name="byc_tipo_cuenta" id="byc_tipo_cuenta" class="form-control" onchange="mostrar()">
                    <option value="">SELECCIONE</option>
                    <option value="0">CORRIENTE</option>
                    <option value="1">AHORROS</option>
                  </select>

                  <script type="text/javascript">
                    window.onload = function () {
                      var tipo='<?php echo $tipo?>';
                       byc_tipo.value=tipo;
                       var tipo_cuenta='<?php echo $tipo_cuenta?>';
                      byc_tipo_cuenta.value=tipo_cuenta;
                    } 
                  </script>
                  
                </div> 
                
                <div class="form-group <?php if(form_error('byc_documento')!=''){ echo 'has-error';}?> ">
                  <label># Documento:</label>
                  <input type="text" class="form-control" name="byc_documento" value="<?php if(validation_errors()!=''){ echo set_value('byc_documento');}else{ echo $banco_caja->byc_documento;}?>">
                  <?php echo form_error("byc_documento","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('byc_saldo')!=''){ echo 'has-error';}?> ">
                  <label># Saldo:</label>
                  <input type="text" class="form-control" name="byc_saldo" value="<?php if(validation_errors()!=''){ echo set_value('byc_saldo');}else{ echo $banco_caja->byc_saldo;}?>">
                  <?php echo form_error("byc_saldo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('byc_cuenta_contable')!=''){ echo 'has-error';}?> ">
                  <label># Cuenta Contable:</label>
                  <input type="text" class="form-control" name="byc_cuenta_contable" id="byc_cuenta_contable" list="cuentas" onchange="load_codigo(this)" value="<?php if(validation_errors()!=''){ echo set_value('byc_cuenta_contable');}else{ echo $banco_caja->byc_cuenta_contable;}?>">
                  <?php echo form_error("byc_cuenta_contable","<span class='help-block'>","</span>");?>
                  <label>Descripción de la cuenta</label>
                  <label><h5 id="byc_descrip_cta" name="byc_descrip_cta" ><?php echo $banco_caja->pln_descripcion  ?></h5></label>
                  <input type="hidden" class="form-control" name="byc_id_cuenta" id="byc_id_cuenta" value="<?php echo $banco_caja->pln_id  ?>">
                </div>

                <div class="form-group ">
                              <label>Estado</label>
                              <select name="byc_estado" id="byc_estado" class="form-control">
                                 <?php
                                  if(!empty($estados)){
                                    foreach ($estados as $estado) {
                                  ?>
                                  <option value="<?php echo $estado->est_id?>"><?php echo $estado->est_descripcion?></option>
                                  <?php
                                    }
                                  }
                                ?>
                              </select>
                              <?php 
                                if(validation_errors()!=''){
                                  $est=$banco_caja->byc_estado;
                                }else{
                                  $est=set_value('byc_estado');
                                }
                              ?>
                              <script type="text/javascript">
                              // var est='<?php echo $est;?>';
                              // vnd_estado.value=est;
                            </script>
                            </div>
                
                   
                
                
                <input type="hidden" class="form-control" name="byc_id" value="<?php echo $banco_caja->byc_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'bancos_tarjetas/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>

<datalist id="cuentas">
  <?php 
  var_dump($cuentas);
  if(!empty($cuentas)){
    foreach ($cuentas as $cuenta) {
  ?>
    <option value="<?php echo $cuenta->pln_id?>"><?php echo $cuenta->pln_codigo." ".$cuenta->pln_descripcion?></option>
  <?php
    }
  }
  ?>
</datalist>
<script type="text/javascript">
  var base_url='<?php echo base_url();?>';

  function load_codigo(obj) {
    var cuenta = $('#byc_cuenta_contable').val();
    uri=base_url+'bancos_cajas/load_codigo/'+cuenta;
    $.ajax({
    url: uri,
    type: 'POST',
    success: function(dt){
      dat = dt.split('&');
      if (dat[0] != '') {
      $('#byc_id_cuenta').val(dat[0]);
      $('#byc_cuenta_contable').val(dat[1]);
      $('#byc_descrip_cta').html(dat[2].substr(0, 30));
      $('#byc_descrip_cta').attr('title', dat[2]);
      } else {
      $('#pln_id').val('');
      $('#byc_cuenta_contable').val('');
      $('#byc_descrip_cta').html('');
      $('#byc_descrip_cta').attr('title', '');
      }
       } 
    });
  }
</script>
