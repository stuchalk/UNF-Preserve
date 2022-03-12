<div class="left">
	<p style="text-align: center;">
	<?php echo $this->Html->image('unf_logo.png', ['alt'=>'University of North Florida','width'=>'100']); ?>
	</p>
</div>
<div class="right">
	<div class="righttextbox">
		<table width="400" align="center">
			<tr>
			<td align="center">
				<?php
				echo $this->Session->flash('auth').'<br />';
				echo $this->Form->create('User',['url'=>['controller'=>'users','action'=>'login']]);
				echo $this->Form->input('username');
				echo $this->Form->input('password');
				echo $this->Form->end('Login');
				?>
			</td>
			</tr>
		</table>
	</div>
</div>