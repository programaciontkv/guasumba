<section class="content">
                 <table>
                  <tr>
                    <td colspan="2">
                      <table>
                        <tr>
                          <td><img src="<?php echo base_url().'imagenes/logo_empresa.jpg'?>" width="250px" height="100px"></td>
                          <td>
                            <h2>
                              FICHA TECNICA DEL PRODUCTO
                            </h2>
                          </td>
                          <td valign="bottom"><label>FECHA:<?php echo date('Y-m-d')?></label></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                    <tr>
                      <td valign="top">
                        <table class="table" width="100%" height="100%">
                          <tr>
                            <th colspan="3">
                              DATOS GENERALES
                            </th>
                          </tr>  
                          <tr>
                            <td><label>Cliente:</label></td>
                            <td colspan="2">
                              <?php echo $producto->cli_raz_social;?>
                            </td>
                          </tr>
                          <tr>
                              <td><label>Familia:</label></td>
                              <td colspan="2">
                                          <?php echo $producto->tps_nombre?>
                                </td>
                          </tr>
                          <tr>
                                <td><label>Tipo</label></td>
                                <td colspan="2">
                                    <?php echo $producto->tip_nombre?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Codigo:</label></td>
                                <td>
                                  <?php echo $producto->pro_codigo;?>
                                </td>
                                <td>
                                  <?php echo $producto->pro_version;?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Descripcion:</label></td>
                                <td colspan="2">
                                <?php echo $producto->pro_descripcion;?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Unidad:</label></td>
                                <td>
                                  <?php echo $producto->pro_uni?>
                                </td>
                                <td>
                                  <?php
                                            if($producto->pro_md==0){
                                              $pincm='in';
                                            }else{
                                              $pincm='mm';
                                            } 
                                            echo $pincm;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                              <td><label>Estado:</label></td>
                              <td colspan="2">
                                  <?php echo $producto->est_descripcion?>
                              </td>
                            </tr>
                          </table>
                          </td> 
                          <td valign="top">
                            <table class="table">
                              <tr>
                                <th colspan="5" >
                                  SELECCION DE MATERIALES
                                </th>
                              </tr>
                              <tr>
                                <td colspan="2">
                                    <label>Extruir en:</label>
                                    <?php
                                    $pro_ex='';
                                      if($producto->pro_extruir=='1'){
                                        $pro_ex='MANGA';
                                      }else if($producto->pro_extruir=='2'){
                                        $pro_ex='LAMINA';
                                      }else if($producto->pro_extruir=='3'){
                                        $pro_ex='MANGA ABIERTA';
                                      }
                                      echo $pro_ex;
                                    ?>
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
                                   <?php echo $materiales->mp_descripcion1;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_espesor1;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_densidad1;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_gramaje1;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_color1;?>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                    <?php echo $materiales->mp_descripcion2;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_espesor2;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_densidad2;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_gramaje2;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_color2;?>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                    <?php echo $materiales->mp_descripcion3;?>
                                </td>
                               <td>
                                  <?php echo $materiales->mp_espesor3;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_densidad3;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_gramaje3;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_color3;?>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                    <?php echo $materiales->mp_descripcion4;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_espesor4;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_densidad4;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_gramaje4;?>
                                </td>
                                <td>
                                  <?php echo $materiales->mp_color4;?>
                                </td>
                              </tr>
                              <tr>
                                <td></td>
                                <td>
                                  <?php echo $producto->pro_suma_espesor;?>
                                </td>
                                <td>
                                  <?php echo $producto->pro_suma_densidad;?>
                                </td>
                                <td></td>
                                <td></td>
                              </tr>
                            </table>
                          </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <table class="table">
                          <tr>
                            <th colspan="11">
                              PROPIEDADES DEL PRODUCTO
                            </th>
                          </tr>
                          <tr>
                            <td><label>Ancho (<font class="etiqueta"><?php echo $adicionales->etq_und?></font>):</label></td>
                            <td>
                              <?php echo $producto->pro_ancho;?>
                            </td>
                            <td><label>Largo (<font class="etiqueta"><?php echo $adicionales->etq_und?></font>):</label></td>
                            <td>
                              <?php echo $producto->pro_largo;?>
                            </td>
                            <td><label>Deltapack:</label></td>
                            <td>
                                <?php echo $adicionales->doypack?></option>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td><label>Fuelle Lateral (<font class="etiqueta"><?php echo $adicionales->etq_und?></font>):</label></td>
                            <td>
                              <?php echo $producto->pro_mf1;?>
                            </td>
                            <td><label>Fuelle Fondo (<font class="etiqueta"><?php echo $adicionales->etq_und?></font>):</label></td>
                            <td>
                              <?php echo $producto->pro_mf2;?>
                            </td>
                            <td><label>Zipper:</label></td>
                            <td>
                                <?php echo $adicionales->zipper?></option>
                            </td>
                            <th colspan="2"><label>Matriz de Perforacion:</label></th>
                            <td><label>Diametro (mm):</label></td>
                            <td>
                              <?php echo $producto->pro_mf18;?>
                            </td>
                          </tr>
                          <tr>
                            <td><label>Traslapado (<font class="etiqueta"><?php echo $adicionales->etq_und?></font>):</label></td>
                            <td>
                              <?php echo $producto->pro_mf3;?>
                            </td>
                            <td><label>Solapa (<font class="etiqueta"><?php echo $adicionales->etq_und?></font>):</label></td>
                            <td>
                              <?php echo $producto->pro_mf14;?>
                            </td>
                            <td> <label>AbreFacil:</label></td>
                            <td>
                              <?php 
                                if($producto->pro_mp16==1){
                                  $chk_ab='X';
                                }else{
                                  $chk_ab='';
                                }
                              echo $chk_ab?>
                            </td>
                            <td><label>#Perforaciones:</label></td>
                            <td>
                              <?php echo $producto->pro_mf16;?>
                            </td>
                            <td> <label>Tratado Corona:</label></td>
                            <td>
                                <?php 
                                  if($producto->pro_medvul==1){
                                    $chk_tc='X';
                                  }else{
                                    $chk_tc='';
                                  }
                                 echo $chk_tc;
                                 ?>
                            </td>
                          </tr>
                          <tr>
                            <td><label>Espesor (micro):</label></td>
                            <td>
                              <?php echo $producto->pro_espesor;?>
                            </td>
                            <td><label>Doble Solapa (<font class="etiqueta"><?php echo $adicionales->etq_und?></font>):</label></td>
                            <td>
                              <?php echo $producto->pro_mf15;?>
                            </td>
                            <td><label>Microprocesado:</label></td>
                            <td>
                                <?php 
                                if($producto->pro_mp17==1){
                                  $chk_mc='X';
                                }else{
                                  $chk_mc='';
                                }
                                echo $chk_mc;
                              ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td><label>Merma %:</label></td>
                            <td>
                              <?php echo $producto->pro_por_tornillo2;?>
                            </td>
                            <td><label>Presentacion:</label></td>
                            <td colspan="2">
                                <?php echo $adicionales->presentacion?>
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
                              <?php echo $producto->pro_propiedad4;?>
                            </td>
                            <td colspan="3" rowspan="3">
                              <?php
                                if(!empty($adicionales->img_direccion)){
                              ?>
                                <img src="<?php echo base_url().'imagenes/'.$adicionales->img_direccion?>" width="250px" height="200px">
                              <?php
                                }
                              ?>  
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
                              <?php echo $producto->pro_peso;?>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <table class="table" width="100%">
                          <tr>
                            <th colspan="9">
                              DETALLES DE IMPRESION
                            </th>
                          </tr>
                          <tr>
                            <td valign="top" width="33%">
                              <table width="100%">
                                <tr>
                                  <td><label>Rodillo:</label></td>
                                  <td>
                                    <?php echo $producto->pro_propiedad3;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Repeticion:</label></td>
                                  <td>
                                    <?php echo $producto->pro_repeticion;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Bloques:</label></td>
                                  <td>
                                    <?php echo $producto->pro_bloques;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Codigo de Barras:</label></td>
                                  <td>
                                    <?php echo $producto->pro_cod_ean;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Cyreles:</label></td>
                                  <td>
                                    <?php echo $producto->pro_matrices;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td><label>Notif.Sanitaria/BPM:</label></td>
                                  <td>
                                    <?php echo $producto->pro_notif_sanitaria;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="2">
                                      <label>Interior</label>
                                      <?php
                                        if($producto->pro_posicion==0){
                                          $chk_p1='X';
                                          $chk_p2='';
                                        }else{
                                          $chk_p2='X';
                                          $chk_p1='';
                                        }
                                      echo $chk_p1
                                      ?>
                                      <label>Exterior</label>
                                      <?php echo $chk_p2?>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td valign="top" width="34%">
                              <table width="100%">
                                <tr>
                                  <td></td>
                                  <td>
                                      <label>Tintas</label>
                                  </td>
                                  <td>
                                      <label>Pantone</label>
                                  </td>
                                </tr>
                                <tr>
                                  <td>1</td>
                                  <td>
                                      <?php echo $adicionales->tnt1?>
                                  </td>
                                  <td>
                                      <?php echo $producto->pro_panton1;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>2</td>
                                  <td>
                                      <?php echo $adicionales->tnt2?>
                                  </td>
                                  <td>
                                    <?php echo $producto->pro_panton2;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>3</td>
                                  <td>
                                      <?php echo $adicionales->tnt3?>
                                  </td>
                                  <td>
                                    <?php echo $producto->pro_panton3;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>4</td>
                                  <td>
                                      <?php echo $adicionales->tnt4?>
                                  </td>
                                  <td>
                                    <?php echo $producto->pro_panton4;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>5</td>
                                  <td>
                                      <?php echo $adicionales->tnt5?>
                                  </td>
                                  <td>
                                    <?php echo $producto->pro_panton5;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>6</td>
                                  <td>
                                      <?php echo $adicionales->tnt6?>
                                  </td>
                                  <td>
                                    <?php echo $producto->pro_panton6;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>7</td>
                                  <td>
                                      <?php echo $adicionales->tnt7?>
                                  </td>
                                  <td>
                                    <?php echo $producto->pro_panton7;?>
                                  </td>
                                </tr>
                                <tr>
                                  <td>8</td>
                                  <td>
                                      <?php echo $adicionales->tnt8?>
                                  </td>
                                  <td>
                                    <?php echo $producto->pro_panton8;?>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td valign="top" width="33%">
                              <table width="100%">
                                <tr>
                                  <td>
                                    <?php
                                      if(!empty($adicionales->img_direccion)){
                                    ?>
                                      <img src="<?php echo base_url().'imagenes/'.$producto->pro_propiedad2 ?>" width="200px" height="200px">
                                      <?php
                                        }
                                      ?>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>     

                  </table>
         
    </section>
<style type="text/css">
  td{
    font-size:12px; 
  }
  label{
    font-weight: bolder;
  }
  .table{
    border: 1px solid;
  }

  th{
    background: #2E2E2E;
    color: #FFFFFF;
  }
</style>