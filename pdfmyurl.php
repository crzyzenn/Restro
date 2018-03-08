<?php
/**
 * filename	:	pdfmyurl.php
 * version	:	1.42
 *
 * You can use this library as follows:
 *
 *		Step 1: Initiliaze the PDFmyURL class and (optionally) set server settings
 *
 *		require 'pdfmyurl.php';						// make sure that the class is there
 *
 *      $pdf = new PDFmyURL($license);				// initialize with your private license key
 *      
 *      $pdf->SetEndPoint(...);						// set the endpoint of the API (in case you have a private server with us)
 *      $pdf->SetTempDirectory(...);				// set the directory for your temporary files
 *      $pdf->SetCurlTimeOut(30);					// your default Curl timeout is 10 seconds, if you convert large files or are on a slow network then please increase this
 *      
 *      Step 2: (Optional) Change conversion options if you want to
 *      
 *      // page conversion settings
 *      $pdf->SetPageSize(...);						// Set the page size to one of the standard formats like A4, B0, Letter etc
 *      $pdf->SetPageOrientation(...);				// Set the page orientation to either Portrait or Landscape
 *      $pdf->SetDimensionUnit(....);				// Set the unit of measure for the dimensions (mm, inches or points)
 *      $pdf->SetPageDimensions(...);				// Set the page size to exact dimensions or force a single page PDF
 *      $pdf->SetMargins(....);						// Set the margins
 *      $pdf->SetViewport(....);					// Set the viewport
 *      $pdf->SetGrayscale();						// Create the PDF in grayscale
 *      $pdf->NoJavaScript();						// Disables Javascript
 *      $pdf->SetJavaScriptDelay(...);				// Define the amount of msec to wait before JavaScript completes
 *      $pdf->Bookmarks();							// Add bookmarks to the PDF based on the <h1> - <h4> tags
 *      $pdf->NoImages();							// Disables images
 *      $pdf->NoBackground();						// Disables the background
 *      $pdf->NoInternalLinks();					// Disables internal links
 *      $pdf->NoExternalLinks();					// Disables external links
 *      $pdf->SetCssMediaType(...);					// Define the CSS media type that should be used for the conversion
 *      $pdf->SetZoomFactor(...);					// Set the zoom factor for the conversion process
 *      $pdf->SetTitle(...);						// Sets the title of the PDF
 *      
 *      // content and layout manipulation
 *      $pdf->SetCustomCSS(....);					// Applies custom CSS to the document
 *      $pdf->HideContent(....);					// Hides the content IDs in the array
 *      $pdf->ShowContent(....);					// Shows the content IDs in the array
 *      
 *      // header & footer settings
 *      $pdf->SetHeader(...);						// Define the header in HTML
 *      $pdf->SetFooter(...);						// Define the footer in HTML
 *      $pdf->SetPageOffset(...);					// Define the offset for page numbering
 *      
 *      // watermarking
 *      $pdf->SetWatermarkLocation(...);			// Define location of the watermark
 *      $pdf->SetWatermarkTransformation(...);		// Define rotation, opacity and scaling of the watermark
 *      $pdf->SetWatermarkImage(...);				// Define the URL of the watermark image
 *      $pdf->SetWatermarkText(...);				// Define the text of the watermark and it's font characteristics (font, color, size)
 *      											// Note - you can only display an image watermark OR a text watermark
 *      
 *      // stationary settings aka (full) background settings
 *      $pdf->SetStationaryLocation(...);			// Define location of the stationary background
 *      $pdf->SetStationaryTransformation(...);		// Define rotation, opacity and scaling of the stationary - set scaling to 0 for FULL BACKGROUND DISPLAY
 *      $pdf->SetStationaryImages(...);				// Define the URL of the stationary images for first page, next pages and last page
 *      
 *      // authorization settings
 *      $pdf->SetHttpAuthentication(...);			// Set user name and password for basic HTTP Authentication
 *      $pdf->SetFormAuthentication(...);			// Set parameters for form login authentication
 *      $pdf->SetSessionID(....);					// Set a cookie with the JSESSIONID
 *      $pdf->SetCookieJar(....);					// Set the contents of a cookie jar
 *      $pdf->SetNetscapeCookieJar(....);			// Set the contents of a cookie jar in the old Netscape format
 *      
 *      // encryption & password protection settings
 *      $pdf->SetEncryptionLevel(...);				// Define the encryption level for the PDF
 *      $pdf->SetPasswords(...);					// Set the owner password and the 'document open' password
 *      $pdf->SetPermissions(...);					// Set the permissions for everyone except the owner, for example if printing is allowed etc.
 *     
 *      Step 3: Choose one of the following use cases
 *      
 *      $pdf->CreateFromURL('http://www.ab.com');	// create a PDF from a URL
 *      $pdf->CreateFromHTML('<html><head>....');	// create a PDF from a string containing HTML code
 *
 *		Step 4: Choose to either save it on your system or display it to your user
 *
 *      $pdf->Display();							// Display the PDF as attachment in the user's browser
 *      $pdf->Display('example.pdf');				// Display the PDF as attachment in the user's browser with filename example.pdf     
 *      $pdf->DisplayInline();						// Display the PDF inline in the user's browser
 *
 *      $pdf->Save('/files/yourfile.pdf');			// Save the PDF as local file
 *      
 *		$pdf->StringValue();						// Returns the PDF a string value
 *
 * Exception handling:
 *  
 * This library will throw standard PHP Exceptions in case of serious errors.
 * It is therefore recommended to use the components of this API in a try / catch block as follows:
 * 
 * Try {
 * 		$pdf = new PDFmyURL ($license);
 *  	$pdf->CreateFromURL('http://www.google.com');
 *  	$pdf->Display();
 *  } catch (Exception $error) {
 *  	// do something with $error->getMessage() and/or $error->getCode()
 *  }
 *  
 * Please see http://pdfmyurl.com/html-to-pdf-api-examples for a list of error codes and their meanings. 
 *
 */

