<?php

declare(strict_types=1);

$logfile_name = "./initdata.json";
$log_file = fopen($logfile_name, "r") or die("Can't open log file");
$log = json_decode(fread($log_file, filesize($logfile_name)), true);
fclose($log_file);

function country_flag_emoji(string $countryIsoAlpha2): string
{
	$unicodePrefix = "\xF0\x9F\x87";
	$unicodeAdditionForLowerCase = 0x45;
	$unicodeAdditionForUpperCase = 0x65;

	if (preg_match('/^[A-Z]{2}$/', $countryIsoAlpha2)) {
		$emoji = $unicodePrefix . chr(ord($countryIsoAlpha2[0])
			+ $unicodeAdditionForUpperCase)
			. $unicodePrefix . chr(ord($countryIsoAlpha2[1])
			+ $unicodeAdditionForUpperCase);
	} elseif (preg_match('/^[a-z]{2}$/', $countryIsoAlpha2)) {
		$emoji = $unicodePrefix . chr(ord($countryIsoAlpha2[0])
			+ $unicodeAdditionForLowerCase)
			. $unicodePrefix . chr(ord($countryIsoAlpha2[1])
			+ $unicodeAdditionForLowerCase);
	} else {
		$emoji = '';
	}

	return strlen($emoji) ? $emoji : '';
}
?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="/css/style.css">
</head>

<body>
	<header class="bg-blue-400 flex p-6 justify-between items-center">
		<div>
			<img src="/makerfabs.png">
			<span class="font-bold text-3xl text-white">
				Hambook - HL2 Logbook
			</span>
		</div>
		<img src="/hermes.png">
	</header>
	<main>
		<div class="border-2 border-gray-200 p-4">
			<button id="toggleBtn" class="btn">
				Add new contact
			</button>
		</div>
		<table class="m-4 rounded">
			<tr>
				<?php
				$fields = ["Date", "Time", "Callsign", "Frequency",
					"Mode", "Flag", "Country", "Operator name", "Comment"];
				forEach($fields as $field) {
					echo "<th>$field</th>\r\n";
				}
				?>
			</tr>
			<?php
			foreach ($log as $log_item) {
			?>
			<tr>
				<td><?php echo date("Y-m-d", $log_item["time"]) ?></td>
				<td class="text-red-500"><?php echo date("H:i", $log_item["time"]); ?></td>
				<td class='font-bold'><?php echo $log_item["callsign"] ?></td>
				<td><?php echo $log_item["frequency"] ?></td>
				<td><?php echo $log_item["mode"] ?></td>
				<td><?php echo country_flag_emoji("be") ?></td>
				<td><?php echo $log_item["country"] ?> </td>
				<td><?php echo $log_item["operator"] ?></td>
				<td><?php echo $log_item["comment"] ?></td>
			</tr>
			<?php } ?>
		</table>
	</main>
	<div id="modal" class="p-8 popup absolute bg-white border-2 border-gray-300 rounded-lg shadow-xl">
		<h1 class="text-3xl">Add new contact</h1>
		<form action="/newcontact.php" method="post">
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
				<input type="text" name="Country" required></input>
			</div>
			<div class="form-item">
				<label>Comments</label>
				<textarea name="comment" cols="30"></textarea>
			</div>
			<br/>
			<button class="btn" type="submit">Confirm new contact</button>
		</form>
	</div>
	<script>
		let button = document.getElementById("toggleBtn");
		let modal = document.getElementById("modal");

		function hideModal() {
			modal.classList.toggle("hidden");
		}

		button.addEventListener("click", hideModal);
	</script>
</body>
</html>
