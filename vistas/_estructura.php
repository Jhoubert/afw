<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Municipalida De Independencia</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="css/navbar-fixed-top.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet" />

       <script type="text/javascript">
      function alerta(p1="",p2=null,p3=null){
        if (typeof swal === "function") { 
          if(p2!=null && p3!=null)
          swal(p1,p2,p3);
          else if(p2!=null)
          swal(p1,p2);
          else
          swal(p1);
        }else{
          alert(p1);
        }
      }
    </script>
  </head>

  <body> 

    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="navbar-brand">Sistema de Gestion - MDI</span>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <?php $this->makeMenu('<li class="{active}"><a href="{link}"><span class="{icon}"></span> {nombre}</a></li>'); ?>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="jumbotron">
        <?=$this->Vista();?>
      </div>
    </div> <!-- /container -->
    <script src="js/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>

    <script src="js/tablas.js"></script>
    <script src="js/sweetalert.min.js"></script>

<script src="js/select2.min.js"></script>


<script type="text/javascript">
  if (typeof onload === "function") { 
    onload();
  }
</script>

  </body>
</html>
