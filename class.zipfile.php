<?php

/**
 * Class to dynamically create a zip file (archive)
 *
 * @author Rochak Chauhan
 */

class createZip  {  
	var $compressedData = array(); 
	var $centralDirectory = array(); // central directory   
	var $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
	var $oldOffset = 0;

	/**
	 * Function to create the directory where the file(s) will be unzipped
	 *
	 * @param $directoryName string 
	 *
	 */
	
	function addDirectory($directoryName) {
		$directoryName = str_replace("\\", "/", $directoryName);  

		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x0a\x00";    
		$feedArrayRow .= "\x00\x00";    
		$feedArrayRow .= "\x00\x00";    
		$feedArrayRow .= "\x00\x00\x00\x00"; 

		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("v", strlen($directoryName) ); 
		$feedArrayRow .= pack("v", 0 ); 
		$feedArrayRow .= $directoryName;  

		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 

		$this -> compressedData[] = $feedArrayRow;
		
		$newOffset = strlen(implode("", $this->compressedData));

		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x0a\x00";    
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x00\x00\x00\x00"; 
		$addCentralRecord .= pack("V",0); 
		$addCentralRecord .= pack("V",0); 
		$addCentralRecord .= pack("V",0); 
		$addCentralRecord .= pack("v", strlen($directoryName) ); 
		$addCentralRecord .= pack("v", 0 ); 
		$addCentralRecord .= pack("v", 0 ); 
		$addCentralRecord .= pack("v", 0 ); 
		$addCentralRecord .= pack("v", 0 ); 
		$ext = "\x00\x00\x10\x00";
		$ext = "\xff\xff\xff\xff";  
		$addCentralRecord .= pack("V", 16 ); 

		$addCentralRecord .= pack("V", $this -> oldOffset ); 
		$this -> oldOffset = $newOffset;

		$addCentralRecord .= $directoryName;  

		$this -> centralDirectory[] = $addCentralRecord;  
	}	 
	
	/**
	 * Function to add file(s) to the specified directory in the archive 
	 *
	 * @param $directoryName string 
	 *
	 */
	
	function addFile($data, $directoryName)   {
 
		$directoryName = str_replace("\\", "/", $directoryName);  
	
		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x14\x00";    
		$feedArrayRow .= "\x00\x00";    
		$feedArrayRow .= "\x08\x00";    
		$feedArrayRow .= "\x00\x00\x00\x00"; 

		$uncompressedLength = strlen($data);  
		$compression = crc32($data);  
		$gzCompressedData = gzcompress($data);  
		$gzCompressedData = substr( substr($gzCompressedData, 0, strlen($gzCompressedData) - 4), 2); 
		$compressedLength = strlen($gzCompressedData);  
		$feedArrayRow .= pack("V",$compression); 
		$feedArrayRow .= pack("V",$compressedLength); 
		$feedArrayRow .= pack("V",$uncompressedLength); 
		$feedArrayRow .= pack("v", strlen($directoryName) ); 
		$feedArrayRow .= pack("v", 0 ); 
		$feedArrayRow .= $directoryName;  

		$feedArrayRow .= $gzCompressedData;  

		$feedArrayRow .= pack("V",$compression); 
		$feedArrayRow .= pack("V",$compressedLength); 
		$feedArrayRow .= pack("V",$uncompressedLength); 

		$this -> compressedData[] = $feedArrayRow;

		$newOffset = strlen(implode("", $this->compressedData));

		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x14\x00";    
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x08\x00";    
		$addCentralRecord .="\x00\x00\x00\x00"; 
		$addCentralRecord .= pack("V",$compression); 
		$addCentralRecord .= pack("V",$compressedLength); 
		$addCentralRecord .= pack("V",$uncompressedLength); 
		$addCentralRecord .= pack("v", strlen($directoryName) ); 
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("V", 32 ); 

		$addCentralRecord .= pack("V", $this -> oldOffset ); 
		$this -> oldOffset = $newOffset;

		$addCentralRecord .= $directoryName;  

		$this -> centralDirectory[] = $addCentralRecord;  
	}

