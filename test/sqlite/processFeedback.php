<?
include "class.feedback.php";
include "configuration.php";

$feedback = new Feedback();
$feedback->name = $_POST['name'];
$feedback->email = $_POST['email'];
$feedback->comments = $_POST['comments'];
if ($feedback->Save())
{
	echo "feedback saved successfully!";
}
else
{
	echo "feedback not saved";
}

?>