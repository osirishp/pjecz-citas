      <div class="collapse navbar-collapse nav-wil " id="bs-example-navbar-collapse-1" >
          <ul class="nav navbar-nav" style="display:inline">

              <!--li ><a href="registro.php" class="hvr-bounce-to-bottom" >Busqueda de Usuario</a></li-->
              <!--li ><a href="reportes.php" class="hvr-bounce-to-bottom" >Reportes</a></li -->              
               <li ><a style="color: #fff;" href="calendario.php" class="hvr-bounce-to-bottom">Calendario</a></li>
               <li ><a style="color: #fff;" href="actividades.php" class="hvr-bounce-to-bottom">Actividades del d&iacute;a</a></li>               
               <?php 
			         if($_SESSION['administrador']==1){ ?>
               <li><a href="administracion.php" class="hvr-bounce-to-bottom">Administraci&oacute;n</a></li>
               <?php } ?>
              <!--li><a href="index.php" class="hvr-bounce-to-bottom">Salir</a></li-->
          </ul>
      </div><!-- /.navbar-collapse -->
