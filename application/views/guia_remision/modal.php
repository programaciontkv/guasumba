 <form id="frm_trans" action="<?php echo $action2?>" method="post" autocomplete="off">
              <div class="box-body">
                <div class="form-group <?php if(form_error('tra_identificacion')!=''){ echo 'has-error';}?>">
                  <label>Identificacion:</label>
                  <input type="text" class="form-control" id="tra_identificacion" name="tra_identificacion" value="<?php if(validation_errors()!=''){ echo set_value('tra_identificacion');}else{ echo $transportista->tra_identificacion;}?>">
                  <?php echo form_error("tra_identificacion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_razon_social2')!=''){ echo 'has-error';}?>">
                  <label>Nombre:</label>
                  <input type="text" class="form-control" id="tra_razon_social2" name="tra_razon_social2" value="<?php if(validation_errors()!=''){ echo set_value('tra_razon_social2');}else{ echo $transportista->tra_razon_social;}?>">
                  <?php echo form_error("tra_razon_social2","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_placa2')!=''){ echo 'has-error';}?>">
                  <label>Placa:</label>
                  <input type="text" class="form-control" id="tra_placa2" name="tra_placa2" value="<?php if(validation_errors()!=''){ echo set_value('tra_placa2');}else{ echo $transportista->tra_placa;}?>">
                  <?php echo form_error("tra_placa2","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_direccion')!=''){ echo 'has-error';}?>">
                  <label>Direccion:</label>
                  <input type="text" class="form-control" id="tra_direccion" name="tra_direccion" value="<?php if(validation_errors()!=''){ echo set_value('tra_direccion');}else{ echo $transportista->tra_direccion;}?>">
                  <?php echo form_error("tra_direccion","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_telefono')!=''){ echo 'has-error';}?>">
                  <label>Telefono:</label>
                  <input type="text" class="form-control" id="tra_telefono" name="tra_telefono" value="<?php if(validation_errors()!=''){ echo set_value('tra_telefono');}else{ echo $transportista->tra_telefono;}?>">
                  <?php echo form_error("tra_telefono","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group <?php if(form_error('tra_email')!=''){ echo 'has-error';}?>">
                  <label>Email:</label>
                  <input type="email" class="form-control" id="tra_email" name="tra_email" value="<?php if(validation_errors()!=''){ echo set_value('tra_email');}else{ echo $transportista->tra_email;}?>">
                  <?php echo form_error("tra_email","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group ">
                              <label>Estado</label>
                              <select name="tra_estado" id="tra_estado" class="form-control">
                                <option value="1">Activo</option>
                                <option value="2">Inactivo</option>
                                
                              </select>
                             
                            </div>
                <input type="hidden" class="form-control" name="tra_id" value="<?php echo $transportista->tra_id?>">
              <div >
                <button type="submit" onclick="n_trans()" class="btn btn-primary">Guardar</button>
               
              </div>

          </form>


