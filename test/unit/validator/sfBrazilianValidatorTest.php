<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');
require_once(dirname(__FILE__).'/../../../lib/validator/sfBrazilianValidator.class.php');

$aStrings = array(
	'CEP' => array(
    	'valid' => array(
    		'20.000-000',
    		'20000-000',
    		'20000000',
        ),
    	'invalid' => array(
    		'20080',
    		'20.80002',
    	),
    ),
	'CEI' => array(
    	'valid' => array(
    		'200532108865',
    		'20.053.21088/65',
    	)
    	,'invalid' => array(
    		'200532108863',
    	)
    ),
	'CPF' => array(
		'valid' => array(
			'22233366638',
			'222.333.666-38',
		)
		,'invalid' => array(
			'020.360.438-23',
			'000.000.000-00',
			'111.111.111-11',
			'222.222.222-22',
			'333.333.333-33',
			'444.444.444-44',
			'555.555.555-55',
			'666.666.666-66',
			'777.777.777-77',
			'888.888.888-88',
			'999.999.999-99',
		)
	),
	'CNPJ' => array(
		'valid' => array(
			'11.222.333/0001-81',
		)
		,'invalid' => array(
			'00.000.000/0000-00',
			'11.111.111/1111-11',
			'22.222.222/2222-22',
			'33.333.333/3333-33',
			'44.444.444/4444-44',
			'55.555.555/5555-55',
			'66.666.666/6666-66',
			'77.777.777/7777-77',
			'88.888.888/8888-88',
			'99.999.999/9999-99',
		)
	),
	'DataDMY' => array(
		'valid' => array(
			'01/05/2009',
			'01/05/09',
			'01/5/2009',
			'01/5/09',
			'1/05/2009',
			'1/05/09',
			'1/5/2009',
			'1/5/09',
		)
		,'invalid' => array(
			'41/5/09',
			)
	),
	'Inteiros' => array(
		'valid' => array(
			'12345',
			'0123456789',
		),
		'invalid' => array(
			'12.345',
			'123456.789',
			'123456,789',
		),
	),
	'InscEstadualAC' => array(
    	'valid' => array(
    		'ISENTO',
    		'isento',
    		'01.004.823/001-12',
    		'01.956.867/001-07',
    		'01.012.239/001-38',
    	),
    	'invalid' => array(
    		'01.004.823/001-11',
    		'01.956.867/001-08',
    		'01.012.239/001-37',
    	)
	),
	'InscEstadualAL' => array(
    	'valid' => array(
    		'ISENTO',
    		'isento',
	        '240800206',
    		'248428454',
    		'241067391',
    		'240891449',
    	),
    	'invalid' => array(
    		'240800205',
    		'248428456',
    		'241067397',
    		'240891442',
    	)
	),
	'InscEstadualAM' => array(
    	'valid' => array(
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualAP' => array(
    	'valid' => array(
    		'ISENTO',
    		'isento',
	        '030164406',
    	),
    	'invalid' => array(
    		'030124784',
    	)
	),
	'InscEstadualBA' => array(
    	'valid' => array(
    		'ISENTO',
    		'isento',
	        '61.234.557',
    		'23.822.544',
    		'74.298.473',
    		'01.021.709',
    		'38.327.007',
    	),
    	'invalid' => array(
    		'38.327.017',
    		'73.822.544',
    	)
	),
	'InscEstadualCE' => array(
    	'valid' => array(
    		'06.000001-5',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualDF' => array(
    	'valid' => array(
    		'0730000100109',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualES' => array(
    	'valid' => array(
    		'080.128.84-0',
			'080.266.07-0',
			'081.099.89-4',
			'081.219.92-0',
			'081.245.54-8',
			'081.967.14-4',
			'081.607.67-9',
			'081.955.10-3',
			'082.186.37-5',
			'082.225.32-0',
    	),
    	'invalid' => array(
			'082.225.32-1',
    	)
	),
	'InscEstadualGO' => array(
    	'valid' => array(
    		'10.987.654-7',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualMA' => array(
    	'valid' => array(
    		'120000385',
    	),
    	'invalid' => array(
    		'120000384',
    	)
	),
	'InscEstadualMT' => array(
    	'valid' => array(
    		'13.116.895-9',
			'13.123.599-0',
			'13.050.343-6',
			'13.027.543-3',
			'13.134.064-6',
			'13.183.371-5',
			'13.325.902-1',
			'13.227.180-0',
			'13.281.374-2',
			'13.187.540-0',
			'13.197.470-0',
			'13.272.809-5',
			'13.278.476-9',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualMS' => array(
    	'valid' => array(
			'28.055.484-2',
			'28.249.504-5',
			'28.249.504-5',
			'28.282.716-1',
			'28.055.484-2',
			'28.333.817-2',
			'28.316.790-4',
			'28.282.716-1',
			'28.249.504-5',
			'28.227.400-6',
			'28.503.558-4',
			'28.557.771-9',
			'28.513.250-4',
			'28.001.360-4',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualMG' => array(
    	'valid' => array(
			'367.219.883/0370',
			'367.219.883/0036',
			'625.054.797/0013',
			'625.752.991/0051',
			'688.597.382/0008',
			'461.013.765/0050',
			'512.226.054/0060',
			'518.027.950/0003',
			'062.307.904/0081',
			'621.830610.0018',
			'687.013342.0352',
			'367.198629.0213',
			'734.365553-0345',
    	),
    	'invalid' => array(
			'367.219.883/0360',
    	)
	),
	'InscEstadualPA' => array(
    	'valid' => array(
    		'15.261.936-4',
			'15.123.291-1',
			'15.241.277-8',
			'15.085.243-6',
    	),
    	'invalid' => array(
    		'10.261.936-4',
			'15.123.291-2',
			'15.241.277-9',
			'15.085.243-7',
    	)
	),
	'InscEstadualPB' => array(
    	'valid' => array(
    		'16.148.878-1',
    		'16.004.017-5',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualPE' => array(
    	'valid' => array(
    		'032141840',
    		'32141840',
    		'00000032141840',
    	),
    	'invalid' => array(
    		'032141841',
    	)
	),
	'InscEstadualPI' => array(
    	'valid' => array(
    		'193016567',
    		'19.301.656-7',
    		'194630170',
    		'19.463.017-0',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualPR' => array(
    	'valid' => array(
    		'90174147-60',
    		'42206147-90',
    		'90204521-00',
    		'60121713-99',
    		'90162148-90',
    	),
    	'invalid' => array(
    		'90174147-61',
    	)
	),
	'InscEstadualRJ' => array(
    	'valid' => array(
    		'85.607.030',
    		'82.228.950',
    		'86.114.690',
    		'86.291.894',
    		'77.659.188',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualRN' => array(
    	'valid' => array(
    		'20.204.476-9',
    	),
    	'invalid' => array(
    		'20.204.476-8',
    	)
	),
	'InscEstadualRO' => array(
    	'valid' => array(
    		// new format: after 2000-08-01
    		'0000000062521-3',
    		// old format: before 2000-08-01
    		'101.62521-3',
    		// others
			//,'171743'
			'00000000171743',
			'00000000415138',
			'00000000963011',
			'00000001262025',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualRR' => array(
    	'valid' => array(
    		'24006628-1',
    		'24001755-6',
    		'24003429-0',
    		'24001360-3',
    		'24008266-8',
    		'24006153-6',
    		'24007356-2',
    		'24005467-4',
    		'24004145-5',
    		'24001340-7',
    	),
    	'invalid' => array(
    		'24006628-2',
    		'24001755-3',
    		'24003429-4',
    		'24001360-5',
    		'24008266-6',
    		'24006153-7',
    		'24007356-8',
    		'24005467-9',
    		'24004145-0',
    		'24001340-1',
    	)
	),
	'InscEstadualRS' => array(
    	'valid' => array(
    		'224/3658792',
    		'096.318191-2',
    		'096/3181912',
    		'096.290760-0',
    		'096/2907600',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualSC' => array(
    	'valid' => array(
    		'251.040.852',
    		'251268675',
    		'253.441.013',
    	),
    	'invalid' => array(
    		'251.040.851',
    		'251268674',
    		'253441015',
    	)
	),
	'InscEstadualSE' => array(
    	'valid' => array(
    		'27123456-3',
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualSP' => array(
    	'valid' => array(
    		'114.816.816.117',
    		'110.042.490.114',
    		'675.163.436.118',
			'P-01100424.3/002', // inscrição rural
    	),
    	'invalid' => array(
    	)
	),
	'InscEstadualTO' => array(
    	'valid' => array(
    		'29.065.852-7',
    		'29.069.489-2',
    		'29.067.392-5',
    	),
    	'invalid' => array(
    	)
	),
	'Numero' => array(
		'valid' => array(
			'123',
			'1.234',
		)
		,'invalid' => array(
			'1,234',
			//,'0.987.654.231.234,56'
		)
	),
	'NumeroDecimal' => array(
		'valid' => array(
			'123',
			'1.234',
			'1.234,56',
			'1.234,567890123456789',
		)
		,'invalid' => array(
			'1234,56',
			'1,234.56',
			//'0.987.654.231.234,56',
		)
	),
	'Telefone' => array(
    	'valid' => array(
//			'1234567',
//			'12345678',
			'123-4567',
			'1234-5678',
//			'21 1234567',
//			'21 12345678',
			'21 123-4567',
			'21 123-45678',
//			'(21) 1234567',
//			'(21) 12345678',
//			'(21) 123-4567',
//			'(21) 1234-5678',
    	)
    	,'invalid' => array(
			'123456',
			'123456789',
			'211234567',
			'2112345678',
			'(21) 1234567',
    	)
    ),
);

//
// RUN TESTING
//

// count
$sum = 1;
foreach($aStrings as $val)
{
	$sum += count($val['valid']) + count($val['invalid']);
}

$t = new lime_test($sum, new lime_output_color());

// __construct()
$t->diag('__construct()');
try
{
	new sfBrazilianValidator();
	$t->fail('__construct() throws an RuntimeException if you don\'t pass a pattern option');
}
catch (RuntimeException $e)
{
	$t->pass('__construct() throws an RuntimeException if you don\'t pass a pattern option');
}

foreach($aStrings as $type => $aStringsType)
{
	// ->type
	$t->diag($type);
	$v = new sfBrazilianValidator(array('type' => $type));

	// valid
	foreach($aStringsType['valid'] as $string)
	{
		try
		{
			$v->clean($string);
			$t->pass('checking valid...');
		}
		catch (sfValidatorError $e)
		{
			$t->fail('Must be valid: ' . $string);
			$t->skip('', 1);
		}
	}

	// invalid
	foreach($aStringsType['invalid'] as $string)
	{
		try
		{
		  $v->clean($string);
		  $t->fail('Must be invalid: ' . $string);
		  $t->skip('', 1);
		}
		catch (sfValidatorError $e)
		{
			$t->pass('checking invalid...');
		}
	}
}