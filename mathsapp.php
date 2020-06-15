<?php 

	function mathsApp($req){
		$allParams = $req['queryResult']['allRequiredParamsPresent'];
		if($allParams === 0) exit();

		$math = $req['queryResult']['parameters']['aritmetica'];
		$regex = '/([\d]+[.][\d]+|[\d]+[.]?|[.][\d]+)\s*?([+]|[x]|[*]|[\/]|[-])\s*?([\d]+[.][\d]+|[\d]+|[.][\d]+)/';

		if(!preg_match($regex, $math, $match)){
			echo json_encode((array(
 			'fulfillmentText' => 'Oops, parece que no entendí esa operación. Intenta de nuevo escribiendo "MathsApp"',
 			'source' => 'MathsApp')));
 			exit();
		}

		$a = $match['1']; $b = $match['3'];

		switch ($match['2']) {
			case '-':
				$result = $a-$b;
				break;

			case '+':
				$result = $a+$b;
				break;
			
			case '*':
				$result = $a*$b;
				break;
						
			case 'x':
				$result = $a*$b;
				break;
									
			case '/':
				$result = (!empty($b)) 
					? $a/$b 
					: NULL;
				break;
		}

		if(isset($result)){
			echo json_encode((array(
				'fulfillmentText' => 'El resultado es: *'.$result
					.'*. Realiza otra operación escribiendo el comando *MathsApp*',
				'source' => 'MathsApp')));
		}else{
			echo json_encode((array(
				'fulfillmentText' => '*Es imposible dividir entre 0!* Realiza otra operación escribiendo el comando *MathsApp*',
				'source' => 'MathsApp')));			
		}

		exit();
	}

	function logRequest($input){
		if(is_array($input)){
			ob_start();
			print_r($input);
			$input = ob_get_contents();
			ob_end_clean();
			file_put_contents('request.log',$input.PHP_EOL,FILE_APPEND);
		}elseif(is_string($input)){
			file_put_contents('vars.log',$input.PHP_EOL,FILE_APPEND);			
		}
	}

  	//obtenemos el post desde dw
  	$req = file_get_contents("php://input");
  	$req = json_decode($req, true);

	mathsApp($req);

?>