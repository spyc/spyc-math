<?php
  class Image extends HttpServlet {
    public function doGet($req, $resp){
      println("<!DOCTYPE html>\n<html lang=\"en\">");
      print("<head>");
      include("inc/html/head.php");
      println("</head>");
      print("<body>");
      print("<div data-role=\"page\" id=\"home\">");
      print("<div data-role=\"header\">");
      h1("Photoes");
      include("inc/html/navbar.php");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      
      print("</div>");
      include("inc/html/footer.php");
      print("</div>");
      println("</body>\n</html>");
    }
  }
?>