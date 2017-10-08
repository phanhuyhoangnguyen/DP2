<!DOCTYPE html>
<html lang="en">
<head>
	<title>Friendly Pharmacy | Sale Reporting and Prediction System</title>
	<meta charset="utf-8"/>
	<meta name="description" content="Sale Reporting and Prediction System"/>
	<meta name="keywords" content="Sale, Report, Predict, System, Pharmacy"/>

    <link href="../resources/styles/style.css" rel="stylesheet" type="text/css"/>
    <link href="../resources/styles/layout.css" rel="stylesheet" type="text/css"/>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- References to external responsive CSS file -->
    <link href="../resources/styles/responsive_desktop.css" rel="stylesheet" media="screen and (max-width: 1919px)"/>
    <link href="../resources/styles/responsive_tabletandmobile.css" rel="stylesheet" media="screen and (max-width: 680px)"/>
</head>

<body>
		<header class="headerwrapper">
			<div class="topwrapper">
				<a href="">
					<img src="../resources/images/logout_n.png" alt="admin Icon"/>
                    <form id="logout" method="post" action="php/logout.php">
                        <input type="submit" id="submit_logout" name="submit_logout" value="Logout"/></form>
				</a>
				
				<a href="login.html">
					<img src="../resources/images/admin.png" alt="logout"/>
					<span id="adminIcon">Login as Admin</span>
					</a>
			</div>
				
			</div>
			<div id="logoandsearch">
				<a href="home.php"><img src="../resources/images/logo.png" alt="WatchStyle Logo" title="Home - WatchStyle"/></a>
				
				<div class="searchwrapper">
					<form>
						<input type="text" name="search" placeholder="Search for Products/Brand"/>
					</form>
				</div>
			</div>
		</header>
		
		<!-- Nav -->
		<nav>
			<ul>
				<li id="active"><a href="home.php"><img src="../resources/images/home.png"/><span>Home</span></a></li>
				<li><a href=""><img src="../resources/images/notification.png"/><span>Notification</span></a></li>
				<li><a href=""><img src="../resources/images/setting.png"/><span>Setting</span></a></li>
			</ul>	
		</nav>
	
		<div id="maincontentwrapper">
			<div id="productdisplay">
				
				<ul>
					<li><a href="display_inventory.html">
						<img src="../resources/images/audemarspiguet.jpg" alt="audemarspiguet"/></a>
						<a href="display_inventory.html">
							<span class="buttonstyle">Inventory</span>
						</a>
					</li>
					<li><a href="display_daily_report.html">
						<img src="../resources/images/breitling.jpg" alt="breitling"/></a>
						<a href="display_daily_report.html">
							<span class="buttonstyle">Sale Report</span>
						</a>
					</li>
					<li><a href="check_stock.html">
						<img src="../resources/images/blvgari.jpg" alt="blvgari"/></a>
						<a href="check_stock.html">
							<span class="buttonstyle">Stock Control</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
</body>
</html>