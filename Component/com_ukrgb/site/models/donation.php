<?php
/**
 * @version		0.1
 * @package		UKRGB - Donation
 * @copyright	Copyright (C) 2012 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

// Transaction State Enumeration
class UkrgbTxState
{
	const None = 0;  // No Tx variable in Request
	const Good = 1; // Good Response with payment data.
	const Error = 2; // Bad response (Response code not 200 or status not SUCSESS)
}

/**
 * UKRGB Donation Model
*/
class UkrgbModelDonation extends JModelItem
{
	/**
	 * @var int $status;
	 */
	protected $status;
	
	/**
	 * @var array $data;
	 */
	protected $data;
	
	/**
	 * @var $responce
	 */
	protected $response;
	
	
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       type    The table type to instantiate
	 * @param       string  A prefix for the table class name. Optional.
	 * @param       array   Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'Donation', $prefix = 'UkrgbTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function getTransactionStatus()
	{
		error_log("---Enter getTransactionStatus");
		$this->status = UkrgbTxState::None;
		
		$input = JFactory::getApplication()->input;
		
		$transaction = $input->get ('tx');
		$sig = $input->get ('sig'); // not uses yet
		$message = $input->get('cm'); // this seems to contain the 'custom' value.
		
		if (!isset($transaction))
		{
			// Not a transaction
			return $this->status;
		}
		
		// Load the IdentityToken from the Component configuration
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('value'));
		$query->from('#__ukrgb_configuration');
		$query->where('name = ' . $db->Quote('paypal_identitytoken'));
		$db->setQuery($query); 

		try {
			$result = $db->loadObject();
			$identityToken = $result->value;					
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
		}		
		
		// Should we use Sandbox.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('value'));
		$query->from('#__ukrgb_configuration');
		$query->where('name = ' . $db->Quote('paypal_sandbox'));
		$db->setQuery($query);
		
		try {
			$result = $db->loadObject();
			$sandbox = $result->value;
							
		} catch (Exception $e) {
			// catch any database errors.
			error_log($e);
		}
		
		if (filter_var($sandbox, FILTER_VALIDATE_BOOLEAN)){
			$payPalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}else{
			$payPalURL = 'https://www.paypal.com/cgi-bin/webscr';
		}
		error_log('---'.$payPalURL);
		
		
		// Create an instance of a default JHttp object.
		//$http = JHttpFactory::getHttp();  // - not in Platform 11.3?
		$http = new JHttp;
		$options = new JRegistry;
		$transport = new JHttpTransportStream($options);
			
		// Create a 'stream' transport.
		$http = new JHttp($options, $transport);
					
		// Prepare the update data.
		$data = array('cmd' => '_notify-synch', 'tx' => $transaction, 'at' => $identityToken);
			
		// Invoke the GET request.
		$response = $http->post($payPalURL, $data);
		$this->responce = $response;
		if($response->code == 200 AND strpos($response->body, 'SUCCESS') === 0)
		{
			error_log("---Status Good");
			//error_log("---".$response->body);
			// Good response
			$this->status = UkrgbTxState::Good;
			
			// Remove SUCCESS part (7 characters long)
			$data = substr($response->body,7);
		
			// URL decode
			$data = urldecode($data);
				
			// Turn into associative array
			preg_match_all('/^([^=\s]++)=(.*+)/m', $data, $m, PREG_PATTERN_ORDER);
			$data = array_combine($m[1], $m[2]);
					
			// Fix character encoding if different from UTF-8 (in my case)
			if(isset($data['charset']) AND strtoupper($data['charset']) !== 'UTF-8')
			{
				foreach($data as $key => &$value)
				{
					$value = mb_convert_encoding($value, 'UTF-8', $data['charset']);
				}
				$data['charset'] = 'UTF-8';
			}
			
			$data['tx'] = $transaction;
			// put it in the DB
			$this->storeTransaction($data);
			$this->data = $data;
		}
		else
		{
			error_log("---Status Error");
			error_log("---Responce: ".$response->code);
			//error_log("---".$response->body);
			$user = JFactory::getUser();
			$this->status = UkrgbTxState::Error;
			$data = array(
					'tx' => $transaction,
					'user_id' => $user->id,);
			$this->data = $data;
		}
		
		// return the data to the View
		return $this->status;
	}
	
	/**
	 * Get the amount
	 * @return double the amount of the donation
	 */
	public function getAmount()
	{
		return $this->data['mc_gross'];
	}
	
	/**
	 * Get the name
	 * @return string the First Name of the dorner 
	 */
	public function getName()
	{
		return $this->data['first_name'];
	}
	
	/**
	 * Get the payment status
	 * @return string payment status
	 */
	public function getPaymentStatus()
	{
		return $this->data['payment_status'];
	}
	
	/**
	 * Get Http Responce Code
	 */
	public function getResponceCode()
	{
		return $this->responce->code;
	}
	
	/**
	 * Get PayPal Error Mgs
	 */
	public function getErrorMsg()
	{
		return $this->responce->body;
	}
	/**
	 * Get return Forum id
	 */
	public function getForumId()
	{
		$bits = explode(":", $this->data['item_name']);
		return $bits[0];
	}
	/**
	 * Get return Forum Name
	 */
	public function getForumName()
	{
		$bits = explode(":", $this->data['item_name']);
		return $bits[1];
	}
	
	
	/**
	 * Store transaction data in the Database
	 * @return void 
	*/
	private function storeTransaction($ppData)
	{
		error_log("---storeTransaction");
		// Get a Table instance
		$table = $this->getTable();
		
		// Check this is not caused by a refresh i.e a repeat for the same transaction
		$db =  $table->getDbo();
		$query = "SELECT ".$db->nameQuote('tx')." FROM ".$db->nameQuote('#__ukrgb_doantion')." WHERE ".$db->nameQuote('tx')." = ".$db->quote($ppData["tx"]).";";
		$db->setQuery($query);
		$indb = $db->loadResult();
		
		if (!isset($indb))
		{
			$user = JFactory::getUser();
			$data = array(
					"tx" => $ppData["tx"],
					"user_id" => $user->id,
					"phpBB_user_id" => "",
					"mc_gross" => $ppData["mc_gross"],
					"protection_eligibility" => $ppData["protection_eligibility"],
					"payer_id" => $ppData["payer_id"],
					"tax" => $ppData["tax"],
					"payment_date" => $ppData["payment_date"],
					"payment_status" => $ppData["payment_status"],
					"first_name" => $ppData["first_name"],
					"mc_fee" => $ppData["mc_fee"],
					"custom" => $ppData["custom"],
					"payer_status" => $ppData["payer_status"],
					"business" => $ppData["business"],
					"quantity" => $ppData["quantity"],
					"payer_email" => $ppData["payer_email"] ,
					"payment_type" => $ppData["payment_type"], 
					"last_name" => $ppData["last_name"],
					"receiver_email" => $ppData["receiver_email"], 
					"payment_fee" => $ppData["payment_fee"], 
					"receiver_id" => $ppData["receiver_id"],
					"txn_type" =>  $ppData["txn_type"],
					"item_name" => $ppData["item_name"],
					"mc_currency" => $ppData["mc_currency"],
					"item_number" => $ppData["item_number"],
					"residence_country" => $ppData["residence_country"], 
					"handling_amount" => $ppData["transaction_subject"],
					"transaction_subject" => $ppData["transaction_subject"], 
					"payment_gross" => $ppData["payment_gross"],
					"shipping" => 	$ppData["shipping"],
					);
	
	
			$table->save($data);
		}		
	}
	
	
}