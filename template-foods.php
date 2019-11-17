<div class="health-tracker-wrapper">

	<div class="buttons-wrapper">

		<button class="heatra-add-button">Add Food Logs</button><br>
		<form class="heatra-details-form heatra-details-form-add" method="post">
			<select class="input-food">
				<option value="1">Papaya</option>
				<option value="2">Banana</option>
			</select><br><br>
			<input type="text" class="input-amount" placeholder="Grams" required><br><br>
			<button type="submit" class="submit-form">Submit</button>
		</form>

		<table class="heatra-food-logs">
			<thead>
				<th>Date</th>
				<th>Food</th>
				<th>Grams</th>
				<th>Actions</th>
			</thead>
			<tbody></tbody>
		</table>

	</div>
	
</div>

<style>
.heatra-details-form {
	margin-top: 21px;
	display: none;
}
.heatra-food-logs {
	margin-top: 20px;
}
.heatra-food-logs tr:nth-child(even) {
	display: none;
}
</style>