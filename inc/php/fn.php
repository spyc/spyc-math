<?php
  function stylesheet($href) {
    printf("<link rel=\"stylesheet\" type=\"text/css\" href=\"%s\">", $href);
  }
  function script($src) {
    printf("<script type=\"text/javascript\" src=\"%s\"></script>", $src);
  }
  function icon($href, $type="image/ico") {
    printf("<link rel=\"icon\" type=\"%s\" href=\"%s\">", $type, $href);
  }
  function a($title, $href, $target="_self") {
    printf("<a href=\"%s\" target=\"%s\">%s</a>", $href, $target, $title);
  }
  function h1($title) {
    printf("<h1>%s</h1>", $title);
  }
  function h2($title) {
    printf("<h2>%s</h2>", $title);
  }
  function h3($title) {
    printf("<h3>%s</h3>", $title);
  }
  function p($content) {
    printf("<p>%s</p>", $content);
  }
?>