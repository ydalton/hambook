<?php

require_once "./decls.php";

$fields = ["#", "Date", "Time", "Callsign", "Frequency",
	"Mode", "Country", "Operator name", "Comment", "Actions"];

$pdo = "";
try {
    $pdo = new PDO("sqlite:$logfile_name");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage() . $e->errorInfo);
}

try {
    $pdo->exec(
	'CREATE TABLE IF NOT EXISTS contacts (
	    id INTEGER PRIMARY KEY,
	    callsign VARCHAR(255),
	    frequency FLOAT,
	    mode VARCHAR(255),
	    time INTEGER,
	    country VARCHAR(255),
	    operator VARCHAR(255),
	    comment TEXT
	)'
    );
} catch(Exception $e) {
    die('Creating db failed: ' . $e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM contacts");
$log = $stmt->fetchAll(PDO::FETCH_ASSOC);

$colors = ["black", "yellow", "red", "green", "green"];

?>
<!doctype html>
<html>
<head>
	<link rel="stylesheet" href="/css/style.css">
	<p class="text-yellow-500 text-green-500 hidden">
</head>

<body>
	<header class="bg-blue-400 flex p-6 justify-between items-center">
		<div>
			<img src="/assets/makerfabs.png">
			<span class="font-bold text-3xl text-white">
				Hambook - HL2 to HL2 Logbook
			</span>
		</div>
		<p class="font-bold text-3xl bg-white p-2 rounded shadow-lg">
		<?php for($i = 0; $i < strlen($callsign); $i++ ): ?>
		<span class="text-<?php echo $colors[$i] ?>-500">
			<?php echo $callsign[$i]; ?>
		</span>
		<?php endfor; ?>
		</p>
		<img src="/assets/hermes.png">
	</header>
	<main>
		<div class="border border-gray-200 p-4">
			<button id="toggleBtn" class="btn">
				Add new contact
			</button>
		</div>
		<table class="m-4 rounded">
			<?php if(!empty($log)): ?>
			<tr>
			<?php foreach($fields as $field): ?>
				<th><?php echo $field ?></th>
			<?php endforeach; ?>
			</tr>
			<?php foreach (array_values($log) as $log_number => $log_item): ?>
			<tr>
				<td><?php echo $log_number + 1 ?></td>
				<td><?php echo date("Y-m-d", intval($log_item["time"])) ?></td>
				<td class="text-red-500"><?php echo date("H:i", $log_item["time"])?></td>
				<td class='font-bold'><?php echo $log_item["callsign"] ?></td>
				<td><?php echo $log_item["frequency"] ?></td>
				<td><?php echo $log_item["mode"] ?></td>
				<td><?php echo $log_item["country"] ?> </td>
				<td><?php echo $log_item["operator"] ?></td>
				<td><?php echo $log_item["comment"] ?></td>
				<td>
				<a onclick="return confirm('Click \'OK\' to confirm deletion.')"
					href="/delete.php?id=<?php echo $log_item["id"]?>">
					🗑️
				</span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
				<p class="text-center p-2 font-bold">No log entries found.</p>
			<?php endif; ?>
		</table>
	</main>
	<div id="modal" class="background-shadow flex items-center justify-center hidden">
		<div class="p-8 bg-white border border-gray-300 rounded-lg shadow-2xl">
			<h1 class="text-3xl">Add new contact</h1>
			<form action="/create.php" method="post">
				<div class="form-item">
					<label>Callsign</label>
					<input type="text" name="callsign" required></input>
				</div>
				<div class="form-item">
					<label>Name of operator</label>
					<input type="text" name="name" required></input>
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
