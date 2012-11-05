<?php
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

//var $today = DaysOfWeek::Sunday;

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
	 * @var int $status;
	 */
	protected $data;
	
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
		$this->status = UkrgbTxState::None;
		
		$input = JFactory::getApplication()->input;
		echo "Get Transaction <br>";
		/*
		 * http://area51.ukriversguidebook.co.uk/?option=com_ukrgb&task=donation
		* &tx=8KD08645R4242620M
		* &st=Completed
		* &amt=1.00
		* &cc=GBP
		* &cm=
		* &item_number=
		* &sig=qpQHTyYtztCgnpUM27DRNcG1v%2ffPoUezS1jFfYTPG%2btJx2EggoqGt5exMziA2G7LsdndWEuTXj%2bsdfoFMz8ULjxqVoAAipxpaT5JchBfQDYkF%2f8p8WYsm%2bGzgeinyGRXI2%2fCV%2fF8v8BM6Pu57Ypl4CJjYxgmZlxrM6Elzdr8eYg%3d
		*
		*/
		$transaction = $input->get ('tx','none');
		$sig = $input->get ('sig'); // not uses yet
		
		if (!isset($transaction))
		{
			// Not a transaction
			return $this->status;
		}
		
		echo $transaction;
		echo "<br>";
		
		
		// Create an instance of a default JHttp object.
		//$http = JHttpFactory::getHttp();  // - not in Platform 11.3?
		$http = new JHttp;
		$options = new JRegistry;
		$transport = new JHttpTransportStream($options);
			
		// Create a 'stream' transport.
		$http = new JHttp($options, $transport);
			
					
		// Prepare the update data.
		$data = array('cmd' => '_notify-synch', 'tx' => $transaction, 'at' => '0jGo0GXewcK0ovOtA4RILRyRHOOxiLuibwG_ABUKd4JHdB-4wte4LgqARkW');
			
		// Invoke the GET request.
		$response = $http->post('https://www.sandbox.paypal.com/cgi-bin/webscr', $data);
			
		if($response->code == 200 AND strpos($response->body, 'SUCCESS') === 0)
		{
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
			
			// put it in the DB
			$this->storeTransaction($data);
			$this->data = $data;
		}
		else
		{
			$this->status = UkrgbTxState::Error;
			echo "<p>That didn't work</p>";
			echo $response->body;
			echo '<br>';
			echo $response->code;
		}
		
		// return the data to the View
		return $this->status;
	}
	
	
	
	/**
	 * Get the name
	 * @return string the First Name of the dorner 
	 */
	
	/**
	public function getMsg()
	{
		echo "Donation Model - get";
		echo "<br>";
		
		//if (!is_array($this->msg))
		//{
		//	$this->msg = array();
		//}
		
		//if (!isset($this->msg[$id]))
		//{
			//request the selected id
			$id = 1;
		
			// Get a TableHelloWorld instance
			$table = $this->getTable();
		
			// Load the message
			$table->load($id);
		
			// Assign the message
			$this->msg[$id] = $table->name;
			
		//}
		
		return $this->msg[$id];
		
	}
	*/
	
	 
	
	public function storeTransaction($ppData)
	{
		$user = JFactory::getUser();
		var_dump ($user);
		echo " Donation Model - Store Transaction";
		$data = array(
				"user_id" => $user->id,
				"mc_gross" => $ppData["mc_gross"],
				"protection_eligibility" => $ppData["protection_eligibility"],
				"payer_id" => $ppData["payer_id"],
				"tax" => $ppData["tax"],
				"payment_date" => $ppData["payment_date"],
				"payment_status" => $ppData["payment_status"],
				"terminal_id" => $ppData["terminal_id"],
				"first_name" => $ppData["first_name"],
				"receipt_reference_number" => $ppData["receipt_reference_number"],
				"mc_fee" => $ppData["mc_fee"],
				"custom" => $ppData["custom"],
				"payer_status" => $ppData["payer_status"],
				"business" => $ppData["business"],
				"quantity" => $ppData["quantity"],
				"payer_email" => $ppData["payer_email"] ,
				"payment_type" => $ppData["payment_type"], 
				"last_name" => $ppData["last_name"],
				"receiver_email" => $ppData["receiver_email"], 
				"store_id" => $ppData["store_id"],
				"payment_fee" => $ppData["payment_fee"], 
				"receiver_id" => $ppData["receiver_id"],
				"pos_transaction_type" => $ppData["pos_transaction_type"], 
				"txn_type" =>  $ppData["txn_type"],
				"item_name" => $ppData["item_name"],
				"num_offers" => $ppData["num_offers"],
				"mc_currency" => $ppData["mc_currency"],
				"item_number" => $ppData["item_number"],
				"residence_country" => $ppData["residence_country"], 
				"handling_amount" => $ppData["transaction_subject"],
				"transaction_subject" => $ppData["transaction_subject"], 
				"payment_gross" => $ppData["payment_gross"],
				"shipping" => 	$ppData["shipping"],
				);
		
		// Get a Table instance
		$table = $this->getTable();
		$table->save($data);
		
	}
	
	
}