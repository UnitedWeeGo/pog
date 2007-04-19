<?php
include_once('IPlugin.php');
class Base64 implements POG_Plugin
{
	public $sourceObject;
	public $argv;
	public $version = '1.0';

	public function Version()
	{
		return $this->version;
	}

	public function Base64($sourceObject, $argv)
	{
		$this->sourceObject = $sourceObject;
		$this->argv = $argv;
	}

	public function Execute()
	{
	}

	public function SetupRender()
	{
		if (isset($_POST['install_base64']) || isset($_POST['uninstall_base64']))
		{
			$this->SetupExecute();
		}
		else
		{
			$out = "This plugin allows you to install and uninstall a base64 custom function to and from your database.
			You can then set \$configuration['db_encoding'] = 1 so that all data is transparently encoded and decoded
			with the minimal overhead possible. Enabling data encoding has quite a few advantages: <br/><br/>
			<a href='http://www.phpobjectgenerator.com/plog/article/5/an-explanation-of-the-escape-function-used-in-pog' target='_blank'>http://www.phpobjectgenerator.com/plog/article/5/an-explanation-of-the-escape-function-used-in-pog</a>
			";
			$out .= "<br/><br/><textarea>BASE64 Status";
			if (Base64::IsBase64FunctionInstalled())
			{
				$out .= "\n\tChecking MySQL function....OK!";
				if (!isset($GLOBALS['configuration']['db_encoding']) || $GLOBALS['configuration']['db_encoding'] != 1)
				{
					$out .=	"\n\tChecking db_encoding status....Failed";
					$out .= "\n\n---------------------------------------------------";
					$out .= "\n\$configuration['db_encoding'] is set to 0. Make sure you set the value to 1 to enable data encoding.";
				}
				else
				{
					$out .=	"\n\tChecking db_encoding status....OK!";
					$out .= "\n\nBASE64 Status...OK!";
					$out .= "\n---------------------------------------------------";
				}
				$out .= "</textarea><div style='padding-left:250px;padding-top:10px;'><input type='submit' value='UNINSTALL' name='uninstall_base64'/></div>";
			}
			else
			{
				$out .= "\n\tChecking MySQL function....NOT INSTALLED";
				$out .=	"\n\tChecking db_encoding status ignored";
				$out .= "\n\n---------------------------------------------------";
				$out .= "\nClick the INSTALL button below to install the base64 function to your database.";
				$out .= "</textarea><div style='padding-left:250px;padding-top:10px;'><input type='submit' value='INSTALL' name='install_base64'/></div>";
			}
			$out .= "<input type='hidden' name='plugins' value='true'/>";
			echo $out;
		}
	}

	public function AuthorPage()
	{
		return null;
	}


	private function SetupExecute()
	{
		$out = '';
		$connection = Database::Connect();
		if (isset($_POST['install_base64']) && isset($_POST['install_base64']) == true)
		{
			$initialData = file_get_contents('../plugins/base64_install.sql');
			$statements = explode('|', $initialData);
			if (sizeof($statements) > 0)
			{
				foreach ($statements as $statement)
				{
					if (trim($statement) != '')
					{
						Database::Query($statement, $connection);
					}
				}
			}
			$out .= "<textarea>INSTALL SUCCESSFUL\n\n";
			$out .= "Make sure you set \$configuration[db_encoding] = 1 in the configuration file.</textarea>";
		}
		else if (isset($_POST['uninstall_base64']) && $_POST['uninstall_base64'] == true)
		{
			$initialData = file_get_contents('../plugins/base64_uninstall.sql');
			$statements = explode('|', $initialData);
			if (sizeof($statements) > 0)
			{
				foreach ($statements as $statement)
				{
					if (trim($statement) != '')
					{
						Database::Query($statement, $connection);
					}
				}
			}
			$out .= "<textarea>UNINSTALL SUCCESSFUL\n\n";
			$out .= "Make sure you set \$configuration[db_encoding] = 0 in the configuration file.</textarea>";
		}
		echo $out;
	}

	public static function IsBase64FunctionInstalled()
	{
		$sql1 = "show function status where Db='".$GLOBALS['configuration']['db']."' and (Name='BASE64_DECODE' or Name='BASE64_ENCODE')";
		$sql2 = "show tables like 'base64_data'";
		$connection = Database::Connect();
		$result = Database::Query($sql1, $connection);
		$connection = Database::Connect();
		$result2 = Database::Query($sql2, $connection);
		$count1 = 0;
		foreach ($result as $row)
		{
			$count1++;
		}
		$count2 = 0;
		foreach($result2 as $row)
		{
			$count2++;
		}
		if ($count1 == 2 && $count2 == 1)
		{
			return true;
		}
		return false;
	}
}
