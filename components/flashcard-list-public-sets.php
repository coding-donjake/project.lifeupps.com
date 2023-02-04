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
			<div class="icons-v1" onclick="loadPublicSets()" title="Refresh"><span class="fas">&#xf2f9;</span></div>
		</div>
		<div class="sets-container"></div>
	</div>
</div>

<script>

	let sets = [];

	let loadPublicSets = () => {
		let confirmed = true;
		let url = '/scripts/flashcard_load_public_sets.php';
		let formdata = new FormData();
		let xml = new XMLHttpRequest();
		xml.onreadystatechange = () => {
			if (xml.readyState == 4 && xml.status == 200) {
				try {
					let result = JSON.parse(xml.responseText);
					sets = result.data.slice();
					if (result.data.length <= 0) {
						<?php if (!isset($_SESSION['id'])) { ?>
						document.querySelector('.sets-container').innerHTML = `<div class="sprays-v1">
							<span class="fas">&#xf119;</span>
							<p class="spray-text">Mr. Squil can't find a public flashcard.</p>
							<p class="spray-text"><a onclick="location.assign('index')">Log in</a> or <a onclick="location.assign('register')">register</a> to create your own flashcards</p>
						</div>`;
						<?php } else { ?>
						document.querySelector('.sets-container').innerHTML = `<div class="sprays-v1">
							<span class="fas">&#xf119;</span>
							<p class="spray-text">Mr. Squil can't find a public flashcard.</p>
							<p class="spray-text"><a onclick="location.assign('flashcard')">Go to your flashcards</a></p>
						</div>`;
						<?php } ?>
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

	loadPublicSets();

</script>