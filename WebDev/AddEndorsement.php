<?php 

	class addendorsement{
	function _construct() {
	}
	
	public function create() {
		$args = func_get_args();
		$args = $args[0];
		$argsc = count($args);
		if ($argsc === 25) {
			return $this->_create($args[0], $args[1], $args[2],$args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9], $args[10], $args[11], $args[12],
					              $args[13], $args[14], $args[15], $args[16], $args[17], $args[18], $args[19], $args[20], $args[21], $args[22], $args[23], $args[24]);
		} else {
			return -1;
		}
	}
	private function _create($company_id, $branch_id, $department_id, $income_class_id, $account_executive_id, $billing_to, $client_id, $insurer_id, $insurer_branch_id,
			              $co_broker_id, $co_broker_branch_id, $entity_name, $entity_address, $manual_or_selection, $policy_policy_id, $risk_type_id, $premium,
			              $tax_number, $currency_id, $exchange_rate, $revenue, $gst, $total_revenue, $file, $file_path ) {
		
	
	if (!isset($_SESSION['u_id'])) {
			// unautorized: not logged in
			return -2;
		}
	
		
		$file_name = [ ];
		$file_details = [ ];
		if ($file != null) {
			$file_name[] = $GLOBALS['ctrl']->execut('file', 'check_file', $file);
			for($i = 0; $i < count($file['name']); $i++) {
				$file_details[] = $GLOBALS['ctrl']->execut('file', 'upload_file', (int)$client_id, 'addendorsement', $file_name[0]['name'][$i], $file_name[0]['tmp_name'][$i]);
			}
		}
		
		if ($file_name[0] == -103) {
			return -103;  //file type not allowed
		} elseif ($file_name[0] == -113) {
			return -113; //exceed file size 4 mB
		} else {
			$file_name = '{' . implode(",", $file_name[0]['name']) . '}';
			$file_details = '{' . implode(",", $file_details) . '}';
		}
      // var_dump($file_name); exit();
		global $ctrl;
		$ctrl->instances->di->set('db');
		$db = $ctrl->instances->db;
	
		$query = <<<EOT
 
  INSERT INTO
    revenue_accounting.cpu_endorsement(company_id, branch_id, department_id, income_class_id, account_executive_id, billing_to, 
				                       client_id, insurer_id, insurer_branch_id, co_broker_id, co_broker_branch_id, entity_name,
				                       entity_address, manual_or_selection, policy_id, risk_type_id, premium, gst_or_tax_number, currency_id,
				                       exchange_rate, revenue, gst_or_tax, total_revenue, file_names, file_paths, created_by)
   VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
EOT;
		$bindValues = [
						$company_id,
						$branch_id,
						$department_id,
						$income_class_id,
						$account_executive_id,
						$billing_to,
						$client_id,
						$insurer_id,
						$insurer_branch_id,
				        $co_broker_id,
						$co_broker_branch_id,
						$entity_name,
						$entity_address,
						$manual_or_selection,
				        $policy_policy_id,
						$risk_type_id,
						$premium,
						$tax_number=2020,
						$currency_id,
						$exchange_rate,
						$revenue,
						$gst,
						$total_revenue,
				        $file_name,
						$file_details,
						$_SESSION['u_id']
		];
      var_dump($bindValues);
	
      
		if ($db->init(5, 0) === 1) {
			if ($db->prepare($query) === 1) {
				if ($db->bindArray($bindValues) === 1) {
					if ($db->perform() === 1) {
						$data = $db->data();
						$db->close();
					} else {
						return -20;
					}
				} else {
					return -20;
				}
			} else {
				return -20;
			}
		} else {
			return -20;
		}
	}
	
	/*
	 * Function to read data from invoice.
	 */
	public function read() {
		$args = func_get_args();
		$args = $args[0];
		$argsc = count($args);
		return $this->_read();
	}
	
	private function _read() {
		if (!isset($_SESSION['u_id'])) {
			/*
			 * not logged in
			 */
			return -2;
		}

		global $ctrl;
		$ctrl->instances->di->set('db');
		$db = $ctrl->instances->db;
		
		$query = <<<EOT
     SELECT
       id, invoice_number
     FROM
     revenue_accounting.invoice

EOT;
		
		if ($db->init(5, 0) === 1) {
			if ($db->prepare($query) === 1) {
					if ($db->perform() === 1) {
						$data = $db->data();
						$db->close();
						return $data;
					} else {
						return -20;
					}
				} else {
					return -20;
				}
			} else {
				return -20;
			}
		
	}
	
	
	public function getSingle() {
		$args = func_get_args();
		$args = $args[0];
		$argsc = count($args);
		if ($argsc === 1) {
			return $this->_getSingle($args[0]);
		} else {
			return -1;
		}
		
	}
	private function _getSingle($id) {
		if (!isset($_SESSION['u_id'])) {
			/*
			 * not logged in
			 */
			return -2;
		}
	
		global $ctrl;
		$ctrl->instances->di->set('db');
		$db = $ctrl->instances->db;
	
// 		$query = 'SELECT
//               client_id, policy_id, company_id, branch_id, department_id, income_class_id, 
//      FROM
//      revenue_accounting.invoice
// 	WHERE id = ?			
	
	
	
	  $query = 'SELECT
         cgi.client_name,
	  	 ppd.policy_number,
	  	 ppd.policy_inception_date,
	  	 ppd.policy_expiry_date,
	  	 	
	  		
	  		
        FROM
          revenue_accounting.invoice AS inv
        LEFT JOIN
          client.general_information AS cgi
        ON 
	  		inv.client_id = cgi.id
	  	LEFT JOIN
	  	  policy.policy_details AS ppd
	  	ON 	
	  		inv.policy_id = ppd.id
	  	LEFT JOIN
	  	  
	  	  
	  	  
	  	  
	  		
        WHERE
        inv.client_id = ?';
    
	
	

	
		if ($db->init(5, 0) === 1) {
			if ($db->prepare($query) === 1) {
				if ($db->perform() === 1) {
					$data = $db->data();
					$db->close();
					return $data;
				} else {
					return -20;
				}
			} else {
				return -20;
			}
		} else {
			return -20;
		}
	
	}
	//get tax for client
	public function getTax() {
		$args = func_get_args();
		$args = $args[0];
		$argsc = count($args);
		//Log_i($args[0]);
		if ($argsc === 1) {
			return $this->_getTax($args[0]);
		} else {
			return -1;
		}
		
	}
	
	private function _getTax($client_id) {
		//Log_i("in model------------------------------");
		//Log_i($client_id);
		//var_dump('helloo');
		
		if (!isset($_SESSION['u_id'])) {
			/*
			 * not logged in
			 */
			return -2;
		}
	
		global $ctrl;
		$ctrl->instances->di->set('db');
		$db = $ctrl->instances->db;
	
		$query = <<<EOT
     SELECT
        gst_or_tax_number
     FROM
     client.client_company_gst_tax
	 WHERE client_id = ?		
EOT;
		//var_dump($bindValuesArr);
	
	if ($db->init(5, 0) === 1) {
      if ($db->prepare($query) === 1) {
        if ($db->bind($client_id) === 1) {
          if ($db->perform() === 1) {
            $data = $db->data();
           // Log_i("in model------------------------------");
           // Log_i($client_id);
           // Log_i($data);
              $db->close();
              return $data;
          } else {
            return -20;
          }
        } else {
          return -20;
        }
      } else {
        return -20;
      }
    } else {
      return -20;
    }
  }
  
  //Get tax for insurer
  
  
  
  
  //Get data for invoice index page
  
  public function get_invoice_for_index() {
  	$args = func_get_args();
  	$args = $args[0];
  	$argsc = count($args);
  	return $this->_get_invoice_for_index();
  }
  
  private function _get_invoice_for_index() {
  	if (!isset($_SESSION['u_id'])) {
  		/*
  		 * not logged in
  		 */
  		return -2;
  	}
  
  	global $ctrl;
  	$ctrl->instances->di->set('db');
  	$db = $ctrl->instances->db;
  
  	$query = <<<EOT
     SELECT
       *
     FROM
     revenue_accounting.invoice
EOT;
  
  	if ($db->init(5, 0) === 1) {
  		if ($db->prepare($query) === 1) {
  			if ($db->perform() === 1) {
  				$data = $db->data();
  				$db->close();
  				return $data;
  				Log_i($data);
  			} else {
  				return -20;
  			}
  		} else {
  			return -20;
  		}
  	} else {
  		return -20;
  	}
  
  }
  
  public function getTax_insurer() {
  	$args = func_get_args();
  	$args = $args[0];
  	$argsc = count($args);
  	//Log_i($args[0]);
  	if ($argsc === 1) {
  		return $this->_getTax($args[0]);
  	} else {
  		return -1;
  	}
  
  }
  
  
  //getsingleInvoice for index invoice page
  public function getSingleInvoice() {
  	//var_dump("in model");
  	$args = func_get_args();
  	$args = $args[0];
  	$argsc = count($args);
  
  	if ($argsc === 1) {
  		return $this->_getSingleInvoice($args[0]);
  	}
  	return -1;
  }
  
  private function _getSingleInvoice($id) {
  	if (!isset($_SESSION['u_id'])) {
  		/*
  		 * not logged in
  		 */
  		return -2;
  	}
  
  	if (empty($id)) {
  		/*
  		 * Empty values sent.
  		 */
  		return -9;
  	}
  	if (!is_int($id)) {
  		/*
  		 * Invalid values type sent.
  		 */
  		return -3;
  	}
  	global $ctrl;
  	$ctrl->instances->di->set('db');
  	$db = $ctrl->instances->db;
  
  	$query = <<<EOT
       select 
    rai.invoice_number,
    rai.create_date,
    ddbr.broker_name,
    ddb.branch_name,
    ddsg.solution_group_name,
    ddic.income_class_code,
    cgi.client_name,
    rapd.policy_number,
    rapd.policy_inception_date,
    rapd.policy_expiry_date,
    rappi.gross_premium,
    rai.billing_to,
    rai.billing_name,
    rai.billing_address,
    rai.gst_or_tax_number,
    ddc.currency_code,
    rai.exchange_rate,
    rai.revenue,
    rai.gst_or_tax,
    rai.total_revenue,
    rapcf.cpu_number
    from
    revenue_accounting.invoice as rai
    left join
    dbcs_data.solution_group as ddsg
    on
    ddsg.id= rai.department_id
    left join
    dbcs_data.branch as ddb
    on
    ddb.id = rai.branch_id
    left join
    dbcs_data.broker as ddbr
    on
    ddbr.id = rai.company_id
    left join
    dbcs_data.income_class as ddic
    on
    ddic.id = rai.income_class_id
    left join
    client.general_information as cgi
    on
    cgi.id = rai.client_id
    left join
    policy.policy_details as rapd
    on
    rapd.id = rai.policy_policy_id
    left join
    policy.policy_premium_information as rappi
    on
    rappi.policy_id = rapd.id
    left join
    dbcs_data.currency as ddc
    on
    ddc.id = rai.currency_id
    left join
    revenue_accounting.policy_cpu_form as rapcf
    on
    rapcf.id = rai.cpu_form_id
    
    
    where
    rai.id = ?
EOT;
  
  	if ($db->init(5, 0) === 1) {
  		if ($db->prepare($query) === 1) {
  			if ($db->bind($id) === 1) {
  				if ($db->perform() === 1) {
  					$data = $db->data();
  					$db->close();
  					//var_dump($data);
  					if (count($data) === 1 && !empty($data[0])) {
  						return $data[0];
  					}
  					return -7;
  				} else {
  					return -20;
  				}
  			} else {
  				return -20;
  			}
  		} else {
  			return -20;
  		}
  	} else {
  		return -20;
  	}
  }
  
  
  //getsingleInvoice for index invoice page
  public function getJVDetails() {
  	//var_dump("in model");
  	$args = func_get_args();
  	$args = $args[0];
  	$argsc = count($args);
  
  	if ($argsc === 1) {
  		return $this->_getJVDetails($args[0]);
  	}
  	return -1;
  }
  
  private function _getJVDetails($invoice_number) {
  	if (!isset($_SESSION['u_id'])) {
  		/*
  		 * not logged in
  		 */
  		return -2;
  	}
  
  	if (empty($id)) {
  		/*
  		 * Empty values sent.
  		 */
  		return -9;
  	}
  	if (!is_int($id)) {
  		/*
  		 * Invalid values type sent.
  		 */
  		return -3;
  	}
  	global $ctrl;
  	$ctrl->instances->di->set('db');
  	$db = $ctrl->instances->db;
  
  	$query = <<<EOT
       select
    rai.invoice_number,
    rai.create_date,
    ddbr.broker_name,
    ddb.branch_name,
    ddsg.solution_group_name,
    ddic.income_class_code,
    cgi.client_name,
    rapd.policy_number,
    rapd.policy_inception_date,
    rapd.policy_expiry_date,
    rappi.gross_premium,
    rai.billing_to,
    rai.billing_name,
    rai.billing_address,
    rai.gst_or_tax_number,
    ddc.currency_code,
    rai.exchange_rate,
    rai.revenue,
    rai.gst_or_tax,
    rai.total_revenue,
    rapcf.cpu_number
    from
    revenue_accounting.invoice as rai
    left join
    dbcs_data.solution_group as ddsg
    on
    ddsg.id= rai.department_id
    left join
    dbcs_data.branch as ddb
    on
    ddb.id = rai.branch_id
    left join
    dbcs_data.broker as ddbr
    on
    ddbr.id = rai.company_id
    left join
    dbcs_data.income_class as ddic
    on
    ddic.id = rai.income_class_id
    left join
    client.general_information as cgi
    on
    cgi.id = rai.client_id
    left join
    policy.policy_details as rapd
    on
    rapd.id = rai.policy_policy_id
    left join
    policy.policy_premium_information as rappi
    on
    rappi.policy_id = rapd.id
    left join
    dbcs_data.currency as ddc
    on
    ddc.id = rai.currency_id
    left join
    revenue_accounting.policy_cpu_form as rapcf
    on
    rapcf.id = rai.cpu_form_id
  
  
    where
    rai.id = ?
EOT;
  
  	if ($db->init(5, 0) === 1) {
  		if ($db->prepare($query) === 1) {
  			if ($db->bind($invoice_number) === 1) {
  				if ($db->perform() === 1) {
  					$data = $db->data();
  					$db->close();
  					//var_dump($data);
  					if (count($data) === 1 && !empty($data[0])) {
  						return $data[0];
  					}
  					return -7;
  				} else {
  					return -20;
  				}
  			} else {
  				return -20;
  			}
  		} else {
  			return -20;
  		}
  	} else {
  		return -20;
  	}
  }
  
  
  
  public function putJVDetails() {
  	var_dump("model");
  	$args = func_get_args();
  	$args = $args[0];
  	$argsc = count($args);
  	if ($argsc === 21) {
  		return $this->_putJVDetails($args[0], $args[1], $args[2],$args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9], $args[10], $args[11], $args[12],
  				$args[13], $args[14], $args[15], $args[16], $args[17], $args[18], $args[19], $args[20]);
  	} else {
  		return -1;
  	}
  }
  private function _putJVDetails($invoice_number, $client_name,  $policy_no, $policy_start_date,
      		                     $policy_end_date, $premium,  $company_name, $branch_name, $department, $income_class,
    		                     $amount, $tax, $debit_currency, $debit_exchange_rate, $credit_currency, $credit_exchange_rate,
    		                     $company_name_credit, $branch_name_credit,  $department_name_credit, 
  		                         $income_class_credit, $credit_income_amount ) {
  	var_dump("in model");
  
  			
  
  
  
  
  			
  }
  
}



?>