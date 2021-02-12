<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$record['Datalog']['data'] = json_decode($record['Datalog']['data'], true);
	$fields = array_keys($record['Datalog']['data']);
	
	$walker = function($array) use (&$walker){
		$class = 'divided';
		if(\G2\L\Arr::is_assoc($array)){
			$class = 'divided';
		}
		
		echo '<div class="ui '.$class.' list">';
		
		foreach($array as $key => $value){
			echo '<div class="item">';
			if(is_array($value)){
				echo '<div class="content">';
				if(!is_numeric($key)){
					echo '<div class="header" style="padding:0;">';
					echo $key;
					echo '</div>';
				}
				echo '<div class="description">';
				if(\G2\L\Arr::is_assoc($value)){
					$walker($value);
				}else{
					echo json_encode($value, JSON_UNESCAPED_UNICODE);
				}
				echo '</div>';
				echo '</div>';
			}else{
				echo '<div class="content">';
				if(!is_numeric($key)){
					echo '<div class="header" style="padding:0;">';
					echo $key;
					echo '</div>';
				}
				echo '<div class="description">';
				echo nl2br($value);
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
		
		echo '</div>';
	};
?>
<form action="<?php echo r2('index.php?ext=chronoforms&cont=logs&form_id='.$this->data('form_id')); ?>" method="post" name="admin_form" id="admin_form" class="ui form">
	
	<h2 class="ui header"><?php el('Logs manager'); ?></h2>
	<div class="ui">
		<a class="compact ui button red icon labeled toolbar-button" href="<?php echo r2('index.php?ext=chronoforms&cont=logs&form_id='.$record['Datalog']['form_id']); ?>">
			<i class="left arrow icon"></i><?php el('Back'); ?>
		</a>
	</div>
	
	<div class="ui clearing divider"></div>
	
	<table class="ui very basic collapsing celled table">
		<thead>
			<tr>
				<th><?php el('Field name'); ?></th>
				<th><?php el('Field value'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><strong><?php el('ID'); ?></strong></td>
				<td><?php echo $record['Datalog']['aid']; ?></td>
			</tr>
			<tr>
				<td><strong><?php el('Unique ID'); ?></strong></td>
				<td><?php echo $record['Datalog']['uid']; ?></td>
			</tr>
			<tr>
				<td><strong><?php el('Created Date'); ?></strong></td>
				<td><?php echo $record['Datalog']['created']; ?></td>
			</tr>
			<tr>
				<td><strong><?php el('Modified Date'); ?></strong></td>
				<td><?php echo $record['Datalog']['modified']; ?></td>
			</tr>
			<tr>
				<td><strong><?php el('IP Address'); ?></strong></td>
				<td><?php echo $record['Datalog']['ipaddress']; ?></td>
			</tr>
			<tr>
				<td><strong><?php el('User'); ?></strong></td>
				<td><?php echo $record['Datalog']['user_id']; ?></td>
			</tr>
			<?php foreach($fields as $field): ?>
				<tr>
					<td><strong><?php echo $field; ?></strong></td>
					<td>
					<?php
						if(is_array($record['Datalog']['data'][$field])){
							$walker($record['Datalog']['data'][$field]);
							//json_encode($record['Datalog']['data'][$field], JSON_UNESCAPED_UNICODE);
						}else{
							echo nl2br($record['Datalog']['data'][$field]);
						}
					?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</form>
