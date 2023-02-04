<style>

	.component-form-login {
		max-width: 400px;
		margin: 80px auto;
		padding: 20px;
	}

	.component-form-login img {
		width: 100%;
		max-width: 270px;
		margin: auto;
		margin-bottom: 30px;
		display: block;
	}

	.component-form-login .input-container {
		margin: 10px 0;
	}

	.component-form-login .input-container input {
		width: 100%;
	}

	.component-form-login .form-btns {
		margin-top: 30px;
		overflow: auto;
	}

	.component-form-login .form-btns button:first-child {
		width: calc(50% - 5px);
		float: right;
	}

	.component-form-login .form-btns button:last-child {
		width: calc(50% - 5px);
		float: left;
	}

	@media (max-width: 500px) {
		.component-form-login .form-btns button:first-child {
			width: 100%;
			margin-bottom: 10px;
			float: unset;
		}

		.component-form-login .form-btns button:last-child {
			width: 100%;
			float: unset;
		}
	}

</style>

<div class="component-form-login">
	<img src="/files/system/logo.png" alt="<?php echo APP_NAME; ?> - logo">
	<div class="input-container">
		<input class="inpts-classic-v1" id="username" type="text" placeholder="Username" autocapitalize="off">
	</div>
	<div class="input-container">
		<input class="inpts-classic-v1" id="password" type="password" placeholder="Password">
	</div>
	<div class="form-btns">
		<button class="btns-classic-v1 ctc3" id="btn-login" onclick="login(this)">Log In</button>
		<button class="btns-classic-v1" onclick="location.assign('register')">Register</button>
	</div>
</div>

<script>
	
	document.querySelector('#username').addEventListener('keypress', function(event) {
		if (event.key === 'Enter') {
			event.preventDefault();
			document.querySelector('#btn-login').click();
		}
	});

	document.querySelector('#password').addEventListener('keypress', function(event) {
		if (event.key === 'Enter') {
			event.preventDefault();
			document.querySelector('#btn-login').click();
		}
	});

	let login = button => {
		let confirmed = true;
		let btnText = button.innerHTML;
		let url = '/scripts/login.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		formdata.append('username', document.querySelector('#username').value);
		formdata.append('password', document.querySelector('#password').value);
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					if (result.status == 'ok') {
						location.assign('home');
					}
					if (result.status == 'not found user') {
						window.alert(`User account was not fount. Please tr again.`);
						button.disabled = false;
						button.innerHTML = btnText;
					}
					if (result.status == 'invalid password') {
						window.alert(`Invalid password. Please try again.`);
						button.disabled = false;
						button.innerHTML = btnText;
					}
				} catch {
					console.log(xml.responseText);
				}
			}
		}
		if (confirmed) {
			button.disabled = true;
			button.innerHTML = '<span class="fas spin">&#xf110;</span>';
			xml.open('POST', url, true);
			xml.send(formdata);
		}
	}

</script>