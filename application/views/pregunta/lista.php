<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>pregunta/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Preguntas Frecuentes
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				<div class="col-md-2">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>pregunta/nuevo" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Nueva Seccion</a>
					<?php 
					}
					?>
				</div>		
				<div class="col-md-4">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							<td><label>Buscar:</label></td>
							<td><input type="text" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td>
							</td>
							<td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td>
						</tr>
					</table>
					</form>
				</div>	
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover" width="100%">
						<thead id="tbl_thead">
							<th></th>
							<th>Seccion</th>
							<th>PDF</th>
							<th>Video</th>
							<th>Acciones</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						$gr="";
						if(!empty($preguntas)){
							foreach ($preguntas as $pregunta) {
								$n++;

								if($gr!=$pregunta->pre_menu){
						?>
							<tr class="total">
								<td class="acordeon" onclick="mostrar('<?php echo $pregunta->pre_menu?>',this)">-</td>
								<td style="mso-number-format:'@'"><?php echo $pregunta->pre_menu?></td>
								<td></td>
								<td></td>
								<td></td>	
							</tr>
						<?php			
								}	    
						?>
							<tr class="<?php echo 'tr_'.$pregunta->pre_menu?>">
								<td></td>
								<td><?php echo $pregunta->pre_seccion?></td>
								<td>
									<a href="<?php echo base_url();?>pregunta/show_frame/<?php echo $pregunta->pre_archivo?>/<?php echo $permisos->opc_id?>" class="btn btn-warning"> <span class="fa fa-file-pdf-o"></span></a>
								</td>
								<td><?php 
										
										if($pregunta->pre_video!=''){
										?>
										<a href="#" class="btn btn-info" onclick="ver_video('<?php echo $pregunta->pre_video?>')" title="Video"> <span class="fa fa-film"></span></a>
										<?php
										}
										?>
								</td>
								
								<td align="center">
									<div class="btn-group">
										<?php 
										
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>pregunta/editar/<?php echo $pregunta->pre_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>pregunta/eliminar/<?php echo $pregunta->pre_id?>/<?php echo $pregunta->pre_seccion?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
										<?php 
										}
										?>
									</div>
								</td>
							</tr>
						<?php
							$gr=$pregunta->pre_menu;
							}
						}
						?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>
<style type="text/css">
	.acordeon{
		font-size: 18px !important;
		text-align: center !important;
	}

	td{
		padding-top: 0px !important;
		padding-bottom: 0px !important;

	}

	th{
		text-align: center !important;

	}

</style>

<script type="text/javascript">
	function ver_video(obj){
		var win = window.open(obj, '_blank');
		win.focus();
	}

	function mostrar(id, obj){
		if($(obj).html()=='+'){
			$(obj).html('-');
			$('.tr_'+id).show();
				
		}else{
			$(obj).html('+');
			$('.tr_'+id).hide();
		}
	}
</script>
