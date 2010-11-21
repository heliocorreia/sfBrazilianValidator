<?php

class myBrazilValidator extends sfValidatorBase {

	private $check_mask;

	protected function configure($options = array(), $messages = array())
	{
    	$this->check_mask = false;
    	$this->addRequiredOption('type');
    	$this->addOption('check_mask');
	}

    protected function doClean($value)
    {
		$clean = (string) $value;

		$this->check_mask = $this->getOption('check_mask');
		if (!is_bool($this->check_mask))
		{
			$this->check_mask = true;
		}

    	$type = $this->getOption('type');

		switch($type)
    	{
    		case 'InscEstadualAC':
    		case 'InscEstadualAL':
    		case 'InscEstadualAM':
    		case 'InscEstadualAP':
    		case 'InscEstadualBA':
    		case 'InscEstadualCE':
    		case 'InscEstadualDF':
    		case 'InscEstadualES':
    		case 'InscEstadualGO':
    		case 'InscEstadualMA':
    		case 'InscEstadualMT':
    		case 'InscEstadualMS':
    		case 'InscEstadualMG':
    		case 'InscEstadualPA':
    		case 'InscEstadualPB':
    		case 'InscEstadualPE':
    		case 'InscEstadualPI':
    		case 'InscEstadualPR':
    		case 'InscEstadualRJ':
    		case 'InscEstadualRN':
    		case 'InscEstadualRO':
    		case 'InscEstadualRR':
    		case 'InscEstadualRS':
    		case 'InscEstadualSC':
    		case 'InscEstadualSE':
    		case 'InscEstadualTO':
    		case 'InscMunicipal':
    			if (strtoupper($clean) == 'ISENTO')
    			{
    				return $clean;
    			}
    		case 'CEI':
    		case 'CEP':
    		case 'CPF':
    		case 'CNPJ':
    		case 'DateDMY':
    		case 'Integers':
    		case 'Number':
    		case 'NumberAndDecimal':
    		case 'Phone':
    			if (method_exists($this, 'v' . $type))
    			{
	    			if (!call_user_func(array($this, 'v' . $type), $clean))
	    			{
	    				throw new sfValidatorError($this, 'invalid', array('value' => $value));
	    			}
    			}
    		default: break;
    	}
    	return $clean;
	}

	// AC: 01.NNN.NNN/TTT-DD
	private function vInscEstadualAC($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 13)
		{
			return false;
		}

		// 1st digit
		$b = 4;
		$sum = 0;
		for ($i=0; $i < 11; $i++)
		{
			$sum += ((int)$s[$i]) * $b;
			$b--;
			if ($b == 1)
			{
				$b = 9;
			}
		}

		$d = 11 - ($sum % 11);
		if ($d >= 10)
		{
			$d = 0;
		}

		if ((string)$d != $s[11])
		{
			return false;
		}

		// 2nd digit
		$b = 5;
		$sum = 0;
		for($i=0; $i < 12; $i++)
		{
			$sum += ((int)$s[$i]) * $b;
			$b--;
			if ($b == 1)
			{
				$b = 9;
			}
		}

		$dv = 11 - ($sum % 11);
		if ($dv >= 10)
		{
			$dv = 0;
		}

