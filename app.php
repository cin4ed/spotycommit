<?php

require('util.php');

$env_vars = parse_ini_file('.env');

// $ch = curl_init();

// session_start();
// $query_parameters = [
// 	'grant_type' => 'refresh_token',
// 	'refresh_token' => $_SESSION['refresh_token']
// ];

// $query_parameters_formated = http_build_query($query_parameters);

// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, [
// 	'Content-type: application/x-www-form-urlencoded',
// 	'Authorization: Basic ' . base64encode($env_vars['CLIENT_ID'] . ':' . $env_vars['CLIENT_SECRET'])
// ]);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $query_parameters_formated);

// $ch_res = curl_exec($ch);
// $ch_res_arr = json_decode($ch, true);

// $_SESSION['access_token'] = $ch_res_arr['access_token'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SpotyCommit</title>
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
	<div class="mx-md-2 mx-3 d-flex flex-column flex-items-end flex-xl-items-center overflow-hidden pt-1 height-full text-center">
		<svg width="823" height="128">
			<g transform="translate(15, 20)" id="grid-container"></g>
		</svg>
	</div>
  	<script type="text/javascript">
	    // $('.tile').mouseenter(function() {
	    // 	let date = parseInt($(this).data('date'));
	    // 	console.log(date);
		// });

  		// so this code generates a list of timestamps of every day of the year 2022
		let calendar_timestamps = [];
		let year = 2022;
		for (let month = 0; month < 12; month++) {
			let monthData = [];
			let daysInMonth = new Date(year, month + 1, 0).getDate();
			for (let day = 1; day <= daysInMonth; day++) {
				let date = new Date(year, month + 1, day);
				let timestamp = date.getTime();
				calendar_timestamps.push(timestamp);	
			}
		}

		// now we gonna generate the svg grid with data-timestamp attribute for each one
		let gridContainer = document.getElementById('grid-container');
		let x2 = 16; // honestly idk what this fcking value does, well it modifies the x parameter in the rect element but idk
		let week = 0;
		for (let x = 0; x < 832; x += 16) {
			let day = 0;

			let g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
			g.setAttribute('transform', `translate(${x}, 0)`);
			g.setAttribute('id', `g-${week}`);
			//let g = `<g transform="translate(${x}, 0)" id="g-">`; // insert this first

			for (let y = 0; y <= 90; y += 15) {
				let index = (week * 7) + day; // this is for accesing the timestamp list limit 364
				// but maybe the hardcoded values in the loop shouldn't be harcoded cuz not every year
				// has 364 days right?, maybe compute the loop using the Date api or something.

				let timestamp = calendar_timestamps[index];

				let rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
				rect.setAttribute('data-timestamp', timestamp);
				rect.setAttribute('x', x2);
				rect.setAttribute('y', y);
				rect.setAttribute('width', 11);
				rect.setAttribute('height', 11);
				rect.setAttribute('rx', 2);
				rect.setAttribute('ry', 2);
				rect.setAttribute('style', 'fill: black;');
				// this is the pixel like svg
				// let rect = `<rect data-timestamp="${calendar_timestamps[index]}" x="${x2}" y="${y}" width="11" height="11" rx="2" ry="2" style="fill: black;"></rect>`; // then insert this

				// now what we want to do when someone hover with the cursor
				rect.addEventListener('mouseenter', () => {
					console.log(timestamp);
				})

				g.appendChild(rect);

				day++;
			}

			// insert g
			gridContainer.appendChild(g);

			week++;
			x2--;
		}	
  	</script>
</body>
</html>
