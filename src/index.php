<?php declare(strict_types=1) ?>
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
			<button 
				id="toggleBtn" 
				class="bg-green-500 p-2 border-2 border-green-700 rounded text-white">
				Add new contact
			</button>
		</div>
		<table class="m-4 rounded">
			<?php
			$logfile_name = "./initdata.json";
			$log_file = fopen($logfile_name, "r") or die("Can't open file");
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
					<td>
						<?php echo date("Y-m-d", $log_item["time"]); ?>
					</td>
					<td class=\"text-red-500\">
						<?php echo date("H:i", $log_item["time"]); ?>
					</td>
					<td>
						<code class='font-bold'>
							<?php echo $log_item["callsign"] ?>
						</code>
					</td>
					<td>
						<?php echo $log_item["frequency"] ?>
					</td>
					<td>
						<?php echo $log_item["mode"] ?>
					</td>
					<td>
						<?php echo country_flag_emoji("be") ?>
					</td>
					<td>
						<?php echo $log_item["country"] ?>
					</td>
					<td>
						<?php echo $log_item["operator"] ?>
					</td>
					<td>
						<?php echo $log_item["comment"] ?>
					</td>
				</tr>
				<?php } ?>
			</table>
			<div>
			</div>
		</main>
    <script>
			let button = document.getElementById("toggleBtn");
			button.addEventListener("click", function() {
				console.log("Button clicked")
			})
    </script>
</body>
</html>
