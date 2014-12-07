<?php
  class Events extends HttpServlet {
    public function doGet($req, $resp) {      
      $events = (array) json_decode(file_get_contents("inc/data/events.json", true));
      println("<!DOCTYPE html>");
      print("<html lang=\"en-GB\">");
      print("<head>");
      include("inc/html/head.php");
      print("<style type=\"text/css\">");
      print("#events {");
      print("width: 80%;");
      print("margin-left: 5%;");
      print("}");
      print("</style>");
      println("</head>");
      print("<body>");
      print("<div data-role=\"page\" id=\"home\">");
      print("<div data-role=\"header\">");
      h1("Maths Club");
      include("inc/html/navbar.php");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      print("<a href=\"/subject/math/Photo\"><button data-icon=\"camera\" class=\"ui-btn ui-btn-inline ui-shadow ui-corner-all ui-icon-camera ui-btn-icon-left\">Photo</button></a>");
      print("<table data-role=\"table\" data-mode=\"reflow\" id=\"events\" class=\"ui-responsive table-stroke\">");
      print("<thead>");
      print("<tr>");
      print("<th>Date</th>");
      print("<th>Theme</th>");
      print("</tr>");
      print("</thead>");
      print("<tbody>");
      foreach ($events as $date => $event) {
        print("<tr>");
        print("<td>$date</td>");
        print("<td>$event</td>");
        print("</tr>");
      }
      print("</tbody>");
      print("</table>");
      print("</div>");
      include("inc/html/footer.php");
      print("</div>");
      print("</html>");
    }
  }
?>