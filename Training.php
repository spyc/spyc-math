<?php
  class Training extends HttpServlet {
    public function doGet($request, $response) {
      require_once("inc/php/mysql.php");
      list($train, $tutor) = (array) json_decode(file_get_contents("inc/data/training.json", true));
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
      print("<div data-role=\"page\" id=\"home\">");
      print("<div data-role=\"header\">");
      h1("Maths Club");
      include("inc/html/navbar.php");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      h3("Tutor:");
      
      foreach($tutor as $team => $pyc) {
        $param = "\"" . join("\",\"", explode(",", $pyc)) . "\"";
        $sql = sprintf("SELECT `ename`,`class` FROM `student` WHERE `pycid` IN (%s)", $param);
        $stmt = $dbh->query($sql);
        print($team . ": ");
        $output = array();
        while(list($ename, $class) = $stmt->fetch(PDO::FETCH_NUM)) {
          array_push($output, $class . " " . $ename);
        }
        print(join(", ", $output) . "<br>");
      }
      print("<a href=\"/subject/math/Paper\">Get Papers</a>");
      print("<table data-role=\"table\" data-mode=\"reflow\" id=\"training\" class=\"ui-responsive table-stroke\">");
      print("<thead>");
      print("<tr>");
      print("<th>Date</th>");
      print("<th>Team</th>");
      print("</tr>");
      print("</thead>");
      print("<tbody>");
      
      foreach ($train as $date => $team) {
        print("<tr>");
        print("<td>$date</td>");
        print("<td>$team</td>");
        print("</tr>");
      }
      
      print("</tbody>");
      print("</table>");
      print("</div>");
      include("inc/html/footer.php");
      print("</div>");
      
      print("</body");
      
      print("</html>");
    }
  }
?>