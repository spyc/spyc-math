<?php
  class Paper extends HttpServlet {
    public function doGet($request, $response) {
      require_once("inc/php/mysql.php");    
      $paper = (array) json_decode(file_get_contents("inc/data/paper.json", true));
      println("<!DOCTYPE html>");
      print("<html lang=\"en\">");
      print("<head>");
      include("inc/html/head.php");
      print("<style type=\"text/css\">");
      print("#training {");
      print("width: 80%;");
      print("margin-left: 5%;");
      print("}");
      print("</style>");
      println("</head>");
      print("<body>");
      print("<div data-role=\"page\" id=\"paper\">");
      print("<div data-role=\"header\">");
      h1("Maths Club");
      include("inc/html/navbar.php");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      
      print("<table data-role=\"table\" data-mode=\"reflow\" id=\"training\" class=\"ui-responsive table-stroke\">");
      print("<thead>");
      print("<tr>");
      print("<th>Date</th>");
      print("<th>Form</th>");
      print("<th>Paper</th>");
      print("</tr>");
      print("</thead>");
      print("<tbody>");
      foreach ($paper as $file => $date) {
        $level = substr($file, 2);
        print("<tr>");
        printf("<td>%s</td>", $date);
        printf("<td>%s</td>", $level);
        printf("<td><a href=\"/subject/math/Download/file/%s.pdf\" data-ajax=\"false\">%s</a></td>", urlencode($file), $file);
        print("</tr>");
      }
      print("</tbody>");
      print("</table>");
      
      print("</div>");
      include("inc/html/footer.php");
      print("</div>");

      print("</body>");
      print("</html>");
    }
}
?>