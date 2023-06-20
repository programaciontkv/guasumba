<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>ayuda/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
        Manual de Ayuda e Instrucciones
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
						<a href="<?php echo base_url();?>ayuda/nuevo" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Nueva Seccion</a>
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
							<th>Sección</th>
							<th>PDF</th>
							<th>Video</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						$gr="";
						if(!empty($ayudas)){
							foreach ($ayudas as $ayuda) {
								$n++;

								if($gr!=$ayuda->ayu_codigo){
						?>
							<tr class="total">
								<td class="acordeon" onclick="mostrar('<?php echo $ayuda->ayu_codigo?>',this)">-</td>
								<td style="mso-number-format:'@'"><?php echo $ayuda->sbm_nombre?></td>
								<td></td>
								<td></td>
								<td></td>	
							</tr>
						<?php			
								}	    
						?>
							<tr class="<?php echo 'tr_'.$ayuda->ayu_codigo?>">
								<td></td>
								<td><?php echo $ayuda->opc_nombre?></td>
								<td>
									<a href="<?php echo base_url();?>ayuda/show_frame/<?php echo $ayuda->ayu_archivo?>/<?php echo $permisos->opc_id?>" class="btn btn-warning"> <span class="fa fa-file-pdf-o"></span></a>
								</td>
								<td><?php 
										
										if($ayuda->ayu_video!=''){
										?>
										<a href="#" class="btn btn-info" onclick="ver_video('<?php echo $ayuda->ayu_video?>')" title="Video"> <span class="fa fa-film"></span></a>
										<?php
										}
										?>
								</td>
								
								<td align="center">
									<div class="btn-group">
										<?php 
										
										if($permisos->rop_actualizar){
										?>
											<a href="<?php echo base_url();?>ayuda/editar/<?php echo $ayuda->ayu_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
										<a href="<?php echo base_url();?>ayuda/eliminar/<?php echo $ayuda->ayu_id?>/<?php echo $ayuda->ayu_descripcion?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a>
										<?php 
										}
										?>
									</div>
								</td>
							</tr>
						<?php
							$gr=$ayuda->ayu_codigo;
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
</div>