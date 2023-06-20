
<section class="content-header">
      <h1>
        Transportistas
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
                <div class="form-group <?php if(form_error('tra_identificacion')!=''){ echo 'has-error';}?>">
                  <label>Cedula/RUC:</label>
                  <input type="text" class="form-control" name="tra_identificacion" value="<?php if(validation_errors()!=''){ echo set_value('tra_identificacion');}else{ echo $transportista->tra_identificacion;}?>">
                  <?php echo form_error("tra_identificacion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_razon_social')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="tra_razon_social" value="<?php if(validation_errors()!=''){ echo set_value('tra_razon_social');}else{ echo $transportista->tra_razon_social;}?>">
                  <?php echo form_error("tra_razon_social","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_placa')!=''){ echo 'has-error';}?>">
                  <label>Placa:</label>
                  <input type="text" class="form-control" name="tra_placa" value="<?php if(validation_errors()!=''){ echo set_value('tra_placa');}else{ echo $transportista->tra_placa;}?>">
                  <?php echo form_error("tra_placa","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_direccion')!=''){ echo 'has-error';}?>">
                  <label>Direccion:</label>
                  <input type="text" class="form-control" name="tra_direccion" value="<?php if(validation_errors()!=''){ echo set_value('tra_direccion');}else{ echo $transportista->tra_direccion;}?>">
                  <?php echo form_error("tra_direccion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_telefono')!=''){ echo 'has-error';}?>">
                  <label>Telefono:</label>
                  <input type="text" class="form-control" name="tra_telefono" value="<?php if(validation_errors()!=''){ echo set_value('tra_telefono');}else{ echo $transportista->tra_telefono;}?>">
                  <?php echo form_error("tra_telefono","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_email')!=''){ echo 'has-error';}?>" style="display: none;">
                  <label>Email:</label>
                  <input type="email" class="form-control" name="tra_email" value="<?php if(validation_errors()!=''){ echo set_value('tra_email');}else{ echo $transportista->tra_email;}?>">
                  <?php echo form_error("tra_email","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                              <label>Estado</label>
                              <select name="tra_estado" id="tra_estado" class="form-control">
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
                                  $est=$transportista->tra_estado;
                                }else{
                                  $est=set_value('tra_estado');
                                }
                              ?>
                              <script type="text/javascript">
                              var est='<?php echo $est;?>';
                              tra_estado.value=est;
                            </script>
                            </div>
                <input type="hidden" class="form-control" name="tra_id" value="<?php echo $transportista->tra_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>transportista" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>