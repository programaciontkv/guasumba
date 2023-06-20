
<section class="content-header">
      <h1>
        Usuarios
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
                
                

                <div class="form-group <?php if(form_error('cre_descripcion')!=''){echo 'has-error';}?>">
                  <label>Descripcion :</label>
                  <input type="text" class="form-control" name="cre_descripcion" placeholder="Descripcion" value="<?php if(validation_errors()!=''){ echo set_value('cre_descripcion');}else{ echo $credito_dias->cre_descripcion;}?>">
                  <?php echo form_error("cre_descripcion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('cre_dias')!=''){echo 'has-error';}?>">
                  <label>Dias:</label>
                  <input type="text" class="form-control" name="cre_dias" id="cre_dias" placeholder="Usuario" value="<?php if(validation_errors()!=''){ echo set_value('cre_dias');}else{echo  $credito_dias->cre_dias;}?>">
                  <?php echo form_error("cre_dias","<span class='help-block'>","</span>");?>
                </div>
                
                <div class="form-group ">

                  <label>Estado</label>
                  <select name="cre_estado" id="cre_estado" class="form-control">
                    <?php
                    if(validation_errors()==''){
                          $est=$credito_dias->cre_estado;
                        }else{
                          $est=set_value('cre_estado');
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
                      cre_estado.value=est;
                    </script>
                </div>
                <input type="hidden" class="form-control" name="cre_id" value="<?php echo $credito_dias->cre_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'usuario/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    