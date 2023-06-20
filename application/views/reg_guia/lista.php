<section class="content-header">
	<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>reg_guia/excel/<?php echo $permisos->opc_id?>/<?php echo $fec1?>/<?php echo $fec2?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
      <h1>
      Registro de Guias de Remisión
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			
			<div class="row">
				
				<div class="col-md-8">
					<form action="<?php echo $buscar;?>" method="post" id="frm_buscar">
						
					<table style="margin-left:-5px">
						<tr>
							<td class="hidden-mobile"><label>Buscar:</label></td>
							<td class="hidden-mobile"><input type="text" placeholder="RUC/NOMBRE/NC" id='txt' name='txt' class="form-control" style="width: 180px" value='<?php echo $txt?>'/></td>
							<td>
							</td>
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control"  value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control"  value='<?php echo $fec2?>' /></td>
							<!-- <td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td> -->
						</tr>
					</table>
					<!-- </form> -->
				</div>				
			</div>
			<br>
			<div class="row">
				<div class="col-sm-2">
					<button style="width:120px" type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
				</div>	
				<div class="col-sm-8">
					
				</div>
				<div class="col-md-2">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="#" onclick="unificar()" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Registrar Factura Compra</a>
					<?php 
					}
					?>
				</div>
				</div>
	</form>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover" style="margin-left: -22px">
						<thead>
						<!-- 	<th>No</th> -->
							<th>Fecha</th>
							<th>Registro No</th>
							<th>Guia Remision No</th>
							<th class="hidden-mobile">Ingreso</th>
							<th class="hidden-mobile">Ruc</th>
							<th>Cliente</th>
							<th class="hidden-mobile">Estado</th>
							<th>Seleccionar</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						$grup = '';
						if(!empty($guias)){
							foreach ($guias as $guia) {
								$n++;

								if($guia->rgu_estado==7){
									$style="background:$guia->est_color";
								}else{
									$style="";				
								}
						?>
							<tr style="<?php echo $style;?>">
							<!-- 	<td><?php echo $n?></td> -->
								<td><?php echo $guia->rgu_fregistro?></td>
								<td><?php echo $guia->rgu_secuencia_unif?></td>
								<td><?php echo $guia->rgu_num_documento?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $guia->rgu_num_ingreso?></td>
								<td class="hidden-mobile" style="mso-number-format:'@'"><?php echo $guia->cli_ced_ruc?></td>
								<td ><?php echo $guia->cli_raz_social?></td>
								<td class="hidden-mobile"><?php echo $guia->est_descripcion?></td>
								<td align="center">
									<?php
									if($guia->rgu_estado==7 || $guia->rgu_estado==15 ){
									?>
										<input type='checkbox' id='<?php echo $guia->rgu_id?>' class='chk' lang='<?php echo $guia->cli_ced_ruc?>'/>
									<?php	
									}
									?>
								</td>
								
							</tr>
						<?php
							}
							$grup=$guia->rgu_secuencia_unif;
						}
						?>
						
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>



<script type="text/javascript">
	
	function envio(id,opc){
		if(opc==0){
			url='<?php echo $buscar?>';
		}else if(opc==1){
			url="<?php echo base_url();?>reg_guia/show_frame/"+id+"/<?php echo $permisos->opc_id?>";
		}
		
		$('#frm_buscar').attr('action',url);
		$('#frm_buscar').submit();
	}


	function unificar() {
                n=0;
                v=0;
                var data=Array();
                        $('.chk').each(function () {
                            if($(this).prop('checked')==true){
                                if(n==0){
                                    ced=this.lang;
                                }
                                if(ced!=this.lang){
                                    v=1;
                                }        
                                
                                data.push(this.id);
                                ced=ced;
                                n++;
                            }
                            
                        })
            if(n!=0){
                         
                if (v==1){
                	swal("", "Las guias solo se pueden unir si son del mismo proveedor.!", "info");
                } else if(v==0){  
                    $.ajax({
                        beforeSend: function () {
                        },
                        type: 'POST',
                        url: "<?php echo base_url();?>reg_guia/unir/<?php echo $permisos->opc_id?>",
                        data: {op: 1, 'data[]': data}, 
                        success: function (dt) {
                            window.location.replace("<?php echo base_url();?>reg_guia/nuevo/<?php echo $permisos->opc_id?>/"+dt);
                        }
                    })        
                }
            }else{
                swal("", 'Seleccione una guia!', "info");
            }
        }

	
</script>