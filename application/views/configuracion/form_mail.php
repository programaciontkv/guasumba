
<section class="content-header">
      <h1>
        Credenciales Envio de Mail
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-6">
          <?php 
          $dt=explode('&',$configuracion);
          if($this->session->flashdata('error')){
            ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <p><i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error')?></p>
            </div>
            <?php
          }
          if ($usu_sesion != 1) {
            $readonly="readonly";
          }
          else{
            $readonly="";
          }
          ?>
          <div class="box box-primary">
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off">
              <div class="box-body">
                <div class="form-group <?php if(form_error('secure')!=''){ echo 'has-error';}?> ">
                  <label>SMTPSecure:</label>
                  <input <?php echo $readonly ?> type="text" class="form-control" name="secure" value="<?php if(validation_errors()!=''){ echo set_value('secure');}else{ echo $dt[0];}?>">
                  <?php echo form_error("secure","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('puerto')!=''){ echo 'has-error';}?> ">
                  <label>Puerto:</label>
                  <input <?php echo $readonly ?>  type="text" class="form-control" name="puerto" value="<?php if(validation_errors()!=''){ echo set_value('puerto');}else{ echo $dt[1];}?>">
                  <?php echo form_error("puerto","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('host')!=''){ echo 'has-error';}?> ">
                  <label>Host:</label>
                  <input <?php echo $readonly ?>  type="text" class="form-control" name="host" value="<?php if(validation_errors()!=''){ echo  set_value('host');}else{echo $dt[2];}?>" style="text-transform:lowercase;">
                  <?php echo form_error("host","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('usuario')!=''){ echo 'has-error';}?> ">
                  <label>Nombre del Usuario:</label>
                  <input <?php echo $readonly ?>  type="email" class="form-control" name="usuario" value="<?php if(validation_errors()!=''){ echo  set_value('usuario');}else{echo $dt[3];}?>" style="text-transform:lowercase;">
                  <?php echo form_error("usuario","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('contrasena')!=''){ echo 'has-error';}?> ">
                  <label>Contraseña:</label>
                  <input <?php echo $readonly ?>  type="password" class="form-control" name="contrasena" value="<?php if(validation_errors()!=''){ echo set_value('contrasena');}else{ echo $dt[4];}?>">
                  <?php echo form_error("contrasena","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('emisor')!=''){ echo 'has-error';}?> ">
                  <label>Nombre del Emisor:</label>
                  <input <?php echo $readonly ?>  type="text" class="form-control" name="emisor" value="<?php if(validation_errors()!=''){ echo  set_value('emisor');}else{echo $dt[5];}?>">
                  <?php echo form_error("emisor","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('asunto')!=''){ echo 'has-error';}?> ">
                  <label>Asunto:</label>
                  <input type="text" class="form-control" name="asunto" value="<?php if(validation_errors()!=''){ echo  set_value('asunto');}else{ echo  $dt[6];}?>">
                  <?php echo form_error("asunto","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('asunto')!=''){ echo 'has-error';}?> ">
                  <label>Mensaje:</label>
                  <br>
                  <textarea style="width:350px;height:200px" maxlength="50" name="mensaje">
                    <?php if(validation_errors()!=''){ echo set_value('mensaje');}else{echo $dt[7];}?>
                  </textarea>
                  <?php echo form_error("mensaje","<span class='help-block'>","</span>");?>
                </div>

                <input type="hidden" class="form-control" name="con_id" value="<?php echo $con_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>configuracion" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
