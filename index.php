


<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Facebook Theme Demo</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="assets/css/bootstrap.css" rel="stylesheet">
	<!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
	<link href="assets/css/facebook.css" rel="stylesheet">
</head>

<body>

	<div class="wrapper">
		<div class="box">
			<div style="display:flex;justify-content:center;" class="row row-offcanvas row-offcanvas-left">

				<!-- sidebar -->
				
				<!-- /sidebar -->

				<!-- main right col -->
				<div class="column col-sm-10 col-xs-11" id="main">

					<!-- top nav -->
					<div class="navbar navbar-blue navbar-static-top">
						<div class="navbar-header">
						</div>
						<nav class="collapse navbar-collapse" role="navigation">
							<ul class="nav navbar-nav">
								<li>
									<a href="#"><i class="glyphicon glyphicon-home"></i> Home</a>
								</li>
								<li>
									<a href="#postModal" role="button" data-toggle="modal">
									<i class="glyphicon glyphicon-plus" ></i> Post</a>
									
								</li>
							</ul>
						</nav>
					</div>
					<!-- /top nav -->

					<div class="padding">
						<div class="full col-sm-9">
							<!-- content -->
							<div class="row">

								<!-- main col left -->

								<!-- main col right -->
								<div class="panel-heading pull-right text-lg">
									<?= $_SESSION['alertMsg'] ?>
								</div>

								<div class="col-sm-7" >								
									<div class="panel panel-default">
										<div class="panel-heading">
											<h1>Your Posts !</h1>											
										</div>
									</div>

									<?php		
									require_once 'fonc/functions.php';
									// Method that get all the medias in the DB and add them in a div					
									showAllImages();																				
									?>
									 
								</div>
							</div>
							<!--/row-->

							<div class="row">
								<div class="col-sm-6">
									<a href="#">Twitter</a> <small class="text-muted">|</small> <a href="#">Facebook</a>
									<small class="text-muted">|</small> <a href="#">Google+</a>
								</div>
							</div>

							<div class="row" id="footer">
								<div class="col-sm-6">

								</div>
								<div class="col-sm-6">
									<p>
										<a href="#" class="pull-right">�Copyright 2013</a>
									</p>
								</div>
							</div>
							<hr>
							<h4 class="text-center">
								<h4 class="text-center">Badr Boucherine © CopyRight</h4>
							</h4>
							<hr>
						</div><!-- /col-9 -->
					</div><!-- /padding -->
				</div>
				<!-- /main -->

			</div>
		</div>
	</div>


	<!--post modal-->
	<div id="postModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
					Update Status
				</div>
				<div class="modal-body">
					<form class="form center-block" enctype="multipart/form-data" method="POST" action="fonc/post.php">
						<div class="form-group">
							<textarea name="postInput" class="form-control input-lg" autofocus="" placeholder="What do you want to share?"></textarea>
						</div>
						<div>
							<div class="modal-footer">								
								<input type="file" name="fileUploaded[]" class="btn btn-primary pull-left" class="pull left" multiple />
								<input type="submit" class="btn btn-primary btn-sm" style="padding:8px;font-weight:bold;" value="Post" aria-hidden="true" />
							</div>								
						</div>
					</form>
				</div>
				
			</div>
		</div>
	</div>

	<script
			  src="http://code.jquery.com/jquery-3.4.1.min.js"
			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			  crossorigin="anonymous"></script>
	<script type="text/javascript" src="assets/js/bootstrap.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('[data-toggle=offcanvas]').click(function () {
				$(this).toggleClass('visible-xs text-center');
				$(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
				$('.row-offcanvas').toggleClass('active');
				$('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
				$('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
				$('#btnShow').toggle();
			});
		});
	</script>
</body>

</html>
