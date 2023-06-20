
<section class="content-header">
      <h1>
        Vendedores
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
                
                <div class="form-group <?php if(form_error('vnd_nombre')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" name="vnd_nombre" value="<?php if(validation_errors()!=''){ echo set_value('vnd_nombre');}else{ echo $vendedor->vnd_nombre;}?>">
                  <?php echo form_error("vnd_nombre","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('vnd_local')!=''){ echo 'has-error';}?>">
                  <label>Usuario</label>
                  <select name="vnd_local" id="vnd_local" class="form-control">
                    <option value="">SELECCIONE</option>
                    <?php
                        if(!empty($usuarios)){
                          foreach ($usuarios as $usuario) {
                            if($usuario->usu_id==$vendedorres->vnd_local){
                              $select='selected';
                            }else{
                              $select='';
                            }
                    ?>
                        <option value='<?php echo $usuario->usu_id?>' <?php echo $select?>><?php echo $usuario->usu_login?></option>
                    <?php        
                          }
                        }
                    ?>
                  </select>
                             <?php 
                                if(validation_errors()!=''){
                                  $usu=$vendedor->vnd_local;
                                }else{
                                  $usu=set_value('vnd_local');
                                }
                              ?>
                              <script type="text/javascript">
                              window.onload = function () {
                              // var usu='<?php echo $usu;?>';
                              
                              // vnd_local.value=usu;
                              }
      
                              
                            </script>
                </div>
                <div class="form-group ">
                              <label>Estado</label>
                              <select name="vnd_estado" id="vnd_estado" class="form-control">
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
                                  $est=$vendedor->vnd_estado;
                                }else{
                                  $est=set_value('vnd_estado');
                                }
                              ?>
                              <script type="text/javascript">
                              // var est='<?php echo $est;?>';
                              // vnd_estado.value=est;
                            </script>
                            </div>
                <input type="hidden" class="form-control" name="vnd_id" value="<?php echo $vendedor->vnd_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>vendedor" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>