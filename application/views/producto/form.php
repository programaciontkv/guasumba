
<section class="content-header">
      <h1>
        Producto
      </h1>
</section>
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
            <form role="form" action="<?php echo $action?>" method="post" autocomplete="off" enctype="multipart/form-data">
              <div class="box-body" >
                 <table class="table col-sm-12" border="0">
                    <tr>
                      <td class="col-sm-5">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        <div class="panel-heading">DATOS GENERALES</div>
                        <table class="table">
                          <tr>
                            <td><label>Cliente:</label></td>
                            <td colspan="2">
                              <div class="form-group <?php echo !empty(form_error('cli_raz_social'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control" name="cli_raz_social" id="cli_raz_social" value="<?php echo !empty(validation_errors())? set_value('cli_raz_social') :  $producto->cli_raz_social;?>" list="list_clientes" onchange="traer_cliente()">
                                    <?php echo form_error("cli_raz_social","<span class='help-block'>","</span>");?>
                                
                                </div>
                                <input type="hidden" class="form-control" name="cli_id" id="cli_id" value="<?php echo !empty(validation_errors())? set_value('cli_id') :$producto->cli_id;?>" >
                              </td>
                            </tr>
                            <tr>
                              <td><label>Familia:</label></td>
                              <td colspan="2">
                                <div class="form-group <?php echo !empty(form_error('pro_familia'))? 'has-error' : '';?>">
                                    <select name="pro_familia" id="pro_familia" class="form-control" onchange="load_tipo()">
                                      <option value="">SELECCIONE</option>
                                      <?php
                                          if(empty(validation_errors())){
                                                $fam=$producto->pro_familia;
                                              }else{
                                                $fam=set_value('tps_id');
                                          }
                                          if(!empty($familias)){
                                            foreach ($familias as $familia) {
                                      ?>
                                          <option value='<?php echo $familia->tps_id?>'><?php echo $familia->tps_nombre?></option>
                                      <?php        
                                            }
                                          }
                                      ?>
                                    </select>
                                    <?php echo form_error("pro_familia","<span class='help-block'>","</span>");?>
                                  </div>
                                  <script type="text/javascript">
                                      var fam='<?php echo $fam?>';
                                      pro_familia.value=fam;
                                  </script>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Tipo</label></td>
                                <td colspan="2">
                                  <div class="form-group <?php echo !empty(form_error('emp_id'))? 'has-error' : '';?> ">
                                    <select name="emp_id"  id="emp_id" class="form-control">
                                      <option value="">SELECCIONE</option>
                                      <?php
                                          if(!empty($tipos)){
                                            foreach ($tipos as $tipo) {
                                      ?>
                                          <option value='<?php echo $tipo->tps_id?>' <?php echo $select?>><?php echo $tipo->tps_nombre?></option>
                                      <?php        
                                            }
                                          }
                                      ?>
                                    </select>
                                    <?php 
                                      if(empty(validation_errors())){
                                        $tip=$producto->emp_id;
                                      }else{
                                        $tip=set_value('emp_id');
                                      }
                                    ?>
                                    <script type="text/javascript">
                                        var tip='<?php echo $tip?>';
                                        emp_id.value=tip;
                                    </script>
                                    <?php echo form_error("emp_id","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Codigo:</label></td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_codigo'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="pro_codigo" id="pro_codigo" value="<?php echo !empty(validation_errors())? set_value('pro_codigo') :  $producto->pro_codigo;?>">
                                    <?php echo form_error("pro_codigo","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_version'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="pro_version" id="pro_version" value="<?php echo !empty(validation_errors())? set_value('pro_version') :  $producto->pro_version;?>" size="7px" readonly>
                                    <?php echo form_error("pro_version","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Descripcion:</label></td>
                                <td colspan="2">
                                  <div class="form-group <?php echo !empty(form_error('pro_descripcion'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="pro_descripcion" id="pro_descripcion" value="<?php echo !empty(validation_errors())? set_value('pro_descripcion') :  $producto->pro_descripcion;?>">
                                    <?php echo form_error("pro_descripcion","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Unidad:</label></td>
                                <td>
                                  <div class="form-group">
                                    <select id="pro_uni" name="pro_uni" class="form-control" onchange="cambio_unidad(),load_bobinados();">
                                            <option value=''>Seleccione</option>
                                            <option value='KG'>kg</option>
                                            <option value='LB'>lb</option>
                                            <option value='GR'>gr</option>
                                            <option value='LITRO'>litro</option>
                                            <option value='GALON'>galon</option>
                                            <option value='M'>m</option>
                                            <option value='CM'>cm</option>
                                            <option value='FT'>ft</option>
                                            <option value='IN'>in</option>
                                            <option value='UNIDAD'>UNIDAD</option>
                                            <option value='MILLAR'>MILLAR</option>
                                            <option value='ROLLO'>rollo</option>
                                    </select>
                                    <?php 
                                      if(empty(validation_errors())){
                                                $uni=$producto->pro_uni;
                                              }else{
                                                $uni=set_value('pro_uni');
                                      }
                                    ?>
                                    <script>
                                            var puni = '<?php echo $uni?>';
                                            pro_uni.value=puni;
                                    </script>
                                    <input type="text" name="pro_formula" id="pro_formula" value="<?php echo !empty(validation_errors())? set_value('pro_formula') : $producto->pro_formula?>" hidden>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group">
                                    <select id="pro_md" name="pro_md" class="form-control" onchange="calculos(this)">
                                            <option value="1">mm</option>
                                            <option value="0">in</option>
                                    </select>
                                    <?php
                                      if(empty(validation_errors())){
                                        $pincm=$producto->pro_md;
                                      }else{
                                        $pincm=set_value('pro_md');
                                      }
                                    ?>  
                                    <script>
                                            var pincm = '<?php echo $pincm ?>';
                                            pro_md.value=pincm;
                                    </script>
                                  </div>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Estado:</label></td>
                              <td colspan="2">
                                <div class="form-group ">
                                  <select name="pro_estado"  id="pro_estado" class="form-control">
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
                                    if(empty(validation_errors())){
                                      $est=$producto->pro_estado;
                                    }else{
                                      $est=set_value('pro_estado');
                                    }
                                  ?>
                                  <script type="text/javascript">
                                    var est='<?php echo $est;?>';
                                    pro_estado.value=est;
                                  </script>
                                </div>
                              </td>
                            </tr>
                          </table>
                          </div>
                          </div>
                          </td> 
                          <td class="col-sm-7">
                            <div class="box-body">
                            <div class="panel panel-default col-sm-12">
                            <div class="panel-heading">SELECCION DE MATERIALES</div>
                            <table class="table">
                              <tr>
                                <td colspan="2">
                                  <div class="form-group">
                                    <label>Extruir en:</label>
                                    <select id="pro_extruir" name="pro_extruir" class="form-control">
                                            <option value="1">MANGA</option>
                                            <option value="2">LAMINA</option>
                                            <option value="3">MANGA ABIERTA</option>
                                    </select>
                                    <?php 
                                      if(empty(validation_errors())){
                                        $pro_ex=$producto->pro_extruir;
                                      }else{
                                        $pro_ex=set_value('pro_extruir');
                                      }
                                    ?>
                                    <script>
                                            var pro_ex= '<?php echo $pro_ex ?>';
                                            pro_extruir.value=pro_ex;
                                    </script>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td><label>Material</label></td>
                                <td><label>Espesor</label></td> 
                                <td><label>Densidad kg/cm3</label></td> 
                                <td><label>Gramaje</label></td> 
                                <td><label>Color</label></td> 
                              </tr>
                              <tr>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_mp1'))? 'has-error' : '';?> ">
                                     
                                    <select id="pro_mp1" name="pro_mp1" lang="1" class="form-control" onchange="load_mp(this)">
                                            <option value="0">SELECCIONE</option>
                                            <?php
                                            if(!empty($cns_mp1)){
                                              foreach ($cns_mp1 as $rst_mp1) {
                                            ?>
                                                <option value="<?php echo $rst_mp1->mp_id?>"><?php echo $rst_mp1->mp_descripcion?></option>
                                            <?php
                                              }
                                            }
                                            ?>
                                    </select>
                                    <?php 
                                      if(empty(validation_errors())){
                                        $pmp1=$producto->pro_mp1;
                                      }else{
                                        $pmp1=set_value('pro_mp1');
                                      }
                                    ?>
                                    <script>
                                      var pmp1 = '<?php echo $pmp1 ?>';
                                      pro_mp1.value=pmp1;
                                    </script>
                                    <?php echo form_error("pro_mp1","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_espesor1'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control decimal" name="mp_espesor1" id="mp_espesor1" value="<?php echo !empty(validation_errors())? set_value('mp_espesor1') :  $materiales->mp_espesor1;?>" size="7px" onchange="calculos()">
                                    <?php echo form_error("mp_espesor1","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_densidad1'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_densidad1" id="mp_densidad1" value="<?php echo !empty(validation_errors())? set_value('mp_densidad1') :  $materiales->mp_densidad1;?>" size="7px" readonly>
                                    <?php echo form_error("mp_densidad1","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_gramaje1'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_gramaje1" id="mp_gramaje1" value="<?php echo !empty(validation_errors())? set_value('mp_gramaje1') :  $materiales->mp_gramaje1;?>" size="7px" readonly>
                                    <?php echo form_error("mp_gramaje1","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td hidden>
                                  <div class="form-group <?php echo !empty(form_error('mp_ancho1'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_ancho1" id="mp_ancho1" value="<?php echo !empty(validation_errors())? set_value('mp_ancho1') :  $materiales->mp_ancho1;?>" size="7px" readonly>
                                    <?php echo form_error("mp_ancho1","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_color1'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_color1" id="mp_color1" value="<?php echo !empty(validation_errors())? set_value('mp_color1') :  $materiales->mp_color1;?>" readonly>
                                    <?php echo form_error("mp_color1","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_mp2'))? 'has-error' : '';?> ">
                                     
                                    <select id="pro_mp2" name="pro_mp2" lang="2" class="form-control" onchange="load_mp(this)">
                                            <option value="0">SELECCIONE</option>
                                            <?php
                                            if(!empty($cns_mp2)){
                                              foreach ($cns_mp2 as $rst_mp2) {
                                            ?>
                                                <option value="<?php echo $rst_mp2->mp_id?>"><?php echo $rst_mp2->mp_descripcion?></option>
                                            <?php
                                              }
                                            }
                                            ?>
                                    </select>
                                    <?php 
                                      if(empty(validation_errors())){
                                        $pmp2=$producto->pro_mp2;
                                      }else{
                                        $pmp2=set_value('pro_mp2');
                                      }
                                    ?>
                                    <script>
                                      var pmp2 = '<?php echo $pmp2 ?>';
                                      pro_mp2.value=pmp2;
                                    </script>
                                    <?php echo form_error("pro_mp2","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_espesor2'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control decimal" name="mp_espesor2" id="mp_espesor2" value="<?php echo !empty(validation_errors())? set_value('mp_espesor2') :  $materiales->mp_espesor2;?>" size="7px" onchange="calculos()">
                                    <?php echo form_error("mp_espesor2","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_densidad2'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_densidad2" id="mp_densidad2" value="<?php echo !empty(validation_errors())? set_value('mp_densidad2') :  $materiales->mp_densidad2;?>" size="7px" readonly>
                                    <?php echo form_error("mp_densidad2","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_gramaje2'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_gramaje2" id="mp_gramaje2" value="<?php echo !empty(validation_errors())? set_value('mp_gramaje2') :  $materiales->mp_gramaje2;?>" size="7px" readonly>
                                    <?php echo form_error("mp_gramaje2","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td hidden>
                                  <div class="form-group <?php echo !empty(form_error('mp_ancho2'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_ancho2" id="mp_ancho2" value="<?php echo !empty(validation_errors())? set_value('mp_ancho2') :  $materiales->mp_ancho2;?>" size="7px" readonly>
                                    <?php echo form_error("mp_ancho2","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_color2'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_color2" id="mp_color2" value="<?php echo !empty(validation_errors())? set_value('mp_color2') :  $materiales->mp_color2;?>" readonly>
                                    <?php echo form_error("mp_color2","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_mp3'))? 'has-error' : '';?> ">
                                     
                                    <select id="pro_mp3" name="pro_mp3" lang="3" class="form-control" onchange="load_mp(this)">
                                            <option value="0">SELECCIONE</option>
                                            <?php
                                            if(!empty($cns_mp3)){
                                              foreach ($cns_mp3 as $rst_mp3) {
                                            ?>
                                                <option value="<?php echo $rst_mp3->mp_id?>"><?php echo $rst_mp3->mp_descripcion?></option>
                                            <?php
                                              }
                                            }
                                            ?>
                                    </select>
                                    <?php 
                                      if(empty(validation_errors())){
                                        $pmp3=$producto->pro_mp3;
                                      }else{
                                        $pmp3=set_value('pro_mp3');
                                      }
                                    ?>
                                    <script>
                                      var pmp3 = '<?php echo $pmp3 ?>';
                                      pro_mp3.value=pmp3;
                                    </script>
                                    <?php echo form_error("pro_mp3","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_espesor3'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control decimal" name="mp_espesor3" id="mp_espesor3" value="<?php echo !empty(validation_errors())? set_value('mp_espesor3') :  $materiales->mp_espesor3;?>" size="7px" onchange="calculos()">
                                    <?php echo form_error("mp_espesor3","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_densidad3'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_densidad3" id="mp_densidad3" value="<?php echo !empty(validation_errors())? set_value('mp_densidad3') :  $materiales->mp_densidad3;?>" size="7px" readonly>
                                    <?php echo form_error("mp_densidad3","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_gramaje3'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_gramaje3" id="mp_gramaje3" value="<?php echo !empty(validation_errors())? set_value('mp_gramaje3') :  $materiales->mp_gramaje3;?>" size="7px" readonly>
                                    <?php echo form_error("mp_gramaje3","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td hidden>
                                  <div class="form-group <?php echo !empty(form_error('mp_ancho3'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_ancho3" id="mp_ancho3" value="<?php echo !empty(validation_errors())? set_value('mp_ancho3') :  $materiales->mp_ancho3;?>" size="7px" readonly>
                                    <?php echo form_error("mp_ancho3","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_color3'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_color3" id="mp_color3" value="<?php echo !empty(validation_errors())? set_value('mp_color3') :  $materiales->mp_color3;?>" readonly>
                                    <?php echo form_error("mp_color3","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_mp19'))? 'has-error' : '';?> ">
                                     
                                    <select id="pro_mp19" name="pro_mp19" lang="4" class="form-control" onchange="load_mp(this)">
                                            <option value="0">SELECCIONE</option>
                                            <?php
                                            if(!empty($cns_mp4)){
                                              foreach ($cns_mp4 as $rst_mp4) {
                                            ?>
                                                <option value="<?php echo $rst_mp4->mp_id?>"><?php echo $rst_mp4->mp_descripcion?></option>
                                            <?php
                                              }
                                            }
                                            ?>
                                    </select>
                                    <?php 
                                      if(empty(validation_errors())){
                                        $pmp4=$producto->pro_mp19;
                                      }else{
                                        $pmp4=set_value('pro_mp19');
                                      }
                                    ?>
                                    <script>
                                      var pmp4 = '<?php echo $pmp4?>';
                                      pro_mp19.value=pmp4;
                                    </script>
                                    <?php echo form_error("pro_mp19","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_espesor4'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control decimal" name="mp_espesor4" id="mp_espesor4" value="<?php echo !empty(validation_errors())? set_value('mp_espesor4') :  $materiales->mp_espesor4;?>" size="7px" onchange="calculos()">
                                    <?php echo form_error("mp_espesor4","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_densidad4'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_densidad4" id="mp_densidad4" value="<?php echo !empty(validation_errors())? set_value('mp_densidad4') :  $materiales->mp_densidad4;?>" size="7px" readonly>
                                    <?php echo form_error("mp_densidad4","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_gramaje4'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_gramaje4" id="mp_gramaje4" value="<?php echo !empty(validation_errors())? set_value('mp_gramaje4') :  $materiales->mp_gramaje4;?>" size="7px" readonly>
                                    <?php echo form_error("mp_gramaje4","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td hidden>
                                  <div class="form-group <?php echo !empty(form_error('mp_ancho4'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_ancho4" id="mp_ancho4" value="<?php echo !empty(validation_errors())? set_value('mp_ancho4') :  $materiales->mp_ancho4;?>" size="7px" readonly>
                                    <?php echo form_error("mp_ancho4","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('mp_color4'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="mp_color4" id="mp_color4" value="<?php echo !empty(validation_errors())? set_value('mp_color4') :  $materiales->mp_color4;?>" readonly>
                                    <?php echo form_error("mp_color4","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_suma_espesor'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="pro_suma_espesor" id="pro_suma_espesor" value="<?php echo !empty(validation_errors())? set_value('pro_suma_espesor') :  $producto->pro_suma_espesor;?>" size="7px" readonly>
                                    <?php echo form_error("pro_suma_espesor","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td>
                                  <div class="form-group <?php echo !empty(form_error('pro_suma_densidad'))? 'has-error' : '';?> ">
                                    <input type="text" class="form-control" name="pro_suma_densidad" id="pro_suma_densidad" value="<?php echo !empty(validation_errors())? set_value('pro_suma_densidad') :  $producto->pro_suma_densidad;?>" size="7px" readonly>
                                    <?php echo form_error("pro_suma_densidad","<span class='help-block'>","</span>");?>
                                  </div>
                                </td>
                                <td></td>
                                <td></td>
                              </tr>
                            </table>
                            </div>
                            </div>
                          </td>
                    </tr>
                    <tr>
                      <td class="col-sm-12" colspan="2">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        <div class="panel-heading">PROPIEDADES DEL PRODUCTO</div>
                        <table class="table">
                          <tr>
                            <td><label>Ancho (<font class="etiqueta">mm</font>):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_ancho'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_ancho" id="pro_ancho" value="<?php echo !empty(validation_errors())? set_value('pro_ancho') :  $producto->pro_ancho;?>" size="8px" onchange="calculos()">
                                <?php echo form_error("pro_ancho","<span class='help-block'>","</span>");?>
                                <input type="text" name="pro_ancho_total" id="pro_ancho_total" value="<?php echo $producto->pro_ancho_total?>" hidden>
                                </div>
                                
                            </td>
                            <td><label>Largo (<font class="etiqueta">mm</font>):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_largo'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_largo" id="pro_largo" value="<?php echo !empty(validation_errors())? set_value('pro_largo') :  $producto->pro_largo;?>" size="8px" onchange="calculos()">
                                <?php echo form_error("pro_largo","<span class='help-block'>","</span>");?>
                                <input type="text" name="pro_largo_total" id="pro_largo_total" value="<?php echo !empty(validation_errors())? set_value('pro_largo_total') : $producto->pro_largo_total?>" hidden>
                                </div>

                            </td>
                            <td><label>Deltapack:</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_doypack'))? 'has-error' : '';?> ">
                                <select name="pro_doypack"  id="pro_doypack" class="form-control" onchange="load_doypack()">
                                  <option value="0">SELECCIONE</option>
                                 <?php
                                if(!empty($cns_doypack)){
                                  foreach ($cns_doypack as $rst_dyp) {
                                ?>
                                <option value="<?php echo $rst_dyp->mp_id?>"><?php echo $rst_dyp->mp_descripcion?></option>
                                <?php
                                  }
                                }
                              ?>
                              </select>
                              <?php 
                                if(empty(validation_errors())){
                                  $pdyk=$producto->pro_doypack;
                                }else{
                                  $pdyk=set_value('pro_doypack');
                                }
                              ?>
                              <script type="text/javascript">
                                var pdyk='<?php echo $pdyk;?>';
                                pro_doypack.value=pdyk;
                              </script>
                                <?php echo form_error("pro_doypack","<span class='help-block'>","</span>");?>
                                <input type="text" name="pro_mp14" id="pro_mp14" value="<?php echo !empty(validation_errors())? set_value('pro_mp14') : $producto->pro_mp14?>" hidden>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td><label>Fuelle Lateral (<font class="etiqueta">mm</font>):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_mf1'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_mf1" id="pro_mf1" value="<?php echo !empty(validation_errors())? set_value('pro_mf1') :  $producto->pro_mf1;?>" size="8px" onchange="calculos()">
                                <?php echo form_error("pro_mf1","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td><label>Fuelle Fondo (<font class="etiqueta">mm</font>):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_mf2'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_mf2" id="pro_mf2" value="<?php echo !empty(validation_errors())? set_value('pro_mf2') :  $producto->pro_mf2;?>" size="8px" onchange="calculos()">
                                <?php echo form_error("pro_mf2","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td><label>Zipper:</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_zipper'))? 'has-error' : '';?> ">
                                <select name="pro_zipper"  id="pro_zipper" class="form-control" onchange="load_zipper()">
                                  <option value="0">SELECCIONE</option>
                                 <?php
                                if(!empty($cns_zipper)){
                                  foreach ($cns_zipper as $rst_zpp) {
                                ?>
                                <option value="<?php echo $rst_zpp->mp_id?>"><?php echo $rst_zpp->mp_descripcion?></option>
                                <?php
                                  }
                                }
                              ?>
                              </select>
                              <?php 
                                if(empty(validation_errors())){
                                  $zpp=$producto->pro_zipper;
                                }else{
                                  $zpp=set_value('pro_zipper');
                                }
                              ?>
                              <script type="text/javascript">
                                var zpp='<?php echo $zpp;?>';
                                pro_zipper.value=zpp;
                              </script>
                                <?php echo form_error("pro_zipper","<span class='help-block'>","</span>");?>
                                <input type="text" name="pro_mp15" id="pro_mp15" value="<?php echo !empty(validation_errors())? set_value('pro_mp15') : $producto->pro_mp15?>" hidden>
                                </div>
                            </td>
                            <td><label>Matriz de Perforacion:</label></td>
                            <td><label>Diametro (mm):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_mf18'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_mf18" id="pro_mf18" value="<?php echo !empty(validation_errors())? set_value('pro_mf18') :  $producto->pro_mf18;?>" size="8px">
                                <?php echo form_error("pro_mf18","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                          </tr>
                          <tr>
                            <td><label>Traslapado (<font class="etiqueta">mm</font>):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_mf3'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_mf3" id="pro_mf3" value="<?php echo !empty(validation_errors())? set_value('pro_mf3') :  $producto->pro_mf3;?>" size="8px" onchange="calculos()">
                                <?php echo form_error("pro_mf3","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td><label>Solapa (<font class="etiqueta">mm</font>):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_mf14'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_mf14" id="pro_mf14" value="<?php echo !empty(validation_errors())? set_value('pro_mf14') :  $producto->pro_mf14;?>" size="8px" onchange="calculos()">
                                <?php echo form_error("pro_mf14","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td> <label>AbreFacil:</label></td>
                            <td>
                              <?php 
                              if(validation_errors()){
                                if(set_value('pro_mp16')){
                                  $chk_ab='checked';
                                }else{
                                  $chk_ab='';
                                }
                              }else{
                                if($producto->pro_mp16==1){
                                  $chk_ab='checked';
                                }else{
                                  $chk_ab='';
                                }
                              }  
                              ?>
                              <div class="form-group">
                                <input type="checkbox" name="pro_mp16" id="pro_mp16" <?php echo $chk_ab?>>
                                </div>
                            </td>
                            <td><label>#Perforaciones:</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_mf16'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_mf16" id="pro_mf16" value="<?php echo !empty(validation_errors())? set_value('pro_mf16') :  $producto->pro_mf16;?>" size="8px">
                                <?php echo form_error("pro_mf16","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td> <label>Tratado Corona:</label></td>
                            <td>
                              <div class="form-group">
                                <?php 
                                if(validation_errors()){
                                  if(set_value('pro_medvul')){
                                    $chk_tc='checked';
                                  }else{
                                    $chk_tc='';
                                  }
                                }else{
                                  if($producto->pro_medvul==1){
                                    $chk_tc='checked';
                                  }else{
                                    $chk_tc='';
                                  }
                                }  
                                ?>
                                <input type="checkbox" name="pro_medvul" id="pro_medvul" <?php echo $chk_tc;?>>
                                </div>
                            </td>
                          </tr>
                          <tr>
                            <td><label>Espesor (micro):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_espesor'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_espesor" id="pro_espesor" value="<?php echo !empty(validation_errors())? set_value('pro_espesor') :  $producto->pro_espesor;?>" size="8px" onchange="calculos()">
                                
                                <?php echo form_error("pro_espesor","<span class='help-block'>","</span>");?>
                                </div>
                                <input type="hidden" class="form-control" name="pro_espesor_total" id="pro_espesor_total" value="<?php echo !empty(validation_errors())? set_value('pro_espesor_total') :  $producto->pro_espesor_total;?>" size="8px" >
                            </td>
                            <td><label>Doble Solapa (<font class="etiqueta">mm</font>):</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_mf15'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_mf15" id="pro_mf15" value="<?php echo !empty(validation_errors())? set_value('pro_mf15') :  $producto->pro_mf15;?>" size="8px">
                                <?php echo form_error("pro_mf15","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td><label>Microprocesado:</label></td>
                            <td>
                              <div class="form-group">
                              <?php 
                              if(validation_errors()){
                                  if(set_value('pro_mp17')){
                                    $chk_mc='checked';
                                  }else{
                                    $chk_mc='';
                                  }
                                }else{  
                                  if($producto->pro_mp17==1){
                                    $chk_mc='checked';
                                  }else{
                                    $chk_mc='';
                                  }
                                }  
                              ?>
                                <input type="checkbox" name="pro_mp17" id="pro_mp17" <?php echo $chk_mc;?>>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td><label>Merma %:</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_por_tornillo2'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_por_tornillo2" id="pro_por_tornillo2" value="<?php echo !empty(validation_errors())? set_value('pro_por_tornillo2') :  $producto->pro_por_tornillo2;?>" size="8px">
                                <?php echo form_error("pro_por_tornillo2","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td><label>Presentacion:</label></td>
                            <td colspan="2">
                              <div class="form-group">
                                <select name="pro_propiedad1"  id="pro_propiedad1" class="form-control" onchange="load_imagen()">
                                  <option value="0">SELECCIONE</option>
                                 <?php
                                if(!empty($cns_bob)){
                                  foreach ($cns_bob as $rst_bob) {
                                ?>
                                <option value="<?php echo $rst_bob->img_id?>"><?php echo $rst_bob->img_descripcion?></option>
                                <?php
                                  }
                                }
                              ?>
                              </select>
                              <?php 
                                if(empty(validation_errors())){
                                  $bob=$producto->pro_propiedad1;
                                }else{
                                  $bob=set_value('pro_propiedad1');
                                }
                              ?>
                              <script type="text/javascript">
                                var bob='<?php echo $bob;?>';
                                pro_propiedad1.value=bob;
                              </script>
                                </div>
                                <input type="text" name="pro_mp18" id="pro_mp18" value="<?php echo !empty(validation_errors())? set_value('pro_mp18') :  $producto->pro_mp18?>" hidden>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td><label>Cantidad Fundas:</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_propiedad4'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_propiedad4" id="pro_propiedad4" value="<?php echo !empty(validation_errors())? set_value('pro_propiedad4') :  $producto->pro_propiedad4;?>" size="8px" readonly>
                                <?php echo form_error("pro_propiedad4","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td colspan="2" rowspan="2">
                              <div class="form-group">
                                <img id="fotografia1" class="fotografia1" src="<?php echo base_url().'imagenes/'.$adicionales->img_direccion?>" width="250px" height="200px" class="form-control">
                              </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td><label>Peso <font id="etq_millar"><?php echo $adicionales->etq_millar ?></font>:</label></td>
                            <td>
                              <div class="form-group <?php echo !empty(form_error('pro_peso'))? 'has-error' : '';?> ">
                                <input type="text" class="form-control decimal" name="pro_peso" id="pro_peso" value="<?php echo !empty(validation_errors())? set_value('pro_peso') :  $producto->pro_peso;?>" size="8px" readonly onchange="calculos()">
                                <?php echo form_error("pro_peso","<span class='help-block'>","</span>");?>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </table>
                        </div>
                        </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="col-sm-12" colspan="2">
                        <div class="box-body">
                        <div class="panel panel-default col-sm-12">
                        <div class="panel-heading">DETALLES DE IMPRESION</div>
                        <table class="table">
                          <tr>
                            <td>
                              <table class="table">
                                <tr>
                                  <td><label>Rodillo:</label></td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_propiedad3" value="<?php echo !empty(validation_errors())? set_value('pro_propiedad3') : $producto->pro_propiedad3;?>">
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Repeticion:</label></td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_repeticion" value="<?php echo !empty(validation_errors())? set_value('pro_repeticion') : $producto->pro_repeticion;?>">
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Bloques:</label></td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_bloques" value="<?php echo !empty(validation_errors())? set_value('pro_bloques') : $producto->pro_bloques;?>">
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Codigo de Barras:</label></td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_cod_ean" value="<?php echo !empty(validation_errors())? set_value('pro_cod_ean') : $producto->pro_cod_ean;?>">
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Cyreles:</label></td>
                                  <td>
                                    <div class="form-group">
                                      <input type="text" class="form-control" name="pro_matrices" value="<?php echo !empty(validation_errors())? set_value('pro_matrices') : $producto->pro_matrices;?>">
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Notif.Sanitaria/BPM:</label></td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_notif_sanitaria" value="<?php echo !empty(validation_errors())? set_value('pro_notif_sanitaria') : $producto->pro_notif_sanitaria;?>">
                                    </div>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                    <div class="form-group ">
                                      <label>Interior</label>
                                      <?php
                                      if(validation_errors()){
                                        if(set_value('pro_posicion')==0){
                                          $chk_p1='checked';
                                          $chk_p2='';
                                        }else{
                                          $chk_p2='checked';
                                          $chk_p1='';
                                        }
                                      }else{
                                        if($producto->pro_posicion==0){
                                          $chk_p1='checked';
                                          $chk_p2='';
                                        }else{
                                          $chk_p2='checked';
                                          $chk_p1='';
                                        }
                                      }
                                      ?>
                                      <input type="radio" name="pro_posicion" id="pro_posicion1" value="0" <?php echo $chk_p1?>>
                                      <label>Exterior</label>
                                      <input type="radio" name="pro_posicion" id="pro_posicion2" value="1" <?php echo $chk_p2?>>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td>
                              <table class="table">
                                <tr>
                                  <td></td>
                                  <td>
                                    <div class="form-group ">
                                      <label>Tintas</label>
                                    </div>  
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <label>Pantone</label>
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>1</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp4"  id="pro_mp4" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta1)){
                                        foreach ($cns_tinta1 as $rst_tn1) {
                                      ?>
                                      <option value="<?php echo $rst_tn1->mp_id?>"><?php echo $rst_tn1->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt1=$producto->pro_mp4;
                                        }else{
                                          $tnt1=set_value('pro_mp4');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt1='<?php echo $tnt1;?>';
                                        pro_mp4.value=tnt1;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton1" value="<?php echo !empty(validation_errors())? set_value('pro_panton1') : $producto->pro_panton1;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>2</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp5"  id="pro_mp5" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta2)){
                                        foreach ($cns_tinta2 as $rst_tn2) {
                                      ?>
                                      <option value="<?php echo $rst_tn2->mp_id?>"><?php echo $rst_tn2->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt2=$producto->pro_mp5;
                                        }else{
                                          $tnt2=set_value('pro_mp5');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt2='<?php echo $tnt2;?>';
                                        pro_mp5.value=tnt2;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton2" value="<?php echo !empty(validation_errors())? set_value('pro_panton2') : $producto->pro_panton2;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>3</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp6"  id="pro_mp6" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta3)){
                                        foreach ($cns_tinta3 as $rst_tn3) {
                                      ?>
                                      <option value="<?php echo $rst_tn3->mp_id?>"><?php echo $rst_tn3->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt3=$producto->pro_mp6;
                                        }else{
                                          $tnt3=set_value('pro_mp6');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt3='<?php echo $tnt3;?>';
                                        pro_mp6.value=tnt3;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton3" value="<?php echo !empty(validation_errors())? set_value('pro_panton3') : $producto->pro_panton3;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>4</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp7"  id="pro_mp7" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta4)){
                                        foreach ($cns_tinta4 as $rst_tn4) {
                                      ?>
                                      <option value="<?php echo $rst_tn4->mp_id?>"><?php echo $rst_tn4->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt4=$producto->pro_mp7;
                                        }else{
                                          $tnt4=set_value('pro_mp7');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt4='<?php echo $tnt4;?>';
                                        pro_mp7.value=tnt4;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton4" value="<?php echo !empty(validation_errors())? set_value('pro_panton4') : $producto->pro_panton4;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>5</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp8"  id="pro_mp8" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta5)){
                                        foreach ($cns_tinta5 as $rst_tn5) {
                                      ?>
                                      <option value="<?php echo $rst_tn5->mp_id?>"><?php echo $rst_tn5->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt5=$producto->pro_mp8;
                                        }else{
                                          $tnt5=set_value('pro_mp8');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt5='<?php echo $tnt5;?>';
                                        pro_mp8.value=tnt5;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton5" value="<?php echo !empty(validation_errors())? set_value('pro_panton5') : $producto->pro_panton5;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>6</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp9"  id="pro_mp9" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta6)){
                                        foreach ($cns_tinta6 as $rst_tn6) {
                                      ?>
                                      <option value="<?php echo $rst_tn6->mp_id?>"><?php echo $rst_tn6->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt6=$producto->pro_mp9;
                                        }else{
                                          $tnt6=set_value('pro_mp9');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt6='<?php echo $tnt6;?>';
                                        pro_mp9.value=tnt6;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton6" value="<?php echo !empty(validation_errors())? set_value('pro_panton6') : $producto->pro_panton6;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>7</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp10"  id="pro_mp10" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta7)){
                                        foreach ($cns_tinta7 as $rst_tn7) {
                                      ?>
                                      <option value="<?php echo $rst_tn7->mp_id?>"><?php echo $rst_tn7->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt7=$producto->pro_mp10;
                                        }else{
                                          $tnt7=set_value('pro_mp10');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt7='<?php echo $tnt7;?>';
                                        pro_mp10.value=tnt7;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton7" value="<?php echo !empty(validation_errors())? set_value('pro_panton7') : $producto->pro_panton7;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                                <tr>
                                  <td>8</td>
                                  <td>
                                    <div class="form-group">
                                      <select name="pro_mp11"  id="pro_mp11" class="form-control">
                                        <option value="0">SELECCIONE</option>
                                       <?php
                                      if(!empty($cns_tinta8)){
                                        foreach ($cns_tinta8 as $rst_tn8) {
                                      ?>
                                      <option value="<?php echo $rst_tn8->mp_id?>"><?php echo $rst_tn8->mp_descripcion?></option>
                                      <?php
                                        }
                                      }
                                      ?>
                                      </select>
                                      <?php 
                                        if(empty(validation_errors())){
                                          $tnt8=$producto->pro_mp11;
                                        }else{
                                          $tnt8=set_value('pro_mp11');
                                        }
                                      ?>
                                      <script type="text/javascript">
                                        var tnt8='<?php echo $tnt8;?>';
                                        pro_mp11.value=tnt8;
                                      </script>
                                    </div>
                                  </td>
                                  <td>
                                    <div class="form-group ">
                                      <input type="text" class="form-control" name="pro_panton8" value="<?php echo !empty(validation_errors())? set_value('pro_panton8') : $producto->pro_panton8;?>" size="10px">
                                    </div>  
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td>
                              <table class="table">
                                <tr>
                                  <td>
                                    <div class="form-group ">
                                      <?php
                                        if(empty(validation_errors())){
                                          $imagen2=$producto->pro_propiedad2;
                                        }else{
                                          $imagen2=set_value('pro_propiedad2');
                                        }
                                      ?> 
                                      <img id="fotografia2" class="fotografia2" src="<?php echo base_url().'imagenes/'.$imagen2 ?>" width="250px" height="200px" class="form-control">
                                      <input type="text" hidden name="pro_propiedad2" id="pro_propiedad2" value="<?php echo !empty(validation_errors())? set_value('pro_propiedad2') :  $producto->pro_propiedad2;?>" >
                                      <input type="file" name="direccion2" id="direccion2"  onchange="uploadAjax(2)">
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        </div>
                        </div>
                      </td>
                    </tr>     
                  </table>
              </div>
                                
                <input type="hidden" class="form-control" name="pro_id" value="<?php echo $producto->pro_id?>">
                <input type="hidden" class="form-control" name="pro_tipo" value="<?php echo !empty(validation_errors())? set_value('pro_tipo') : $producto->pro_tipo?>">
                <input type="hidden" class="form-control" name="ped_id" value="<?php echo !empty(validation_errors())? set_value('ped_id') : $adicionales->ped_id?>">
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <?php 
                  if(empty($adicionales->ped_id)){
                    $cancelar= base_url().'producto';
                  }else{
                    $cancelar= base_url().'pedido/editar/'.$adicionales->ped_id;
                  }
                ?>
                <a href="<?php echo $cancelar?>" class="btn btn-default">Cancelar</a>
              </div>

            </form>
          </div>
         
      </div>
      <!-- /.row -->
    </section>
    <datalist id="list_clientes">
      <?php 
        if(!empty($cns_clientes)){
          foreach ($cns_clientes as $rst_cli) {
      ?>
        <option value="<?php echo $rst_cli->cli_id?>"><?php echo $rst_cli->cli_ced_ruc .' '.$rst_cli->cli_raz_social?></option>
      <?php 
          }
        }
      ?>
      
    </datalist>


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
        margin-bottom: 5px !important;
        margin-top: 5px !important;
        padding-bottom: 5px !important;
        padding-top: 5px !important;
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
    <script >
      var id='<?php echo $producto->pro_id?>';
      

      var base_url='<?php echo base_url();?>';
      function uploadAjax(n) {
                var nom = 'direccion'+n;
                var inputFileImage = document.getElementById(nom);
                var file = inputFileImage.files[0];
                var data = new FormData();
                propiedad='pro_propiedad'+n;
                data.append(propiedad, file);
                $.ajax({
                    beforeSend: function () {
                      if ($('#pro_codigo').val().length == 0) {
                            alert('Ingrese un codigo');
                            return false;
                        }
                        if ($('#direccion'+n).val().length == 0) {
                            alert('Ingrese una imagen');
                            return false;
                        }
                    },
                    url: base_url+"upload/subir_imagen/"+propiedad+"/"+pro_codigo.value,
                    type: 'POST',
                    contentType: false,
                    data: data,
                    processData: false,
                    cache: false,
                    success: function (dt) {
                        dat = dt.split('&&');
                        if (dat[0] == 0) {
                            $('#pro_propiedad'+n).val(dat[1]);
                            $('#fotografia'+n).prop('src', base_url+'imagenes/'+dat[1]);
                        } else {
                            $('#pro_propiedad'+n).val('');
                            $('#direccion'+n).val('');
                            $('#fotografia'+n).prop('src','');
                        }
                    }
                })
            }

            function traer_cliente(){
              $.ajax({
                    beforeSend: function () {
                      if ($('#cli_raz_social').val().length == 0) {
                            alert('Ingrese el cliente');
                            return false;
                      }
                    },
                    url: base_url+"producto/traer_cliente/"+cli_raz_social.value,
                    type: 'POST',
                    success: function (dt) {
                        dat = dt.split('&&');
                        if(dat[0]!=""){
                          $('#cli_id').val(dat[0]);
                          $('#cli_raz_social').val(dat[1]);
                        }else{
                          $('#cli_id').val('0');
                          $('#cli_raz_social').val('');
                        }  
                    },
                    error : function(xhr, status) {
                      $('#cli_id').val('0');
                      $('#cli_raz_social').val('');
                    }
                    });    
            }

            function load_tipo(){
            uri=base_url+'producto/traer_tipos/'+$('#pro_familia').val();
            $.ajax({
                    url: uri,
                    type: 'POST',
                    success: function(dt){
                       $('#emp_id').html(dt);
                    } 
              });
           }

           function load_mp(obj){
                n = obj.lang;

                if(n==4){
                  pro=$('#pro_mp19').val();
                }else{
                  pro=$('#pro_mp'+n).val();
                }
                $.ajax({
                    url: base_url+"producto/load_mp/"+pro,
                    type: 'POST',
                    success: function(dt){
                    dat = dt.split("&&");
                    if (dat[0] != "") {
                        $('#mp_densidad' + n).val(dat[1]);
                        $('#mp_ancho' + n).val(dat[2]);
                        $('#mp_espesor' + n).val(dat[3]);
                        $('#mp_color' + n).val(dat[4]);
                        $('#mp_gramaje' + n).val(parseFloat(dat[1] * dat[3]).toFixed(2));
                    } else {
                        $('#mp_densidad' + n).val('0');
                        $('#mp_ancho' + n).val('0');
                        $('#mp_espesor' + n).val('0');
                        $('#mp_color' + n).val('');
                        $('#mp_gramaje' + n).val('0');
                    }
                    calculos();
                }
              });
            }

            function load_doypack()
            {
                $.ajax({
                    url: base_url+"producto/load_mp/"+$('#pro_doypack').val(),
                    type: 'POST',
                    success: function(dt){
                    dat = dt.split("&&");
                    if (dat[0] != "") {
                        $("#pro_mp14").val(dat[1]);
                        calculos();
                    } else {
                        $("#pro_mp14").val('0');
                        calculos();
                    }
                }
              });
            }

            function load_zipper(){
                $.ajax({
                    url: base_url+"producto/load_mp/"+$('#pro_zipper').val(),
                    type: 'POST',
                    success: function(dt){
                    dat = dt.split("&&");
                    if (dat[0] != "") {
                        $("#pro_mp15").val(dat[1]);
                        calculos();
                    }else{
                       $("#pro_mp15").val('0');
                        calculos();
                    }
                }
                
              });
            }


           function calculos() {
                switch ($('#pro_mp18').val()){
                    case '0':
                        $('#pro_mf1').prop('readonly',false);
                        $('#pro_mf3').prop('readonly',false);
                        $('#pro_mf2').prop('readonly',true);
                        $('#pro_mf14').prop('readonly',true);
                        $('#pro_mf15').prop('readonly',true); 
                        $('#pro_doypack').prop('disabled',true);
                        $('#pro_zipper').prop('disabled',true);   
                        $('#pro_mf2').val('0');
                        $('#pro_mf14').val('0');
                        $('#pro_mf15').val('0');
                        vl=0.5;
                    break;
                    case '1':
                        $('#pro_mf1').prop('readonly',true);
                        $('#pro_mf3').prop('readonly',true);
                        $('#pro_mf2').prop('readonly',false);
                        $('#pro_mf14').prop('readonly',false);
                        $('#pro_mf15').prop('readonly',false); 
                        $('#pro_doypack').prop('disabled',false);
                        $('#pro_zipper').prop('disabled',false);  
                        $('#pro_mf1').val('0');
                        $('#pro_mf3').val('0');
                        vl=0.5;
                    break;
                    case '2':
                        $('#pro_mf1').prop('readonly',true);
                        $('#pro_mf3').prop('readonly',true);
                        $('#pro_mf2').prop('readonly',true);
                        $('#pro_mf14').prop('readonly',true);
                        $('#pro_mf15').prop('readonly',true); 
                        $('#pro_doypack').prop('disabled',true);
                        $('#pro_zipper').prop('disabled',true);   
                        $('#pro_mf1').val('0');
                        $('#pro_mf3').val('0');
                        $('#pro_mf2').val('0');
                        $('#pro_mf14').val('0');
                        $('#pro_mf15').val('0');
                        vl=0.5;
                    break;
                    default:
                        $('#pro_mf1').prop('readonly',true);
                        $('#pro_mf3').prop('readonly',true);
                        $('#pro_mf2').prop('readonly',true);
                        $('#pro_mf14').prop('readonly',true);
                        $('#pro_mf15').prop('readonly',true); 
                        $('#pro_doypack').prop('disabled',true);
                        $('#pro_zipper').prop('disabled',true); 
                        $('#pro_mf1').val('0');
                        $('#pro_mf2').val('0');
                        $('#pro_mf14').val('0');
                        $('#pro_mf15').val('0');
                        vl=1;
                    break;
                }
                ///cmbo etiquetas

                if ($('#pro_md').val() == '0') {
                    $(".etiqueta").html('in');
                    $(".etiqueta_esp").html('mlp');
                } else {
                    $(".etiqueta").html('mm');
                    $(".etiqueta_esp").html(unescape('%u039C'));
                }
                //ancho total
                formula = "";
                an = $("#pro_ancho").val();
                fl = $("#pro_mf1").val();
                tp = $("#pro_mf3").val();
                if (an.length == 0) {
                    an = 0;
                }
                if (fl.length == 0) {
                    fl = 0;
                }
                if (tp.length == 0) {
                    tp = 0;
                }
                anchototal = parseFloat(an) + 2 * parseFloat(fl) + parseFloat(tp);
                $("#pro_ancho_total").val(anchototal.toFixed(2));
                //largo total
                lr = $("#pro_largo").val();
                ff = $("#pro_mf2").val();
                sl = $("#pro_mf14").val();
                sd = $("#pro_mf15").val();
                if (lr.length == 0) {
                    lr = 0;
                }
                if (ff.length == 0) {
                    ff = 0;
                }
                if (sl.length == 0) {
                    sl = 0;
                }
                if (sd.length == 0) {
                    sd = 0;
                }

                largototal = parseFloat(lr) + parseFloat(ff) + parseFloat(sl) / 2 + parseFloat(sd);
                $("#pro_largo_total").val(largototal.toFixed(2));
                //densidad
                dm1 = $("#mp_densidad1").val();
                dm2 = $("#mp_densidad2").val();
                dm3 = $("#mp_densidad3").val();
                dm4 = $("#mp_densidad4").val();
                em1 = $("#mp_espesor1").val();
                em2 = $("#mp_espesor2").val();
                em3 = $("#mp_espesor3").val();
                em4 = $("#mp_espesor4").val();
                if (dm1.length == 0) {
                    dm1 = 0;
                }
                if (dm2.length == 0) {
                    dm2 = 0;
                }
                if (dm3.length == 0) {
                    dm3 = 0;
                }
                if (dm4.length == 0) {
                    dm4 = 0;
                }
                if (em1.length == 0) {
                    em1 = 0;
                }
                if (em2.length == 0) {
                    em2 = 0;
                }
                if (em3.length == 0) {
                    em3 = 0;
                }
                if (em4.length == 0) {
                    em4 = 0;
                }
                i = 0;
                cont = 0;
                while (i <= 4) {
                    if ($('#pro_mp' + i).val() != '0') {
                        cont++;
                    }
                    i++;
                }
                sum_espesor = parseFloat(em1) + parseFloat(em2) + parseFloat(em3)+ parseFloat(em4);
                
                sum_densidad = (parseFloat(dm1) * parseFloat(em1) + parseFloat(dm2) * parseFloat(em2) + parseFloat(dm3) * parseFloat(em3)+ parseFloat(dm4) * parseFloat(em4)) / sum_espesor;
                if (sum_densidad.toString() == "NaN") {
                    sum_densidad = 0;
                }
                
                j=4;
                vtn=0;
                while (j <= 11) {
                    if ($('#pro_mp' + j).val() != '0') {
                        vtn=1;
                    }
                    j++;
                }
                if(vtn==0){
                    pega=0;
                }else{
                    pega=2;
                }
                tot_espesor = sum_espesor + pega + 1 * (cont - 1);
                
                $('#suma_densidad').val(sum_densidad.toFixed(2));
                $('#suma_espesor').val(sum_espesor.toFixed(2));
                $('#pro_espesor_total').val(tot_espesor.toFixed(2));

                if ($('#pro_uni').val() != "KG") {
                    ///peso millar

                    merma = $("#pro_por_tornillo2").val();
                    if (merma.length == 0) {
                        merma = 0;
                    }

                    if(parseFloat(merma)>100){
                        alert('La merma no debe pasar de 100%');
                        $("#pro_por_tornillo2").val('0');
                        merma = 0;
                    }
                    doypack = $("#pro_mp14").val();
                    if (doypack.length == 0) {
                        doypack = 0;
                    }
                    zipper = $("#pro_mp15").val();
                    if (zipper.length == 0) {
                        zipper = 0;
                    }

                    if ($("#pro_md").val() == '0') {
                        md = (2.54 * 2.54);
                    } else {
                        md = 1;
                    }

                    pm=calculo_formula(anchototal,tot_espesor,sum_densidad,md,merma,doypack,an,zipper);


                    /////calculo de peso por unidades
                    if($('#pro_md').val()=='1'){
                                pm=pm/100;
                    }
                    switch($('#pro_uni').val()){
                        case 'MILLAR':
                            $('#pro_peso').val(pm.toFixed(2));
                        break;
                        case 'ROLLO':
                        cnt_fnd=$('#pro_propiedad4').val();
                            if(cnt_fnd.length==0){
                                cnt_fnd=0;
                            }else{
                                cnt_fnd=parseFloat(cnt_fnd);
                            }

                            prll=pm/1000*cnt_fnd;
                            $('#pro_peso').val(prll.toFixed(2));
                        break;
                    }
                    calculo_formula(anchototal,tot_espesor,sum_densidad,md,merma,doypack,an,zipper);
                
                }else{
                /////calculo de peso por KG
                        if ($("#pro_md").val() == '0') {
                            coef = 61.0237441;
                            sum_espesor=sum_espesor/ 25.4;
                        } else {
                            coef = 1000000;
                            sum_espesor=sum_espesor;
                        }

                        fin_peso=$('#pro_peso').val();
                        if(fin_peso.length==0){
                                fin_peso=0;
                            }else{
                                fin_peso=parseFloat(fin_peso);
                            }
                         kgl=((fin_peso/(vl*anchototal*sum_espesor*sum_densidad))-ff-sl-sd)*coef;
                         if(kgl.toFixed(2)=='NaN' || kgl.toFixed(2)=='Infinity'){
                            kgl=0;
                         }
                            if(kgl.toFixed(2)=='NaN'){
                            kgl=0;
                         }
                            $('#pro_largo').val(kgl.toFixed(2));
                            calculo_formula(anchototal,tot_espesor,sum_densidad,md,merma,doypack,an,zipper);
                }

                $('#pro_suma_densidad').val(sum_densidad.toFixed(2));
                    $('#pro_suma_espesor').val(sum_espesor.toFixed(2));
                    $('#pro_espesor_total').val(tot_espesor.toFixed(2));
                    $('#pro_densidad').val(sum_densidad.toFixed(2));
                calc_espesor();
            }

            function calculo_formula(anchototal,tot_espesor,sum_densidad,md,merma,doypack,an,zipper){

              //largo total
                lr = $("#pro_largo").val();
                ff = $("#pro_mf2").val();
                sl = $("#pro_mf14").val();
                sd = $("#pro_mf15").val();
                if (lr.length == 0) {
                    lr = 0;
                }
                if (ff.length == 0) {
                    ff = 0;
                }
                if (sl.length == 0) {
                    sl = 0;
                }
                if (sd.length == 0) {
                    sd = 0;
                }

                largototal = parseFloat(lr) + parseFloat(ff) + parseFloat(sl) / 2 + parseFloat(sd);
                $("#pro_largo_total").val(largototal.toFixed(2));

              if ($('#pro_mp18').val() == '0'  || $('#pro_mp18').val() == '1' || $('#pro_mp18').val() == '2') {
                        pm = 2 * anchototal * largototal * tot_espesor * sum_densidad * (md / 10000) * (1 - parseFloat(merma)/100) + parseFloat(doypack) * parseFloat(an) + parseFloat(zipper) * parseFloat(an);
                        formula = "2";
                    } else {
                        pm = anchototal * largototal * tot_espesor * sum_densidad * (md / 10000) * (1 - parseFloat(merma)/100) + parseFloat(doypack) * parseFloat(an) + parseFloat(zipper) * parseFloat(an);
                        formula = "";
                    }

                    
                    ///formula
                    if (formula != "") {
                        formula += "X";
                    }
                    var anchtot = "(";
                    if (parseFloat(an) != 0) {
                        anchtot += an;
                    }
                    if (parseFloat(an) != 0 && parseFloat(fl) != 0) {
                        anchtot += "+";
                    }
                    if (parseFloat(fl) != 0) {
                        anchtot += '2fl' + fl;
                    }
                    if (parseFloat(fl) != 0 && parseFloat(tp) != 0) {
                        anchtot += "+";
                    }
                    if (parseFloat(tp) != 0) {
                        anchtot += tp;
                    }
                    anchtot += ")";
                    if (anchtot == "()") {
                        anchtot = "";
                    }

                    var lartot = "(";
                    if (parseFloat(lr) != 0) {
                        lartot += lr;
                    }
                    if (parseFloat(lr) != 0 && parseFloat(ff) != 0) {
                        lartot += "+";
                    }
                    if (parseFloat(ff) != 0) {
                        lartot += ff;
                    }
                    if (parseFloat(ff) != 0 && parseFloat(sl) != 0) {
                        lartot += "+";
                    }
                    if (sl != 0) {
                        lartot += sl;
                    }
                    if (parseFloat(sl) != 0 && parseFloat(sd) != 0) {
                        lartot += "+";
                    }
                    if (parseFloat(sd) != 0) {
                        lartot += sd;
                    }
                    lartot += ")";
                    if (lartot == "()") {
                        lartot = "";
                    }
                    formula += anchtot;
                    if (anchtot != "" && lartot != "") {
                        formula += "X";
                    }
                    formula += lartot;
                    if (lartot != "" || anchtot != "") {
                        formula += "X";
                    }

                    if (parseFloat(tot_espesor) != 0) {
                        formula += tot_espesor.toFixed(2);
                    }
                    if (parseFloat(sum_densidad) != 0) {
                        formula += 'X' + sum_densidad.toFixed(2);
                    }
//                    if ($("#pro_md").val() == '0') {
//                        formula += '*(2.54 * 2.54)/1000';
//                    } else {
//                        formula += '/1000'
//                    }
//                    formula += "*(1-" + merma + ")";
//                    alert('anchototal:'+anchototal+' largo total:'+largototal+' espesortotal:'+tot_espesor)
                $("#pro_formula").val(formula);
                return pm;
            }

            function calc_espesor() {
                $("#pro_espesor").css({borderColor: ""});

                if ($("#pro_md").val() == '0') {
                    esp = parseFloat($("#pro_suma_espesor").val()) / 25.4;
                } else {
                    esp = parseFloat($("#pro_suma_espesor").val());
                }
                var max = esp * (1.1);
                var min = esp * (0.9);

                if (parseFloat($("#pro_espesor").val()) > max) {
                    $("#pro_espesor").css({borderColor: "red"});
                    $("#pro_espesor").val('0');
                    $("#pro_espesor").focus();
                }
                if (parseFloat($("#pro_espesor").val()) < min) {
                    $("#pro_espesor").css({borderColor: "red"});
                    $("#pro_espesor").val('0');
                }
            }

            function cambio_unidad(){
                switch($('#pro_uni').val()){
                    case "ROLLO":
                        $('#pro_propiedad4').prop('readonly',false);
                        $('#pro_propiedad4').val('0');
                        $('#pro_largo').val('0');
                        $('#pro_largo').prop('readonly',false);
                        $('#etq_millar').html('ROLLO');
                        $('#pro_peso').val('0');
                        $('#pro_peso').prop('readonly',true);
                        break;
                    case 'KG':
                        $('#pro_propiedad4').prop('readonly',true);
                        $('#pro_propiedad4').val('0');
                        $('#pro_largo').val('0');
                        $('#pro_largo').prop('readonly',true);
                        $('#etq_millar').html('');
                        // $('#pro_peso').val('0');
                        $('#pro_peso').prop('readonly',false);
                        break; 
                    case 'MILLAR':
                        $('#pro_propiedad4').prop('readonly',true);
                        $('#pro_propiedad4').val('0');
                        $('#pro_largo').val('0');
                        $('#pro_largo').prop('readonly',false);
                        $('#etq_millar').html('MILLAR');
                        $('#pro_peso').val('0');
                        $('#pro_peso').prop('readonly',true);
                        
                        break;        
                }
                calculos();
            }

            function load_bobinados(){
                imgs = 0;
                img = $("#pro_uni").val();
                if (img == "MILLAR") {
                    imgs = 0;
                    $('#etq_millar').html('MILLAR');
                } else {
                    imgs = 1;
                    $('#etq_millar').html('');
                }
                $.ajax({
                    url: base_url+"producto/load_bobinados/"+imgs,
                    type: 'POST',
                    success: function(dt){
                    if (dt.length != 0) {
                        $("#pro_propiedad1").html(dt);
                        $("#fotografia1").prop('src', '');
                        calculos();
                    } else {
                        $("#pro_propiedad1").html('');
                        $("#fotografia1").prop('src', '');
                        calculos();
                    }
                }
              });
            }

            function load_imagen(){
                $.ajax({
                    url: base_url+"producto/load_imagen/"+$("#pro_propiedad1").val(),
                    type: 'POST',
                    success: function(dt){
                    dat = dt.split("&&");
                    if (dat[0].length != 0) {
                        $('#fotografia1').prop('src', base_url+'imagenes/'+dat[0]);
                        $('#pro_mp18').val(dat[1]);
                    } else {
                        $('#fotografia1').prop('src', dt);
                        $('#pro_mp18').val('0');
                    }
                    calculos();
                }
              });
            }

    </script>

