<?php

class CI_Email {
	//These settings need to be changed to the needs of your SMTP server:
	
	var $smtp_default_from = 'yourusername@your_from_email_address.com';
	var $smtp_default_name = 'Your Name';
	var $useragent		= "CodeIgniter";
	var $mailpath		= "/usr/sbin/sendmail";
	var $protocol		= "smtp";	// mail/sendmail/smtp
	var $smtp_host		= "smtp.your_smtp_server.net";		// SMTP Server.  Example: mail.earthlink.net
	var $smtp_user		= "your_smtp_server_username@your_smtp_server_name.com";		// SMTP Username
	var $smtp_pass		= "your_smtp_server_password";		// SMTP Password
	var $smtp_port		= "587";	// SMTP Port, default: 25
	var $smtp_timeout	= 5;		// SMTP Timeout in seconds
	var $smtp_crypto	= "tls";	// SMTP Encryption. Can be null, tls or ssl.
	var $wordwrap		= TRUE;		// TRUE/FALSE  Turns word-wrap on/off
	var $wrapchars		= "76";		// Number of characters to wrap at.
	var $mailtype		= "html";	// text/html  Defines email formatting
	var $charset		= "iso-8859-1";	// Default char set: iso-8859-1 or us-ascii
	var $multipart		= "mixed";	// "mixed" (in the body) or "related" (separate)
	var $alt_message	= '';		// Alternative message for HTML emails
	var $validate		= FALSE;	// TRUE/FALSE.  Enables email validation
	var $priority		= "3";		// Default priority (1 - 5)
	var $newline		= "\r\n";	// Default newline. "\r\n" or "\n" (Use "\r\n" to comply with RFC 822)
	var $crlf		= "\r\n";	// The RFC 2045 compliant CRLF for quoted-printable is "\r\n".  Apparently some servers,
						// even on the receiving end think they need to muck with CRLFs, so using "\n", while
						// distasteful, is the only thing that seems to work for all environments.
	var $send_multipart	= TRUE;		// TRUE/FALSE - Yahoo does not like multipart alternative, so this is an override.  Set to FALSE for Yahoo.
	var $bcc_batch_mode	= FALSE;	// TRUE/FALSE  Turns on/off Bcc batch feature
	var $bcc_batch_size	= 200;		// If bcc_batch_mode = TRUE, sets max number of Bccs in each batch
	var $_safe_mode		= FALSE;
	var $_subject		= "";
	var $_body		= "";
	var $_finalbody		= "";
	var $_alt_boundary	= "";
	var $_atc_boundary	= "";
	var $_header_str	= "";
	var $_smtp_connect	= "";
	var $_encoding		= "8bit";
	var $_IP			= FALSE;
	var $_smtp_auth		= TRUE;
	var $_replyto_flag	= FALSE;
	var $_debug_msg		= array();
	var $_recipients	= array();
	var $_cc_array		= array();
	var $_bcc_array		= array();
	var $_headers		= array();
	var $_attach_name	= array();
	var $_attach_type	= array();
	var $_attach_disp	= array();
	var $_protocols		= array('mail', 'sendmail', 'smtp');
	var $_base_charsets	= array('us-ascii', 'iso-2022-');	// 7-bit charsets (excluding language suffix)
	var $_bit_depths	= array('7bit', '8bit');
	var $_priorities	= array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');


	/**
	 * Constructor - Sets Email Preferences
	 *
	 * The constructor can be passed an array of config values
	 */
	public function __construct($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}
		else
		{
			$this->_smtp_auth = ($this->smtp_user == '' AND $this->smtp_pass == '') ? FALSE : TRUE;
			$this->_safe_mode = ((boolean)@ini_get("safe_mode") === FALSE) ? FALSE : TRUE;
		}

		//log_message('debug', "Email Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	public function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$method = 'set_'.$key;

				if (method_exists($this, $method))
			
