
<section class="content-header">
  <h1>
    Ordenes de Compra
  </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $dec = $dec->con_valor;
      $dcc = $dcc->con_valor;
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
        <form id="frm_save" role="form" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="box-body" >
           <table class="table col-sm-12" border="0">
            <tr>
              <td class="col-sm-6">
                <table class="table">
                  <tr>
                    <td><label>No.Orden:</label></td>
                    <td><?php echo $orden->orc_codigo?></td>
                  </tr>
                  <tr>
                    <td><label>Fecha de Orden:</label></td>
                    <td><?php echo $orden->orc_fecha?></td>
                    <td><label>Fecha de Entrega:</label></td>
                    <td><?php echo $orden->orc_fecha_entrega?></td>
                    <td><label>Direccion de Entrega:</label></td>
                    <td><?php echo $orden->orc_direccion_entrega?></td>
                  </tr>
                  <tr>

                    <td><label>Condicion de Pago:</label></td>
                    <td><?php echo $orden->orc_condicion_pago?></td>
                    <td><label>Concepto:</label></td>
                    <td><?php echo $orden->orc_concepto;?></td> 
                    <td><label>Proveedor:</label></td>
                    <td><?php echo $orden->cli_raz_social;?></td>  
                </tr>
                <tr>
                      <td><label>Observaciones:</label></td>
                      <td valign="top" colspan="3"><?php echo $orden->orc_obs ?></td> 
                </tr> 
              </table>
            </td>
          </tr>    
          <tr>
           <td class="col-sm-12">
            <div class="box-body">
              <div class="panel panel-default col-sm-12">

                <table class="table table-bordered table-striped" id="tbl_detalle">
                  <thead>
                    <tr>
                      <th style="width: 70px">Item</th>
                      <th>Codigo</th>
                      <th>Descripcion</th>
                      <th>Unidad</th>
                      <th style="width: 100px">Cantidad</th>
                      <th style="width: 100px">IVA</th>
                      <th style="width: 100px">Val.Unitario</th>
                      <th style="width: 100px">Val.Unit.Utl.Aprobado</th>
                      <th style="width: 100px">Val.Total</th>
                    </tr>
                  </thead>
                    
                  <tbody id='lista'>
                    <?php
                    $count_det=0;
                    if(!empty($detalle)){
                      $n=0;
                      foreach ($detalle as $det) {
                        $n++;
                        $style="style=''";
                        if(round($det->orc_det_vu,$dec)<>round($det->val_aprob,$dec)){
                          $style="style='background:#ffbfaa'";
                        }
                    ?>
                    <tr <?php echo $style?>>
                      <td id="item<?php echo $n?>" align="center" lang="<?php echo $n?>">
                        <?php echo $n?>
                        <input type ="hidden"  id="pro_id<?php echo $n?>" name="pro_id<?php echo $n?>" lang="<?php echo $n?>" value="<?php echo $det->mp_id?>"/>
                      </td>
                      <td id="pro_codigo<?php echo $n?>" name="pro_codigo<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_c?></td>
                      <td id="pro_descripcion<?php echo $n?>" name="pro_descripcion<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_d?></td>
                      <td id="pro_uni<?php echo $n?>" name="pro_uni<?php echo $n?>" lang="<?php echo $n?>"><?php echo $det->mp_q?></td>

                      <td align="right"><?php echo str_replace(',','',number_format($det->orc_det_cant,$dec))?></td>
                      <td align="center"><?php echo $det->orc_det_iva?></td>
                      <td align="right"><?php echo str_replace(',','',number_format($det->orc_det_vu,$dec))?></td>
                      <td align="right"><?php echo str_replace(',','',number_format($det->val_aprob,$dec))?></td>
                      <td align="right"><?php echo str_replace(',','',number_format($det->orc_det_vt,$dec))?></td>
                    </tr>
                    <?php    
                      }
                      $count_det=$n;
                    }
                    ?>
                  </tbody>
                  
                </table>
              </div>
            </div>
          </td>
        </tr> 


      </table>
    </div>
    <input type="hidden" class="form-control" id="count_detalle" name="count_detalle" value="<?php echo $count_det?>">
    <input type="hidden" class="form-control" id="orc_id" name="orc_id" value="<?php echo $orden->orc_id?>">
    <div class="box-footer">
      <a href='<?php echo base_url()."orden_compra_aprobacion/actualizar/$orden->orc_id/$opc_id/13"?>' class="btn btn-success">Aprobar</a>
      <a href='<?php echo base_url()."orden_compra_aprobacion/actualizar/$orden->orc_id/$opc_id/14"?>' class="btn btn-danger">Rechazar</a>
      <a href="<?php echo $cancelar?>" class="btn btn-default">Cancelar</a>
    </div>

  </form>
</div>

</div>

  <script>
    var dec='<?php echo $dec?>';
    var dcc='<?php echo $dcc?>';
    var base_url='<?php echo base_url();?>';

    function validar_decimal(obj){
      obj.value = (obj.value + '').replace(/[^0-9.]/g, '');
    }

    

    

  </script>
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
      margin-bottom: 3px !important;
      margin-top: 3px !important;
      padding-bottom: 3px !important;
      padding-top: 3px !important;
    }

    .totales{
      font-size: 16px !important;
      text-align: right !important;
    }
  </style>
  <!-- /.row -->
</section>
