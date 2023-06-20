	
<form id="frm_save" role="form" action="<?php echo $action_est?>" method="post" autocomplete="off" enctype="multipart/form-data">
              	<?php
              	if(!empty($cns_estados)){
              		$n=0;
              		foreach ($cns_estados as $std) {
              			if($std->est_id==$pedido->ped_estado){
                            $chk='checked';
                        }else{
                            $chk='';
                        }	
              		$n++;	
              	?>		
              		<input type="radio" name="ped_estado" id="ped_estado<?php echo $n?>" value="<?php echo $std->est_id?>" <?php echo $chk?>><?php echo $std->est_descripcion?><br>
              	<?php
              		}
              	}	
              	?>
              	<input type="hidden" name="ped_id" id="ped_id" value="<?php echo $pedido->ped_id?>">
</form>

