
<section class="content-header">
  <h1>
    Ingreso de Cantidades 
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
        <form id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="box-body" >
           <table class="table col-sm-12" border="0">
            <tr>
                    <td>
                      <input type="hidden" name="txt" id="txt" value="<?php echo $txt?>">
                      <input type="hidden" name="fec1" id="fec1" value="<?php echo $fec1?>">
                      <input type="hidden" name="fec2" id="fec2" value="<?php echo $fec2?>">
                      <input type="hidden" name="estado" id="estado" value="<?php echo $est?>">
                    </td>
                  </tr>
            <tr>
              <td class="col-sm-6">
                <table class="table">
                  <tbody>
                    <?php
                    $n=0;
                    $sum=0;
                    foreach ($etiquetas as $etq) {
                     $n++;
                     ?>
                     <tr>
                        <td style="width: 10px;"><?php echo $n?></td>
                        <td >
                          <input type="text" class="form-control decimal item" name="etq_peso<?php echo $n?>" id="etq_peso<?php echo $n?>" lang="<?php echo $n?>" style="width: 60px; text-align: right;" onkeyup=" this.value = this.value.replace(/[^0-9.]/, '')" onchange="calculo(this)" value="<?php echo str_replace(',','',number_format($etq->etq_peso,$dec))?>" disabled>
                        </td>
                      </tr>
                     <?php
                     $sum+=round($etq->etq_peso,$dec);
                    }
                    
                    while($n <100) {
                      $n++;
                      if($n==11 || $n==21 || $n==31 || $n==41 || $n==51 || $n==61 || $n==71 || $n==81 || $n==91){
                    ?>
                    </tbody>
                    <tbody>
                    <?php    
                      }
                    ?>
                    <tr>
                      <td style="width: 10px;"><?php echo $n?></td>
                      <td >
                        <input type="text" class="form-control decimal item" name="etq_peso<?php echo $n?>" id="etq_peso<?php echo $n?>" lang="<?php echo $n?>" style="width: 60px; text-align: right;" onkeyup=" this.value = this.value.replace(/[^0-9.]/, '')" onchange="calculo(this)">
                      </td>
                    </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                 
                </table>
                <table class="table">
                  <tr>
                     <th>Cantidad Maxima</th>
                     <th colspan="2"><input type="text" class="form-control decimal" name="peso_reg" id="peso_reg" style="width: 60px; text-align: right;" readonly value="<?php echo str_replace('',',',number_format($registro->peso_max,$dec))?>"></th>
                     <th><?php echo $registro->mp_q?></th>
                     <th>Cantidad Ingresada</th>
                     <th><input type="text" class="form-control decimal" name="sum_peso" id="sum_peso" style="width: 60px; text-align: right;" readonly value="<?php echo str_replace(',','',number_format($sum,$dec))?>">
                     </th>
                     <th><?php echo $registro->mp_q?></th>
                  </tr>
                </table>
              </div>
            </div>
          </td>
        </tr> 


      </table>
    </div>
    <input type="hidden" class="form-control" id="orc_id" name="orc_id" value="<?php echo $registro->orc_id?>">
    <input type="hidden" class="form-control" id="orc_det_id" name="orc_det_id" value="<?php echo $registro->orc_det_id?>">
    <div class="box-footer">
      <button type="button" class="btn btn-primary" onclick="save(0)">Guardar</button>
      <button type="button" class="btn btn-success" onclick="save(1)">Guardar e Imprimir</button>
      <a href="#" class="btn btn-default" onclick="envio(0,1)">Regresar</a>
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

      function round(value, decimals) {
        return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
      }

      function envio(id,opc){
        if(opc==0){
          url='<?php echo $action?>';
        }else if(opc==1){
          url="<?php echo $cancelar?>";
        }
        
        $('#frm_save').attr('action',url);
        $('#frm_save').submit();
      }

      function calculo(obj){
        cal=round($('#sum_peso').val(),dec)+round($(obj).val(),dec);
        if(parseFloat(cal)>parseFloat($('#peso_reg').val())){
          alert('Peso supera el peso maximo');
          $(obj).val('');
          $(obj).focus();
        }
          var total=0;
          $('.item').each(function () {
            if (this.value.length > 0) {
              total+= round(this.value,dec);
            }
          });

          $('#sum_peso').val(total.toFixed(dec));
      }   

      function save(opc){
        if (parseFloat($('#sum_peso').val()) == 0) {
          swal("", "Ingrese almenos un peso", "info");
          return false;
        }

        var n=1;  
        $('.item').each(function () {
            if ($(this).attr('disabled')=='disabled' || $(this).attr('disabled')==false) {
              n= n+1;
            }else{
              return false;
            }
          });

        if ($('#etq_peso'+n).val().length == 0 || parseFloat($('#etq_peso'+n).val()) == 0   ) {
          swal("", "Ingrese almenos un peso", "info");
          return false;
        }

        if(opc==0){
          url='<?php echo $action?>';
        }else if(opc==1){
          url="<?php echo base_url();?>orden_compra_seguimiento/guardar_pesos/"+<?php echo $opc_id?>+"/1";
        }
        
        $('#frm_save').attr('action',url);
        $('#frm_save').submit();

      }

  </script>
  <style type="text/css">
    tbody{
      float: left;
    }
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
      height:20px !important;
    }

    td{
      margin-bottom: 1px !important;
      margin-top: 1px !important;
      padding-bottom: 1px !important;
      padding-top: 1px !important;
    }

    .totales{
      font-size: 16px !important;
      text-align: right !important;
    }
  </style>
  <!-- /.row -->
</section>
