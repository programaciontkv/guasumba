
<section class="content-header">
      <h1>
        Roles
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
            <div class="form-group <?php if(form_error('rol_nombre')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="rol_nombre" value="<?php if(validation_errors()!=''){ echo set_value('rol_nombre');}else{ echo  $rol->rol_nombre;}?>">
                  <?php echo form_error("rol_nombre","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('rol_descripcion')!=''){ echo 'has-error';}?>">
                  <label>Descripcion</label>
                  <input type="text" class="form-control" name="rol_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('rol_descripcion');} else{ echo $rol->rol_descripcion;}?>">
                  <?php echo form_error("rol_descripcion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                <label>Estado</label>
                  <select name="rol_estado" id="rol_estado" class="form-control">
                    <?php
                    if(validation_errors()==''){
                          $est=$rol->rol_estado;
                        }else{
                          $est=set_value('rol_estado');
                    }
                        
                    if($estados!=''){
                      foreach ($estados as $estado) {
                    ?>
                    <option value="<?php echo $estado->est_id?>"><?php echo $estado->est_descripcion?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <script type="text/javascript">
                      var est='<?php echo $est?>';
                      rol_estado.value=est;
                  </script>
                </div>

          </div>
          <div class="box-footer">
                <input type="hidden" class="form-control" name="rol_id" value="<?php echo $rol->rol_id?>">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>rol" class="btn btn-default">Cancelar</a>
              </div>
        </form>
      </div>     
    </div>   
  </div>
      <!-- /.row -->
</section>