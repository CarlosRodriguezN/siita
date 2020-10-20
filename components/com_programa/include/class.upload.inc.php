<?php

///////////////////////////////////////////////////////////////////////////////////////////
//
// class.upload.inc.php - Module for uploading files to the server
//
///////////////////////////////////////////////////////////////////////////////////////////
//
//	EXAMPLE:
//	Use: 		$up_file = new upload(
//																							"name_of_the_form_file_field"
//																							[, "the_path_in_which_save_the_file"]
//																							[, the_destination_file_name]
//																						);
//					$up_file->save();
//					if ( $up_file->is_uploaded )
//						echo "<h3>LISTO!!!</h3>";
//
//
///////////////////////////////////////////////////////////////////////////////////////////
 
class upload {

    var $org_file_name;  //	The original file name on client machine
    var $file_ext;    //	The original file extension on client machine
    var $file_type;    //	The mime file type i.e: "image/gif"
    var $file_size;    //	The file received size in bytes.
    var $temp_name;    //	The temporal file name used to store the received file
    var $dest_directory; //	The destination directory name in wich store the uploaded file
    var $dest_file_name; //	The destination file name shall be used to store the file
    var $is_uploaded;   //	Stores the boolean result of uploading process
    var $img2binario;

    //
    //	Constructor
    //

    //	if thirth attribute setted pass only file name, without extension

    function upload( $file_field, $indice, $dest_directory = NULL, $dest_file_name = NULL )
    {
        $this->org_file_name = ( $indice )  ? $_FILES[$file_field]["name"][$indice] 
                                            : $_FILES[$file_field]["name"];

        $file_name_parts = $this->get_file_name_parts($this->org_file_name);

        $this->file_name = $file_name_parts['name'];
        $this->file_ext = $file_name_parts['ext'];

        $this->file_type = ( $indice )  ? $_FILES[$file_field]["type"][$indice] 
                                        : $_FILES[$file_field]["type"];

        $this->file_size = ( $indice )  ? $_FILES[$file_field]["size"][$indice] 
                                        : $_FILES[$file_field]["size"];

        $this->temp_name = ( $indice )  ? $_FILES[$file_field]["tmp_name"][$indice] 
                                        : $_FILES[$file_field]["tmp_name"];

        $this->dest_directory = $dest_directory . "/";
        //  $this->dest_directory = $dest_directory;
        
        $this->dest_file_name = ( $dest_file_name == NULL ) ? $this->org_file_name 
                                                            : $dest_file_name . $this->file_ext;

        if (strstr($this->dest_file_name, " "))
            $this->dest_file_name = str_replace(" ", "_", $this->dest_file_name);

        if (strstr($this->dest_file_name, ","))
            $this->dest_file_name = str_replace(",", "_", $this->dest_file_name);

        if (strstr($this->dest_file_name, "#"))
            $this->dest_file_name = str_replace("#", "_", $this->dest_file_name);

        $this->is_uploaded = ( is_uploaded_file($this->temp_name) ) ? true 
                                                                    : false;
    }

    //
    //	Method for copping the file uploaded to the final destination
    //	Requieres that $dest_directory & $dest_file_name were set first
    //

    function save() {
        $retval = 0;
        if( $this->is_uploaded ) {
            $rm_file = explode( $this->file_ext, $this->dest_file_name );
            $rm_file = $rm_file[0];
            $act_dir = dir( $this->dest_directory );

            while( $entry = $act_dir->read() ){
                $aux = explode(".", $entry);
                $aux = $aux[0];
                if ($aux == $rm_file)
                    unlink($this->dest_directory . "/" . $entry);
            }

            $act_dir->close();

            $destination = $this->dest_directory . "" . $this->dest_file_name;
            $retval = move_uploaded_file($this->temp_name, $destination);

            if ($retval) {
                chmod($destination, 0644);
                $retval = $destination;
            }
        }
        
        return file_exists(  $this->dest_directory . DS . $this->file_name );
    }

    function _save() {
        $retval = 0;
        if ($this->is_uploaded) {
            $rm_file = explode($this->file_ext, $this->dest_file_name);
            $rm_file = $rm_file[0];
            $act_dir = dir($this->dest_directory);

            while ($entry = $act_dir->read()) {
                $aux = explode(".", $entry);
                $aux = $aux[0];
                if ($aux == $rm_file)
                    unlink($this->dest_directory . "/" . $entry);
            }

            $act_dir->close();

            $destination = $this->dest_directory . "" . $this->dest_file_name;
            $retval = move_uploaded_file($this->temp_name, $destination);

            if ($retval) {
                chmod($destination, 0644);
                $retval = 1;
            }
        }

        return $retval;
    }

    //
    //
		//
		
    function get_file_name_parts($org_file_name) {
        $arr_file_parts = explode(".", $org_file_name);
        $num_elements = count($arr_file_parts);
        $retval['ext'] = "." . $arr_file_parts[$num_elements - 1];
        $aux_name = explode($retval['ext'], $org_file_name);
        $retval['name'] = $aux_name[0];

        return( $retval );
    }

}

//	Closes the class definition
?>