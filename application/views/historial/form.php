
<section class="content-header">
     <!--  <h1>
        Usuarios
      </h1> -->
</section>
<script type="text/javascript">
      var base_url='<?php echo base_url();?>'
      $('#his_solicitud').val('');
      $('#his_archivos').val('');
      $('#his_obser').val('');
    </script>
<section class="content">
      <div class="row">
        <div class="col-md-12">
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

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group ">
                      <label>Fecha de registro:</label>
                      <td>
                          <div class="form-group <?php if(form_error('his_fregistro')!=''){ echo 'has-error';}?> ">
                            <input type="date" class="form-control" name="his_fregistro" id="his_fregistro" value="<?php if(validation_errors()!=''){ echo set_value('his_fregistro');}else{ echo $historial->his_fregistro;}?>" readonly >
                              <?php echo form_error("his_fregistro","<span class='help-block'>","</span>");?>
                          </div>
                        </td>
                     </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group ">
                      <label>Fecha de cambio:</label>
                      <td>
                          <div class="form-group <?php if(form_error('his_fcambio')!=''){ echo 'has-error';}?> ">
                            <input type="date" class="form-control" name="his_fcambio" id="his_fcambio" value="<?php if(validation_errors()!=''){ echo set_value('his_fcambio');}else{ echo $historial->his_fcambio;}?>"  >
                              <?php echo form_error("fac_fecha_emision","<span class='help-block'>","</span>");?>
                          </div>
                        </td>
                     </div>
                  </div>

                  <div class="col-md-4">
                      <div class="form-group <?php if(form_error('his_usuario_soli')!=''){echo 'has-error';}?>">
                  <label>Usuario-Solicita:</label>
                  <input type="text" class="form-control" name="his_usuario_soli" placeholder="Usuario-Solicita" value="<?php if(validation_errors()!=''){ echo set_value('his_usuario_soli');}else{ echo $historial->his_usuario_soli;}?>">
                  <?php echo form_error("his_usuario_soli","<span class='help-block'>","</span>");?>
                </div> 
                  </div>
                  
                </div>
                <div class="row">
                  <div class="col-md-4">
                      <div class="form-group <?php if(form_error('his_per_modifica')!=''){echo 'has-error';}?>">
                          <label>Personal Encargado Modificación:</label>
                          <input type="text" class="form-control" name="his_per_modifica" placeholder="Personal Encargado Modificación" value="<?php if(validation_errors()!=''){ echo set_value('his_usuario_soli');}else{ echo $historial->his_per_modifica;}?>">
                          <?php echo form_error("his_per_modifica","<span class='help-block'>","</span>");?>
                       </div>
                    
                  </div>
                  

                  <div class="col-md-4">
                    <div class="form-group <?php if(form_error('his_lugar')!=''){echo 'has-error';}?>">
                        <label>Pestaña:</label>
                        <select class="form-control" id="his_lugar" name="his_lugar">
                       
                        <?php
                        if(!empty($pestanias)){
                          foreach ($pestanias as $rst_est) {
                        ?>
                        <option value="<?php echo $rst_est->opc_id?>"><?php echo $rst_est->opc_nombre?></option>
                        <?php   
                          }
                        }
                        ?>
                      </select>
                  </div>
                  <script type="text/javascript">
                    var lugar='<?php echo $historial->his_lugar;?>';
                     window.onload = function(){
                    his_lugar.value=lugar;
                     }
                   
                  </script>
                    
                </div>
                  
                </div>
                
                

              <div class="row">
                <div class="col-md-12">
                      <div class="form-group <?php if(form_error('his_solicitud')!=''){echo 'has-error';}?>">
                        <label>Solicitud:</label>
                        <textarea class="form-control" name="his_solicitud" id="his_solicitud" placeholder="Nombre" onkeydown="return enter(event)"><?php if(validation_errors()!=''){ echo set_value('his_solicitud');}else{ echo trim($historial->his_solicitud);}?>
                        </textarea>
                       </div>
                  </div>                
              </div>                
                
                

                <div class="form-group <?php if(form_error('his_archivos')!=''){echo 'has-error';}?>">
                  <label>Archivos involucrados:</label>
                  <textarea class="form-control" name="his_archivos" id="his_archivos" placeholder="Nombre" ><?php if(validation_errors()!=''){ echo set_value('his_archivos');}else{ echo $historial->his_archivos;}?>
                    
                  </textarea>
                 
                </div>

                <div class="form-group <?php if(form_error('his_obser')!=''){echo 'has-error';}?>">
                  <label>Observaciones a tomar en cuenta:</label>
                  <textarea class="form-control" name="his_obser" id="his_obser" placeholder="Nombre" value=""><?php if(validation_errors()!=''){ echo set_value('his_obser');}else{ echo $historial->his_obser;}?>
                    
                  </textarea>
                 
                </div>
                
               
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url().'historial/';echo $opc_id ?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    
     