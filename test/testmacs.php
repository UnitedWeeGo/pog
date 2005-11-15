<?
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `macs` (
	`macsid` int(11) auto_increment,
	`userid` INT,
	`mac` VARCHAR(255),
	`status` VARCHAR(255),
	`sort_id` INT, PRIMARY KEY  (`macsid`));
*/

/**
* Macs class with integrated CRUD methods.
* @author Php Object Generator
* @version 1.5 rev2
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://phpobjectgenerator.com/?language=php4&wrapper=pog&objectName=macs&attributeList=array+%28%0A++0+%3D%3E+%27userid%27%2C%0A++1+%3D%3E+%27mac%27%2C%0A++2+%3D%3E+%27status%27%2C%0A++3+%3D%3E+%27sort_id%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27INT%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27INT%27%2C%0A%29
*/
class macs
{
	var $macsId;
	var $userid;
	var $mac;
	var $status;
	var $sort_id;
	
	
	function macs($userid='', $mac='', $status='', $sort_id='')
	{
		$this->userid = $userid;
		$this->mac = $mac;
		$this->status = $status;
		$this->sort_id = $sort_id;
	}
	
	
	/**
	* Gets object from database
	* @param integer $macsId 
	* @return object $macs
	*/
	function Get($macsId)
	{
		$Database = new DatabaseConnection();
		$query = "select * from `macs` where `macsid`='".$macsId."' LIMIT 1";
		$Database->Query($query);
		$this->macsId = $Database->Result(0,"macsid");
		$this->userid = $Database->Unescape($Database->Result(0,"userid"));
		$this->mac = $Database->Unescape($Database->Result(0,"mac"));
		$this->status = $Database->Unescape($Database->Result(0,"status"));
		$this->sort_id = $Database->Unescape($Database->Result(0,"sort_id"));
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param string $field 
	* @param string $comparator 
	* @param string $fieldValue 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @return array $macsList
	*/
	function GetmacsList($field,$comparator,$fieldValue,$sortBy="",$ascending=true)
	{
		
		$macsList = Array();
		$Database = new DatabaseConnection();
		$query = "select macsid from macs where `".$field."`".$comparator."'".$Database->Escape($fieldValue)."'";
		$Database->Query($query);
		for ($i=0; $i < $Database->Rows(); $i++)
		{
			$macs = new macs();
			$macs->Get($Database->Result($i,"macsid"));
			$macsList[] = $macs;
		}
		switch(strtolower($sortBy))
		{
			case strtolower("userid"):
				usort($macsList, array("macs","ComparemacsByuserid"));
				if (!$ascending)
				{
					$macsList = array_reverse($macsList);
				}
			break;
			case strtolower("mac"):
				usort($macsList, array("macs","ComparemacsBymac"));
				if (!$ascending)
				{
					$macsList = array_reverse($macsList);
				}
			break;
			case strtolower("status"):
				usort($macsList, array("macs","ComparemacsBystatus"));
				if (!$ascending)
				{
					$macsList = array_reverse($macsList);
				}
			break;
			case strtolower("sort_id"):
				usort($macsList, array("macs","ComparemacsBysort_id"));
				if (!$ascending)
				{
					$macsList = array_reverse($macsList);
				}
			break;
			case "":
			default:
			break;
		}
		return $macsList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $macsId
	*/
	function Save()
	{
		$Database = new DatabaseConnection();
		$query = "select macsid from `macs` where `macsid`='".$this->macsId."' LIMIT 1";
		$Database->Query($query);
		if ($Database->Rows() > 0)
		{
			$query = "update `macs` set 
			`userid`='".$Database->Escape($this->userid)."', 
			`mac`='".$Database->Escape($this->mac)."', 
			`status`='".$Database->Escape($this->status)."', 
			`sort_id`='".$Database->Escape($this->sort_id)."' where `macsid`='".$this->macsId."'";
		}
		else
		{
			$query = "insert into `macs` (`userid`, `mac`, `status`, `sort_id` ) values (
			'".$Database->Escape($this->userid)."', 
			'".$Database->Escape($this->mac)."', 
			'".$Database->Escape($this->status)."', 
			'".$Database->Escape($this->sort_id)."' )";
		}
		$Database->InsertOrUpdate($query);
		if ($this->macsId == "")
		{
			$this->macsId = $Database->GetCurrentId();
		}
		return $this->macsId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $macsId
	*/
	function SaveNew()
	{
		$this->macsId='';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$Database = new DatabaseConnection();
		$query = "delete from `macs` where `macsid`='".$this->macsId."'";
		return $Database->Query($query);
	}
	
	
	/**
	* private function to sort an array of macs by userid
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	function ComparemacsByuserid($macs1, $macs2)
	{
		return strcmp(strtolower($macs1->userid), strtolower($macs2->userid));
	}
	
	
	/**
	* private function to sort an array of macs by mac
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	function ComparemacsBymac($macs1, $macs2)
	{
		return strcmp(strtolower($macs1->mac), strtolower($macs2->mac));
	}
	
	
	/**
	* private function to sort an array of macs by status
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	function ComparemacsBystatus($macs1, $macs2)
	{
		return strcmp(strtolower($macs1->status), strtolower($macs2->status));
	}
	
	
	/**
	* private function to sort an array of macs by sort_id
	* @return +1 if attribute1 > attribute2, 0 if attribute1==attribute2 and -1 if attribute1 < attribute2
	*/
	function ComparemacsBysort_id($macs1, $macs2)
	{
		return strcmp(strtolower($macs1->sort_id), strtolower($macs2->sort_id));
	}
}
?>