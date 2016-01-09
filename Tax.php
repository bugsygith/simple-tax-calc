<?php 

class Tax
{
	
	private static $annualTaxable = 5000000000;
	private static $tariff = array();
	
	function __construct($annualTaxable,$tariff=array())
	{
		self::$annualTaxable = $annualTaxable;

		//set default tariff if not defined by class caller
		if(is_array($tariff))
		{
			if(count($tariff)==0)
			{
				self::$tariff[] =  array("bottom"=>0,"top"=>50000000, "tax"=>0.05);
				self::$tariff[] = array("bottom"=>50000000, "top"=>250000000,"tax"=>0.15);
				self::$tariff[] = array("bottom"=>250000000,"top"=>500000000,"tax"=>0.25);
				self::$tariff[] = array("bottom"=>500000000,"top"=>500000001,"tax"=>0.3);
				
			}else{
				self::$tariff = $tariff;
			}
			//sort tariff by bottom value, ascending
			usort(self::$tariff,function($a,$b){return $a["bottom"]>$b["bottom"];} );
		}
		
	}
	
	/*
	 * calculate tax
	 */
	public function calculateTax()
	{
		$result = 0;
		
		foreach (self::$tariff as $idx=>$t)
		{
			if(self::$annualTaxable > $t["top"] && count(self::$tariff)>$idx+1)
			{
				$result += ($t["top"]-$t["bottom"]) * $t["tax"];
			}else{
				if(self::$annualTaxable - $t["bottom"] > 0)
				{
					$result += (self::$annualTaxable - $t["bottom"]) * $t["tax"];
				}
			}
		}
		
		return $result;	
	}
	
	/*
	 * set annual taxable income foranother value
	*/
	public function setAnnualTaxable($annualTaxable)
	{
		self::$annualTaxable = $annualTaxable;
	}
	
	/*
	 * set different tax tariff
	*/
	public function setTariff($tariff)
	{
		self::$tariff = $tariff;
	}
	
}
