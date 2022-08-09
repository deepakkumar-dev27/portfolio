<?php
$conn=mysqli_connect('localhost','id17777862_darsh_user','vdn|Bz%)=2qj1Bjc');
mysqli_select_db($conn,'id17777862_darsh_db');
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<title>Admin-Darshanam</title>
		<!--== META TAGS ==-->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!--== FAV ICON ==-->
		<link rel="shortcut icon" href="images/fav.ico">
		 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<!-- GOOGLE FONTS -->
		<link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,600,700" rel="stylesheet">
		
		<!-- FONT-AWESOME ICON CSS -->
		<link rel="stylesheet" href="css/font-awesome.min.css">
		
		<!--== ALL CSS FILES ==-->
		<link rel="stylesheet" href="../asset/css/style.css">
	</head>
	
	<body>
		<!--= BODY CONTNAINER ==-->
		<div class="container-fluid sb2">
			
			<div class="sb2-2">
				<div class="sb2-2-3">
					<div class="row">
						<div class="col-md-12">
							<?php
							$sql = "SELECT * FROM contact";
							$result = $conn->query($sql);
							$i = 1;
							if ($result->num_rows > 0) { ?>
								<div class="box-inn-sp">
									<div class="inn-title">
										<h4 class="text-center my-4">Contacts List</h4>
									</div>
									<div class="tab-inn">
										<div class="table-responsive table-desi">
											<table class="table table-hover" border="1">
												<thead>
												<tr>
													<th>Id</th>
													<th>Name</th>
													<th>Email</th>
													<th>Subject</th>
													<th>Message</th>
												</tr>
												</thead>
												<tbody>
												<?php while ($row = $result->fetch_array()) { ?>
													<tr>
														<td><?php echo $i;
															$i++; ?>
														</td>
														<td><?php echo $row['name']; ?></td>
														<td><?php echo $row['email']; ?></td>
														<td><?php echo $row['subject']; ?></td>
														<td><?php echo $row['message']; ?></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							<?php } else { ?>
								<h3 class="text-center">Sorry, no order to display.</h3>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!--== BOTTOM FLOAT ICON ==-->
		
		<!--======== SCRIPT FILES =========-->
	
	</body>
	
	</html>