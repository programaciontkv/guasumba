<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Html5pdf {

    var $html;
    var $path;
    var $filename;
    var $paper_size;
    var $orientation;
    var $nombre;
    var $identificacion;
    var $direcion;
    var $telefono; 
    var $logo;
    
    
    /**
     * Constructor
     *
     * @access	public
     * @param	array	initialization parameters
     */	
    function Html5pdf($params = array())
    {
        $this->CI =& get_instance();
        
        if (count($params) > 0)
        {
            $this->initialize($params);
        }
    	
        log_message('debug', 'PDF Class Initialized');
    
    }

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */	
    function initialize($params)
	{
        $this->clear();
		if (count($params) > 0)
        {
            foreach ($params as $key => $value)
            {
                if (isset($this->$key))
                {
                    $this->$key = $value;
                }
            }
        }
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set html
	 *
	 * @access	public
	 * @return	void
	 */	
	function html($html = NULL)
	{
        $this->html = $html;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */	
	function folder($path)
	{
        $this->path = $path;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Set path
	 *
	 * @access	public
	 * @return	void
	 */	
	function filename($filename)
	{
        $this->filename = $filename;
	}
	
	// --------------------------------------------------------------------


	/**
	 * Set paper
	 *
	 * @access	public
	 * @return	void
	 */	
	function paper($paper_size = NULL, $orientation = NULL)
	{
        $this->paper_size = $paper_size;
        $this->orientation = $orientation;
	}

	function empresa($nombre,$identificacion,$direcion,$telefono,$logo)
	{
        $this->nombre         = $nombre;
        $this->identificacion = $identificacion;
        $this->direcion       = $direcion;
        $this->telefono       = $telefono;
        $this->logo 		  = $logo;
	}
	
	// --------------------------------------------------------------------


	/**
	 * Create PDF
	 *
	 * @access	public
	 * @return	void
	 */	
	function create($mode = 'download') 
	{
	    
   		if (is_null($this->html)) {
			show_error("HTML is not set");
		}
	    
   		if (is_null($this->path)) {
			show_error("Path is not set");
		}
	    
   		if (is_null($this->paper_size)) {
			show_error("Paper size not set");
		}
		
		if (is_null($this->orientation)) {
			show_error("Orientation not set");
		}
		
		include_once('tcpdf/mypdf.php');
		header('Content-Type: text/html; charset=UTF-8');

		ob_start();

		set_time_limit(0);
		$pdf->SetX(50);
		$pdf->Ln();
		$pdf->SetFont('helvetica', 'B', 8);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->Cell(190, 5, utf8_decode($this->nombre), 0, 0, 'L');
		$pdf->Ln();
		$pdf->Cell(190, 5, $this->identificacion, 0, 0, 'L');
		$pdf->SetFont('helvetica', '', 8);
		$pdf->Ln();
		$pdf->Cell(190, 5, $this->direcion, 0, 0, 'L');
		$pdf->Ln();
		$pdf->SetFont('helvetica', '', 8);
		if ($this->telefono!='') {
			$pdf->Cell(190, 5, "TELEFONO: " . $this->telefono, 0, 0, 'L');
		}
		$pdf->SetX(0);
		$pdf->Cell(190, 5, $pdf->Image('./imagenes/'.$this->logo, 175, 4, 25), 0, 0, 'R');
		$pdf->setY(30);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('TCPDF Example 003');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// ---------------------------------------------------------
		// set default font subsetting mode
        $pdf->SetFont('Calibri', '', 9);
		$pdf->AddPage();
		$pdf->writeHTML(utf8_encode($this->html));
		ob_get_clean();
		$pdf->Output($this->path.$this->filename, 'F');

	}
	
	function output($option) 
	{
	    
   		if (is_null($this->html)) {
			show_error("HTML is not set");
		}
	    
   		
   		if (is_null($this->paper_size)) {
			show_error("Paper size not set");
		}
		
		if (is_null($this->orientation)) {
			show_error("Orientation not set");
		}
	    ob_start();
	    header('Content-Type: text/html; charset=UTF-8');
		include_once('tcpdf/mypdf.php');
		// create new PDF document

		$pdf = new MYPDF('PDF_PAGE_ORIENTATION', PDF_UNIT, $this->paper_size, true, 'UTF-8', false);

		$pdf->setFooterData(array(0,64,0), array(0,64,128));
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 2, 10);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(true);
		//$pdf->SetHeaderMargin(10);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// ---------------------------------------------------------
		// set default font subsetting mode
		//$pdf->setFont('helvetica', '', 14, '', true);

        $pdf->SetFont('Calibril', '', 9);
		$pdf->AddPage('a7','portrait');
		$pdf->writeHTML( utf8_encode($this->html));
		///var_dump($this->html);
		ob_get_clean();
		$pdf->Output($this->path.$this->filename, 'I');
	    
	}
	
}

/* End of file Html2pdf.php */