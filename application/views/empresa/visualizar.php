<p><strong>Ruc:</strong> <?php echo $empresa->emp_identificacion?></p>
<p><strong>Razon Social:</strong> <?php echo $empresa->emp_nombre?></p>
<p><strong>Pais:</strong> <?php echo $empresa->emp_pais?></p>
<p><strong>Ciudad:</strong> <?php echo $empresa->emp_ciudad?></p>
<p><strong>Direccion Matriz:</strong> <?php echo $empresa->emp_direccion?></p>
<p><strong>Telefono:</strong> <?php echo $empresa->emp_telefono?></p>
<p><strong>Email:</strong> <?php echo $empresa->emp_email?></p>
<p><strong>Codigo Tributario:</strong> <?php echo $empresa->emp_contribuyente_especial?></p>
<p><strong>Obligado a Llevar Contabilidad:</strong> <?php echo $empresa->emp_obligado_llevar_contabilidad?></p>
<p><strong>Leyenda SRI:</strong> <?php echo $empresa->emp_leyenda_sri?></p>
<p><strong>Estado:</strong> <?php echo $empresa->est_descripcion?></p>
<p><strong>Logo:</strong></p>
<p><img id="fotografia" class="fotografia" src="<?php echo base_url().'imagenes/'.$empresa->emp_logo ?>" width="100px" height="100px" class="form-control"></p>

