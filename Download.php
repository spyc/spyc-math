<?php
  class Download extends HttpServlet {
    public function doGet($request, $response) {
      $file_url = $request->getParameter("file");
      $response->setContentType("application/octet-stream");
      $response->setHeader("Content-Transfer-Encoding", "Binary"); 
      $response->setHeader("Content-disposition", "attachment; filename=\"past-paper-" . basename($file_url) . "\""); 
      readfile("pdf/" . $file_url);
    }
  }
?>
