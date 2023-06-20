
<section class="content-header">
      <h1>
        Opciones
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
                <div class="form-group <?php if(form_error('opc_nombre')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="opc_nombre" value="<?php if(validation_errors()!=''){ echo set_value('opc_nombre');}else{ echo $opcion->opc_nombre;}?>">
                  <?php echo form_error("usu_person","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('opc_direccion')!=''){ echo 'has-error';}?>">
                  <label>Direccion Path</label>
                  <input type="text" class="form-control" name="opc_direccion" value="<?php if(validation_errors()!= ''){ echo set_value('opc_direccion'); }else{ echo  $opcion->opc_direccion;}?>">
                  <?php echo form_error("opc_direccion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group">
                  <label>Descripcion</label>
                  <input type="text" class="form-control" name="opc_descripcion" value="<?php if(validation_errors()!=''){ echo set_value('opc_descripcion'); }else{ echo $opcion->opc_descripcion;}?>">
                </div>
                <div class="form-group ">
                  
                  <label>Emisor</label>
                  <select name="opc_caja" id="opc_caja" class="form-control">
                    <?php
                    if(validation_errors()==''){
                          $cja=$opcion->opc_caja;
                        }else{
                          $cja=set_value('opc_caja');
                    }
                    if($cajas!=''){
                      foreach ($cajas as $caja) {
                    ?>
                    <option value="<?php echo $caja->cja_id?>"><?php echo $caja->emp_nombre.' '.$caja->emi_nombre.' '.$caja->cja_nombre?></option>
                    <?php
                      }
                    }
                  ?>
                  </select>
                  <script type="text/javascript">
                      var cja='<?php echo $cja?>';
                      opc_caja.value=cja;
                  </script>
                </div>
                <div class="form-group ">
                  
                  <label>Estado</label>
                  <select name="opc_estado" id="opc_estado" class="form-control">
                    <?php
                    if(validation_errors()==''){
                          $est=$opcion->opc_estado;
                        }else{
                          $est=set_value('opc_estado');
                    }
                    if(!empty($estados)){
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
                      opc_estado.value=est;
                  </script>
                </div>
                <div class="form-group <?php if(form_error('opc_orden')!=''){ echo 'has-error';}?>">
                  <label>Orden:</label>
                  <input type="number" class="form-control numerico" name="opc_orden" value="<?php if(validation_errors()!=''){ echo set_value('opc_orden');}else{ echo $opcion->opc_orden;}?>">
                  <?php echo form_error("opc_orden","<span class='help-block'>","</span>");?>
                </div>
                <input type="hidden" class="form-control" name="opc_id" value="<?php echo $opcion->opc_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>opcion" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>