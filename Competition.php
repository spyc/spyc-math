<?php
  class Competition extends HttpServlet {
    public function doGet($req, $resp) {
      require_once("inc/php/mysql.php");
      println("<!DOCTYPE html>");
      print("<html lang=\"en-GB\">");
      print("<head>");
      include("inc/html/head.php");
      print("</head>");
      print("<body>");
      print("<div data-role=\"page\" id=\"home\">");
      print("<div data-role=\"header\">");
      h1("Maths Club");
      include("inc/html/navbar.php");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      if (is_null($detail = $req->getParameter("detail"))) {
        $sql = "SELECT `id`, `held_date`, `name` FROM `math_competition` WHERE `held_date` > NOW() ORDER BY `held_date` DESC";
        $stmt = $dbh->query($sql);
        print("<table data-role=\"table\" class=\"ui-responsive\">");
        print("<thead>");
        print("<tr>");
        print("<th>Date</th>");
        print("<th>Competition</th>");
        print("</tr>");
        print("</thead>");
        print("<tbody>");
        while(list($id, $date, $name) = $stmt->fetch(PDO::FETCH_NUM)) {
          print("<tr>");
          print("<td>$date</td>");
          print("<td><a href=\"/subject/math/Competition/detail/$id\">$name</a></td>");
        }
        print("</tbody>");
        print("</table>");
      } else {
        $sql = "SELECT * FROM `math_competition` WHERE `id` = %d";
        $stmt = $dbh->query(sprintf($sql, intval($detail)));
        list($id, $name, $descipt, $date) = $stmt->fetch(PDO::FETCH_NUM);
        print("<a href=\"/subject/math/Competition\" class=\"ui-btn ui-corner-all ui-shadow ui-icon-back ui-btn-icon-left\" style=\"width: 5%; min-width: 44px;\">Back</a>");
        print("<div>");
        print("Compitition: $name <br>");
        print("Date: $date <br>");
        print("Detail: <br> $descipt");
        print("</div>");
      }
      print("</div>");
      include("inc/html/footer.php");
      print("</div>");
      print("</html>");
    }
  }
?>