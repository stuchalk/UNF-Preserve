<div class="left">
	<p style="text-align: center;">
	<?php echo $this->Html->image('unf_logo.png', array('alt'=>'University of North Florida','width'=>'150'))."<br>"; ?>
	</p>
</div>
<div class="right">
	<?php echo $this->Form->create('User', array('action' => 'register')); ?>
	<table width="600">
		<tr>
			<td colspan="4" align="center">&nbsp;<br />Please register for this site below. Fields with * are required.</td>
		</tr>
		<tr>
			<td width="150" style="text-align: right;">*Username</td>
			<td width="150"><?php echo $this->Form->input('username',array('div'=>false,'label'=>false)); ?></td>
			<td width="150" style="text-align: right;">*Password</td>
			<td width="150"><?php echo $this->Form->input('password',array('type'=>'password','div'=>false,'label'=>false)); ?></td>
		</tr>
		<tr>
			<td style="text-align: right;">*Email</td>
			<td><?php echo $this->Form->input('email',array('div'=>false,'label'=>false)); ?></td>
			<td style="text-align: right;">Phone</td>
			<td><?php echo $this->Form->input('phone',array('div'=>false,'label'=>false)); ?></td>
		</tr>
		<tr>
			<td colspan="4"><?php echo $this->Form->end('Register'); ?></td>
		</tr>
	</table>
</div>