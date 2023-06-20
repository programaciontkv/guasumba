
<section class="content-header">
      <h1>
        Asignar Estado <?php echo $estado->est_descripcion;?> 
      </h1>
</section>
<section class="content">
      <div class="row">
        <div class="col-md-5">
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
                  <label>Opcion:</label>
                  <select class="form-control" name="opc_id">
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
                
                
                <input type="hidden" class="form-control" name="est_id" value="<?php echo $estado->est_id;?>">
                <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="<?php echo base_url();?>estado" class="btn btn-default">Cancelar</a>
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
                  <th>Opcion</th>
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
                      <td><?php echo $lista->opc_nombre?></td>
                      <td align="center">
                        <div class="btn-group">
                          <a href="<?php echo base_url();?>estado/eliminar_opcion/<?php echo $lista->eop_id?>/<?php echo $estado->est_id?>/<?php echo $lista->opc_nombre?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
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
    