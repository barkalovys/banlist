<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Banlist</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <script src="../../js/jquery-1.11.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.tablesorter.min.js"></script>
    <script src="../../js/picnet.table.filter.min.js"></script>
    <script src="../../js/jquery-ui-1.11.1.custom/jquery-ui.min.js"></script>
    <script src="../../js/jquery-ui-timepicker-addon.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/styles.css">
    <link rel="stylesheet" type="text/css" href="../../js/jquery-ui-1.11.1.custom/jquery-ui.min.css">
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    
  </head>

  <body>
    <div class="navbar navbar-default">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Banlist</a>
      </div>
      <div class="navbar-collapse collapse navbar-responsive-collapse">
        <ul class="nav navbar-nav">
          <?php if (!$this->isAdmin()): ?>
          <li><a href="/?banlist/index">Вход</a></li>
          <?php endif;?>
        </ul>

      </div>
    </div>
    <div class="container" >
		<?php $this->out($this->tpl,true); ?>
    </div> 

  </body>
</html>
