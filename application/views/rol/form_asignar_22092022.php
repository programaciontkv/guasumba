
<section class="content-header">
      <h1>
        Asignar Opciones de Rol <?php echo $rol->rol_nombre;?> 
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-8">
          <?php 
          if($this->session->flashdata('error')){
            ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <p><i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error')?></p>
            </div>
            <?php

          }
          if($usu_sesion != 1 ){
            $readonly="disabled";
          }else{
            $readonly="";  
          }
          ?>
          <div class="box box-primary">
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off">
              <div class="box-body">
                <div class="form-group <?php if(form_error('rol_nombre')!=''){echo 'has-error';}?>">
                  <label>Menu:</label>
                  <select <?php echo $readonly ?> class="form-control" id="men_id" name="men_id">
                     <?php 
                        if(!empty($menus)){
                          foreach ($menus as $menu) {
                      ?>
                          <option value="<?php echo $menu->men_id?>"><?php echo $menu->men_nombre?></option>
                      <?php
                          }
                        }
                      ?> 
                  </select>
                </div>
                <div class="form-group <?php if(form_error('rol_nombre')!=''){echo 'has-error';}?>">
                  <label>Submenu:</label>
                  <select <?php echo $readonly ?> class="form-control" id="sbm_id" name="sbm_id">
                     <?php 
                        if(!empty($submenus)){
                          foreach ($submenus as $submenu) {
                      ?>
                          <option value="<?php echo $submenu->sbm_id?>"><?php echo $submenu->sbm_nombre?></option>
                      <?php
                          }
                        }
                      ?> 
                  </select>
                </div>
                <div class="form-group <?php if(form_error('rol_nombre')!=''){echo 'has-error';}?>">
                  <label>Pestañas:</label>
                  <select class="form-control" name="opc_id" id="opc_id" onchange="opciones()">
                     <?php 
                        if(!empty($opciones)){
                          foreach ($opciones as $opcion) {
                      ?>
                          <option value="<?php echo $opcion->opc_id?>"><?php echo $opcion->opc_nombre?></option>
                      <?php
                          }
                        }
                      ?> 
                  </select>
                </div>
                <div class="form-group">
                  <label>Permisos:</label>
                </div>
                <div class="form-group">
                  <label>Todos:</label>  
                  <input type="checkbox" name="rop_todos" id="rop_todos" class="form-group" onclick="bloquear()">
                  &nbsp;&nbsp;&nbsp;
                  <label>Insertar:</label>  
                  <input type="checkbox" name="rop_insertar" id="rop_insertar" class="form-group">
                  &nbsp;&nbsp;&nbsp;
                  <label>Actualizar:</label>  
                  <input type="checkbox" name="rop_actualizar" id="rop_actualizar" class="form-group">
                  &nbsp;&nbsp;&nbsp;
                  <label>Eliminar:</label>  
                  <input type="checkbox" name="rop_eliminar" id="rop_eliminar" class="form-group">
                  &nbsp;&nbsp;&nbsp;
                  <label>Visualizar:</label>  
                  <input type="checkbox" name="rop_visualizar" id="rop_visualizar" class="form-group">
                  &nbsp;&nbsp;&nbsp;
                  <label>Reporte:</label>  
                  <input type="checkbox" name="rop_reporte" id="rop_reporte" class="form-group">
                </div>
                <input type="hidden" class="form-control" name="rol_id" value="<?php echo $rol->rol_id;?>">
                <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>rol" class="btn btn-default">Cancelar</a>
              </div>
            </form>
          </div>
          <div>
            <p><h3>Lista de Opciones</h3></p>
          </div>
          <div>
        
            <table id="tbl_list" class="table table-bordered table-list table-hover">
             <thead>
                <tr>
                  <th>No</th>
                  <th>Menu</th>
                  <th>Opcion</th>
                  <th>Todos</th>
                  <th>Insertar</th>
                  <th>Actualizar</th>
                  <th>Eliminar</th>
                  <th>Visualizar</th>
                  <th>Reporte</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $n=0;
                  if(!empty($listas)){
                    foreach ($listas as $lista) {
                      $n++;
                  ?>
                    <tr>
                      <td><?php echo $n?></td>
                      <td><?php echo $lista->men_nombre?></td>
                      <td><?php echo $lista->sbm_nombre?></td>
                      <td><?php echo $lista->opc_nombre?></td>
                      <td><?php echo $lista->rop_todos?></td>
                      <td><?php echo $lista->rop_insertar?></td>
                      <td><?php echo $lista->rop_actualizar?></td>
                      <td><?php echo $lista->rop_eliminar?></td>
                      <td><?php echo $lista->rop_visualizar?></td>
                      <td><?php echo $lista->rop_reporte?></td>
                      
                      <td align="center">
                        <div class="btn-group">
                          <a href="<?php echo base_url();?>rol/eliminar_opcion/<?php echo $lista->rop_id?>/<?php echo $rol->rol_id?>/<?php echo $lista->opc_nombre?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
                        </div>
                      </td>
                    </tr>
                  <?php
                    }
                  }
                  ?> 
              </tbody>
            </table>
        
         </div>
      </div>
      <!-- /.row -->
    </section>
    <script type="text/javascript">
      function bloquear(){
        if($('#rop_todos').prop('checked')=='checked' || $('#rop_todos').prop('checked')==true){
          $('#rop_insertar').prop('checked',true);
          $('#rop_actualizar').prop('checked',true);
          $('#rop_eliminar').prop('checked',true);
          $('#rop_visualizar').prop('checked',true);
          $('#rop_reporte').prop('checked',true);
        }else{
          $('#rop_insertar').prop('checked',false);
          $('#rop_actualizar').prop('checked',false);
          $('#rop_eliminar').prop('checked',false);
          $('#rop_visualizar').prop('checked',false);
          $('#rop_reporte').prop('checked',false);
        }  
      }
      function opciones(){
        var id1 = $('#opc_id').val(); 
        if (id1 != 0 ) {
        $.ajax({
              url: base_url+"rol/traer_cods/"+id1,
              type: 'POST',
              success: function(dt){

              dat = dt.split("&&");
              if (dat[0] != 1) {

                  $('#men_id').val(dat[0]);
                  $('#sbm_id').val(dat[1]);
              } 

          }
        });

        }else{

          alert("dd");
        }
          
      }
    </script>>