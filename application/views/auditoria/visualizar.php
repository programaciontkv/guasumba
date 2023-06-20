<?php
$detalles=json_decode($auditoria[0]->adt_campo, true);
?>
<table class="table table-bordered table-list table-hover">
	<thead>
		<tr>
			<th>Item</th>
			<th>Campo</th>
			<th>Valor</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$n=0;
		foreach ($detalles as $det=>$value) {
			$n++;
		?>	
			<tr>
				<td><?php echo $n ?></td>
				<td><?php echo $det;?></td>
				<td><?php echo $value;?></td>
				
			</tr>
		<?php	
					
		}	

		?>
	</tbody>
</table>