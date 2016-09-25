
<?php
$username = array('name' => 'username', 'placeholder' => 'Nombre de usuario');
$password = array('name' => 'password',    'placeholder' => 'Tu password');
$submit = array('name' => 'submit', 'value' => 'Iniciar sesión', 'title' => 'Iniciar sesión');
?>



	

		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron">
		
			<h1>Clickdelivery</h1>  
			Formulario de login<br /><br />
			<?php echo form_open(base_url().'login/new_user')?>
			<table border="0"  style="width: 320px;">
			<tr>
				<td>
					<label for="username">Email:</label>
				</td>
				<td>
					<?php echo form_input($username)?>
					<p>
						<?=form_error('username')?>
					</p>
				</td>
				
				
				
			</tr>
			
			<tr>
				<td>
					<label for="password">Tu password:</label>
				</td>
				<td>
					<?php  echo form_password($password)?>
					<p>
					<?=form_error('password')?>
					</p>
					<?php echo  form_hidden('token',$token)?>
				</td>
				
				
				
			</tr>
			
			<tr>
				<td>
					<?php echo form_submit($submit)?>
				</td>
				<td>
					&nbsp;
				</td>
				
				
				
			</tr>
			
			</table>
			<?php echo form_close()?>

			
			<?php echo "<a href='$login_url'>Login con: <img src=\"". base_url(). "assets/images/facebook_square-128.png\"></a> o <a href=\"" .  base_url() . "nlogin/\" >crear una cuenta</a>"; ?>

			
			
			
			
			
			
			
			
			
			
			<?php
			if($this->session->flashdata('usuario_incorrecto'))
			{
				?>
			<p>
			<?php echo $this->session->flashdata('usuario_incorrecto')?>
			</p>
			<?php
			}
			?>

			
		</div>
	</div>

