<?php
  class Index extends HttpServlet {
    public function doGet($req, $resp){
      println("<!DOCTYPE html>\n<html>");
      print("<head>");
      include("inc/html/head.php");
      println("</head>");
      print("<body>");
?>
<div data-role="page" id="home">
  <div data-role="header">
    <h1>Maths</h1>
    <?php include("inc/html/navbar.php"); ?>
  </div>
  <div data-role="main" class="ui-content">
    <h2>Maths Website</h2>
    <p>Welcome to Maths Website, you can find your own way to learn the amazing Maths!</p>
    <p>Maths is Our Language!</p>
    <img src="/subject/math/img/commitee/classic.jpg" style="width:60%; height: 80%;">
  </div>
  <?php include_once("inc/html/footer.php"); ?>
</div>
<?php
      println("</body>\n</html>");
    }
  }
?>