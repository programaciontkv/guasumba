
<section class="content-header">
      <h1>
        Estado
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-4">
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
              <div class="box-body" >
                
                <div class="form-group <?php if(form_error('est_descripcion')!=''){ echo 'has-error';}?>">
                  <label>Descripcion:</label>
                  <input type="text" class="form-control" name="est_descripcion" id="est_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('est_descripcion');}else{ echo $estado->est_descripcion;}?>">
                  <?php echo form_error("est_descripcion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('est_orden')!=''){ echo 'has-error';}?>">
                  <label>Orden:</label>
                  <input type="number" class="form-control numerico" name="est_orden" value="<?php if(validation_errors()!=''){ echo set_value('est_orden');}else{ echo $estado->est_orden;}?>">
                  <?php echo form_error("est_orden","<span class='help-block'>","</span>");?>
                </div>
                
                <div class="form-group">
                  <label>Color:</label>
                  <input type="color" class="form-control" name="est_color" value="<?php if(validation_errors()!=''){ echo set_value('est_color');}else{ $estado->est_color;}?>">
                </div>
                
                <input type="hidden" class="form-control" name="est_id" value="<?php echo $estado->est_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>estado" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>

    