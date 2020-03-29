<div class="top-list">
	<table class="table">
		<thead>
			<tr>
				<th>Casino</th>
				<th>Rating</th>
			</tr>
		</thead>
		<tbody>
			
			<?php 
			foreach ($table as $value):
				?>
				<tr>
					<td><?=$value['name']?></td>
					<td><?=$value['rating']?></td>
				</tr>
				<?php 
			endforeach;
			?>
			
		</tbody>
	</table>
</div>