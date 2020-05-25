<?php

/**
 * MvcFramework
 *
 * @link        https://github.com/joncarlmatthews/MvcFramework for the canonical source repository
 * @copyright   
 * @link        Coded to the Zend Framework Coding Standard for PHP
 *              http://framework.zend.com/manual/1.12/en/coding-standard.html
 *
 * File format: UNIX
 * File encoding: UTF8
 * File indentation: Spaces (4). No tabs
 *
 */

namespace MvcFramework\Validator
{
	use \MvcFramework\Exception\Exception;

	/**
	 * Is negative integer validator
	 *
	 * @author      Jon Matthews
	 * @category    MvcFramework
	 * @package     Validator
	 */
	class ReCaptcha extends ValidatorAbstract
	{
		const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';
		const VERIFY_TIMEOUT = 5;

		/**
		 * Invalid error message key.
		 *
		 * @static
		 * @access public
		 * @param string
		 */
		const IS_INVALID = 'isInvalid';

		/**
		 * Not configured error message key.
		 *
		 * @static
		 * @access public
		 * @param string
		 */
		const NOT_CONFIGURED = 'notConfigured';

		/**
		 * Error message templates.
		 *
		 * @access protected
		 * @var array
		 */
		protected $_messageTemplates = array(
			self::IS_INVALID => 'The captcha value is invalid',
			self::NOT_CONFIGURED => 'Not configured'
		);

		/**
		 * Returns the current user's remote ip.
		 *
		 * @return string
		 */
		private function _getRemoteIP()
		{
			$client  = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote  = $_SERVER['REMOTE_ADDR'];

			if(filter_var($client, FILTER_VALIDATE_IP)) {
				$ip = $client;
			} else if(filter_var($forward, FILTER_VALIDATE_IP)) {
				$ip = $forward;
			} else {
				$ip = $remote;
			}

			return $ip;
		}

		/**
		 * Makes request to verify a captcha.
		 *
		 * @param string $value
		 *
		 * @return bool
		 * @throws Exception
		 */
		private function _isValidCaptcha($value) {
			if (!isset($this->_options['secret'])) {
				throw new Exception('The "secret" option must be set to a valid Google recaptcha secret key.');
			}

			$remoteIp = isset($this->_options['remoteip']) ? $this->_options['remoteip'] : $this->_getRemoteIP();
			$verifyUrl = isset($this->_options['verifyUrl']) ? $this->_options['verifyUrl'] : self::VERIFY_URL;
			$verifyTimeout = isset($this->_options['verifyTimeout']) ? $this->_options['verifyTimeout'] : self::VERIFY_TIMEOUT;

			$ch = curl_init();
			$opts = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => $verifyUrl,
				CURLOPT_NOBODY => true,
				CURLOPT_TIMEOUT => $verifyTimeout,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => http_build_query(array(
					'secret' => $this->_options['secret'],
					'response' => $value,
					'remoteip' => $remoteIp
				))
			);

			curl_setopt_array($ch, $opts);
			$response = curl_exec($ch);
			$isValid = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200;
			curl_close($ch);

			if (!$isValid) {
				throw new Exception('The captcha verification request failed. Make sure the verification url is valid.');
			}

			$response = json_decode($response, true);
			if (!$response) {
				return false;
			}

			return $response['success'];
		}

		/**
		 * Is valid method.
		 *
		 * @access public
		 * @author  Jon Matthews
		 * @param string $value
		 * @return bool
		 */
		public function isValid($value)
		{
			$isValid = $this->_isValidCaptcha($value);

			if ($isValid) {
				return true;
			} else {
				$this->_addMessage(self::IS_INVALID);
				return false;
			}
		}
	}
}