	/**
	 * Fucntion to return the zip file
	 *
	 * @return zipfile (archive)
	 */

	function getZippedfile() { 

		$data = implode("", $this -> compressedData);  
		$controlDirectory = implode("", $this -> centralDirectory);  

		return   
			$data.  
			$controlDirectory.  
			$this -> endOfCentralDirectory.  
			pack("v", sizeof($this -> centralDirectory)).     
			pack("v", sizeof($this -> centralDirectory)).     
			pack("V", strlen($controlDirectory)).             
			pack("V", strlen($data)).                
			"\x00\x00";                             
	}

	/**
	 *
	 * Function to force the download of the archive as soon as it is created
	 *
	 * @param archiveName string - name of the created archive file
	 */

	function forceDownload($archiveName) {
		$headerInfo = '';
		 
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		// Security checks
		if( $archiveName == "" ) {
			echo "<html><title>Public Photo Directory - Download </title><body><BR><B>ERROR:</B> The download file was NOT SPECIFIED.</body></html>";
			exit;
		} 
		elseif ( ! file_exists( $archiveName ) ) {
			echo "<html><title>Public Photo Directory - Download </title><body><BR><B>ERROR:</B> File not found.</body></html>";
			exit;
		}

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=".basename($archiveName).";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($archiveName));
		readfile("$archiveName");
		
	 }

}
?>

<?
/*

Zip file creation class
makes zip files on the fly...

use the functions add_dir() and add_file() to build the zip file;
see example code below

by Eric Mueller
http://www.themepark.com

v1.1 9-20-01
  - added comments to example

v1.0 2-5-01

initial version with:
  - class appearance
  - add_file() and file() methods
  - gzcompress() output hacking
by Denis O.Philippov, webmaster@atlant.ru, http://www.atlant.ru

*/

