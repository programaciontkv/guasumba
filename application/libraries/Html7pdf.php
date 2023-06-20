<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Html7pdf {

    var $html;
    var $path;
    var $filename;
    var $paper_size;
    var $orientation;
    
    /**
     * Constructor
     *
     * @access	public
     * @param	array	initialization parameters
     */	
    function Html7pdf($params = array())
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

		ob_start();
		header('Content-Type: text/html; charset=UTF-8');
		 include_once('tcpdf/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF('PDF_PAGE_ORIENTATION', PDF_UNIT,$this->size, true, 'UTF-8', false);
		//$pdf->SetCreator('TechResources');
		//$pdf->SetAuthor('Departamento de Tecnologia');
		//$pdf->SetTitle('Reporte de garantia');
		//$pdf->setHeaderData(PDF_HEADER_LOGO, 10, "TechResources", "Mayorista en Seguridad Electroníca", array(0, 6, 255), array(0, 64, 128));
		$pdf->setFooterData(array(0,64,0), array(0,64,128));
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 20, 10);
		//$pdf->SetHeaderMargin(10);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// ---------------------------------------------------------
		// set default font subsetting mode
		$pdf->AddPage();
		$pdf->writeHTML(utf8_encode($this->html));
		//ob_get_clean();
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
		include_once('tcpdf/tcpdf.php');
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		//$pdf->setFooterData(array(0,0,0), array(0,0,0));
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(0, 0, 0,false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetAutoPageBreak(true,0);  
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// ---------------------------------------------------------
		// set default font subsetting mode
		$pdf->SetFont('calibril', '', 9);
		$pdf->AddPage('P', 'TALON');
		$pdf->writeHTML( utf8_encode($this->html));
		///var_dump($this->html);
		//ob_get_clean();
		$pdf->Output($this->path.$this->filename, 'I');
	    
	}
	
}

/* End of file Html2pdf.php */