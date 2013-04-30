<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title_for_layout; ?></title>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
	<![endif]-->

	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
	echo $this->Html->script('jquery');
	echo $this->Bootstrap->load('dev');
	?>

</head>
<body>
	<div class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container-fluid">
				<div class="nav-collapse">
					<ul class="nav">
						<li><?php echo $this->Html->image('appicon.png', array('class' => 'brand', 'url' => '/')); ?></li>
						<?php if($this->Session->check('Auth.User')) : ?>
						<li><?php echo $this->Html->link('Dashboard', '/'); ?></li>
						<li><?php echo $this->Html->link('Categories', array('controller' => 'categories', 'action' => 'index'));?></li>
						<li><?php echo $this->Html->link('Feeds', array('controller' => 'feeds', 'action' => 'index'));?></li>
						<li><?php echo $this->Html->link('Articles', array('controller' => 'articles', 'action' => 'index'));?></li>
						<?php if ($this->Session->read('Auth.User.role') === 'admin') : ?>
							<li><?php echo $this->Html->link('Users', array('controller' => 'users', 'action' => 'index'));?></li>
							<li><?php echo $this->Html->link('Loves', array('controller' => 'loves', 'action' => 'index'));?></li>
						<?php endif; ?>
						<?php endif; ?>
						<li>
							<?php echo $this->Session->check('Auth.User')
							? 
								$this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'))
							: 
								$this->Html->link('Login', array('controller' => 'users', 'action' => 'login'));
							?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">

			<div id="main-content" class="span12">

				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>

			</div>

		</div>

		<footer>
			<?php echo $this->element('sql_dump'); ?>
		</footer>

	</div>

</body>
</html>
