<?php if (Battleships::fetchTheConfig()->getCheating() === true): ?>
<div class="mainDiv">
<table class="mainTable">
	<tr>
		<td>&nbsp;</td>
		<?php foreach ($theGrid[1] as $key => $i):?>
		<td><?php echo chr($letterOffset + $key); ?></td>
		<?php endforeach;?>
	</tr>
	<?php foreach ($theGrid as $key => $row): ?>
	<tr>
		<?php $number = $key + 1;?>
		<td><?php echo $number;?></td>
		<?php foreach ($row as $key => $item):?>
			<td><?php echo $item;?></td>
		<?php endforeach;?>
	</tr>
	<?php endforeach;?>
</table>
</div>
<?php endif;?>