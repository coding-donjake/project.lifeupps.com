<style>

	.component-flashcard-set-preview {
		margin-top: 73px;
	}

	.component-flashcard-set-preview .component-actions {
		display: flex;
	}

	.component-flashcard-set-preview .component-actions .icons-v1 {
		margin-right: 10px;
	}

	.component-flashcard-set-preview .set-questions-counter {
		text-align: center;
	}

	.component-flashcard-set-preview .set-preview-container {
		margin-top: 20px;
	}

	.component-flashcard-set-preview .item-question {
		margin: 20px 0;
		padding: 50px 20px;
		position: relative;
		border-radius: 40px;
		box-shadow: 5px 5px 20px #aaa;
		text-align: center;
	}

	.component-flashcard-set-preview .item-selections .enumeration input {
		width: 100%;
	}

	.component-flashcard-set-preview .item-selections .choices {
		display: grid;
		gap: 10px;
		grid-template-columns: repeat(2, 1fr);
	}

	.component-flashcard-set-preview .item-selections .choices .selections:nth-child(odd):last-child {
		grid-column: 1/3;
	}

	.component-flashcard-set-preview .item-selections .choices img {
		width: 100%;
		max-height: 300px;
		padding: 10px;
		object-fit: contain;
	}

	.component-flashcard-set-preview .item-actions {
		margin-top: 30px;
		display: flex;
	}

	.component-flashcard-set-preview .item-actions button {
		margin-left: auto;
	}

	@media (max-width: 600px) {

		.component-flashcard-set-preview .item-selections .choices .selections {
			grid-column: 1/3;
		}

	}

</style>

<div class="centered-components component-flashcard-set-preview">
	<div class="component-content-container">
		<?php if (!isset($_SESSION['id'])) { ?>
		<div class="component-actions">
			<div class="icons-v1" onclick="location.assign('flashcard-public')" title="Back to Public Flashcards"><span class="fas">&#xf015;</span></div>
		</div>
		<?php } else { ?>
		<div class="component-actions">
			<div class="icons-v1" onclick="loadFormCreateItem()" title="Add Question"><span class="fas">&#x2b;</span></div>
			<div class="icons-v1" onclick="loadFormUpdateItem(0, 0)" title="Update Set Questions"><span class="fas">&#xf013;</span></div>
		</div>
		<?php } ?>
		<div class="set-preview-container"></div>
	</div>
</div>

