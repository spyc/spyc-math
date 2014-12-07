<?php
  abstract class HttpServlet {
      const METHOD_DELETE = "DELETE";
      const METHOD_HEAD = "HEAD";
      const METHOD_GET = "GET";
      const METHOD_OPTIONS = "OPTIONS";
      const METHOD_POST = "POST";
      const METHOD_PUT = "PUT";
      const METHOD_TRACE = "TRACE";
      
      const HEADER_IFMODSINCE = "If-Modified-Since";
      const HEADER_LASTMOD = "Last-Modified";
  
    public function  __constuct(){}
    protected function doDelete($req, $resp){
      $protocol = $req->getProtocol();
      $resp->setStatus(HttpServletResponse::SC_METHOD_NOT_ALLOWED);
    }
    protected function doGet($req, $resp){
      $protocol = $req->getProtocol();
      $resp->setStatus(HttpServletResponse::SC_METHOD_NOT_ALLOWED);
    }
    protected function doHead($req, $resp){
      $this->doGet($req, $resp);
      ob_clean();
    }
    protected function doOptions($req, $resp){}
    protected function doPost($req, $resp){
      $protocol = $req->getProtocol();
      $resp->setStatus(HttpServletResponse::SC_METHOD_NOT_ALLOWED);
    }
    protected function doPut($req, $resp){
      $protocol = $req->getProtocol();
      $resp->setStatus(HttpServletResponse::SC_METHOD_NOT_ALLOWED);
    }
    protected function doTrace($req, $resp){}
    protected function getLastModified($req){
      return -1;
    }
    public function service($req, $resp){
      $method = $req->getMethod();
      switch ($method) {
        case HttpServlet::METHOD_GET:
          $lastModified = $this->getLastModified($req);
          if ($lastModified == -1){
            $this->doGet($req, $resp);
          } else {
            $ifModifiedSince = $req->getHeader(HttpServelt::HEADER_IFMODSINCE);
            if ($ifModifiedSince < ($lastModified / 1000 * 1000)) {
              $resp->setDateHeader(HttpServelt::HEADER_LASTMOD, $lastModified);
              $this->doGet($req, $resp);
            } else {
              $resp->setStatus(HttpServletResponse::SC_NOT_MODIFIED);
            }
          }
        break;
        case HttpServelt::METHOD_HEAD:
          $lastModified = $this->getLastModified($req);
          $resp->setDateHeader(HttpServelt::HEADER_LASTMOD, $lastModified);
          $this->doHead($req, $resp);
        break;
        case HttpServelt::METHOD_POST:
        case HttpServelt::METHOD_PUT:
        case HttpServelt::METHOD_DELETE:
        case HttpServelt::METHOD_OPTIONS:
        case HttpServelt::METHOD_TRACE:
          $method = strtolower($method);
          $method = "do" . ucfirst($method);
          $this->$method($req, $resp);
        break;
        default:
          ob_clean();
          $resp->setStatus(HttpServletResponse::SC_NOT_IMPLEMENTED);
      }
    } 
  }
  
  interface HttpSession {
    public function getAttribute(String $name);
    public function getAttributeNames();
    public function getCreationTime();
    public function getId();
    public function getMaxInactiveInterval();
    public function isNew();
    public function removeAttribute(String $name);
    public function setAttribute(String $name, $value);
    public function setMaxInactiveInterval(int $interval);
  }
  
  class RESTfulSession implements HttpSession {
    private $sessionData = array();
    private $interval;
    private $sessionName = "PHPSESSID";
    
    public function __construct($data = array()) {
      $this->interval = ini_get("session.gc_maxlifetime");
      $this->sessionName = ini_get("session.name");
      foreach ($data as $name => $value) {
        $this->sessionData[$name] = $value;
      }      
    }
    public function getAttribute(String $name) {
      return $this->getAttribute($name);
    }
    public function getAttributeNames() {
      $names = array();
      foreach ($this->sessionData as $name => $value) {
        $names[] = $name;
      }
      return $names;
    }
    public function getCreationTime() {
      return $_SERVER["REQUEST_TIME"]; 
    }
    public function getId() {
      return session_start();
    }
    public function getMaxInactiveInterval() {
      return $this->interval;
    }
    public function isNew() {
      return count($this->sessionData) > 0;
    }
    public function removeAttribute(String $name) {
      unset($this->sessionData[$name]);
    }
    public function setAttribute(String $name, $value) {
      $this->sessionData[$name]=$value;
    }
    public function setMaxInactiveInterval(int $interval) {
      if ($this->isNew()) {
        session_set_cookie_params($interval);
        $this->interval = $interval;
      } else {
        setcookie($this->sessionName, $this->getId(), time() + $interval);
        $this->interval = $interval;
      }
    }
  }
  
  class Cookie{
    
    private $name;
    private $value;
    private $comment;
    private $domain;
    private $maxAge = -1;
    private $path;
    private $secure;
    private $version = 0;
    
    public function __construct($name, $value) {
      if (!preg_match("/[,;\s\W]*/", $name) || strcasecmp($name, "Comment")  || strcasecmp($name, "Discard") || strcasecmp($name, "Domain") || strcasecmp($name, "Expires") || strcasecmp($name, "Max-Age") || strcasecmp($name, "Path") || strcasecmp($name, "Secure") || strcasecmp($name, "Version")) {
        return;
      }
      $this->name = $name;
      $this->value = $value;
      $this->send();
    }
    
    public function setComment(String $purpose) {
      $this->comment = $purpose;
      $this->send();
    }
    public function getComment() {
      return $this->comment;
    }
    public function setDomain(String $pattern) {
      $this->domain = strtolower($pattern);
      $this->send();
    }
    public function getDomain() {
      return $this->domain;
    }
    public function setMaxAge(int $expiry) {
      $this->maxAge = $expiry;
      $this->send();
    }
    public function getMaxAge() {
      return $this->maxAge;
    }
    public function setPath(String $uri) {
      $this->path = $uri;
      $this->send();
    }
    public function getPath() {
      return $this->path;
    }
    public function  setSecure(boolean $flag) {
      $this->secure = $flag;
      $this->send();
    }
    public function getSecure() {
      return $this->secure;
    }
    public function getName() {
      return $this->name;
    }
    public function setValue(String $value) {
      $this->value = $value;
      $this->send();
    }
    public function getValue() {
      return $this->value;
    }
    public function setVersion(int $v) {
      $this->version = $v;
      $this->send();
    }
    public function getVersion() {
      return $this->version;
    }
    private function send() {
      setcookie($this->name, $this->value, $this->maxAge, $this->path, $this->domain, $this->secure, null);
    }
  }  
  
  interface HttpServletRequest {
    const BASIC_AUTH = "BASIC";
    const FORM_AUTH = "FORM";
    const CLIENT_CERT_AUTH = "CLIENT_CERT";
    const DIGEST_AUTH = "DIGEST";
    
    public function getAttribute($name);
    public function getAttributeNames();
    public function getContentLength();
    public function getContentType();
    public function getLocalAddr();
    public function getLocalName();
    public function getLocalPort();
    public function getParameter($name);
    public function getParameterMap();
    public function getParameterNames();
    public function getParameterValues($name);
    public function getProtocol();
    public function getRemoteAddr();
    public function getRemoteHost();
    public function getRemotePort();
    public function getScheme();
    public function getServerName();
    public function getServerPort();
    public function removeAttribute($name);
    public function setAttribute($name, $o);
    
    public function changeSessionId();
    public function getCookies();
    public function getDateHeader($name);
    public function getHeader($name);
    public function getHeaders($name);
    public function getIntHeader($name);
    public function getHeaderNames();
    public function getMethod();
    public function getPathInfo();
    public function getPathTranslated();
    public function getContextPath();
    public function getQueryString();
    public function getRemoteUser();
    public function getRequestedSessionId();
    public function getRequestURI();
    public function getRequestURL();
    public function getServletPath();
    public function getSession($create = null);
  }
  
  
  class RESTfulRequest implements HttpServletRequest {
    
    private $parameter = array();
    private $cookies = array();
    private $session = null;
    private $header = array();
    private $env = array();
    private $attr = array();
    
    public function __construct($param, $server, $cookie, $session, $segement) {
      $this->parameter = $param;
      for ($i = count($segement) - 1; $i >= 0; $i-=2){
        $this->parameter[$segement[$i-1]] = $segement[$i];
      }
      foreach ($cookie as $name => $value) {
        $this->cookies = new Cookie($name, $value);
      }
      if (isset($session)){
        $session = new RESTfulSession($session);
      }
      foreach ($server as $name => $value){
        $name = str_replace("_", "-", $name);
        $value = str_replace("\\", "/", $value);
        if (strpos($name, "HTTP-") === 0){
          $arg = explode("-", str_replace("HTTP-", "", $name));
          foreach ($arg as $v){
            $v = strtolower($v);
            $v =ucfirst($v);
          }
          $header[join("-", $arg)] = $value;
        } else {
          $this->env[$name] = $value;
        }
      }
    }
    
    public function getAttribute($name) {
      return isset($this->attr[$name]) ? $this->attr[$name] : null;
    }
    public function getAttributeNames() {
      $names = array();
      foreach ($this->attr as $name => $value) {
        $names[] = $name;
      }
    }
    public function setAttribute($name, $o) {
      $this->attr[$name] = $o;
    }
    public function removeAttribute($name) {
      unset($this->env[$name]);
    }
    public function getContentLength() {
      return ob_get_length();
    }
    public function getContentType() {
      return "text/html";
    }
    public function getLocalAddr() {
      return $this->env["SERVER-ADDR"];
    }
    public function getLocalName() {
      return $this->env["SERVER-NAME"];
    }
    public function getLocalPort() {
      return $this->env["SERVER-PORT"];
    }
    public function getParameter($name) {
      return isset($this->parameter[$name])? $this->parameter[$name] : null;
    }
    public function getParameterMap() {
      return $this->parameter;
    }
    public function getParameterNames() {
      $names = array();
      foreach($this->parameter as $name => $value) {
        $names[] = $name;
      }
      return $names;
    }
    public function getParameterValues($name){
      return $this->getParameter($name);
    }
    public function getProtocol() {
      return $this->env["SERVER-PROTOCOL"];
    }
    public function getRemoteAddr() {
      return $this->env["REMOTE-ADDR"];
    }
    public function getRemoteHost() {
      if ($host = gethostbyaddr($this->getRemoteAddr())) {
        return $host;
      } else {
        return $this->getRemoteAddr();
      }
    }
    public function getScheme() {
      return $this->env["REQUEST-SCHEME"];
    }
    public function getServerName() {
      return $this->env["SERVER-NAME"];
    }
    public function getServerPort() {
      return $this->env["SERVER-PORT"];
    }
    
    public function changeSessionId() {
      session_regenerate_id();
    }
    public function getCookies() {
      return $this->cookies;
    }
    public function getDateHeader($name) {
      return strtotime($this->getHeader($name));
    }
    public function getHeader($name) {
      return $this->header[$name];
    }
    public function getHeaderNames() {
      $names = array();
      foreach ($this->header as $name => $value) {
        $names[] = $name;
      }
      return $names;
    }
    public function getHeaders($name) {
      return $this->getHeader($name);
    }
    public function getIntHeader($name) {
      return intval($this->getHeader($name));
    }
    public function getMethod() {
      return $this->env["REQUEST-METHOD"];
    }
    public function getPathInfo() {
      return isset($this->env["PATH-INFO"]) ? $this->env["PATH-INFO"] : null;
    }
    public function getPathTranslated() {
      return isset($this->env["PATH-TRANSLATED"]) ? $this->env["PATH-TRANSLATED"] : $this->env["SCRIPT-FILENAME"];
    }
    public function getQueryString() {
      return isset($this->env["QUERY-STRING"]) ? $this->env["QUERY-STRING"] : null;
    }
    public function getRemoteUser() {
      return isset($this->env["REMOTE-USER"]) ? $this->env["REMOTE-USER"] : null;
    }
    public function getRequestedSessionId() {
      return session_id();
    }
    public function getRequestURI() {
      return substr($this->env["REQUEST-URI"], 1);
    }
    public function getRequestURL() {
      return $this->env["REQUEST-SCHEME"] . "://" . $this->getHeader("Host") . $this->env["SCRIPT-NAME"];
    }
    public function getServletPath() {
      return substr($this->env["SCRIPT-NAME"], 1);
    }
    public function getSession($create = null) {
      if ($create){
        session_start();
      }
      return $this->session;
    }
    public function getContextPath() {
      return $this->env["REQUEST-URI"];
    }
    public function getRemotePort() {
      return $this->env["REMOTE-ADDR"];
    }
  }
  
  if (!function_exists("println")){
    function println($v) {
      return print($v . "\n");
    }
  }
  
  interface HttpServeltResponse {
    const SC_CONTINUE = 100;
    const SC_SWITCHING_PROTOCOLS = 101;
    const SC_OK = 200;
    const SC_CREATED = 201;
    const SC_ACCEPTED = 202;
    const SC_NON_AUTHORITATIVE_INFORMATION = 203;
    const SC_NO_CONTENT = 204;
    const SC_RESET_CONTENT = 205;
    const SC_PARTIAL_CONTENT = 206;
    const SC_MULTIPLE_CHOICES = 300;
    const SC_MOVED_PERMANENTLY = 301;
    const SC_FOUND = 302;
    const SC_SEE_OTHER = 303;
    const SC_NOT_MODIFIED = 304;
    const SC_USE_PROXY = 305;
    const SC_TEMPORARY_REDIRECT = 307;
    const SC_BAD_REQUEST = 400;
    const SC_UNAUTHORIZED = 401;
    const SC_PAYMENT_REQUIRED = 402;
    const SC_FORBIDDEN = 403;
    const SC_NOT_FOUND = 404;
    const SC_METHOD_NOT_ALLOWED = 405;
    const SC_NOT_ACCEPTABLE = 406;
    const SC_PROXY_AUTHENTICATION_REQUIRED = 407;
    const SC_REQUEST_TIMEOUT = 408;
    const SC_CONFLICT = 409;
    const SC_GONE = 410;
    const SC_LENGTH_REQUIRED = 411;
    const SC_PRECONDITION_FAILED = 412;
    const SC_REQUEST_ENTITY_TOO_LARGE = 413;
    const SC_REQUEST_URI_TOO_LONG = 414;
    const SC_UNSUPPORTED_MEDIA_TYPE = 415;
    const SC_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const SC_EXPECTATION_FAILED = 417;
    const SC_INTERNAL_SERVER_ERROR = 500;
    const SC_NOT_IMPLEMENTED = 501;
    const SC_BAD_GATEWAY = 502;
    const SC_SERVICE_UNAVAILABLE = 503;
    const SC_GATEWAY_TIMEOUT = 504;
    const SC_HTTP_VERSION_NOT_SUPPORTED = 505;
    
    public function flushBuffer();
    public function getBufferSize();
    public function getCharacterEncoding();
    public function getContentType();
    public function setCharacterEncoding($charset);
    public function setContentLength(int $len);
    public function setContentType($type);
        
    public function addCookie(Cookie $cookie);
    public function addDateHeader($name, int $date);
    public function addHeader($name, $value);
    public function addIntHeader($name, int $value);
    public function containsHeader($name);
    public function encodeRedirectURL($url);
    public function encodeURL($url);
    public function getHeader($name);
    public function getHeaderNames();
    public function getHeaders($name);
    public function getStatus();
    public function sendError(int $sc, $msg);
    public function sendRedirect($location);
    public function setDateHeader($name, int $date);
    public function setHeader($name, $value);
    public function setIntHeader($name, int $value);
    public function setStatus($sc, $msg);
  }
  
  class RESTfulResponse implements HttpServeltResponse{
    private $headers = array();
    private $contentType = "text/html";
    private $charset;
    private $status;
    private $cookie = array();
    private $req;
    
    public function __construct(HttpServletRequest $req) {
      $tmp_header = headers_list();      
      foreach ($tmp_header as $header){
        $tmp = explode(":", $header, 2);
        $this->headers[trim($tmp[0])] = trim($tmp[1]);
      }
      $this->charset = mb_internal_encoding();
      $this->status = 200;
      $this->req = $req;
    }
    
    public function flushBuffer() {
      ob_flush();
    }
    public function getBufferSize() {
      return ob_get_length();
    }
    public function getCharacterEncoding() {
      return $this->charset;
    }
    public function getContentType() {
      return $this->contentType;
    }
    public function setCharacterEncoding($charset) {
      $this->charset = $charset;
      $this->setHeader("Charset", $charset);
    }
    public function setContentLength(int $len) {
      $this->setHeader("Content-Lenth", $len);
    }
    public function setContentType($type) {
      $this->contentType = $type;
      $this->setHeader("Content-Type", $type);
    }
    
    public function addCookie(Cookie $cookie) {
      $this->cookie[] = $cookie;
    }
    public function addDateHeader($name, int $date) {
      $this->setDateHeader($name, $date);
    }
    public function addHeader($name, $value) {
      $this->setHeader($name, $value);
    }
    public function addIntHeader($name, int $value) {
      $this->setHeader($name, intval($value));
    }
    public function containsHeader($name) {
      return isset($this->headers[$name]);
    }
    public function encodeRedirectURL($url) {
      $url = htmlspecialchars($url);
      if (!ini_get("session")) {
        $url .= ":" . session_id();
      }
      return $url;
    }
    public function encodeURL($url) {
      return htmlspecialchars($url);
    }
    public function getHeader($name) {
      return $this->headers[$name];
    }
    public function getHeaderNames() {
      $names = array();
      foreach ($this->headers as $name => $value) {
        $names[] = $name;
      }
      return $names;
    }
    public function getHeaders($name) {
      return $this->getHeader($name);
    }
    public function getStatus() {
      return $this->status;
    }
    public function sendError(int $sc, $msg=null) {
      $this->setStatus($sc, $msg);
    }
    public function sendRedirect($location) {
      $this->setHeader("Location", $location);
    }
    public function setDateHeader($name, int $date) {
      $this->setHeader($name, gmdate("D, d M Y H:i:s", $date) . "GMT");
    }
    public function setHeader($name, $value) {
      $this->headers[$name] = $value;
    }
    public function setIntHeader($name, int $value) {
      $this->setHeader($name, intval($value));
    }
    public function setStatus($sc, $msg=null){
      if (!is_null($msg)) {
        header($this->req->getProtocol() . " " . $sc . " " . $msg);
      }
      $this->status = $sc;
    }
    public function send() {
      $this->setStatus($this->status);
      foreach ($this->headers as $name => $value) {
        header($name . ": " . $value);
      }
      ob_flush();
    }
  }
?>