<?php
  class Photo extends HttpServlet {
    public function doGet($request, $response) {
      println("<!DOCTYPE html>");
      print("<html lang=\"en-GB\">");
      print("<head>");
      include("inc/html/head.php");
      printf("<script src=\"%s\"></script>", "/console/cdn/jquery/plugin/sudoslider/js/jquery.sudoSlider.js");
      printf("<link href=\"%s\" rel=\"stylesheet\" type=\"text/css\">", "/console/cdn/jquery/plugin/sudoslider/css/style.min.css");
      if ($request->getParameter("event") != null) {
        $event = preg_replace("/\\W+/", "", $request->getParameter("event"));
        $photos = scandir("./img/events/$event");
        array_shift($photos);
        array_shift($photos);
?>
<script>
        $(document).ready(function(){
          var sudoSlider = $("#slider").sudoSlider({ 
            numeric : true,
            continuous : true
          });
        });
</script>
<?php
      }
      println("</head>");
      print("<body>");
      print("<div data-role=\"page\" id=\"home\">");
      print("<div data-role=\"header\">");
      h1("Maths Club");
      include("inc/html/navbar.php");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      if ($request->getParameter("event") == null) {
        $events = (array) json_decode(file_get_contents("inc/data/photo.json", true));
        print("<table data-role=\"table\" data-mode=\"reflow\" id=\"events\" class=\"ui-responsive table-stroke\">");
        print("<thead>");
        print("<tr>");
        print("<th>Event</th>");
        print("<th>Photo</th>");
        print("</tr></thead>");
        print("<tbody>");
        foreach ($events as $event => $file) {
          print("<tr>");
          print("<td>$event</td>");
          printf("<td>");
          printf("<a href=\"/subject/math/Photo/event/%s\">View</a>", $file);
          print("</td>");
          print("</tr>");
        }
        print("</tbody>");
        print("</table>");
      } else {
        foreach($photos as $photo) {
          printf("<img src=\"/subject/math/img/events/$event/%s\" alt=\"\" style=\"width: 40%%; height: 30%%; margin-left: 10px\">", $photo);
        }
      }
      print("</div>");
      include("inc/html/footer.php");
      print("</div>");
      print("</html>");
    }
  }
?>