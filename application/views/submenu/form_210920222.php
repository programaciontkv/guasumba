
<section class="content-header">
      <h1>
        Submenus
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
                
                <div class="form-group <?php if(form_error('sbm_nombre')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="sbm_nombre" value="<?php if(validation_errors()!=''){ echo set_value('sbm_nombre');}else{ echo $submenu->sbm_nombre;}?>">
                  <?php echo form_error("sbm_nombre","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                  
                  <label>Estado</label>
                  <select name="sbm_estado" id="sbm_estado" class="form-control">
                    <?php
                    if(validation_errors()==''){
                          $est=$submenu->sbm_estado;
                        }else{
                          $est=set_value('sbm_estado');
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
                      sbm_estado.value=est;
                    </script>
                </div>
                <div class="form-group <?php if(form_error('sbm_orden')!=''){ echo 'has-error';}?>">
                  <label>Orden:</label>
                  <input type="number" class="form-control numerico" name="sbm_orden" value="<?php if(validation_errors()!=''){ echo set_value('sbm_orden');}else{ echo $submenu->sbm_orden;}?>">
                  <?php echo form_error("sbm_orden","<span class='help-block'>","</span>");?>
                </div>
                <input type="hidden" class="form-control" name="sbm_id" value="<?php echo $submenu->sbm_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>submenu" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>