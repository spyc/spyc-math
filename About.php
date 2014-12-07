<?php
  class About extends HttpServlet {
    public function doGet($req, $resp) {      
      $commitee =json_decode(file_get_contents("inc/data/about.json", true));
      $commitee = (array) $commitee;
      require_once("inc/php/mysql.php");
      println("<!DOCTYPE html>");
      println("<html lang=\"en-GB\">");
      print("<head>");
      include("inc/html/head.php");
      print("</head>");
      print("<body>");
      print("<div data-role=\"page\" id=\"about\">");
      print("<div data-role=\"header\">");
      h1("Maths Club");
      include("inc/html/navbar.php");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      h2("Our Team");
      print("<table data-role=\"table\" class=\"ui-responsive\">");
      print("<thead>");
      print("<th>Post</th>");
      print("<th>Name</th>");
      print("</thead>");
      print("<tbody>");
      $sql = "SELECT `ename`, `class` FROM `student` WHERE `pycid` = '%s'";
      foreach ($commitee as $stud) {
        list($post, $person) = explode("|", $stud);
        $stmt = $dbh->query(sprintf($sql, $person));
        try {
          list($ename, $class) = $stmt->fetch(PDO::FETCH_NUM);
          print("<tr>");
          print("<td>$post</td>");
          print("<td>$ename ($class)</td>");
          print("</tr>");
        } catch (PDOException $e) {
          exit;
        }
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