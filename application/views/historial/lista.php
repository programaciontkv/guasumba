<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style type="text/css">
	.timeline{
  margin-top:20px;
  position:relative;
  
}

.timeline:before{
  position:absolute;
  content:'';
  width:4px;
  height:calc(100% + 50px);
background: rgb(138,145,150);
background: -moz-linear-gradient(left, rgba(138,145,150,1) 0%, rgba(122,130,136,1) 60%, rgba(98,105,109,1) 100%);
background: -webkit-linear-gradient(left, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(98,105,109,1) 100%);
background: linear-gradient(to right, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(98,105,109,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8a9196', endColorstr='#62696d',GradientType=1 );
  left:14px;
  top:5px;
  border-radius:4px;
}

.timeline-month{
  position:relative;
  padding:4px 15px 4px 35px;
  background-color:#d0def2;
  display:inline-block;
  width:auto;
  border-radius:40px;
  border:1px solid #c6ffff;
  border-right-color:black;
  margin-bottom:30px;
}

.timeline-month span{
  position:absolute;
  top:-1px;
  left:calc(100% - 10px);
  z-index:-1;
  white-space:nowrap;
  display:inline-block;
  background-color:#111;
  padding:4px 10px 4px 20px;
  border-top-right-radius:40px;
  border-bottom-right-radius:40px;
  border:1px solid black;
  box-sizing:border-box;
}

.timeline-month:before{
  position:absolute;
  content:'';
  width:20px;
  height:20px;
background: rgb(138,145,150);
background: -moz-linear-gradient(top, rgba(138,145,150,1) 0%, rgba(122,130,136,1) 60%, rgba(112,120,125,1) 100%);
background: -webkit-linear-gradient(top, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(112,120,125,1) 100%);
background: linear-gradient(to bottom, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(112,120,125,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8a9196', endColorstr='#70787d',GradientType=0 );
  border-radius:100%;
  border:1px solid #c6ffff;
  left:5px;
}

.timeline-section{
  padding-left:35px;
  display:block;
  position:relative;
  margin-bottom:30px;
}

.timeline-date{
  margin-bottom:15px;
  padding:2px 15px;
  background:linear-gradient(#74cae3, #5bc0de 60%, #4ab9db);
  position:relative;
  display:inline-block;
  border-radius:20px;
  border:1px solid #c6ffff;
  color:#fff;
text-shadow:1px 1px 1px rgba(0,0,0,0.3);
}
.timeline-section:before{
  content:'';
  position:absolute;
  width:30px;
  height:1px;
  background-color:#d0def2;
  top:12px;
  left:20px;
}

.timeline-section:after{
  content:'';
  position:absolute;
  width:10px;
  height:10px;
  background:linear-gradient(to bottom, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(112,120,125,1) 100%);
  top:7px;
  left:11px;
  border:1px solid #c6ffff;
  border-radius:100%;
}

.timeline-section .col-sm-4{
  margin-bottom:15px;
}

.timeline-box{
  position:relative;
  
 background-color:#d0def2;
  border-radius:15px;
  border-top-left-radius:0px;
  border-bottom-right-radius:0px;
  border:1px solid #c6ffff;
  transition:all 0.3s ease;
  overflow:hidden;
}

.box-icon{
  position:absolute;
  right:5px;
  top:0px;
}

.box-title{
  padding:5px 15px;
  border-bottom: 1px solid #c6ffff;
}

.box-title i{
  margin-right:5px;
}

.box-content{
  padding:5px 15px;
  background-color:#c6ffff;
}

.box-content strong{
  color:#666;
  font-style:italic;
  margin-right:5px;
}

.box-item{
  margin-bottom:5px;
}

.box-footer{
 padding:5px 15px;
  border-top: 1px solid #c6ffff;
  background-color:#d0def2;
  text-align:right;
  font-style:italic;
}
}
</style>
<section class="content-header">
	<!-- <form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>usuario/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form> -->
      <!-- <h1>
        Usuarios
      </h1> -->
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			<div class="row">
				<div class="col-md-2">
					<a href="<?php echo base_url();?>historial/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear Registro</a>
				</div>	
				<div class="col-md-7">
					<form action="<?php echo $buscar;?>" method="post">
						
					<table width="100%">
						<tr>
							
							
							<td><label>Desde:</label></td>
							<td><input type="date" id='fec1' name='fec1' class="form-control" style="width: 150px" value='<?php echo $fec1?>' /></td>
							<td><label>Hasta:</label></td>
							<td><input type="date" id='fec2' name='fec2' class="form-control" style="width: 150px" value='<?php echo $fec2?>' /></td>
							<td><label>Pestaña </label> </td>
							<td>
								<select class="form-control" name="his_lugar" id="his_lugar">
								<option value="">TODOS</option>
								<?php
								if(!empty($pestanias)){
									foreach ($pestanias as $rst_est) {
								?>
								<option value="<?php echo $rst_est->opc_id?>"><?php echo $rst_est->opc_nombre?></option>
								<?php		
									}
								}
								?>
							</select>
							</td>
							
							<td><button type="submit" class="btn btn-info"><span class="fa fa-search"></span> Buscar</button>
								</td>
						</tr>
					</table>
					</form>
				</div>
			</div>
			<br>
			
<div class="container">
  <div class="timeline">
    
    
    <?php
      if(!empty($historial)){
      	$mes="";
          foreach ($historial as $rst) {
          	if($rst->mes != $mes){
          	
            ?>
    
    <div class="timeline-month">
     <?php echo $rst->mes ?>
      <!-- <span>3 Entries</span> -->
    </div>
    <?php 
  		}?>

    <div class="timeline-section">
      <div class="timeline-date">
        <?php echo $rst->his_fregistro?>
      </div>
      <div class="row">
        <div class="col-sm-10">
          <div class="timeline-box">
            <div class="box-title">
              <i class="fa fa-tasks text-success" aria-hidden="true"></i> <?php echo $rst->his_solicitud?>
            </div>
            <div class="box-content">
              <a href="<?php echo base_url();?>historial/editar/<?php echo $rst->his_id?>/<?php echo $opc_id?>" class="btn btn-xs btn-success pull-right ">Detalles - Editar</a>
              <div class="row">
              	
              </div>
              <div class="box-item"><b>Usuario Solicita</b>: <?php echo $rst->his_usuario_soli?></div>
              <div class="box-item"><b>Pestaña</b>: <?php echo $rst->opc_nombre?></div>
              <div class="box-item"><b>Solicitud</b>: <?php echo $rst->his_obser?> </div>
            </div>
            <div class="box-footer"><?php echo $rst->his_per_modifica?></div>
          </div>
        </div>
      </div>

    </div>

    <?php
    $mes = $rst->mes;
  }
}
    	?>

  </div>
</div>


	</div>


</section>

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Usuario</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>
<script type="text/javascript">
	function cambiar_es(estado,id){
		 var base_url='<?php echo base_url();?>';
		 var op = <?php echo $actual_opc; ?>;
		
		Swal.fire({
		  title: 'Desea cambiar de estado al usuario?',
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {

		    var  uri=base_url+"usuario/cambiar_estado/"+estado+"/"+id+"/"+op;
				      $.ajax({
				              url: uri,
				              type: 'POST',
				              success: function(dt){
				              	if(dt==1){
				              	   window.location.href = window.location.href;
				              	}else{
				              		swal("Error!", "No se pudo modificar .!", "warning");
				              	}
				                
				              } 
				        });

		  } else if (result.isDenied) {
		    // Swal.fire('No ha registrado cambios', '', 'info');
		  }
		})
	   
		 
	}
	
</script>