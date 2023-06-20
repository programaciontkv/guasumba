
<section class="content-header">
      <h1>
        Formas de Pago
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

                <div class="form-group <?php if(form_error('fpg_codigo')!=''){ echo 'has-error';}?> ">
                  <label>Codigo SRI:</label>
                  <input type="text" class="form-control" name="fpg_codigo" value="<?php if(validation_errors()!=''){ echo set_value('fpg_codigo') ;}else{ echo $forma_pago->fpg_codigo;}?>">
                  <?php echo form_error("fpg_codigo","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('fpg_descripcion')!=''){ echo 'has-error';}?> ">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" name="fpg_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('fpg_descripcion');}else{ echo $forma_pago->fpg_descripcion;}?>">
                  <?php echo form_error("fpg_descripcion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('fpg_descripcion_sri')!=''){ echo 'has-error';}?> ">
                  <label>Descripcion SRI:</label>
                  <input type="text" class="form-control" name="fpg_descripcion_sri" value="<?php if(validation_errors()!=''){ echo set_value('fpg_descripcion_sri');}else{ echo $forma_pago->fpg_descripcion_sri;}?>">
                  <?php echo form_error("fpg_descripcion_sri","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('fpg_tipo')!=''){ echo 'has-error';}?> ">
                  <label>Tipo:</label>
                  <?php
                        if(validation_errors()==''){
                              $tipo=$forma_pago->fpg_tipo;
                            }else{
                              $tipo=set_value('fpg_tipo');
                        }
                     ?>   
                  <select name="fpg_tipo" id="fpg_tipo" class="form-control">
                    <option value="">SELECCIONE</option>
                    <option value="1">TARJETA DE CREDITO</option>
                    <option value="2">TARJETA DE DEBITO</option>
                    <option value="3">CHEQUE</option>
                    <option value="4">EFECTIVO</option>
                    <option value="5">CERTIFICADOS</option>
                    <option value="6">TRANSFERENCIA</option>
                    <option value="7">RETENCION</option>
                    <option value="8">NOTA CREDITO</option>
                    <option value="9">CREDITO</option>
                  </select>
                  <script type="text/javascript">
                      var tipo='<?php echo $tipo?>';
                      fpg_tipo.value=tipo;
                  </script>
                  <?php echo form_error("fpg_tipo","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('fpg_banco')!=''){ echo 'has-error';}?> ">
                  <label>Banco:</label>
                  <?php
                        if(validation_errors()==''){
                              $banc=$forma_pago->fpg_banco;
                            }else{
                              $banc=set_value('fpg_banco');
                        }
                     ?>   
                  <select name="fpg_banco" id="fpg_banco" class="form-control">
                    <option value="0">SELECCIONE</option>
                    <?php
                    if(!empty($bancos)){
                      foreach ($bancos as $banco) {
                    ?>
                    <option value="<?php echo $banco->btr_id?>"><?php echo $banco->btr_descripcion?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <script type="text/javascript">
                      var banco='<?php echo $banc?>';
                      fpg_banco.value=banco;
                  </script>
                  <?php echo form_error("fpg_banco","<span class='help-block'>","</span>");?>
                </div>

                <div class="form-group <?php if(form_error('fpg_tarjeta')!=''){ echo 'has-error';}?> ">
                  <label>Tarjeta</label>
                  <select name="fpg_tarjeta"  id="fpg_tarjeta" class="form-control">
                    <option value="0">SELECCIONE</option>
                    <?php
                    if(!empty($tarjetas)){
                      foreach ($tarjetas as $tarjeta) {
                    ?>
                    <option value="<?php echo $tarjeta->btr_id?>"><?php echo $tarjeta->btr_descripcion?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <?php 
                    if(validation_errors()==''){
                              $tj=$forma_pago->fpg_tarjeta;
                            }else{
                              $tj=set_value('fpg_tarjeta');
                    }
                  ?> 
                  <script type="text/javascript">
                      var tj='<?php echo $tj?>';
                      fpg_tarjeta.value=tj;
                  </script>
                  <?php echo form_error("fpg_tarjeta","<span class='help-block'>","</span>");?>
                </div>
                <!-- <div class="form-group <?php if(form_error('fpg_tarjeta')!=''){ echo 'has-error';}?> ">
                  <label>Precio</label>
                  <select name="fpg_precio"  id="fpg_precio" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                  <?php 
                    if(validation_errors()==''){
                              $precio=$forma_pago->fpg_precio;
                            }else{
                              $precio=set_value('fpg_precio');
                    }
                  ?> 
                  <script type="text/javascript">
                      var precio='<?php echo $precio?>';
                      fpg_precio.value=precio;
                  </script>
                  <?php echo form_error("fpg_tarjeta","<span class='help-block'>","</span>");?>
                </div> -->
                <!-- <div class="form-group <?php if(form_error('pln_id')!=''){ echo 'has-error';}?> ">
                  <label>Codigo Cuenta:</label>
                  <input type="text" class="form-control" name="pln_codigo" id="pln_codigo" value="<?php if(validation_errors()!=''){ echo set_value('pln_codigo');}else{ echo $forma_pago->pln_codigo;}?>" list="list_cuentas" onchange="load_cuenta()">
                  <input type="text" name="pln_id" id="pln_id" value="<?php if(validation_errors()!=''){ echo set_value('pln_id');}else{ echo $forma_pago->pln_id;}?>" hidden>
                  <?php echo form_error("pln_id","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  <label>Descripcion Cuenta:</label>
                  <input type="text" class="form-control" name="pln_descripcion" id="pln_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('pln_descripcion');}else{ echo $forma_pago->pln_descripcion;}?>" readonly>
                </div> -->
                
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="fpg_estado"  id="fpg_estado" class="form-control">
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
                    if(validation_errors()==''){
                      $est=$forma_pago->fpg_estado;
                    }else{
                      $est=set_value('fpg_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    fpg_estado.value=est;
                  </script>
                </div>
                
                <input type="hidden" class="form-control" name="fpg_id" value="<?php echo $forma_pago->fpg_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>forma_pago" class="btn btn-default">Cancelar</a>
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
    <option value="<?php echo $cuenta->pln_id?>"><?php echo $cuenta->pln_codigo.' '.$cuenta->pln_descripcion?></option>
  <?php
    }
  }
  ?>
</datalist>
    <script type="text/javascript">
      var base_url='<?php echo base_url();?>';

      function load_cuenta(){
        uri=base_url+'forma_pago/traer_cuenta/'+$('#pln_codigo').val();
        $.ajax({
                url: uri,
                type: 'POST',
                success: function(dt){
                  dat=dt.split('&&');
                   $('#pln_id').val(dat[0]);
                   $('#pln_codigo').val(dat[1]);
                   $('#pln_descripcion').val(dat[2]);
                } 
              });
      }
    </script>