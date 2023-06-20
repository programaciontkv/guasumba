	
<form id="frm_save" role="form" action="<?php echo $action_est?>" method="post" autocomplete="off" enctype="multipart/form-data">
              	
     <input type="radio" name="ped_estado" id="ped_estado3" value="07" >Pendiente<br>
     <input type="radio" name="ped_estado" id="ped_estado1" value="17" >Suspendido<br>
     <input type="radio" name="ped_estado" id="ped_estado2" value="18" >Caducado<br>
     
              	
              	<input type="hidden" name="ped_id" id="ped_id" value="<?php echo $pedido->ped_id?>">
</form>

