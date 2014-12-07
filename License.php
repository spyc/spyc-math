<?php
  class License extends HttpServlet {
    public function doGet($req, $resp){
      println("<!DOCTYPE html>\n<html lang=\"en\">");
      print("<head>");
      include("inc/html/head.php");
      println("</head>");
      print("<body>");
      print("<div data-role=\"page\" id=\"home\">");
      print("<div data-role=\"header\">");
      print("<a href=\"#\" class=\"ui-btn ui-corner-all ui-shadow ui-icon-back ui-btn-icon-left\" onclick=\"history.back();\">Back</a>");
      h1("The MIT License (MIT)");
      print("</div>");
      print("<div class=\"ui-content\" data-role=\"main\">");
      h2("The MIT License (MIT)");
      h3("Copyright (c) 2014 Shatin Pui Ying College");
      p("Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the \"Software\"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:<br>The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.");
      p("THE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.");
      print("</div>");
      print("</div>");
      println("</body>\n</html>");
    }
  }
?>