<script>

	let id_set = <?php echo $_GET['id']; ?>;
	let setItems = [];
	let setItemsEdit = [];
	let setItemSelections = [];
	let setItemsAnswered = [];

	let score = 0;
	let correct = 0;
	let wrong = 0;

	let addSelectionField = () => {
		if (document.querySelector('.modal').querySelector('#type').value == 'selections') {
			let container = document.createElement("div");
			container.className = 'input-container';
			container.innerHTML = '<input class="inpts-classic-v1 selections" type="text" placeholder="selection">';
			document.querySelector('.modal').querySelector('.selections-container').appendChild(container);
		}
	}

	let createItem = button => {
		let confirmed = true;
		let btnText = button.innerHTML;
		let url = '/scripts/flashcard_create_item.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		formdata.append('id_set', id_set);
		formdata.append('question', document.querySelector('.modal').querySelector('#question').value);
		formdata.append('type', document.querySelector('.modal').querySelector('#type').value);
		if (document.querySelector('.modal').querySelector('#type').value == 'selections-img') {
			for (let i = 0; i < document.querySelector('.modal').querySelector('.selections').files.length; i++) {
				formdata.append('selections[]', document.querySelector('.modal').querySelector('.selections').files[i]);
			}
		} else {
			for (let i = 0; i < document.querySelector('.modal').getElementsByClassName('selections').length; i++) {
				const element = document.querySelector('.modal').getElementsByClassName('selections')[i];
				formdata.append('selections[]', element.value);
			}
		}
		if (confirmed && document.querySelector('.modal').querySelector('#question').value == '') {
			window.alert(`Question is blank.`);
			confirmed = false;
		}
		if (confirmed && document.querySelector('.modal').querySelector('#type').value == '') {
			window.alert(`Please select a proper type value.`);
			confirmed = false;
		}
		if (document.querySelector('.modal').querySelector('#type').value == 'enumeration') {
			if (confirmed && document.querySelector('.modal').querySelector('.selections').value == '') {
				window.alert(`Answer is blank.`);
				confirmed = false;
			}
		} else if (document.querySelector('.modal').querySelector('#type').value == 'selections') {
			if (confirmed && document.querySelector('.modal').getElementsByClassName('selections')[0].value == '') {
				window.alert(`Selection #1 is blank.`);
				confirmed = false;
			}
			if (confirmed && document.querySelector('.modal').getElementsByClassName('selections')[1].value == '') {
				window.alert(`Selection #2 is blank.`);
				confirmed = false;
			}
		} else if (document.querySelector('.modal').querySelector('#type').value == 'selections-img') {
			if (confirmed && document.querySelector('.modal').querySelector('.selections').files.length <= 1) {
				window.alert(`Please select images to upload as a selection of answers. Images must be greater than 1.`);
				confirmed = false;
			}
		}
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					if (result.status == 'created') {
						window.alert(`Question has been added.`);
						closeModal();
						loadSetItems(id_set);
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

	let applyAnswer = (selection, answer) => {
		if (selection != null) {
			for (let i = 0; i < document.getElementsByClassName('selections').length; i++) {
				const element = document.getElementsByClassName('selections')[i];
				element.style.outline = 'none';
			}
			selection.style.outline = '#000 solid 3px';
		}
		setItemsAnswered[setItemsAnswered.length - 1].answer = answer;
	}

	let changeAnswerFields = type => {
		if (type == 'enumeration') {
			document.querySelector('.modal').querySelector('.selections-container').innerHTML = `<div class="input-container">
				<input class="inpts-classic-v1 selections" type="text" placeholder="answer">
			</div>`;
		} else if (type == 'selections') {
			document.querySelector('.modal').querySelector('.selections-container').innerHTML = `<div class="input-container">
				<input class="inpts-classic-v1 selections" type="text" placeholder="selection (correct answer)">
			</div>
			<div class="input-container">
				<input class="inpts-classic-v1 selections" type="text" placeholder="selection">
			</div>`;
		} else if (type == 'selections-img') {
			document.querySelector('.modal').querySelector('.selections-container').innerHTML = `<div class="input-container">
				<label>Upload images (first image in queue must be the correct answer)</label>
				<input class="inpts-classic-v1 selections" type="file" accept="image/png, image/gif, image/jpeg" multiple="multiple">
			</div>`;
		}
	}

	let loadFormCreateItem = () => {
		document.querySelector('.modal').innerHTML = `<div class="modal-content-container" style="max-width: 500px;">
			<div class="modal-actions">
				<div class="icons-v1 ctc1" title="Close" onclick="closeModal()"><span class="fas">&#xf00d;</span></div>
			</div>
			<div class="modal-header">Add Question</div>
			<div class="inputs-container-wrap">
				<div class="input-container">
					<label>Question</label>
					<textarea class="inpts-classic-v1" id="question"></textarea>
				</div>
				<div class="input-container">
					<label>Answer type</label>
					<select class="inpts-classic-v1" id="type" onchange="changeAnswerFields(this.value)">
						<option value="enumeration">Enumeration</option>
						<option value="selections">Multiple choice</option>
						<option value="selections-img">Multiple choice (images)</option>
					</select>
				</div>
			</div>
			<hr>
			<div class="inputs-container-wrap selections-container">
				<div class="input-container">
					<input class="inpts-classic-v1 selections" type="text" placeholder="answer">
				</div>
			</div>
			<div class="modal-btns">
				<button class="btns-classic-v1 ctc3" onclick="addSelectionField()">Add Selection</button>
				<button class="btns-classic-v1 ctc3" onclick="createItem(this)">Save</button>
			</div>
		</div>`;
		document.querySelector('.modal').style.display = 'block';
	}

	let loadFormUpdateItem = (index, offset) => {
		index += offset;
		if (index < 0) {
			index = setItemsEdit.length - 1;
		} else if (index >= setItemsEdit.length) {
			index = 0;
		}
		let str = `<div class="modal-content-container" style="max-width: 500px;">
			<div class="modal-actions">
				<div class="icons-v1 ctc3" title="Close" onclick="loadFormUpdateItem(${index}, -1)"><span class="fas">&#xf0d9;</span></div>
				<div class="icons-v1 ctc3" title="Close" onclick="loadFormUpdateItem(${index}, 1)"><span class="fas">&#xf0da;</span></div>
				<div class="icons-v1 ctc1" title="Close" onclick="closeModal()"><span class="fas">&#xf00d;</span></div>
			</div>
			<div class="modal-header">Update Question ${index + 1}/${setItemsEdit.length}</div>
			<input id="id" type="hidden" value="${setItemsEdit[index].id}">
			<div class="inputs-container-wrap">
				<div class="input-container">
					<label>Question</label>
					<textarea class="inpts-classic-v1" id="question">${setItemsEdit[index].question}</textarea>
				</div>
				<div class="input-container">
					<label>Answer type</label>
					<select class="inpts-classic-v1" id="type" onchange="changeAnswerFields(this.value)">
						<option value="enumeration">Enumeration</option>
						<option value="selections">Multiple choice</option>
						<option value="selections-img">Multiple choice (images)</option>
					</select>
				</div>
			</div>
			<hr>
		<div class="inputs-container-wrap selections-container">`;
		if (setItemsEdit[index].type == 'enumeration') {
			setItemSelections.forEach(element => {
				if (element.id == setItemsEdit[index].id_selection) {
					let answer = element.content;
					str += `<div class="input-container">
						<input class="inpts-classic-v1 selections" type="text" placeholder="answer" value="${answer}">
					</div>`;
				}
			});
		} else if (setItemsEdit[index].type == 'selections') {
			let temp = true;
			setItemSelections.forEach(element => {
				if (element.id_item == setItemsEdit[index].id) {
					let answer = element.content;
					if (temp) {
						str += `<div class="input-container">
							<input class="inpts-classic-v1 selections" type="text" placeholder="selection (correct answer)" value="${answer}">
						</div>`;
						temp = false;
					} else {
						str += `<div class="input-container">
							<input class="inpts-classic-v1 selections" type="text" placeholder="selection" value="${answer}">
						</div>`;
					}
				}
			});
		} else if (setItemsEdit[index].type == 'selections-img') {
			setItemSelections.forEach(element => {
				if (element.id == setItemsEdit[index].id_selection) {
					let answer = element.content;
					str += `<div class="input-container">
						<label>Upload images (first image in queue must be the correct answer)</label>
						<input class="inpts-classic-v1 selections" type="file" accept="image/png, image/gif, image/jpeg" multiple="multiple">
					</div>`;
				}
			});
		}
		str += `</div>
			<div class="modal-btns">
				<button class="btns-classic-v1 ctc3" onclick="addSelectionField()">Add Selection</button>
				<button class="btns-classic-v1 ctc3" onclick="updateItem(this)">Save</button>
			</div>
		</div>`;
		document.querySelector('.modal').innerHTML = str;
		document.querySelector('.modal').style.display = 'block';
		document.querySelector('.modal').querySelector('#type').value = setItemsEdit[index].type;
	}

	let loadSetItems = (id_set) => {
		let confirmed = true;
		let url = '/scripts/flashcard_load_set_items.php';
		let formdata = new FormData();
		formdata.append('id_set', id_set);
		let xml = new XMLHttpRequest();
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					if (result.data.length <= 0) {
						document.querySelector('.set-preview-container').innerHTML = `<div class="sprays-v1">
							<span class="fas">&#xf119;</span>
							<p class="spray-text">Mr. Squil found nothing.</p>
						</div>`;
					} else {
						setItemsAnswered = [];
						score = 0;
						correct = 0;
						wrong = 0;
						setItems = result.data.slice();
						setItemsEdit = result.data.slice();
						setItemSelections = result.data2.slice();
						playSet();
					}
				} catch (err) {
					console.log(xml.responseText);
				}
			}
		}
		if (confirmed) {
			document.querySelector('.set-preview-container').innerHTML = `<div class="sprays-v1">
				<span class="fas spin">&#xf013;</span>
				<p class="spray-text">Please wait... Mr. Squil is working on it.</p>
			</div>`;
			xml.open('POST', url, true);
			xml.send(formdata);
		}
	}

	let playSet = () => {
		if (setItems.length <= 0) {
			document.querySelector('.modal').innerHTML = `<div class="modal-content-container" style="max-width: 700px;">
				<div class="modal-actions">
					<div class="icons-v1 ctc1" title="Close" onclick="closeModal(); location.assign('flashcard');"><span class="fas">&#xf00d;</span></div>
				</div>
				<div class="sprays-v1">
					<span class="fas">&#xf024;</span>
					<h2 class="spray-text">Score: ${score}/${setItemsAnswered.length}</h2>
				</div>
				<div class="modal-btns">
					<button class="btns-classic-v1 ctc3" onclick="closeModal(); replaySet(id_set);">Play Again</button>
					<?php if (!isset($_SESSION['id'])) { ?>
					<button class="btns-classic-v1 ctc3" onclick="location.assign('flashcard-public')">back to Sets</button>
					<?php } else { ?>
					<button class="btns-classic-v1 ctc3" onclick="location.assign('flashcard')">back to Sets</button>
					<?php } ?>
				</div>
			</div>`;
			document.querySelector('.modal').style.display = 'block';
		} else {
			setItems = NonObjectFucntions.shuffleArray(setItems);
			setItemsAnswered[setItemsAnswered.length] = setItems[0];
			setItems.splice(0, 1);
			let temp = [];
			setItemSelections.forEach(element => {
				if (element.id_item == setItemsAnswered[setItemsAnswered.length - 1].id) {
					temp[temp.length] = element;
				}
			});
			temp = NonObjectFucntions.shuffleArray(temp);
			let str = '';
			if (setItemsAnswered[setItemsAnswered.length - 1].question.length > 100) {
				str = `<div class="set-questions-counter">Question ${setItemsAnswered.length}/${setItems.length + setItemsAnswered.length}</div>
					<div class="item-question ctc3">${setItemsAnswered[setItemsAnswered.length - 1].question}</div>
				<div class="item-selections">`;
			} else if (setItemsAnswered[setItemsAnswered.length - 1].question.length > 50) {
				str = `<div class="set-questions-counter">Question ${setItemsAnswered.length}/${setItems.length + setItemsAnswered.length}</div>
					<div class="item-question ctc3 font-medium">${setItemsAnswered[setItemsAnswered.length - 1].question}</div>
				<div class="item-selections">`;
			} else {
				str = `<div class="set-questions-counter">Question ${setItemsAnswered.length}/${setItems.length + setItemsAnswered.length}</div>
					<div class="item-question ctc3 font-large">${setItemsAnswered[setItemsAnswered.length - 1].question}</div>
				<div class="item-selections">`;
			}
			if (setItemsAnswered[setItemsAnswered.length - 1].type == 'enumeration') {
				str += `<div class="enumeration">`;
			} else if (setItemsAnswered[setItemsAnswered.length - 1].type == 'selections' || setItemsAnswered[setItemsAnswered.length - 1].type == 'selections-img') {
				str += `<div class="choices">`;
			}
			for (let i = 0; i < temp.length; i++) {
				const element = temp[i];
				if (setItemsAnswered[setItemsAnswered.length - 1].type == 'enumeration') {
					str += `<input class="inpts-classic-v1" id="answer" type="text" placeholder="Answer here..." onkeyup="applyAnswer(null, this.value)">`;
				} else if (setItemsAnswered[setItemsAnswered.length - 1].type == 'selections') {
					str += `<button class="btns-classic-v1 selections" onclick="applyAnswer(this, '${element.content}')">${element.content}</button>`;
				} else if (setItemsAnswered[setItemsAnswered.length - 1].type == 'selections-img') {
					str += `<img class="btns-classic-v1 selections" src="/files/${element.content}" onclick="applyAnswer(this, '${element.content}')">`;
				}
			}
			str += `</div>
			</div>
				<div class="item-actions">
					<button class="btns-classic-v1 ctc3" onclick="submitAnswer(this)">Submit</button>
			</div>`;
			document.querySelector('.set-preview-container').innerHTML = str;
		}
	}

	let replaySet = () => {
		setItems = setItemsEdit;
		setItemsAnswered = [];
		score = 0;
		correct = 0;
		wrong = 0;
		playSet();
	}

	let submitAnswer = button => {
		let temp = null;
		for (let i = 0; i < setItemSelections.length; i++) {
			const element = setItemSelections[i];
			if (element.id == setItemsAnswered[setItemsAnswered.length - 1].id_selection) {
				temp = element;
				break;
			}
		}
		if (setItemsAnswered[setItemsAnswered.length - 1].answer != temp.content) {
			let correctAnswer = '';
			let icon = '';
			correct = 0;
			wrong++;
			switch (wrong) {
				case 1:
					icon = '&#xf11a;';
					break;
				case 2:
					icon = '&#xf119;';
					break;
				case 3:
					icon = '&#xf5b4;';
					break;
				default:
					icon = '&#xf5b3;';
					break;
			}
			if (setItemsAnswered[setItemsAnswered.length - 1].type == 'selections-img') {
				correctAnswer = `<img style="width: 100%; max-height: 200px; object-fit: contain;" src="/files/${temp.content}">`;
			} else {
				correctAnswer = `<b>${temp.content}</b>`;
			}
			document.querySelector('.modal').innerHTML = `<div class="modal-content-container" style="max-width: 500px;">
				<div class="modal-actions">
					<div class="icons-v1 ctc1" title="Close" onclick="closeModal(); playSet();"><span class="fas">&#xf00d;</span></div>
				</div>
				<div class="sprays-v1">
					<span class="fas">${icon}</span>
					<h2 class="spray-text">WRONG!</h2>
					<p>Correct answer is: ${correctAnswer}</p>
				</div>
			</div>`;
			document.querySelector('.modal').style.display = 'block';
		} else {
			let icon = '';
			wrong = 0;
			score++;
			correct++;
			switch (correct) {
				case 1:
					icon = '&#xf118;';
					break;
				case 2:
					icon = '&#xf5b8;';
					break;
				case 3:
					icon = '&#xf59a;';
					break;
				default:
					icon = '&#xf587;';
					break;
			}
			document.querySelector('.modal').innerHTML = `<div class="modal-content-container" style="max-width: 500px;">
				<div class="modal-actions">
					<div class="icons-v1 ctc1" title="Close" onclick="closeModal(); playSet();"><span class="fas">&#xf00d;</span></div>
				</div>
				<div class="sprays-v1">
					<span class="fas">${icon}</span>
					<h2 class="spray-text">CORRECT!</h2>
				</div>
			</div>`;
			document.querySelector('.modal').style.display = 'block';
		}
	}

	let updateItem = button => {
		let confirmed = true;
		let btnText = button.innerHTML;
		let url = '/scripts/flashcard_update_item.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		formdata.append('id', document.querySelector('.modal').querySelector('#id').value);
		formdata.append('question', document.querySelector('.modal').querySelector('#question').value);
		formdata.append('type', document.querySelector('.modal').querySelector('#type').value);
		if (document.querySelector('.modal').querySelector('#type').value == 'selections-img') {
			for (let i = 0; i < document.querySelector('.modal').querySelector('.selections').files.length; i++) {
				formdata.append('selections[]', document.querySelector('.modal').querySelector('.selections').files[i]);
			}
		} else {
			for (let i = 0; i < document.querySelector('.modal').getElementsByClassName('selections').length; i++) {
				const element = document.querySelector('.modal').getElementsByClassName('selections')[i];
				formdata.append('selections[]', element.value);
			}
		}
		if (confirmed && document.querySelector('.modal').querySelector('#question').value == '') {
			window.alert(`Question is blank.`);
			confirmed = false;
		}
		if (confirmed && document.querySelector('.modal').querySelector('#type').value == '') {
			window.alert(`Please select a proper type value.`);
			confirmed = false;
		}
		if (document.querySelector('.modal').querySelector('#type').value == 'enumeration') {
			if (confirmed && document.querySelector('.modal').querySelector('.selections').value == '') {
				window.alert(`Answer is blank.`);
				confirmed = false;
			}
		} else if (document.querySelector('.modal').querySelector('#type').value == 'selections') {
			if (confirmed && document.querySelector('.modal').getElementsByClassName('selections')[0].value == '') {
				window.alert(`Selection #1 is blank.`);
				confirmed = false;
			}
			if (confirmed && document.querySelector('.modal').getElementsByClassName('selections')[1].value == '') {
				window.alert(`Selection #2 is blank.`);
				confirmed = false;
			}
		} else if (document.querySelector('.modal').querySelector('#type').value == 'selections-img') {
			if (confirmed && document.querySelector('.modal').querySelector('.selections').files.length <= 1) {
				window.alert(`Please select images to upload as a selection of answers. Images must be greater than 1.`);
				confirmed = false;
			}
		}
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					if (result.status == 'ok') {
						window.alert(`Question has been updated.`);
						closeModal();
						loadSetItems(id_set);
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

	loadSetItems(id_set);

</script>