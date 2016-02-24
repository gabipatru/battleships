<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="<?php echo HtTP_CSS?>/style.css">
</head>

<body>
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
			<td><?php echo $this->displayGridChar($item, self::CHART_HITS);?></td>
		<?php endforeach;?>
	</tr>
	<?php endforeach;?>
</table>
</div>

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
			<td><?php echo $this->displayGridChar($item, self::CHART_MISS);?></td>
		<?php endforeach;?>
	</tr>
	<?php endforeach;?>
</table>
</div>
</div>

<br style="clear: both;" />

<?php if (!Battleships::allShipsAreSunk()): ?>
<div class="formDiv">
	<form method="POST" action="">
		<span>Enter coordinates (row, col), e.g. A5: </span>
		<input type="text" name="fire" id="fire" />
		<input type="submit" name="submit" value="FIRE" />
		<input type="submit" name="new_game" value="New Game" />
	</form>
</div>
<?php else:?>
	<h3>You won the game!</h3>
	<form method="POST" action="">
		<input type="submit" name="new_game" value="New Game" />
	</form>
<?php endif;?>

<div>
<?php foreach (Battleships::getShips() as $shipName => $ship):?>
	<p><?php echo $shipName; ?>: <?php echo ($ship->getShipIsSunk() ? 'sunk' : 'floating')?></p>
<?php endforeach;?>
<p>Shots fired: <?php echo Battleships::fetchTheGrid()->getShotsFired();?></p>
</div>

</body>
</html>