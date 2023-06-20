<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
      <h1>
        Control de Cobros <?php echo $titulo?>
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
            <form id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">

              <div class="box-body" >
                <table class="table col-sm-12" border="0">
                  <tr>
                    <td class="col-sm-12">
                      <div class="box-body" class="col-sm-12">
                          <div class="panel panel-default col-sm-12">
                            <div class="panel panel-heading"><label>Datos Generales</label></div>
                              <table class="table">
                                <tr>
                                  <td><label>Identificacion Cliente:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_ced_ruc')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_ced_ruc" id="cli_ced_ruc" value="<?php if(validation_errors()!=''){ echo set_value('cli_ced_ruc');}else{ echo $cheque->cli_ced_ruc;}?>" readonly>
                                          <?php echo form_error("cli_ced_ruc","<span class='help-block'>","</span>");?>
                                    </div>
                                    <input type="hidden" class="form-control" name="chq_id" id="chq_id" value="<?php if(validation_errors()!=''){ echo set_value('chq_id');}else{ echo $cheque->chq_id;}?>">
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Nombre Cliente:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('cli_raz_social')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="cli_raz_social" id="cli_raz_social" value="<?php if(validation_errors()!=''){ echo set_value('cli_raz_social');}else{ echo $cheque->cli_raz_social;}?>" readonly>
                                          <?php echo form_error("cli_raz_social","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>  
                                </tr>
                                <tr>    
                                  <td><label>Banco:</label></td>
                                  <td>
                                    <?php 
                                    $chq_saldo=round($cheque->chq_monto,$dec)-round($cheque->chq_banco,$dec);
                                    ?>
                                    <div class="form-group <?php if(form_error('chq_banco')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="chq_banco" id="chq_banco" value="<?php if(validation_errors()!=''){ echo set_value('chq_banco');}else{ echo $cheque->chq_banco;}?>" readonly>
                                          <?php echo form_error("chq_banco","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>
                                </tr>
                                <tr>    
                                  <td><label>No Documento:</label></td>
                                  <td>
                                    <div class="form-group <?php if(form_error('chq_numero')!=''){ echo 'has-error';}?> ">
                                      <input type="text" class="form-control" name="chq_numero" id="chq_numero" value="<?php if(validation_errors()!=''){ echo set_value('chq_numero');}else{ echo $cheque->chq_numero;}?>" readonly>
                                          <?php echo form_error("chq_numero","<span class='help-block'>","</span>");?>
                                      </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Estado Cheque:</label></td>
                                  <td>
                                      <select name="chq_estado_cheque"  id="chq_estado_cheque" class="form-control">
                                        <option value="11">DEPOSITADO</option>
                                        <option value="12">REBOTADO</option>
                                        <option value="3">ANULADO</option>
                                      </select>
                                      <?php 
                                        if(validation_errors()!=''){
                                          $est=$cheque->chq_estado_cheque;
                                        }else{
                                          $est=set_value('chq_estado_cheque');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var est='<?php echo $est;?>';
                                        chq_estado_cheque.value=est;
                                      </script>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Observacion:</label></td>
                                  <td>
                                      <textarea id="chq_est_observacion"  name="chq_est_observacion" class="form-control"><?php echo $cheque->chq_est_observacion ?></textarea>
                                    </td> 
                                </tr>
                              </table>
                          </div>
                      </div>    
                    </td> 
                  </tr>
                  </table>
              </div>
              <div class="box-footer">
               <!--  <button type="submit" class="btn btn-primary">Guardar</button> -->
                 <button type="button" class="btn btn-primary" onclick="save()" >Guardar</button>
                <a href="<?php echo $cancelar;?>" class="btn btn-default">Cancelar</a>
              </div>

           
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <div class="modal fade" id="facturas">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" onclick="limpiar_notas()" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="text-align:center;">Facturas abonadas con el cheque <?php echo $cheque->chq_numero  ?> </h4>
              </div>
              <div class="modal-body">
                <table class="table table-bordered table-striped" >
                  <th>FACTURA</th>
                  <th>CLIENTE</th>
                  <th>VALOR TOTAL-FACTURA</th>
                  <th>VALOR TOTAL-ABONADO</th>
                  <?php
                  foreach ($facturas as $factura) {
                    ?>
                    <tr>
                      <td><?php echo $factura->fac_numero;  ?></td>
                      <td><?php echo $factura->fac_nombre; ?></td>
                      <td><?php echo $factura->fac_total_valor; ?></td>
                      <td><?php echo $factura->cta_monto; ?></td>
                    </tr>


                    <?php
                  }

                    ?>
                 
                  
                </table>
              </div>

               <div class="modal-footer"  >
                <div style="float:right">
               
                <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button> -->
                  <button type="button" class="btn btn-primary" onclick="guardar()" >Guardar</button>
                </div>
                

              </div>
               </form>
              
            </div>
          </div>
</div>
    

    <style type="text/css">
      .panel{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }

      div{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
      }
      div .panel-heading{
        margin-bottom: 4px !important;
        margin-top: 4px !important;
        padding-bottom: 4px !important;
        padding-top: 4px !important;
      }
      
      .form-control{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
        padding-bottom: 0px !important;
        padding-top: 0px !important;
        height:28px !important;
      }

      td{
        margin-bottom: 1px !important;
        margin-top: 1px !important;
        padding-bottom: 1px !important;
        padding-top: 1px !important;
      }
    </style>
    <script>

      var base_url='<?php echo base_url();?>';
      var dec='<?php echo $dec;?>';
      

      function validar_decimal(obj){
        obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
      }

      function save() {
        

        var estado = $('#chq_estado_cheque').val();
        if(estado==12 || estado == 3)
        {
          $("#facturas").modal('show');

        }else{
          $('#frm_save').submit();
        }

       }
      
  function guardar(){


      Swal.fire({
      title: 'Esta seguro cambiar el estado del cheque ?',
      showCancelButton: true,
      confirmButtonText: 'Confirmar',
      denyButtonText: `Cancelar`,
      }).then((result) => {
      if (result.isConfirmed) {
        $('#frm_save').submit();
      } else if (result.isDenied) {
        Swal.fire('No ha registrado cambios', '', 'info');
      }
      })

    }
            
    </script>