class PDFmyURL {
	private $endpoint = 'http://pdfmyurl.com/api';
	
	private $tempfile=null, $tempdir, $cleanfilename='My PDF file', $options=array();
	private $curltimeout=30;
	
	public function __construct($license) {
		$this->tempdir = sys_get_temp_dir();
		$this->options['license'] = $license;
		$this->options['version'] = 'PHP_1.5';
		$this->SetWatermarkLocation();
		$this->SetWatermarkTransformation();
		$this->SetStationaryLocation();
		$this->SetStationaryTransformation();
	}

    // Remove temporary file when script completes
	public function __destruct() { if($this->tempfile!==null) unlink($this->tempfile); }
	
	// Allows you to set the endpoint of your private server
	public function SetEndPoint($endpoint) {
		$this->endpoint = $endpoint;
	}
	
	// Allows you to set the temporary directory for storing the PDF you receive from us in case the user processing your http requests has no access to the regular tmp directory
	public function SetTempDirectory($tmp) {
		$this->tempdir = $tmp;
	}
	// Allows you to set the timeout for Curl yourself - we default it to 20 seconds and that's usually enough to get your data
	public function SetCurlTimeOut($seconds) {
		$this->curltimeout = intval($seconds);
	}
	
	// Sets the page size to the value passed, but only if it's one of the supported page sizes
	//
	// Returns true on success and false if the page size is not supported
	public function SetPageSize($ps) {
		$allowed = array("a0","a1","a2","a3","a4","a5","a6","a7","a8","a9","b0","b1","b2","b3","b4","b5","b6","b7","b8","b9","b10","c5e","comm10e","dle","executive","folio","ledger","legal","letter","tabloid");
		if (false !== array_search(strtolower($ps), $allowed)) {
			$this->options['page_size'] = $ps;
			return true;
		} else
			return false;
	}
	
	// Sets the page orientation to the value passed, but only if it's portrait or landscape
	//
	// Returns true on success and false if the page orientation is not supported
	public function SetPageOrientation($po) {
		$po = strtolower($po);
		if (($po == 'portrait') || ($po == 'landscape')) {
			$this->options['orientation'] = $po;
			return true;
		} else
			return false;
	}
	
	public function SetDimensionUnit($unit) {
		$allowed = array("in","mm","pt");
		$unit = strtolower($unit);
		if (false !== array_search($unit, $allowed)) {
			$this->options['unit'] = $unit;
		}
	}
	
