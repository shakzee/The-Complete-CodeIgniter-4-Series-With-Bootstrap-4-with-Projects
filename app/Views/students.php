<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<body>
	<h1>Welcome in Codeigniter 4 series</h1>
	<table>
		<th>Id</th>
		<th>Name</th>
		<th>Subject</th>
		<?php foreach ($allStudents as $std): ?>
			<tr>
				<td>
					<?php echo $std->s_id?>
				</td>
				<td>
					<?php echo $std->s_name?>
				</td>
				<td>
					<?php echo $std->s_subjects?>
				</td>

			</tr>
		<?php endforeach;?>
	</table>
	<?php //var_dump($allStudents); ?>
</body>
</html>
