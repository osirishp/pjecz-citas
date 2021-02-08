	<div class="bannerx" style="background-color: #46576b" >
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4" style="padding:30px 80px;">
					<img src='resources/imgs/img_pjecz.png' width="150">
					<h4 style="padding-top: 15px; color: #f2edce;">Sistema de Citas en Linea</h4>	
				</div>
				<div class="col-md-8 text-right" style="padding: 20px 80px;"><br>
				<br>
					<a href='index.php' style="text-decoration: none; color: #fff; font-size:1.5em; padding-right:0px; ">Cerrar Sesión</a><br><br><br><br>
					<h2 style="color: #fff"><?php 
										if($_SESSION['idRol']==2) {echo "Distrito " . $_SESSION['distrito']. " , " . ($_SESSION['juzgado']) ; }
										if($_SESSION['idRol']==3) {echo  $_SESSION['DISTRITO'] ; }
										if($_SESSION['idRol']==4) {echo  "Presidencia Poder Judicial"; }
									?></h2>
					
				</div>
			</div>
		</div>
	</div>

<!--
	style="background: url(resources/imgs/<?php $banner = "banner".rand(1,3).".jpg" ; echo $banner;?>) no-repeat 0px 0px;
                                background-size:cover;
                                -webkit-background-size:cover;
                                -moz-background-size:cover;
                                -o-background-size:cover;
                                -ms-background-size:cover;
                                min-height:350px;"

                                -->