	// Sets the page dimensions to the values passed, but only if width>0
	//
	// If height = 0 then we will generate a single long page PDF for the whole source URL/HTML
	//
	// Returns true on success and false if the page dimensions are not supported
	public function SetPageDimensions($width, $height = 0) {
		if (is_numeric($width) && $width>0) {
			$this->options['width'] = $width;
			$this->options['height'] = $height;
			return true;
		} else
			return false;
	}
	
	// Sets the margins to the values passed
	//
	// By default all margins are set to 0
	public function SetMargins($top = 0, $bottom = 0, $left = 0, $right = 0) {
		$this->options['top'] = $top;
		$this->options['bottom'] = $bottom;
		$this->options['left'] = $left;
		$this->options['right'] = $right;
	}
	
	// Sets the viewport to a certain width and height
	//
	// By default the viewport is 1024*768
	public function SetViewport($width = 1024, $height = 768) {
		$this->options['screen_width'] = $width;
		$this->options['screen_height'] = $height;
	}
	
	// Sets the zoom factor
	//
	public function SetZoomFactor($zoom) {
		$this->options['zoom_factor'] = $zoom;
	}
	
	// Sets the grayscale to true
	//
	public function SetGrayscale() {
		$this->options['grayscale'] = '';
	}
	
	// Disables JavaScript processing
	//
	public function NoJavaScript() {
		$this->options['no_javascript'] = '';
	}
	
	// Sets JavaScript delay to the value passed, but only if it's greater than 0
	//
	// Returns true on success and false if the passed value is not correct
	public function SetJavaScriptDelay($msec) {
		$ms = intval($msec);
		if ($ms > 0) {
			$this->options['javascript_time'] = $ms;
			return true;
		} else
			return false;
	}
	
	// Enable bookmarks in the PDF
	//
	public function Bookmarks() {
		$this->options['bookmarks'] = '';	
	}
	
	// Disables image inclusion
	//
	public function NoImages() {
		$this->options['no_images'] = '';
	}
	
	// Disables background display
	//
	public function NoBackground() {
		$this->options['no_background'] = '';
	}
	
	// Disables internal links
	//
	public function NoInternalLinks() {
		$this->options['no_internal_links'] = '';
	}
	
	// Disables external links
	//
	public function NoExternalLinks() {
		$this->options['no_external_links'] = '';
	}
	
	// Sets CSS Media Type to the value passed, but only if it's 'print' or 'screen'
	//
	// Returns true on success and false if the passed value is not correct
	public function SetCssMediaType($switch) {
		$return = true;
		$switch = strtolower($switch);
		if ( ($switch == 'screen') || ($switch == 'print') )
			$this->options['css_media_type'] = $switch;
		else
			$return = false;
	
		return $return;
	}
	
	// Set the title of the PDF
	//
	public function SetTitle($title) {
		$this->options['title'] = trim($title);
	}
	
	// Hides the content of the divs in the array
	//
	public function HideContent($divs) {
		foreach ($divs as $div) {
			$this->options['content'][$div] = 'hide';
		}
	}
	
	// Shows only the content of the divs in the array
	//
	public function ShowContent($divs) {
		foreach ($divs as $div) {
			$this->options['content'][$div] = 'show';
		}
	}
	
	// Define custom CSS
	//
	// Returns true on success and false if the passed value is an empty string
	public function SetCustomCSS($css) {
		$css = trim($css);
		if (strlen($css)) {
			$this->options['css'] = $css;
			return true;
		} else
			return false;
	}
	
	// Define the header in HTML
	//
	// Returns true on success and false if the passed value is an empty string
	public function SetHeader($headerhtml) {
		$html = trim($headerhtml);
		if (strlen($html)) {
			$this->options['header'] = $html;
			return true;
		} else
			return false;
	}
	
	// Define the footer in HTML
	//
	// Returns true on success and false if the passed value is an empty string
	public function SetFooter($footerhtml) {
		$html = trim($footerhtml);
		if (strlen($html)) {
			$this->options['footer'] = $html;
			return true;
		} else
			return false;
	}
	
	// Define the page offset
	public function SetPageOffset($offset) {
		$this->options['page_offset'] = intval($offset);
	}
	
	/* Sets the encryption level to the parameters specified, possible input:
	 *
	*  level:
	*  	40: 40 bit RC4
	*  	128: 128 bit RC4
	*  	128aes: 128 bit AES (depending on AES switch)
	*  	256: 256 AES encryption
	*  	other values -> 128 bit encryption
	*
	*/
	public function SetEncryptionLevel($level) {
		switch (strtolower($level)) {
			case '40':
			case '128':
			case '256':
			case '128aes':
				$this->options['encryption_level'] = $level;
				break;
			default:
				$this->options['encryption_level'] = 128;
				break;
		}
	}
	