// official ZIP file format: http://www. // pkware.com/appnote.txt
/*
class zipfile  
{  

    var $datasec = array(); // array to store compressed data
    var $ctrl_dir = array(); // central directory   
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
    var $old_offset = 0;

    function add_dir($name)   

    // adds "directory" to archive - do this before putting any files in directory!
    // $name - name of directory... like this: "path/"
    // ...then you can add files using add_file with names like "path/file.txt"
    {  
        $name = str_replace("\\", "/", $name);  

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x0a\x00";    // ver needed to extract
        $fr .= "\x00\x00";    // gen purpose bit flag
        $fr .= "\x00\x00";    // compression method
        $fr .= "\x00\x00\x00\x00"; // last mod time and date

        $fr .= pack("V",0); // crc32
        $fr .= pack("V",0); //compressed filesize
        $fr .= pack("V",0); //uncompressed filesize
        $fr .= pack("v", strlen($name) ); //length of pathname
        $fr .= pack("v", 0 ); //extra field length
        $fr .= $name;  
        // end of "local file header" segment

        // no "file data" segment for path

        $crc = "";
        $c_len = "";
        $unc_len = "";
        
        // "data descriptor" segment (optional but necessary if archive is not served as file)
        $fr .= pack("V",$crc); //crc32
        $fr .= pack("V",$c_len); //compressed filesize
        $fr .= pack("V",$unc_len); //uncompressed filesize

        // add this entry to array
        $this -> datasec[] = $fr;

        $new_offset = strlen(implode("", $this->datasec));

        // ext. file attributes mirrors MS-DOS directory attr byte, detailed
        // at http://support.microsoft.com/support/kb/articles/Q125/0/19.asp

        // now add to central record
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .="\x00\x00";    // version made by
        $cdrec .="\x0a\x00";    // version needed to extract
        $cdrec .="\x00\x00";    // gen purpose bit flag
        $cdrec .="\x00\x00";    // compression method
        $cdrec .="\x00\x00\x00\x00"; // last mod time & date
        $cdrec .= pack("V",0); // crc32
        $cdrec .= pack("V",0); //compressed filesize
        $cdrec .= pack("V",0); //uncompressed filesize
        $cdrec .= pack("v", strlen($name) ); //length of filename
        $cdrec .= pack("v", 0 ); //extra field length   
        $cdrec .= pack("v", 0 ); //file comment length
        $cdrec .= pack("v", 0 ); //disk number start
        $cdrec .= pack("v", 0 ); //internal file attributes
        $ext = "\x00\x00\x10\x00";
        $ext = "\xff\xff\xff\xff";  
        $cdrec .= pack("V", 16 ); //external file attributes  - 'directory' bit set

        $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header
        $this -> old_offset = $new_offset;

        $cdrec .= $name;  
        // optional extra field, file comment goes here
        // save to array
        $this -> ctrl_dir[] = $cdrec;  

         
    }


    function add_file($data, $name)   

    // adds "file" to archive   
    // $data - file contents
    // $name - name of file in archive. Add path if your want

    {  
        $name = str_replace("\\", "/", $name);  
        //$name = str_replace("\\", "\\\\", $name);

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00";    // ver needed to extract
        $fr .= "\x00\x00";    // gen purpose bit flag
        $fr .= "\x08\x00";    // compression method
        $fr .= "\x00\x00\x00\x00"; // last mod time and date

        $unc_len = strlen($data);  
        $crc = crc32($data);  
        $zdata = gzcompress($data);  
        $zdata = substr( substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
        $c_len = strlen($zdata);  
        $fr .= pack("V",$crc); // crc32
        $fr .= pack("V",$c_len); //compressed filesize
        $fr .= pack("V",$unc_len); //uncompressed filesize
        $fr .= pack("v", strlen($name) ); //length of filename
        $fr .= pack("v", 0 ); //extra field length
        $fr .= $name;  
        // end of "local file header" segment
         
        // "file data" segment
        $fr .= $zdata;  

        // "data descriptor" segment (optional but necessary if archive is not served as file)
        $fr .= pack("V",$crc); //crc32
        $fr .= pack("V",$c_len); //compressed filesize
        $fr .= pack("V",$unc_len); //uncompressed filesize

        // add this entry to array
        $this -> datasec[] = $fr;

        $new_offset = strlen(implode("", $this->datasec));

        // now add to central directory record
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .="\x00\x00";    // version made by
        $cdrec .="\x14\x00";    // version needed to extract
        $cdrec .="\x00\x00";    // gen purpose bit flag
        $cdrec .="\x08\x00";    // compression method
        $cdrec .="\x00\x00\x00\x00"; // last mod time & date
        $cdrec .= pack("V",$crc); // crc32
        $cdrec .= pack("V",$c_len); //compressed filesize
        $cdrec .= pack("V",$unc_len); //uncompressed filesize
        $cdrec .= pack("v", strlen($name) ); //length of filename
        $cdrec .= pack("v", 0 ); //extra field length   
        $cdrec .= pack("v", 0 ); //file comment length
        $cdrec .= pack("v", 0 ); //disk number start
        $cdrec .= pack("v", 0 ); //internal file attributes
        $cdrec .= pack("V", 32 ); //external file attributes - 'archive' bit set

        $cdrec .= pack("V", $this -> old_offset ); //relative offset of local header
//      &n // bsp; echo "old offset is ".$this->old_offset.", new offset is $new_offset<br>";
        $this -> old_offset = $new_offset;

        $cdrec .= $name;  
        // optional extra field, file comment goes here
        // save to central directory
        $this -> ctrl_dir[] = $cdrec;  
    }

    function file() { // dump out file   
        $data = implode("", $this -> datasec);  
        $ctrldir = implode("", $this -> ctrl_dir);  

        return   
            $data.  
            $ctrldir.  
            $this -> eof_ctrl_dir.  
            pack("v", sizeof($this -> ctrl_dir)).     // total # of entries "on this disk"
            pack("v", sizeof($this -> ctrl_dir)).     // total # of entries overall
            pack("V", strlen($ctrldir)).             // size of central dir
            pack("V", strlen($data)).                 // offset to start of central dir
            "\x00\x00";                             // .zip file comment length
    }
}  
*/
?> 