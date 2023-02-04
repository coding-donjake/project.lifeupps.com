<style>

	.component-flashcard-list-sets {
		margin-top: 73px;
	}

	.component-flashcard-list-sets .component-actions {
		display: flex;
	}

	.component-flashcard-list-sets .component-actions .icons-v1 {
		margin-right: 10px;
	}

	.component-flashcard-list-sets .sets-container {
		margin-top: 20px;
	}

	.component-flashcard-list-sets .set {
		margin: 20px 0;
		padding: 30px 20px;
		padding-right: 80px;
		position: relative;
		border-radius: 40px;
		box-shadow: 5px 5px 20px #aaa;
	}

	.component-flashcard-list-sets .set:first-child {
		margin-top: 0;
	}

	.component-flashcard-list-sets .set:last-child {
		margin-bottom: 0;
	}

	.component-flashcard-list-sets .set-name {
		margin-bottom: 10px;
		font-size: 1.3rem;
		font-weight: 600;
	}

	.component-flashcard-list-sets .set-actions {
		top: 20px;
		right: 20px;
		position: absolute;
	}

	.component-flashcard-list-sets .set-actions .icons-v1 {
		margin-bottom: 10px;
	}

	.component-flashcard-list-sets .set-actions .icons-v1:last-child {
		margin: none;
	}

</style>

<div class="centered-components component-flashcard-list-sets">
	<div class="component-content-container">
		<div class="component-actions">
			<div class="icons-v1" onclick="loadFormAddSet()" title="Creat Flashcard"><span class="fas">&#x2b;</span></div>
			<div class="icons-v1" onclick="location.assign('flashcard-public')" title="Public Flashcards"><span class="fas">&#xf0ac;</span></div>
			<div class="icons-v1" onclick="loadSets()" title="Refresh"><span class="fas">&#xf2f9;</span></div>
		</div>
		<div class="sets-container"></div>
	</div>
</div>

<script>

	let sets = [];

	let addSet = button => {
		let confirmed = true;
		let btnText = button.innerHTML;
		let url = '/scripts/flashcard_create_set.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		formdata.append('name', document.querySelector('#name').value);
		formdata.append('description', document.querySelector('#description').value);
		formdata.append('privacy', document.querySelector('#privacy').value);
		if (confirmed && document.querySelector('#name').value == '') {
			window.alert(`Set name is blank.`);
			confirmed = false;
		}
		if (confirmed && document.querySelector('#privacy').value == '') {
			window.alert(`Please select a proper privacy.`);
			confirmed = false;
		}
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					if (result.status == 'created') {
						window.alert(`Set has been created.`);
						closeModal();
						loadSets();
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

	let loadFormAddSet = () => {
		document.querySelector('.modal').innerHTML = `<div class="modal-content-container" style="max-width: 500px;">
			<div class="modal-actions">
				<div class="icons-v1 ctc1" title="Close" onclick="closeModal()"><span class="fas">&#xf00d;</span></div>
			</div>
			<div class="modal-header">Create Flashcard</div>
			<div class="inputs-container-wrap">
				<div class="input-container">
					<label>Flashcard Name</label>
					<input class="inpts-classic-v1" id="name" type="text">
				</div>
				<div class="input-container">
					<label>Flashcard Description</label>
					<textarea class="inpts-classic-v1" id="description"></textarea>
				</div>
				<div class="input-container">
					<label>Privacy</label>
					<select class="inpts-classic-v1" id="privacy">
						<option value="private">Private</option>
						<option value="public">Public</option>
					</select>
				</div>
			</div>
			<div class="modal-btns">
				<button class="btns-classic-v1 ctc3" onclick="addSet(this)">Save</button>
			</div>
		</div>`;
		document.querySelector('.modal').style.display = 'block';
	}

	let loadFormUpdateSet = index => {
		document.querySelector('.modal').innerHTML = `<div class="modal-content-container" style="max-width: 500px;">
			<div class="modal-actions">
				<div class="icons-v1 ctc1" title="Close" onclick="closeModal()"><span class="fas">&#xf00d;</span></div>
			</div>
			<div class="modal-header">Update Set</div>
			<input id="id" type="hidden" value="${sets[index].id}">
			<div class="inputs-container-wrap">
				<div class="input-container">
					<label>Set Name</label>
					<input class="inpts-classic-v1" id="name" type="text" value="${sets[index].name}">
				</div>
				<div class="input-container">
					<label>Set Description</label>
					<textarea class="inpts-classic-v1" id="description">${sets[index].description}</textarea>
				</div>
				<div class="input-container">
					<label>Privacy</label>
					<select class="inpts-classic-v1" id="privacy">
						<option value="private">Private</option>
						<option value="public">Public</option>
					</select>
				</div>
			</div>
			<div class="modal-btns">
				<button class="btns-classic-v1 ctc3" onclick="updateSet(this)">Save</button>
			</div>
		</div>`;
		document.querySelector('.modal').querySelector('#privacy').value = sets[index].privacy;
		document.querySelector('.modal').style.display = 'block';
	}

	let loadSets = () => {
		let confirmed = true;
		let url = '/scripts/flashcard_load_sets.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					sets = result.data.slice();
					if (result.data.length <= 0) {
						document.querySelector('.sets-container').innerHTML = `<div class="sprays-v1">
							<span class="fas">&#xf119;</span>
							<p class="spray-text">Mr. Squil found nothing.</p>
							<p class="spray-text"><a onclick="loadFormAddSet()">Create flashcard</a> or <a onclick="location.assign('flashcard-public')">view public flashcards</a></p>
						</div>`;
					} else {
						str = '';
						for (let i = 0; i < result.data.length; i++) {
							const element = result.data[i];
							if (element.description == '') {
								element.description = `No description available.`;
							}
							str += `<div class="set ctc3">
								<div class="set-name">${element.name}</div>
								<p>${element.username} - ${element.datetime_added.substr(0, 11)}<br>${element.description}</p>
								<div class="set-actions">
									<div class="icons-v1" onclick="location.assign('flashcard-preview?id=${element.id}')"><span class="fas">&#xf2f6;</span></div>
									<div class="icons-v1" onclick="loadFormUpdateSet(${i})"><span class="fas">&#xf013;</span></div>
								</div>
							</div>`;
							document.querySelector('.sets-container').innerHTML = str;
						}
					}
				} catch {
					console.log(xml.responseText);
				}
			}
		}
		if (confirmed) {
			document.querySelector('.sets-container').innerHTML = `<div class="sprays-v1">
				<span class="fas spin">&#xf013;</span>
				<p class="spray-text">Please wait... Mr. Squil is working on it.</p>
			</div>`;
			xml.open('POST', url, true);
			xml.send(formdata);
		}
	}

	let updateSet = button => {
		let confirmed = true;
		let btnText = button.innerHTML;
		let url = '/scripts/flashcard_update_set.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		formdata.append('id', document.querySelector('#id').value);
		formdata.append('name', document.querySelector('#name').value);
		formdata.append('description', document.querySelector('#description').value);
		formdata.append('privacy', document.querySelector('#privacy').value);
		if (confirmed && document.querySelector('#name').value == '') {
			window.alert(`Set name is blank.`);
			confirmed = false;
		}
		if (confirmed && document.querySelector('#privacy').value == '') {
			window.alert(`Please select a proper privacy.`);
			confirmed = false;
		}
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					if (result.status == 'ok') {
						window.alert(`Set has been updated.`);
						closeModal();
						loadSets();
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

	loadSets();

</script>