<?php

declare(strict_types=1);

$logfile_name = "./log.json";
$log_file = fopen($logfile_name, "r") or die("Can't open log file");
$log = json_decode(fread($log_file, filesize($logfile_name)), true);
fclose($log_file);

?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="/css/style.css">
</head>

<body>
	<header class="bg-blue-400 flex p-6 justify-between items-center">
		<div>
			<img src="/assets/makerfabs.png">
			<span class="font-bold text-3xl text-white">
				Hambook - HL2 Logbook
			</span>
		</div>
		<img src="/assets/hermes.png">
	</header>
	<main>
		<div class="border-2 border-gray-200 p-4">
			<button id="toggleBtn" class="btn">
				Add new contact
			</button>
		</div>
		<table class="m-4 rounded">
			<?php
			if(!empty($log)) {
				echo '<tr>';
				$fields = ["Date", "Time", "Callsign", "Frequency",
					"Mode", "Country", "Operator name", "Comment"];
				forEach($fields as $field) {
					echo "<th>$field</th>\r\n";
				}
				echo '</tr>';
				foreach ($log as $log_item) {
			?>
			<tr>
				<td><?php echo date("Y-m-d", $log_item["time"]) ?></td>
				<td class="text-red-500"><?php echo date("H:i", $log_item["time"]); ?></td>
				<td class='font-bold'><?php echo $log_item["callsign"] ?></td>
				<td><?php echo $log_item["frequency"] ?></td>
				<td><?php echo $log_item["mode"] ?></td>
				<td><?php echo $log_item["country"] ?> </td>
				<td><?php echo $log_item["operator"] ?></td>
				<td><?php echo $log_item["comment"] ?></td>
			</tr>
			<?php
				}
			} else {
			?>
				<p class="font-bold">No log entries found.</p>
			<?php } ?>
		</table>
	</main>
	<div id="modal" class="background-shadow flex items-center justify-center hidden">
		<div class="p-8 bg-white border-2 border-gray-300 rounded-lg shadow-2xl">
			<h1 class="text-3xl">Add new contact</h1>
			<form action="/new.php" method="post">
				<div class="form-item">
					<label>Name of operator</label>
					<input type="text" name="name" required></input>
				</div>
				<div class="form-item">
					<label>Callsign</label>
					<input type="text" name="callsign" required></input>
				</div>
				<div class="form-item">
					<label>Date and time</label>
					<div class="flex flex-row space-x-2">
						<input type="date" name="date" required></input>
						<input type="time" name="time" required></input>
					</div>
				</div>
				<div class="form-item">
					<label>Frequency</label>
					<input type="number" step="0.001" name="frequency" required></input>
				</div>
				<div class="form-item">
					<label>Country</label>
					<input type="text" name="country" required></input>
				</div>
				<div class="form-item">
					<label>Comments</label>
					<textarea name="comment" cols="30"></textarea>
				</div>
				<br/>
				<div class="flex justify-between">
					<button class="btn" type="submit">Confirm new contact</button>
					<button id="cancel" class="btn-normal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
	<script>
		let button = document.getElementById("toggleBtn");
		let cancel = document.getElementById("cancel");
		let modal = document.getElementById("modal");

		function hideModal() {
			modal.classList.toggle("hidden");
		}

		button.addEventListener("click", hideModal);
		cancel.addEventListener("click", hideModal);
	</script>
</body>
</html>
