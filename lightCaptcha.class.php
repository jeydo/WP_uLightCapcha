<?php
/**
* 
*/
class LightCaptcha
{
	
	var $id;
	var $key = '$!DsBMake_It_Smooth$!';

	function __construct()
	{
		add_action('comment_form', array("LightCaptcha", "loadBox"));
		add_action('comment_post', array("LightCaptcha", "postComment"));
		session_start();
		$this->id = session_id();
	}

	public function generateToken()
	{
		return substr(md5($this->id . $this->key), 4, 16);
	}

	public function validateToken($token)
	{
		return $token == $this->generateToken();
	}

	public function postComment($id)
	{
		global $lightCaptcha;
		if ($lightCaptcha->validateToken($_POST['lightCaptchaToken'])) {
			return $id;
		}
		wp_set_comment_status($id, 'spam');
		exit;
	}

	public function loadBox()
	{
		global $lightCaptcha;
		echo '
			<input type="hidden" name="lightCaptchaToken" id ="lightCaptchaToken" value="" />
			<script type="text/javascript">
				//<![CDATA[
					document.getElementById(\'lightCaptchaToken\').value = \'' . $lightCaptcha->generateToken() . '\';
				//]]>
			</script>
		';
	}
}