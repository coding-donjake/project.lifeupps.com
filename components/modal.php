<style>

	.modal {
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		position: fixed;
		z-index: 11;
		background-color: rgba(0, 0, 0, 0.8);
		display: none;
		overflow: auto;
	}

	.modal .modal-content-container {
		width: calc(100% - 40px);
		margin: 50px auto;
		padding: 20px;
		position: relative;
		background-color: #fff;
		border-radius: 40px;
	}

	.modal .modal-actions {
		top: 20px;
		right: 20px;
		position: absolute;
		overflow: auto;
	}

	.modal .modal-actions .icons-v1 {
		margin-left: 10px;
		float: left;
	}

	.modal .modal-header {
		margin-top: 40px;
		padding: 30px 0;
		padding-bottom: 10px;
		font-size: 1.5rem;
		font-weight: 600;
		text-align: center;
	}

	.modal .inputs-container-wrap {
		display: grid;
		gap: 10px;
		grid-template-columns: repeat(1, 1fr);
	}

	.modal .input-container input,
	.modal .input-container select,
	.modal .input-container textarea {
		width: 100%;
	}

	.modal .modal-btns {
		margin-top: 30px;
		display: flex;
	}

	.modal .modal-btns button {
		margin-left: 10px;
	}

	.modal .modal-btns button:first-child {
		margin-left: auto;
	}

	.modal hr {
		margin: 20px 0;
	}

	.modal .sprays-v1 {
		margin-top: 40px;
		padding: 20px 0;
	}

	@media (max-width: 500px) {

		.modal .modal-btns {
			display: block;
		}

		.modal .modal-btns button {
			width: 100%;
			margin: auto;
			margin-top: 10px;
		}

	}

</style>

<div class="modal">
	<div class="modal-content-container" style="max-width: 500px;">
		<div class="modal-actions">
			<div class="icons-v1"><span class="fas">&#xf2f5;</span></div>
			<div class="icons-v1 ctc1"><span class="fas">&#xf00d;</span></div>
		</div>
		<div class="modal-header">Create Set</div>
		<div class="inputs-container-wrap">
			<div class="input-container">
				<label>Set Name</label>
				<input class="inpts-classic-v1" id="name" type="text">
			</div>
			<div class="input-container">
				<label>Set Description</label>
				<textarea class="inpts-classic-v1"></textarea>
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
			<button class="btns-classic-v1 ctc3"><span class="fas btn-icon">&#xf0c7;</span>Save</button>
		</div>
	</div>
</div>

<script>

	let closeModal = () => {
		document.querySelector('.modal').style.display = 'none';
	}

</script>