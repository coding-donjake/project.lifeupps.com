<style>
	
	.component-form-register {
		max-width: 600px;
		margin: 80px auto;
		padding: 20px;
	}

	.component-form-register img {
		width: 100%;
		max-width: 270px;
		margin: auto;
		margin-bottom: 30px;
		display: block;
	}

	.component-form-register .inputs-container-wrap {
		display: grid;
		gap: 10px;
		grid-template-columns: repeat(2, 1fr);
	}

	.component-form-register .input-container:first-child {
		grid-column: 1/3;
	}

	.component-form-register .input-container input,
	.component-form-register .input-container select {
		width: 100%;
	}

	.component-form-register .form-btns {
		margin-top: 30px;
		overflow: auto;
	}

	.component-form-register .form-btns button:first-child {
		width: calc(50% - 5px);
		float: right;
	}

	.component-form-register .form-btns button:last-child {
		width: calc(50% - 5px);
		float: left;
	}

	@media (max-width: 500px) {
		.component-form-register .input-container {
			grid-column: 1/3;
		}

		.component-form-register .form-btns button:first-child {
			width: 100%;
			margin-bottom: 10px;
			float: unset;
		}

		.component-form-register .form-btns button:last-child {
			width: 100%;
			float: unset;
		}
	}

</style>

<div class="component-form-register">
	<img src="/files/system/logo.png" alt="<?php echo APP_NAME; ?> - logo">
	<div class="inputs-container-wrap">
		<div class="input-container">
			<label>Create Username</label>
			<input class="inpts-classic-v1" id="username" type="text" autocapitalize="off">
		</div>
		<div class="input-container">
			<label>Create Password</label>
			<input class="inpts-classic-v1" id="pass1" type="password">
		</div>
		<div class="input-container">
			<label>Confirm Password</label>
			<input class="inpts-classic-v1" id="pass2" type="password">
		</div>
	</div>
	<div class="form-btns">
		<button class="btns-classic-v1 ctc3" onclick="register(this)">Create Account</button>
		<button class="btns-classic-v1" onclick="location.assign('/')">Log In Instead</button>
	</div>
</div>

<script>

	let register = button => {
		let confirmed = true;
		let btnText = button.innerHTML;
		let url = '/scripts/register.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		formdata.append('username', document.querySelector('#username').value);
		formdata.append('password', document.querySelector('#pass1').value);
		if (confirmed && document.querySelector('#username').value == '') {
			window.alert(`Username is blank.`);
			confirmed = false;
		}
		if (confirmed && document.querySelector('#username').value.length < 6) {
			window.alert(`Username length must be between 6 and 50 characters only.`);
			confirmed = false;
		}
		if (confirmed && document.querySelector('#pass1').value.length < 6) {
			window.alert(`Password must be atleast 6 characters.`);
			confirmed = false;
		}
		if (confirmed && document.querySelector('#pass1').value != document.querySelector('#pass2').value) {
			window.alert(`Password didn't match.`);
			confirmed = false;
		}
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					if (result.status == 'created') {
						location.assign('home');
					}
					if (result.status == 'invalid username') {
						window.alert(`Username can only contain letters and numbers.`);
						button.disabled = false;
						button.innerHTML = btnText;
					}
					if (result.status == 'exists username') {
						window.alert(`Username is already been used by another account.`);
						button.disabled = false;
						button.innerHTML = btnText;
					}
					if (result.status == 'query denied') {
						window.alert(`Unexpected error happened. Please try again.`);
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