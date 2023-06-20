
<section class="content-header">
      <h1>
        Puntos de Emision
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
                <div class="form-group <?php if(form_error('emp_id')!=''){ echo 'has-error';}?> ">
                  <label>Empresa:</label>
                  <select name="emp_id"  id="emp_id" class="form-control">
                    <option value="">SELECCIONE</option>
                    <?php
                    if(!empty($empresas)){
                      foreach ($empresas as $empresa) {
                    ?>
                    <option value="<?php echo $empresa->emp_id?>"><?php echo $empresa->emp_identificacion.' '.$empresa->emp_nombre?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <?php 
                    if(validation_errors()==''){
                      $emp=$emisor->emp_id;
                    }else{
                      $emp=set_value('emp_id');
                    }
                  ?>
                  <script type="text/javascript">
                    var emp='<?php echo $emp;?>';
                    emp_id.value=emp;
                  </script>
                  <?php echo form_error("emp_id","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emi_cod_punto_emision')!=''){ echo 'has-error';}?> ">
                  <label>Codigo Punto de Emision:</label>
                  <input type="text" class="form-control numerico" name="emi_cod_punto_emision" value="<?php if(validation_errors()!=''){ echo set_value('emi_cod_punto_emision');}else{ echo $emisor->emi_cod_punto_emision;}?>">
                  <?php echo form_error("emi_cod_punto_emision","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('emi_cod_orden')!=''){ echo 'has-error';}?> ">
                  <label>Orden:</label>
                  <input type="text" class="form-control numerico" name="emi_cod_orden" value="<?php if(validation_errors()!=''){ echo set_value('emi_cod_orden');}else{ echo $emisor->emi_cod_orden;}?>">
                  <?php echo form_error("emi_cod_orden","<span class='help-block'>","</span>");?>
                </div>   
                <div class="form-group <?php if(form_error('emi_cod_cli')!=''){ echo 'has-error';}?> ">
                  <label>Cliente:</label>
                  <input type="text" class="form-control" name="cliente" id="cliente" value="<?php if(validation_errors()!=''){ echo set_value('cliente');}else{ echo $emisor->cli_raz_social;}?>" list="list_clientes" onchange="traer_cliente(this)">
                  <input type="hidden" class="form-control" name="emi_cod_cli" id="emi_cod_cli" value="<?php if(validation_errors()!=''){ echo set_value('emi_cod_cli');}else{ echo  $emisor->emi_cod_cli;}?>" >
                  <?php echo form_error("emi_cod_cli","<span class='help-block'>","</span>");?>

                </div>
                <div class="form-group <?php if(form_error('emi_nombre')!=''){ echo 'has-error';}?> ">
                  <label>Nombre Punto de Emision:</label>
                  <input type="text" class="form-control" name="emi_nombre" value="<?php if(validation_errors()!=''){ echo set_value('emi_nombre');}else{ echo $emisor->emi_nombre;}?>">
                  <?php echo form_error("emi_nombre","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emi_pais')!=''){ echo 'has-error';}?> ">
                  <label>Pais:</label>
                  <input type="text" class="form-control" name="emi_pais" value="<?php if(validation_errors()!=''){ echo set_value('emi_pais');}else{ echo $emisor->emi_pais;}?>">
                  <?php echo form_error("emi_pais","<span class='help-block'>","</span>");?>
                </div>  
                <div class="form-group <?php if(form_error('emi_ciudad')!=''){ echo 'has-error';}?> ">
                  <label>Ciudad:</label>
                  <input type="text" class="form-control" name="emi_ciudad" value="<?php if(validation_errors()!=''){ echo set_value('emi_ciudad');}else{ echo $emisor->emi_ciudad;}?>">
                  <?php echo form_error("emi_ciudad","<span class='help-block'>","</span>");?>
                </div>  
                <div class="form-group <?php if(form_error('emi_dir_establecimiento_emisor')!=''){ echo 'has-error';}?> ">
                  <label>Direccion Punto Emision:</label>
                  <input type="text" class="form-control" name="emi_dir_establecimiento_emisor" value="<?php if(validation_errors()!=''){ echo set_value('emi_dir_establecimiento_emisor');}else{ echo $emisor->emi_dir_establecimiento_emisor;}?>">
                  <?php echo form_error("emi_dir_establecimiento_emisor","<span class='help-block'>","</span>");?>
                </div>    
                <div class="form-group <?php if(form_error('emi_telefono')!=''){ echo 'has-error';}?> ">
                  <label>Telefono:</label>
                  <input type="text" class="form-control" name="emi_telefono" value="<?php if(validation_errors()!=''){ echo set_value('emi_telefono');}else{ echo $emisor->emi_telefono;}?>">
                  <?php echo form_error("emi_telefono","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emi_email')!=''){ echo 'has-error';}?> ">
                  <label>Email:</label>
                  <input type="email" class="form-control" name="emi_email" value="<?php if(validation_errors()!=''){ echo set_value('emi_email');}else{ echo $emisor->emi_email;}?>">
                  <?php echo form_error("emi_email","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emi_credencial')!=''){ echo 'has-error';}?> ">
                  <label>Credenciales para Facturacion:</label>
                  <?php
                        if(validation_errors()==''){
                              $cred=$emisor->emi_credencial;
                            }else{
                              $cred=set_value('emi_credencial');
                        }
                     ?>   
                  <select name="emi_credencial" id="emi_credencial" class="form-control">
                    <option value="">SELECCIONE</option>
                    <?php
                    if(!empty($credenciales)){
                      foreach ($credenciales as $credencial) {
                        $valor = explode('&', $credencial[con_valor2]);
                    ?>
                    <option value="<?php echo $valor[2]?>"><?php echo $valor[0].' '.$valor[2]?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <script type="text/javascript">
                      var cred='<?php echo $cred?>';
                      emi_credencial.value=cred;
                  </script>
                  <?php echo form_error("emi_credencial","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  <label>Estado</label>
                  <select name="emi_estado"  id="emi_estado" class="form-control">
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
                      $est=$emisor->emi_estado;
                    }else{
                      $est=set_value('emi_estado');
                    }
                  ?>
                  <script type="text/javascript">
                    var est='<?php echo $est;?>';
                    emi_estado.value=est;
                  </script>
                </div>
                
                <input type="hidden" class="form-control" name="emi_id" value="<?php echo $emisor->emi_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>emisor" class="btn btn-default">Cancelar</a>
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
    <script>
      function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#cliente').val().length == 0) {
                            alert('Ingrese Cliente');
                            return false;
                      }
                    },
                    url: base_url+"emisor/traer_cliente/"+cliente.value,
                    type: 'JSON',
                    dataType: 'JSON',
                    success: function (dt) {
                        if(dt!=""){
                          $('#emi_cod_cli').val(dt.cli_id);
                          $('#cliente').val(dt.cli_raz_social);
                        }else{
                          alert('Cliente no existe');
                          $('#emi_cod_cli').val('');
                          $('#cliente').val('');
                        } 
                        
                    },
                    error : function(xhr, status) {
                          alert('Cliente no existe');
                          $('#emi_cod_cli').val('');
                          $('#cliente').val('');
                    }
                    });    
            }
    </script>
      <!-- /.row -->
    </section>
