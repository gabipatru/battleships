<?php $T = Battleships::fetchTheConfig()->getTranslations(); ?>

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

<br style="clear: both;" />

<?php require('html-cheating.php');?>

<?php if (!Battleships::allShipsAreSunk()): ?>
<div class="formDiv">
	<form method="POST" action="">
		<span>
		<?php if (!Battleships::fetchTheConfig()->getPlayerName()):?>
			<?php echo $T->__('Enter your name'); ?>:
		<?php else:?>
			<?php echo $T->__('Enter coordinates (row, col), e.g. A5'); ?>:
		<?php endif?> 
		</span>
		<input type="text" name="fire" id="fire" />
		<input type="submit" name="submit" value="<?php echo $T->__('FIRE'); ?>" />
		<input type="submit" name="new_game" value="<?php echo $T->__('New Game'); ?>" />
	</form>
</div>
<?php else:?>
	<h3><?php echo $T->__('You won the game'); ?>!</h3>
	<form method="POST" action="">
		<input type="submit" name="new_game" value="<?php echo $T->__('New Game'); ?>" />
	</form>
<?php endif;?>

<div>
<?php if (Battleships::fetchTheConfig()->getPlayerName()):?>
	<p><?php echo $T->___('Hello, %s, welcome to Battleships', Battleships::fetchTheConfig()->getPlayerName()); ?>!</p>
<?php endif;?>
<?php foreach (Battleships::getShips() as $shipName => $ship):?>
	<p><?php echo $T->__($shipName); ?>: <?php echo ($ship->getShipIsSunk() ? $T->__('sunk') : $T->__('floating'))?></p>
<?php endforeach;?>
<?php if (Battleships::fetchTheGrid()->getLastShot() !== -1):?>
	<p><?php echo $T->__('Last Shot'); ?> : <?php echo (Battleships::fetchTheGrid()->getLastShot() ? $T->__('hit').'!' : $T->__('miss'));?></p>
<?php endif;?>
<p><?php echo $T->__('Shots fired'); ?> : <?php echo Battleships::fetchTheGrid()->getShotsFired();?></p>
<p><?php echo $T->__('Shots hit'); ?> : <?php echo Battleships::fetchTheGrid()->getShotsHit();?></p>
<p><?php echo $T->__('Shots missed'); ?> : <?php echo Battleships::fetchTheGrid()->getShotsMissed();?></p>
</div>

<?php require('html-hall-of-heroes.php')?>

</body>
</html>