		return ((string)$dv == $s[12]);
	}

	private function vInscEstadualAL($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$b = 9;
		$sum = 0;

		for($i=0; $i < 8; $i++)
		{
			$sum += $s[$i] * $b;
			$b--;
		}

		// dv
		$sum *= 10;
		$dv = $sum - floor($sum / 11) * 11;

		if ($dv == 10)
		{
			$dv = 0;
		}

		return ($s[8] == $dv);
	}

	private function vInscEstadualAM($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$b = 9;
		$sum = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $s[$i] * $b;
			$b--;
		}

		$dv = 0;
		if ($sum < 11)
		{
			$dv = 11 - $sum;
		}
		else
		{
			$i = $sum % 11;
			if ($i <= 1)
			{
				$dv = 0;
			}
			else
			{
				$dv = 11 - $i;
			}
		}

		return ($dv == $s[8]);
	}

	private function vInscEstadualAP($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$p = 0;
		$d = 0;
		$i = (int)substr($s, 0, 8);

		if (($i >= 3000001) && ($i <= 3017000))
		{
			$p = 5;
			$d = 0;
		}
		else if (($i >= 3017001) && ($i <= 3019022))
		{
			$p = 9;
			$d = 1;
		}

		$b = 9;
		$sum = $p;
		for ($i = 0; $i < 9; $i++)
		{
			$sum += $s[$i] * $b;
			$b--;
		}

		$dv = 11 - ($sum % 11);
		if ($dv == 10)
		{
			$dv = 0;
		}
		else if ($dv == 11)
		{
			$dv = $d;
		}

		return ($dv == $s[9]);
	}

	private function vInscEstadualBA($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 8)
		{
			return false;
		}

		$die = substr($s, 0, 8);
		$nro = array();
		$dig = -1;

		for ($i = 0; $i < 8; $i++)
		{
			$nro[$i] = $die[$i];
		}

		$NumMod = 11;
		if (preg_match('/[0123458]/', $nro[0]))
		{
			$NumMod = 10;
		}

		$b = 7;
		$sum = 0;

		for ($i = 0; $i < 6; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$i = $sum % $NumMod;

		if ($NumMod == 10)
		{
			$dig = ($i == 0) ? 0 : $NumMod - $i;
		}
		else
		{
			$dig = ($i <= 1 ) ? 0 : $NumMod - $i;
		}

		$resultado = ($dig == $nro[7]);

		if (!$resultado)
		{
			return false;
		}

		$b = 8;
		$sum = 0;

		for($i = 0; $i < 6; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$sum += $nro[7] * 2;
		$i = $sum % $NumMod;
		if ($NumMod == 10)
		{
			$dig = ($i == 0) ? 0 : $NumMod - $i;
		}
		else
		{
			$dig = ($i <= 1) ? 0 : $NumMod - $i;
		}

		return ($dig == $nro[6]);
	}

	private function vInscEstadualCE($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		while(strlen($s) < 9)
		{
			$s = '0' . $s;
		}

		$die = $s;
		$nro = array();
		for ($i = 0; $i < 9; $i++)
		{
			$nro[$i] = $die[$i];
		}

		$b = 9;
		$sum = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$dig = 11 - ($sum % 11);

		if ($dig >= 10)
		{
			$dig = 0;
		}

		return ($dig == $nro[8]);
	}

	private function vInscEstadualDF($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 13)
		{
			return false;
		}

		$nro = $s;
		$b = 4;
		$sum = 0;

		for ($i = 0; $i < 11; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
			if ($b == 1)
			{
				$b = 9;
			}
		}

		$dig = 11 - ($sum % 11);

		if ($dig >= 10)
		{
			$dig = 0;
		}

		$resultado = ($dig == $nro[11]);

		if (!$resultado)
		{
			return false;
		}

		$b = 5;
		$sum = 0;

		for ($i = 0; $i < 12; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
			if ($b == 1)
			{
				$b = 9;
			}
		}

		$dig = 11 - ($sum % 11);
		if ($dig >= 10)
		{
			$dig = 0;
		}

		return ($dig == $nro[12]);
	}

	private function vInscEstadualES($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$nro = $s;
		$b = 9;
		$sum = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$i = $sum % 11;

		$dig = ($i < 2) ? 0 : 11 - $i;

		return ($dig == $nro[8]);
	}

	private function vInscEstadualGO($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$ie = $s;

		$s = substr($ie, 0, 2);

		if ($s == '10' || $s == '11' || $s = '15')
		{
			$nro = $ie;
			$n = floor($ie / 10);

			if ($n == 11094402)
			{
				if ($nro[8] == 0 || $nro[8] == 1)
				{
					return true;
				}
			}
		}

		$b = 9;
		$sum = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$i = $sum % 11;

		if ($i == 0)
		{
			$dig = 0;
		}
		else
		{
			if ($i == 1)
			{
				if (n >= 10103105 && $n <= 10119997)
				{
					$dig = $i;
				}
				else
				{
					$dig = 0;
				}
			}
			else
			{
				$dig = 11 - $i;
			}
		}

		return ($dig == $nro[8]);
	}

	private function vInscEstadualMA($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$nro = $s;

		$b = 9;
		$sum = 0;
		for ($i = 0; $i <= 7; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$i = $sum % 11;

		if ($i <= 1)
		{
			$dig = 0;
		}
		else
		{
			$dig = 11 - $i;
		}

		return ($dig == $nro[8]);
	}

	private function vInscEstadualMT($ie)
	{
		$ie = preg_replace('/[^0-9]*/', '', $ie);

		if (strlen($ie) < 9) return false;

		$die = $ie;
		while (strlen($die) <= 10) $die = '0' . $die;

		$nro = array();
		for ($i = 0; $i <= 10; $i++) $nro[$i] = $die[$i];

		$b = 3;
		$soma = 0;
		for ($i = 0; $i <= 9; $i++)
		{
		$soma += $nro[$i] * $b;
		$b--;
		if ($b == 1) $b = 9;
		}

		$i = bcmod($soma, 11);
		($i <= 1) ? $dig = 0 : $dig = 11 - $i;

		if ($dig == $nro[10]) return true;
	}

	private function vInscEstadualMS($ie)
	{
		$ie = preg_replace('/[^0-9]*/', '', $ie);

		if (strlen($ie) != 9) return false;

		if (substr($ie, 0, 2) != '28') return false;

		$nro = $ie;

		$b = 9;
		$soma = 0;
		for ($i = 0; $i <= 7; $i++)
		{
		  $soma += $nro[$i] * $b;
		  $b--;
		}

		$i = $soma % 11;

		$dig = ($i <= 1) ? 0 : 11 - $i;

		return ($dig == $nro[8]);
	}

	//
	private function vInscEstadualMG($ie)
	{
		$ie = preg_replace('/[^0-9]*/', '', $ie);

        if (substr($ie, 0, 2) == 'PR') return true;
        if (substr($ie, 0, 5) == 'ISENT') return true;
        if (strlen($ie) != 13) return false;

        $dig1 = substr($ie, 11, 1);
        $dig2 = substr($ie, 12, 1);
        $inscC = substr($ie, 0, 3) . '0' . substr($ie, 3, 8);
        $insc = $inscC;
        $npos = 11;
        $i = 1;
        $psoma = $ptotal = 0;
        while ($npos >= 0)
        {
          $i++;
          $psoma = $insc[$npos] * $i;
          if ($psoma >= 10) $psoma -= 9;

          $ptotal += $psoma;

          if ($i == 2) $i = 0;

          $npos--;
        }

        $nresto = bcmod($ptotal, 10);
        if ($nresto == 0) $nresto = 10;

        $nresto = 10 - $nresto;

        if ($nresto != $dig1) return false;

        $npos = 11;
        $i = 1;
        $ptotal = 0;
        $is = $ie;
        while ($npos >= 0)
        {
          $i++;
          if ($i == 12) $i = 2;

          $ptotal += $is[$npos] * $i;
          $npos--;
        }

        $nresto = bcmod($ptotal, 11);

        if (($nresto == 0) || ($nresto == 1)) $nresto = 11;

        $nresto = 11 - $nresto;

        if ($nresto == $dig2) return true;
	}

	private function vInscEstadualPA($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		if (substr($s, 0, 2) != '15')
		{
			return false;
		}

		$nro = $s;
		$b = 9;
		$sum = 0;
		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$i = $sum % 11;

		$dig = ($i <= 1) ? 0 : 11 - $i;

		return ($dig == $nro[8]);
	}

	private function vInscEstadualPB($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$nro = $s;
		$b = 9;
		$sum = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$i = $sum % 11;

		$dig = ($i <= 1) ? 0 : 11 - $i;

		return ($dig == $nro[8]);
	}

	private function vInscEstadualPE($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		while(strlen($s) < 14)
		{
			$s = '0' . $s;
		}

		// is number?
		if (strlen($s) != 14)
		{
			return false;
		}

		$nro = $s;
		$b = 5;
		$sum = 0;

		for ($i = 0; $i < 13; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
			if ($b == 0)
			{
				$b = 9;
			}
		}

		$dig = 11 - ($sum % 11);

		if ($dig > 9)
		{
			$dig = $dig - 10;
		}

		return ($dig == $nro[13]);
	}

	private function vInscEstadualPI($ie)
	{
      $ie = preg_replace('/[^0-9]*/', '', $ie);

      if (strlen($ie)!= 9) return false;

      $nro = array();

      for ($i = 0; $i <= 8; $i++)  $nro[$i] = substr($ie, $i, 1);

      $b = 9;
      $soma = 0;
      for ($i = 0; $i <= 7; $i++)
      {
            $soma += $nro[$i] * $b;
            $b--;
      }

      $i = bcmod($soma, 11);
      ($i <= 1) ? $dig = 0 : $dig = 11 - $i;

      if ($dig == $nro[8]) return true;
	}

	private function vInscEstadualPR($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 10)
		{
			return false;
		}

		$nro = $s;
		$b = 3;
		$sum = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
			if ($b == 1)
			{
				$b = 7;
			}
		}

		$i = $sum % 11;

		$dig = ($i <= 1) ? 0 : 11 - $i;

		$resultado = ($dig == $nro[8]);

		if (!$resultado)
		{
			return false;
		}

		$b = 4;
		$sum = 0;

		for ($i = 0; $i < 9; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
			if ($b == 1)
			{
				$b = 7;
			}
		}

		$i = $sum % 11;

		$dig = ($i <= 1) ? 0 : 11 - $i;

		return ($dig == $nro[9]);
	}

	private function vInscEstadualRJ($ie)
	{
	    $ie = preg_replace('/[^0-9]*/', '', $ie);

	    if (strlen($ie) != 8) return false;

	    $nro = array();

	    for ($i = 0; $i <= 7; $i++) $nro[$i] = substr($ie, $i, 1);

	    $b = 2;
	    $soma = 0;
	    for ($i = 0; $i <= 6; $i++)
	    {
	          $soma += $nro[$i] * $b;
	          $b--;

	          if ($b == 1) $b = 7;
	    }

	    $i = bcmod($soma, 11);

	    ($i <= 1) ? $dig = 0 : $dig = 11 - $i;

	    if ($dig == $nro[7]) return true;
	}

	private function vInscEstadualRN($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$nro = $s;
		$b = 9;
		$sum = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$sum *= 10;
		$dig = $sum % 11;
		if ($dig == 10)
		{
			$dig = 0;
		}

		return ($dig == $nro[8]);
	}

	private function vInscEstadualRO($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		$nro = $s;
		$b = 6;
		$sum = 0;

		for ($i = 0; $i < 5; $i++)
		{
			$sum += ($nro[$i] * $b);
			$b--;
		}

		$b = 9;

		for ($i = 5; $i < 14; $i++)
		{
			if ($i != 13)
			{
				$sum += ($nro[$i] * $b);
				$b--;
			}
		}

		$dig = 11 - ($sum % 11);

		if ($dig >= 10)
		{
			$dig -= 10;
		}

		$ret = ($dig == $nro[13]);

		// check for older version
		if (!$ret)
		{
			// is number?
			if (strlen($s) != 9)
			{
				return false;
			}

			$nro = $s;
			$b = 6;
			$sum = 0;

			for ($i = 3; $i < 9; $i++)
			{
				if ($i != 8)
				{
					$sum += $nro[$i] * $b;
					$b++;
				}
			}

			$dig = 11 - ($sum % 11);
			if ($dig >= 10)
			{
				$dig -= 10;
			}

			$ret = ($dig == $nro[8]);
		}

		return $ret;
	}

	private function vInscEstadualRR($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		if (substr($s, 0, 2) != '24')
		{
			return false;
		}

		$nro = $s;
		$sum = 0;
		$n = 0;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * ++$n;
		}

		$dig = $sum % 9;

		return ($dig == $nro[8]);
	}

	private function vInscEstadualRS($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 10)
		{
			return false;
		}

		$i = (int)substr($s, 0, 3);

		if ($i >= 1 && $i <= 467)
		{
			$nro = $s;
			$b = 2;
			$sum = 0;

			for($i = 0; $i < 9; $i++)
			{
				$sum += $nro[$i] * $b;
				$b--;
				if ($b == 1)
				{
					$b = 9;
				}
			}

			$dig = 11 - ($sum % 11);

			if ($dig == 10)
			{
				$dig = 0;
			}

			return ($dig == $nro[9]);
		}
		return false;
	}

	private function vInscEstadualSC($s)
	{
		// only numbers
		$s = preg_replace('/[^0-9]*/', '', $s);

		// is number?
		if (strlen($s) != 9)
		{
			return false;
		}

		$nro = $s;
		$b = 9;

		for ($i = 0; $i < 8; $i++)
		{
			$sum += $nro[$i] * $b;
			$b--;
		}

		$i = $sum % 11;
		$dig = ($i <= 1) ? 0 : 11 - $i;

		return ($dig == $nro[8]);
	}

	private function vInscEstadualSE($ie)
	{
	    $ie = preg_replace('/[^0-9]*/', '', $ie);

	    if (strlen($ie) != 9) return false;

	    $nro = array();
	    for ($i = 0; $i <= 8; $i++) $nro[$i] = substr($ie, $i, 1);

	    $b = 9;
	    $soma = 0;
	    for ($i = 0; $i <= 7; $i++)
	    {
	      $soma += $nro[$i] * $b;
	      $b--;
	    }

	    $dig = 11 - ($soma % 11);
	    if ($dig >= 10) $dig = 0;

	    if ($dig == $nro[8]) return true;
	}

	private function vInscEstadualSP($ie)
	{
      if (strtolower(substr($ie, 0, 1)) == 'p')
      {
            $ie = str_replace(array('.', '-'), '', $ie);
        $s = substr($ie, 1, 9);

        $nro = array();
        for ($i = 0; $i <= 8; $i++) $nro[$i] = $s[$i];

        $soma = ($nro[0] * 1) + ($nro[1] * 3) + ($nro[2] * 4) + ($nro[3] * 5) + ($nro[4] * 6) + ($nro[5] * 7) + ($nro[6] * 8) + ($nro[7] * 10);

        $dig = bcmod($soma, 11);

        if ($dig >= 10) $dig = 0;

        if ($dig == $nro[8]) return true;
      }
      else
      {
            $ie = str_replace(array('.', '-'), '', $ie);

        if (strlen($ie) < 12) return false;
        $nro = array();
        for ($i = 0; $i <= 11; $i++) $nro[$i] = substr($ie, $i, 1);

        $soma = ($nro[0] * 1) + ($nro[1] * 3) + ($nro[2] * 4) + ($nro[3] * 5) + ($nro[4] * 6) + ($nro[5] * 7) + ($nro[6] * 8) + ($nro[7] * 10);
        $dig = bcmod($soma, 11);
        if ($dig >= 10) $dig = 0;

        $resultado = ($dig == $nro[8]);

        if (!$resultado) return false;

        $soma = ($nro[0] * 3) + ($nro[1] * 2) + ($nro[2] * 10) + ($nro[3] * 9) + ($nro[4] * 8) + ($nro[5] * 7) + ($nro[6] * 6)  + ($nro[7] * 5) + ($nro[8] * 4) + ($nro[9] * 3) + ($nro[10] * 2);
        $dig = bcmod($soma, 11);

        if ($dig >= 10) $dig = 0;

        if ($dig == $nro[11]) return true;
      }
	}

	private function vInscEstadualTO($ie)
	{
            $ie = preg_replace('/[^0-9]*/', '', $ie);

            $nro = array();
            $b = 9;
            $soma = 0;

            if (strlen($ie) == 9)
            {
                  for ($i=0; $i <= 8; $i++ )
                  {
                        $nro[$i] = substr($ie, $i, 1);

                        if($i != 8)
                        {
                             $soma = $soma + ( $nro[$i] * $b);
                             $b--;
                        }
                  }

                  $ver = bcmod($soma, 11);

                  if ($ver < 2) $dig = 0;

                  if ($ver >= 2) $dig = 11 - $ver;

                  if ($dig == $nro[8]) return true;
            }
            elseif(strlen($ie) == 11)
            {
                  $s = substr($ie, 2, 2);

          if(!strpos($s, array('01', '02', '03', '99')))
          {
        for ($i = 0; $i <= 10; $i++)
        {
                             $nro[$i] = substr($ie, $i, 1);

                             if(!strpos($i, array(3, 4)))
                             {
                                   $soma = $soma + ($nro[$i] * $b);
                                   $b--;
                             }
        }

                        $resto = bcmod($soma, 11);
                        if($resto < 2) $dig = 0;

                        if ($resto >= 2) $dig = 11 - resto;

                        if ($dig == $nro[10]) return true;
          }
        }
	}