	// Sets the owner password (controls the encryption and rights parameters) and user password (needed to open the PDF)
	public function SetPasswords ($ownerpass, $userpass) {
		$this->options['owner_password'] = $ownerpass;
		$this->options['user_password'] = $userpass;
	}
	
	/* Sets the permissions for the PDF
	 *
	* $print :
	* 	true: allow printing
	*  false: disallow printing
	* $modify :
	* 	true: allow document modification
	*  false: disallow document modification
	* $copy :
	* 	true: allow copying of content
	* 	false: disallow copying of content
	*
	*/
	public function SetPermissions($print=true, $modify=true, $copy=true) {
		if (!$print)
			$this->options['no_print'] = '';
		if (!$modify)
			$this->options['no_modify'] = '';
		if (!$copy)
			$this->options['no_copy'] = '';
	}
	
	/* Sets the location of the watermark
	 *
	* $x: x location, this should be an integer value >=0
	* $y: y location, this should be an integer value >=0
	*/
	public function SetWatermarkLocation($x=0, $y=0) {
		$this->options['wm_x'] = intval($x);
		$this->options['wm_y'] = intval($y);
	}
	
	/* Sets the transformation characteristics of the watermark
	 *
	* $angle: angle in degrees to which the watermark is rotated - this is clockwise and should be an integer >=0 and <=360
	* $opacity: opacity of the watermark, this is a float between 0 and 1, 0 is completely transparant and 1 is completely opague
	* $scaling_x: scaling factor in the horizontal direction, 1 = normal size, 2 = twice as wide, 0.5 = half as wide
	* $scaling_y: scaling factor in the vertical direction, 1 = normal size, 2 = twice as tall, 0.5 = half as tall
	*/
	public function SetWatermarkTransformation($angle=0, $opacity=1, $scaling_x=1, $scaling_y=1) {
		$ret = true;
	
		$a = intval($angle); $o = floatval($opacity); $sx = floatval($scaling_x); $sy = floatval($scaling_y);
		if ($a<0 || $a>360) { // the angle should be between 0 and 360 degrees
			$a=0; $ret = false;
		}
		if ($o<0 || $o>1) { // the opacity should be between 0 and 1
			$o=1; $ret = false;
		}
		if ($sx<=0) { // only positive scaling is possible
			$sx = 1; $ret = false;
		}
		if ($sy<=0) { // only positive scaling is possible
			$sy = 1; $ret = false;
		}
	
		$this->options['wm_angle'] = $a;
		$this->options['wm_opacity'] = $o;
		$this->options['wm_sx'] = $sx;
		$this->options['wm_sy'] = $sy;
	
		return $ret;
	}
	
	/* Defines the location of the watermark image
	 *
	* $imageloc: url that points to the image, if needed you can upload to our server; Note we only test whether this exists at our server's side
	*
	*/
	public function SetWatermarkImage($imageloc) {
	
		$iloc = trim($imageloc);
		if (!$this->CheckURL($iloc)) {
			throw new Exception ("Watermark URL is invalid", 905);
			return false;
		}
		$this->options['wm'] = $iloc;
	
		return true;
	}
	
	/* Defines the watermark text and it's text properties
	 *
	* $text: the text that needs to be watermarked
	* $fontname: the name of the font - we support e.g. Helvetica, Courier, Tahoma, Arial and many more
	* $fontcolor: the color of the font in hexadecimal notation e.g. #FF0000 for red or #000000 for black
	* $fontsize: the size of the font
	*/
	public function SetWatermarkText($text, $fontname="Helvetica",$fontcolor="#000000", $fontsize=64) {
		$ret = true;
	
		if (!strlen(trim($text))) { // you need to fill in a text, this would otherwise lead to a hard error since we can not assume anything
			throw new Exception ("Watermark text can not be empty", 906);
			return false;
		}
		$fname = trim($fontname);
		if (!strlen($fname)) { // you need to pass a fontname and otherwise we'll assume Helvetica
			$fname = "Helvetica";
			$ret = false;
		}
		$fcolor = trim($fontcolor); // if you don't pass a valid color we'll assume black
		if (!preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $fcolor)) {
			$fcolor="#000000";
			$ret = false;
		}
		$fsize = intval($fontsize);
		if ($fsize<=0) { // you need to pass a positive font size
			$fsize = 64; $ret = false;
		}
		$this->options['wm_text'] = $text;
		$this->options['wm_font'] = $fname;
		$this->options['wm_fontcolor'] = $fcolor;
		$this->options['wm_fontsize'] = $fsize;
	
