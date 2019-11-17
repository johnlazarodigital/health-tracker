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

		<input type="date" id="input-date" required>
		<button type="button" class="get-daily-status">Submit</button>

		<table class="heatra-nutrients-status">
			<thead>
				<th></th>
				<th>Calories</th>
				<th>Carbs</th>
				<th>Protein</th>
				<th>Fats</th>
				<th>Fiber</th>
				<th>Sugar</th>
			</thead>
			<tbody>
				<tr>
					<td>Target</td>
					<td>2000</td>
					<td>200g</td>
					<td>56g</td>
					<td>40g</td>
					<td>25g</td>
					<td>80g</td>
				</tr>
				<tr>
					<td>Current</td>
					<td class="nutri-calories"></td>
					<td class="nutri-carbs"></td>
					<td class="nutri-protein"></td>
					<td class="nutri-fat"></td>
					<td class="nutri-fiber"></td>
					<td class="nutri-sugar"></td>
				</tr>
			</tbody>
		</table>

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
#input-date, .heatra-food-logs, .heatra-nutrients-status {
	margin-top: 20px;
}
.heatra-food-logs tr:nth-child(even) {
	display: none;
}
</style>