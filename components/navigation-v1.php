<style>
	
	.component-navigation-v1 {
		padding: 0 20px;
		top: 0;
		left: 0;
		position: fixed;
		z-index: 6;
		background-color: #fff;
		box-shadow: 0 5px 20px #aaa;
	}

	.component-navigation-v1 .component-content-container {
		padding: 20px 0;
	}

	.component-navigation-v1 .logo {
		width: 100px;
		cursor: pointer;
		display: block;
	}

	.component-navigation-v1 .icons-container {
		top: 50%;
		right: 0;
		position: absolute;
		transform: translateY(-50%);
		display: flex;
	}

	.component-navigation-v1 .icons-v1 {
		margin-left: 10px;
	}

</style>

<div class="centered-components component-navigation-v1">
	<div class="component-content-container">
		<img class="logo" src="/files/system/logo.png" alt="<?php echo APP_NAME; ?> - Logo" onclick="location.assign('home')">
		<?php if (!isset($_SESSION['id'])) { ?>
		<div class="icons-container">
			<div class="icons-v1 ctc3" title="Log In" onclick="location.assign('index')"><span class="fas">&#xf2f6;</span></div>
			<div class="icons-v1 ctc1" title="Register" onclick="location.assign('register')"><span class="fas">&#xf044;</span></div>
		</div>
		<?php } else { ?>
		<div class="icons-container">
			<div class="icons-v1 ctc3" title="Flashcard" onclick="location.assign('flashcard')"><span class="fas">&#xf5dc;</span></div>
			<div class="icons-v1" title="Log Out" onclick="location.assign('index')"><span class="fas">&#xf2f5;</span></div>
		</div>
		<?php } ?>
	</div>
</div>