		return $ret;
	}
	
	/* Sets the location of the stationary aka (full) page background, starting at the utmost left upper corner of the page
	 *
	* $x: x location, this should be an integer value >=0
	* $y: y location, this should be an integer value >=0
	*/
	public function SetStationaryLocation($x=0, $y=0) {
		$this->options['bg_x'] = intval($x);
		$this->options['bg_y'] = intval($y);
	}
	
	/* Sets the transformation characteristics of the background
	 *
	* $angle: angle in degrees to which the background is rotated - this is clockwise and should be an integer >=0 and <=360
	* $opacity: opacity of the background, this is a float between 0 and 1, 0 is completely transparant and 1 is completely opague
	* $scaling_x: scaling factor in the horizontal direction, 1 = normal size, 2 = twice as wide, 0.5 = half as wide, 0 = FULL WIDTH OF THE PAGE
	* $scaling_y: scaling factor in the vertical direction, 1 = normal size, 2 = twice as tall, 0.5 = half as tall, 0 = FULL HEIGHT OF THE PAGE
	*/
	public function SetStationaryTransformation($angle=0, $opacity=1, $scaling_x=1, $scaling_y=1) {
		$ret = true;
	
		$a = intval($angle); $o = floatval($opacity); $sx = floatval($scaling_x); $sy = floatval($scaling_y);
		if ($a<0 || $a>360) { // the angle should be between 0 and 360 degrees
			$a=0; $ret = false;
		}
		if ($o<0 || $o>1) { // the opacity should be between 0 and 1
			$o=1; $ret = false;
		}
		if ($sx<0) { // only positive scaling is possible (or 0, which is full width)
			$sx = 1; $ret = false;
		}
		if ($sy<0) { // only positive scaling is possible (or 0, which is full height)
			$sy = 1; $ret = false;
		}
	
		$this->options['bg_angle'] = $a;
		$this->options['bg_opacity'] = $o;
		$this->options['bg_sx'] = $sx;
		$this->options['bg_sy'] = $sy;
	
		return $ret;
	}
	
	/* Defines the locations of the stationary akak background images
	 *
	* $i1: the image for the 1st page
	* $i2: the image for all pages, which are not the 1st or the last page
	* $i3: the image for the last page
	*
	* $i1, $i2, $i3: urls that point to images, if needed you can upload to our server; Note we only test whether this exists at our server's side, not if they are valid images
	*
	*/
	public function SetStationaryImages($i1, $i2='', $i3='') {
	
		if ($this->CheckURL($i1) && ($this->CheckURL($i2) || $i2=='') && ($this->CheckURL($i3) || $i3 == '')) {
			$this->options['bg'] = trim($i1);
			$this->options['bg2'] = trim($i2);
			$this->options['bg3'] = trim($i3);
			return true;
		} else {
			throw new Exception ("Stationary background URL is invalid", 907);
			return false;
		}
	}
	
	// Sets the user name and password for basic HTTP authentication, which we'll use to get the page for conversion
	//
	// Returns true on success or false when user name or password is empty
	public function SetHttpAuthentication($username, $password) {
		if (strlen(trim($username)) && strlen(trim($password))) {
			$this->options['username'] = trim($username);
			$this->options['password'] = trim($password);
			return true;
		} else
			return false;
	}
	
	// Sets the URL of the login form and the parameters and values of the input fields that are required (including the submit button!)
	//
	// $form_fields should be an array of the form (Wordpress example): 
	// $form_fields = array(
    //            		'log' => 'username',
    //            		'pwd' => 'password',
    //            		'wp-submit' => 'Log In',
    //            		'testcookie' => '1',
    //           		'rememberme' => 'forever'
    //            		);
	
	public function SetFormAuthentication($form_url, $form_fields) {
		if ($this->CheckURL($form_url) && !empty($form_fields)) {
			$this->options['form_url'] = trim($form_url);
			foreach ($form_fields as $field=>$value) {
				$this->options['form_fields'][$field] = trim($value);
			}
			return true;
		} else
			return false;
	}
	// Set a cookie with the JSESSIONID
	public function SetSessionID($jsessionid) {
		$this->options['session_id'] = trim($jsessionid);
	}
	
	// Set the contents of a cookie jar
	public function SetCookieJar($cookie_jar) {
		$this->options['cookie_jar'] = trim($cookie_jar);
	}
	
	// Set the contents of a cookie jar in the old Netscape format
	public function SetNetscapeCookieJar($cookie_jar) {
		$this->options['ns_cookie_jar'] = trim($cookie_jar);
	}
	
	// Creates a PDF by converting a web page indicated by a URL
	public function CreateFromURL ($url) {
		$u = trim($url);
		if ($this->CheckURL($u)) {
			$this->cleanfilename = str_replace('.','_',$u) . '.pdf';
			$this->options['url'] = $u;
			return $this->CallServer();
		} else {
			throw new Exception ("URL is invalid", 900);
			return false;
		}
	}
	
	// Creates a PDF by converting plain HTML
	public function CreateFromHTML ($html) {
		if (strlen(trim($html))) {
			$this->options['html'] = $html;
			return $this->CallServer();
		} else {
			throw new Exception ("HTML string can not be empty", 901);
			return false;
		}
	}
	
	// Saves the PDF as filename
	public function Save($filename) {
		if($this->tempfile===null) return false;
		else {
			if (copy($this->tempfile,$filename)) return true;
			else {
				throw new Exception ("Not possible to save PDF file", 920);
				return false;
			}
		}
	}
	
	// Displays the PDF as attachment in the user's browser, you can pass a filename that the user will see as downloaded file. This filename should contain the extension .pdf
	public function Display($filename=null) {
		if ($filename !== null)
			return $this->DisplayPDF($filename);
		else
			return $this->DisplayPDF($this->cleanfilename);
	}
	
	// Displays the PDF inline in the user's browser
	public function DisplayInline() {
		return $this->DisplayPDF();
	}
	
	// Returns the PDF as a string
	public function StringValue() {
		return file_get_contents($this->tempfile);
	}
	
	/** Private functions **/
	private function DisplayPDF($filename=null) {
		if($this->tempfile===null) {
			return false;
		}
		else {
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Content-Type: application/pdf');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.filesize($this->tempfile));
	
			if($filename!==null)
				header("Content-Disposition: attachment; filename=\"$filename\"");
	
			readfile($this->tempfile);
			return true;
		}
	}
	
	private function CheckURL($u) {
		if ((strpos($u,'http://') === 0) or (strpos($u,'https://') === 0))
			return filter_var($u, FILTER_VALIDATE_URL);
		else
			return filter_var('http://' . $u, FILTER_VALIDATE_URL);
	}
	
	
	private function CallServer () {
		if( !function_exists("curl_init") || !function_exists("curl_setopt") || !function_exists("curl_exec") || !function_exists("curl_close") ) {
			throw new Exception("cURL must be installed!", 930);
			return false;
		} else { 
			$this->tempfile = tempnam($this->tempdir, 'tmp');
		
			if ((substr($this->tempfile, 0, strlen($this->tempdir)) == $this->tempdir) && ($fp = fopen($this->tempfile, 'w'))) {
				
				$ch = curl_init();
		
				curl_setopt($ch, CURLOPT_URL, $this->endpoint);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $this->options) );
				curl_setopt($ch, CURLOPT_TIMEOUT, $this->curltimeout);
				curl_setopt($ch, CURLOPT_FILE, $fp);
		
				curl_exec($ch);
		
				$errortext = curl_error($ch);
				$errorcode = curl_errno($ch);
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					
				curl_close($ch);
				fclose($fp);
		
				if ($errorcode != 0) {
					throw new Exception('Curl error: ' . $errorcode . ' ' . $errortext, 800+$errorcode);
				} elseif ($httpcode == 200) {
					return true;
				} else {
					throw new Exception('HTTP error: ' . $httpcode, $httpcode);
				}
			} else
				throw new Exception("Access error - your system's web user can not write to $this->tempdir", 940);
				
		}
	}
}
