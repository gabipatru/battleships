<?php if (Battleships::allShipsAreSunk()):?>
	<?php $topScores = Battleships::fetchHallOfHeroes()->fetchTopScores(); ?>
	<?php $topHead   = Battleships::fetchHallOfHeroes()->getHeads(); ?>
<div class="mainDiv">
<table class="mainTable">
	<tr>
		<?php foreach ($topHead as $head): ?>
			<td><?php echo $head;?></td>
		<?php endforeach;?>
	</tr>
	<?php foreach ($topScores as $playerData):?>
	<tr>
		<td><?php echo $playerData[0];?></td>
		<td><?php echo $playerData[1];?></td>
		<td><?php echo $playerData[2];?></td>
		<td><?php echo $playerData[3];?></td>
	</tr>
	<?php endforeach;?>
</table>
</div>
<?php endif;?>