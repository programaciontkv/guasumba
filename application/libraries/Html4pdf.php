<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Html4pdf {

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
    function Html4pdf($params = array())
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
	    
	    //Load the DOMPDF libary
	    require_once("dompdf/dompdf_config.inc.php");
	    
	    $dompdf = new DOMPDF();
	    $dompdf->load_html($this->html);
	    $dompdf->set_paper($this->paper_size, $this->orientation);
	    $dompdf->render();
	    
	    if($mode == 'save') {
    	    $this->CI->load->helper('file');
		    if(write_file($this->path.$this->filename, $dompdf->output())) {
		    	return $this->path.$this->filename;
		    } else {
				show_error("PDF could not be written to the path");
		    }
		} else {
			
			if($dompdf->stream($this->filename)) {
				return TRUE;
			} else {
				show_error("PDF could not be streamed");
			}
	    }
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
	    

	    //Load the DOMPDF libary
	    require_once("dompdf/dompdf_config.inc.php");
	    
	    $dompdf = new DOMPDF();

		$dompdf = new Dompdf();
	    $dompdf->load_html($this->html);
	    $dompdf->set_paper($this->paper_size, $this->orientation);
	    $dompdf->render();

		$canvas = $dompdf->get_canvas();
		$footer = $canvas->open_object();
		$w = $canvas->get_width();
		$h = $canvas->get_height();
		///HEADER

		$header = $canvas->open_object();
	 	//$font = Font_Metrics::get_font("courier", "B");
	 	$font = Font_Metrics::get_font("calibri", "normal");
		$date = date("Y-m-d H:i:s");
		$v =  base_url().'imagenes/'.$this->logo;
		$canvas->page_text(35, 15,$this->nombre, $font, 10, array(0, 0, 0));
		$canvas->page_text(35, 28, $this->identificacion, $font, 10, array(0, 0, 0));
		$canvas->page_text(35, 41, $this->direcion, $font, 10, array(0, 0, 0));
		if ($this->telefono!='') {
			$canvas->page_text(35, 53,'TELÉFONO: '.$this->telefono, $font, 10, array(0, 0, 0));
		}
		

		$canvas->close_object();
		$canvas->add_object($header, "all");

		///FOOTER
		$font = Font_Metrics::get_font("calibri", "normal");
		$canvas->page_text($w-120,$h-28,date("Y-m-d H:i:s"), $fontBold,10);
		$canvas->page_text($w-210,$h-28,"PÁGINA {PAGE_NUM} DE {PAGE_COUNT}", $fontBold,10);
		if ($this->orientation=='landscape') {
			$canvas->page_text($w-800,$h-28,"SISTEMA DE FACTURACIÓN ELECTRÓNICA TIKVASYST  ", $fontBold,10);
		}else{
			$canvas->page_text($w-550,$h-28,"SISTEMA DE FACTURACIÓN ELECTRÓNICA TIKVASYST  ", $fontBold,10);
		}
		


	    $dompdf->stream($this->filename,$option);
	    
	}
	
}

/* End of file Html2pdf.php */