//	private function vInscEstadual($ie)
//	{
//		static $_numero = 37;
//		static $_mascaras = '     NNNNNNNNX- NNNNNNNNNNNXY-   NNNNNNNNNNX-NNNNNNNNNNNNNX-      NNNNNNYX-    NNNNNNNNXY-' +
//  						'    NNNNNNNNNX-  NNNNNNNNXNNY-  NNNNNNNNXNNN-     NNNNNNNXY';
//		static $_pesos = 'GFEDCJIHGFEDCA-FEDCJIHGFEDCAA-GFEDCJIHGFEDAC-AAAAAAAAGFEDCA-AAAAABCDEFGHIA-AAAJIAAHGFEDCA-' +
//						'FEDCBJIHGFEDCA-IHGFEDCHGFEDCA-HGFEDCHGFEDCAA-ABCBBCBCBCBCAA-ADCLKJIHGFEDCA-AABDEFGHIKAAAA-AADCKJIHGFEDCA-' +
//						'AAAAAJIHGFEDCA-AAAAAIHGFEDCAA-AAAAAJIHGFEDCA-AAAAKJIHGFEDCA-';
//		static $_eso = 'ABAAAAABBABAAAAAAJAAIGAHAADAEALLAFNOQ!A!!!!!CC!A!!!!!!K!!H!!!!!!!!!M!!!!P!';
//		static $_alfa = 'ABCDEFGHIJKLMNOPQRS';
//		static $_rotinas = 'EE011EEEEEEEEEEEE2EEEEEE0EEEDEDDEEEE0!E!!!!!EE!E!!!!!!E!!E!!!!!!!!!D!!!!E!';
//		static $_modulos = '99999998999999999899999999997999999990900000890900000090090000000009000090';
//		static $_inicio  = '0020000AB000111X2X11X11X2XXX2XXXX2XX2114333XXXX7XCC2X8X56X89X0XXX4XXXX9XX0';
//		static $_mascara = 'ABAAAAAEEABAAAACABAAFDAEAGADAAHIACAJG';
//		static $_fatores = '0000100000001000000001000011000000000';
//		static $_estados = 'ACACALA1APA2AMBABACEDFESGOGOMAMTMSMGPAPBPRPEPIRJRNRSRORORRSCSPSPSET0TOPERN';
//
//		$ie = trim(strtoupper($ie));
//
//		$result = ($ie == 'ISENTO' || $ie == 'EM ANDAMENTO' || ($uf = 'EX' && (($ie == '') || ($ie = '00000000000000'))));
//
//		$posicao = 0;
//		while ((!$result) && ($posicao < $_numero) && ($ie != ''))
//		{
//			$posicao++;
//			if ($uf == 'AP' && parseFloat($ie) <= 30170009)
//			{
//				$uf = 'A1';
//			}
//			if ($uf == 'AP' && parseFloat($ie) <= 30190230)
//			{
//				$uf = 'A2';
//			}
//			if (substr($_estados, $posicao * 2 - 1, 2) != $uf)
//			{
//				continue;
//			}
//			$inscricao = '';
//			for ($c1 = 0; $c1 < 30; $c1++)
//			{
//				if (strpos($ie[$c1]))
//				{
//					$inscricao += $ie[$c1];
//				}
//				$mascara = substr($_mascaras, strpos(substr($_mascara, $posicao, 1), $_alfa) * 15 - 14, 14);
//				if (strlen($inscricao) != strlen(trim($mascara)))
//				{
//					continue;
//				}
//				$inscricao = substr('00000000000000' . $inscricao, strlen(inscricao) + 1, 14);
//				$erros = 0;
//				$alternativa = 0;
//				while ($alternativa < 2)
//				{
//					$alternativa++;
//					$inicio = $posicao + ($alternativa * $_numero) - $_numero;
//					$peso = substr($_pesos)
//				}
//			}
//		}
///*
//
//      inc(alternativa);
//      inicio := posicao + (alternativa * NUMERO) - NUMERO;
//      peso := copy(PESO_, inicio, 1);
//      if peso = '!' then
//        continue;
//      a1 := copy(INICIO_, inicio, 1);
//      a2 := copy(copy(inscricao, 15 - length(trim(mascara)), length(trim(mascara))), alternativa, 1);
//      if (not ApenasDigitos) and (((pos(a1, 'ABCX') = 0) and (a1 <> a2)) or
//        ((pos(a1, 'ABCX') <> 0) and (pos(a2, copy('0123458888-6799999999-0155555555-0123456789',
//        (pos(a1, 'ABCX') * 11 - 10), 10)) = 0))) then
//        erros := 1;
//      soma1 := 0;
//      soma2 := 0;
//      for C2 := 1 to 14 do
//      begin
//        valor := StrToInt(copy(inscricao, C2, 1)) *
//          (pos(copy(copy(PESOS_, (pos(peso, ALFA_) * 15 - 14), 14), C2, 1), ALFA_) - 1);
//        soma1 := soma1 + valor;
//        if valor > 9 then
//          valor := valor - 9;
//        soma2 := soma2 + valor;
//      end;
//      rotina := copy(ROTINAS_, inicio, 1);
//      modulo := StrToInt(copy(MODULOS_, inicio, 1)) + 2;
//      fator := StrToInt(copy(FATORES_, posicao, 1));
//      if pos(rotina, 'A22') <> 0 then
//        soma1 := soma2;
//      if pos(rotina, 'B00') <> 0 then
//        soma1 := soma1 * 10;
//      if pos(rotina, 'C11') <> 0 then
//        soma1 := soma1 + (5 + 4 * fator);
//      if pos(rotina, 'D00') <> 0 then
//        digito := soma1 mod modulo;
//      if pos(rotina, 'E12') <> 0 then
//        digito := modulo - (soma1 mod modulo);
//      if digito = 10 then
//        digito := 0;
//      if digito = 11 then
//        digito := fator;
//      if (copy(inscricao, pos(copy('XY', alternativa, 1), mascara), 1) <> IntToStr(digito)) then
//        erros := 1;
//
//
//
//
//function IE(IE, UF: string; ApenasDigitos: Boolean = True): boolean;
//const
//  NUMERO: smallint = 37;
//  MASCARAS_: string = '     NNNNNNNNX- NNNNNNNNNNNXY-   NNNNNNNNNNX-NNNNNNNNNNNNNX-      NNNNNNYX-    NNNNNNNNXY-' +
//  '    NNNNNNNNNX-  NNNNNNNNXNNY-  NNNNNNNNXNNN-     NNNNNNNXY';
//  PESOS_: string = 'GFEDCJIHGFEDCA-FEDCJIHGFEDCAA-GFEDCJIHGFEDAC-AAAAAAAAGFEDCA-AAAAABCDEFGHIA-AAAJIAAHGFEDCA-' +
//  'FEDCBJIHGFEDCA-IHGFEDCHGFEDCA-HGFEDCHGFEDCAA-ABCBBCBCBCBCAA-ADCLKJIHGFEDCA-AABDEFGHIKAAAA-AADCKJIHGFEDCA-' +
//    'AAAAAJIHGFEDCA-AAAAAIHGFEDCAA-AAAAAJIHGFEDCA-AAAAKJIHGFEDCA-';
//  PESO_: string = 'ABAAAAABBABAAAAAAJAAIGAHAADAEALLAFNOQ!A!!!!!CC!A!!!!!!K!!H!!!!!!!!!M!!!!P!';
//  ALFA_: string = 'ABCDEFGHIJKLMNOPQRS';
//  ROTINAS_: string = 'EE011EEEEEEEEEEEE2EEEEEE0EEEDEDDEEEE0!E!!!!!EE!E!!!!!!E!!E!!!!!!!!!D!!!!E!';
//  MODULOS_: string = '99999998999999999899999999997999999990900000890900000090090000000009000090';
//  INICIO_ : string = '0020000AB000111X2X11X11X2XXX2XXXX2XX2114333XXXX7XCC2X8X56X89X0XXX4XXXX9XX0';
//  MASCARA_: string = 'ABAAAAAEEABAAAACABAAFDAEAGADAAHIACAJG';
//  FATORES_: string = '0000100000001000000001000011000000000';
//  ESTADOS_: string = 'ACACALA1APA2AMBABACEDFESGOGOMAMTMSMGPAPBPRPEPIRJRNRSRORORRSCSPSPSET0TOPERN';
//var
//  c1, c2, alternativa, inicio, posicao, erros, fator, modulo, soma1, soma2, valor, digito: smallint;
//  mascara, inscricao, a1, a2, peso, rotina: string;
//begin
//  // Copyright: www.cincobytes.net / suporte@cincobytes.net //
//  IE := trim(uppercase(IE));
//  result := ((IE = 'ISENTO') or (IE = 'EM ANDAMENTO') or ((UF = 'EX') and ((IE = '') or (IE = '00000000000000'))));
//  posicao := 0;
//  while ((not result) and (posicao < NUMERO) and (IE <> '')) do
//  begin
//    inc(posicao);
//    if (UF = 'AP') and (StrToFloat(IE) <= 30170009) then
//      UF := 'A1';
//    if (UF = 'AP') and (StrToFloat(IE) >= 30190230) then
//      UF := 'A2';
//    if (copy(ESTADOS_, posicao * 2 - 1, 2)) <> UF then
//      continue;
//    inscricao := '';
//    for C1 := 1 to 30 do
//      if pos(copy(IE, C1, 1), '0123456789') <> 0 then
//        inscricao := inscricao + copy(IE, C1, 1);
//    mascara := copy(MASCARAS_, pos(copy(MASCARA_, posicao, 1), ALFA_) * 15 - 14, 14);
//    if length(inscricao) <> length(trim(mascara)) then
//      continue;
//    inscricao := copy('00000000000000' + inscricao, length(inscricao) + 1, 14);
//    erros := 0;
//    alternativa := 0;
//    while (alternativa < 2) do
//    begin
//      inc(alternativa);
//      inicio := posicao + (alternativa * NUMERO) - NUMERO;
//      peso := copy(PESO_, inicio, 1);
//      if peso = '!' then
//        continue;
//      a1 := copy(INICIO_, inicio, 1);
//      a2 := copy(copy(inscricao, 15 - length(trim(mascara)), length(trim(mascara))), alternativa, 1);
//      if (not ApenasDigitos) and (((pos(a1, 'ABCX') = 0) and (a1 <> a2)) or
//        ((pos(a1, 'ABCX') <> 0) and (pos(a2, copy('0123458888-6799999999-0155555555-0123456789',
//        (pos(a1, 'ABCX') * 11 - 10), 10)) = 0))) then
//        erros := 1;
//      soma1 := 0;
//      soma2 := 0;
//      for C2 := 1 to 14 do
//      begin
//        valor := StrToInt(copy(inscricao, C2, 1)) *
//          (pos(copy(copy(PESOS_, (pos(peso, ALFA_) * 15 - 14), 14), C2, 1), ALFA_) - 1);
//        soma1 := soma1 + valor;
//        if valor > 9 then
//          valor := valor - 9;
//        soma2 := soma2 + valor;
//      end;
//      rotina := copy(ROTINAS_, inicio, 1);
//      modulo := StrToInt(copy(MODULOS_, inicio, 1)) + 2;
//      fator := StrToInt(copy(FATORES_, posicao, 1));
//      if pos(rotina, 'A22') <> 0 then
//        soma1 := soma2;
//      if pos(rotina, 'B00') <> 0 then
//        soma1 := soma1 * 10;
//      if pos(rotina, 'C11') <> 0 then
//        soma1 := soma1 + (5 + 4 * fator);
//      if pos(rotina, 'D00') <> 0 then
//        digito := soma1 mod modulo;
//      if pos(rotina, 'E12') <> 0 then
//        digito := modulo - (soma1 mod modulo);
//      if digito = 10 then
//        digito := 0;
//      if digito = 11 then
//        digito := fator;
//      if (copy(inscricao, pos(copy('XY', alternativa, 1), mascara), 1) <> IntToStr(digito)) then
//        erros := 1;
//    end;
//    result := (erros = 0);
//  end;
//end;
//*/
//	}

	//
	private function vInscMunicipal($s)
	{
		return true;
	}

	// CEI: Cadastro Específico INSS
	// 00.000.00000/00
	// ? 200532108865
	// ? 200532108863
	private function vCEI($s)
	{
		// check mask
		if ($this->check_mask)
		{
			if (!preg_match('/^\d{2}\.\d{3}\.\d{5}\/\d{2}$/', $s))
			{
				return false;
			}
		}

		// check content
		// only numbers
		$s = preg_replace('/([^0-9]*)/', '', $s);

	    if (strlen($s) != 12)
	    {
	    	return false;
	    }

		$m = array(7, 4, 1, 8, 5, 2, 1, 6, 3, 7, 4);
		$len = strlen($s) - 1;
		$sum = 0;

		for ($i = 0; $i < $len; $i++)
		{
			$sum += $s[$i] * $m[$i];
		}

		$p = (string)$sum;

		$len = strlen($p);

		$p = (string)($p[$len - 2] + $p[$len - 1]);

		$len = strlen($p);
		$dv = 10 - $p[$len - 1];

		return ($dv == substr($s, -1));
	}

	// 0000-0000
	// 000-0000
	// 00 0000-0000
	// 00 000-0000
	private function vPhone($s)
	{
		return preg_match('/^'
				//.'(\d{7,8})'					// 00000000
				.'(\d{3,4}\-\d{4})'			// 0000-0000
				.'|(\d{3}\-\d{4})'				// 000-0000
				.'|(\d{2}[ ]{1}\d{3,4}\-\d{4})'	// 00 0000-0000
				//.'|(\(\d{2}\)[ ]{1}\d{3,4}\-\d{4})'	// (00) 0000-0000
				.'$/'
				, $s);
	}

	// 00.000-000
	// 00000-000
	// 00000000
	private function vCEP($s)
	{
		return preg_match('/^(\d{2}\.\d{3}\-\d{3})|(\d{5}\-?\d{3})$/', $s);
	}

	// 00000000...
	private function vIntegers($s)
	{
		return preg_match('/^([0-9]+)$/', $s);
	}

	// dd/mm/yyyy
	// dd/mm/yy
	// dd/m/yyyy
	// dd/m/yy
	// d/mm/yyyy
	// d/mm/yy
	// d/m/yyyy
	// d/m/yy
	private function vDateDMY($s)
	{
		// check mask
		if ($this->check_mask)
		{
			if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{2,4}$/', $s))
			{
				return false;
			}
		}

		// check content
		$d = $m = $y = 0;
		list($d, $m, $y) = explode('/', $s, 3);

		$dt = mktime(0, 0, 0, $m, $d, $y);

		if (
			$s == date('d/m/Y', $dt)	// dd/mm/yyyy
			|| $s == date('d/m/y', $dt)	// dd/mm/yy
			|| $s == date('d/n/Y', $dt)	// dd/m/yyyy
			|| $s == date('d/n/y', $dt)	// dd/m/yy
			|| $s == date('j/m/Y', $dt)	// d/mm/yyyy
			|| $s == date('j/m/y', $dt)	// d/mm/yy
			|| $s == date('j/n/Y', $dt)	// d/m/yyyy
			|| $s == date('j/n/y', $dt)	// d/m/yy
		){
			return true;
		}

		return false;
	}

	// 1.234,567890
	private function vNumberAndDecimal($s)
	{
		return preg_match('/^((\d{1,3}\.)*\d{3}|(\d){1,3})(\,\d+)?$/', $s);
	}

	// 1.234
	private function vNumber($s)
	{
		return preg_match('/^((\d{1,3}\.)*\d{3}|(\d){1,3})$/', $s);
	}

	// 000.000.000-00
	private function vCPF($s)
	{
		// check mask
		if ($this->check_mask)
		{
			if (!preg_match('/^(\d{3}\.){2}\d{3}\-\d{2}$/', $s))
			{
				return false;
			}
		}

		// check content
		$s = preg_replace('/[^0-9]*/', '', $s); // only numbers

		if ((strlen($s) <> 11) || preg_match('/^(0{11}|1{11}|2{11}|3{11}|4{11}|5{11}|6{11}|7{11}|8{11}|9{11})$/', $s))
		{
			return false;
		}

		// 1st char
		$sum = 0;
		for ($i = 0; $i < 9; $i++)
		{
			$sum += ($s[$i] * (10 - $i));
		}

		$c1 = ($sum % 11);
		$c1 = ($c1 < 2) ? 0 : (11 - $c1);

		if ($c1 != $s[9])
		{
			return false;
		}

		// 2nd char
		$sum = 0;
		for ($i = 0; $i < 10; $i++)
		{
			$sum += ($s[$i] * (11 - $i));
		}

		$c2 = ($sum % 11);
		$c2 = ($c2 < 2) ? 0 : (11 - $c2);

		if ($c2 != $s[10])
		{
			return false;
		}

		return true;
	}

	// 00.000.000/0000-00
	private function vCNPJ($s)
	{
		// check mask
		if ($this->check_mask)
		{
			if (!preg_match('/^\d{2}(\.\d{3}){2}\/\d{4}\-\d{2}$/', $s))
			{
				return false;
			}
		}

		// check content
		$s = preg_replace('/(\D)+/', '', $s); // only numbers

		if ((strlen($s) <> 14) || preg_match('/^(0{14}|1{14}|2{14}|3{14}|4{14}|5{14}|6{14}|7{14}|8{14}|9{14})$/', $s))
		{
			return false;
		}

		// 1st char
		$sum = 0;

		$sum += ($s[0] * 5);
		$sum += ($s[1] * 4);
		$sum += ($s[2] * 3);
		$sum += ($s[3] * 2);
		$sum += ($s[4] * 9);
		$sum += ($s[5] * 8);
		$sum += ($s[6] * 7);
		$sum += ($s[7] * 6);
		$sum += ($s[8] * 5);
		$sum += ($s[9] * 4);
		$sum += ($s[10] * 3);
		$sum += ($s[11] * 2);

		$c1 = $sum % 11;
		$c1 = ($c1 < 2) ? 0 : 11 - $c1;

		if ($c1 != $s[12])
		{
			return false;
		}

		// 2nd char
		$sum = 0;
		$sum += ($s[0] * 6);
		$sum += ($s[1] * 5);
		$sum += ($s[2] * 4);
		$sum += ($s[3] * 3);
		$sum += ($s[4] * 2);
		$sum += ($s[5] * 9);
		$sum += ($s[6] * 8);
		$sum += ($s[7] * 7);
		$sum += ($s[8] * 6);
		$sum += ($s[9] * 5);
		$sum += ($s[10] * 4);
		$sum += ($s[11] * 3);
		$sum += ($s[12] * 2);

		$c2 = $sum % 11;
		$c2 = ($c2 < 2) ? 0 : 11 - $c2;

		if ($c2 != $s[13])
		{
			return false;
		}

		return true;
	}
}