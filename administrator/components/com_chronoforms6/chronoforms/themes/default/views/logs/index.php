<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$fields = [];
	foreach($records as $k => $record){
		$records[$k]['Datalog']['data'] = json_decode($record['Datalog']['data'], true);
		$fields = array_unique(array_merge($fields, array_keys($records[$k]['Datalog']['data'])));
	}
?>
<form action="<?php echo r2('index.php?ext=chronoforms&cont=logs&form_id='.$this->data('form_id')); ?>" method="post" name="admin_form" id="admin_form" class="ui form">
	
	<h2 class="ui header">
		<?php echo $connection['Connection']['title']; ?>
		<div class="sub header">Logs manager.</div>
	</h2>
	<div class="ui">
		
		<button type="button" class="compact ui button red icon labeled toolbar-button" data-selections="1" data-message="<?php el('Please make a selection.'); ?>" data-url="<?php echo r2('index.php?ext=chronoforms&cont=logs&act=delete&form_id='.$connection['Connection']['id']); ?>">
			<i class="trash icon"></i><?php el('Delete'); ?>
		</button>
		<button type="button" class="compact ui button blue icon labeled toolbar-button" data-hint="<?php el('If no records are selected then all will be exported'); ?>" data-url="<?php echo r2('index.php?ext=chronoforms&cont=logs&act=csv&form_id='.$connection['Connection']['id']); ?>">
			<i class="download icon"></i><?php el('CSV Export'); ?>
		</button>
		
	</div>
	
	<div class="ui clearing divider"></div>
	
	<div class="ui message top attached" style="padding:7px 12px;">
		<div class="ui action input" style="float:left;">
			<input type="text" name="search" placeholder="<?php el('Serach records...'); ?>">
			<button class="ui icon button">
			<i class="search icon"></i>
			</button>
		</div>
		<div style="float:right;">
			<?php echo $this->Paginator->info('Datalog'); ?>
			<?php echo $this->Paginator->navigation('Datalog'); ?>
			<?php echo $this->Paginator->limiter('Datalog'); ?>
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="ui container fluid" style="overflow:auto;">
	<table class="ui selectable table attached small">
		<thead>
			<tr>
				<th class="collapsing">
					<div class="ui select_all checkbox">
						<input type="checkbox">
						<label></label>
					</div>
				</th>
				<th class="collapsing"><?php el('View'); ?></th>
				<th class="collapsing"><?php echo $this->Sorter->link(rl('ID'), 'record_id'); ?></th>
				<th class="collapsing"><?php echo $this->Sorter->link(rl('Created'), 'record_created'); ?></th>
				<!--<th class="single line"><?php el('Modified'); ?></th>-->
				<th class="collapsing"><?php el('User'); ?></th>
				<th class="collapsing"><?php el('IP Address'); ?></th>
				<?php foreach($fields as $field): ?>
				<th class="collapsing"><?php echo $field; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($records as $i => $record): ?>
			<tr>
				<td class="collapsing">
					<div class="ui checkbox selector">
						<input type="checkbox" class="hidden" name="gcb[]" value="<?php echo $record['Datalog']['aid']; ?>">
						<label></label>
					</div>
				</td>
				<td class="collapsing">
					<a href="<?php echo r2('index.php?ext=chronoforms&cont=logs&act=view&aid='.$record['Datalog']['aid']); ?>"><?php el('View'); ?></a>
				</td>
				<td class="collapsing">
					<?php echo $record['Datalog']['aid']; ?>
					<br><small><?php echo $record['Datalog']['uid']; ?></small>
				</td>
				<td class="collapsing">
					<?php echo $record['Datalog']['created']; ?>
					<?php if(!empty($record['Datalog']['modified'])): ?>
					<br><small><?php el('Modified'); ?>:&nbsp;<?php echo $record['Datalog']['modified']; ?></small>
					<?php endif; ?>
				</td>
				<!--<td class="collapsing"><?php echo $record['Datalog']['modified']; ?></td>-->
				<td class="collapsing">
					<?php if(!empty($record['Datalog']['user_id'])): ?>
						<?php echo $record['Datalog']['user_id']; ?>
						<br><small><?php echo $record['User']['username']; ?></small>
					<?php endif; ?>
				</td>
				<td class="collapsing"><?php echo $record['Datalog']['ipaddress']; ?></td>
				<?php foreach($fields as $field): ?>
				<td class="collapsing">
					<?php
						if(!empty($record['Datalog']['data'][$field])){
							if(is_string($record['Datalog']['data'][$field])){
								echo nl2br($record['Datalog']['data'][$field]);
							}else if(is_array($record['Datalog']['data'][$field])){
								echo json_encode($record['Datalog']['data'][$field], JSON_UNESCAPED_UNICODE);
							}
						}
					?>
				</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
	<div class="ui message bottom attached" style="padding:7px 12px;">
		<div style="float:right">
			<?php echo $this->Paginator->info('Datalog'); ?>
			<?php echo $this->Paginator->navigation('Datalog'); ?>
			<?php echo $this->Paginator->limiter('Datalog'); ?>
		</div>
		<div style="clear:both;"></div>
	</div>
	
</form>
