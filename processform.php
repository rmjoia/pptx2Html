 <?php
 
  // change this to your temp path
	$upload_dir = "./tmp/pptxImport/";
	$upload_file = $upload_dir . basename($_FILES['thefile']['name']);
	
	$pptxFile = $_FILES['thefile']['name'];
	
	if (copy($_FILES['thefile']['tmp_name'], $upload_file))
	{
		echo "Success on Import!";
		echo "<p>File Name: " . $pptxFile . "</p>"; 
	 
		function pptx_to_text ($input_file)
		{
			$zip_handle = new ZipArchive();
			$output_text = "";
			
			if(true === $zip_handle->open($input_file))
			{
				$slide_number = 1; // loop trough number of slides in the pptx file
					while(($xml_index = $zip_handle->locateName("ppt/slides/slide".$slide_number.".xml")) !== false)
					{
						$xml_datas = $zip_handle->getFromIndex($xml_index);
						$xml_handle = DomDocument::load($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
						$output_text .= strip_tags($xml_handle->saveXML());
						$slide_number++;
					}
					if ($slide_number == 1)
					{
						$output_text .= "";
					}
					
					$zip_handle->close();
			}
			else
			{
				$output_text .= "There was an error, couldn't open the file";
			}
			return $output_text;
		}
		
		echo pptx_to_text($upload_dir . $pptxFile); 

	}
	else  // throw error
	{
		echo "There was an error while processing the file!";
		print_r($_FILES);
	}
?> 
