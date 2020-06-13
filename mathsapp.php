<?php 

  require('connection.php');
  
	define('mathInput', 'projects/mathsapp-flow-cirbyf/agent/intents/4058c8f9-bffb-40ef-9794-22f9503309cd');
  
  function pickAgent($req){
	  $intName = ($req) ? $req['queryResult']['intent']['name'] : exit();

	  switch ($intName) {
	  	case ($intName === mathInput):
	  		mathsApp($req);
	  		break;
	  	
	  	default:
	  		exit();
	  		break;
	  }

	  return $agent;
	}

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
				$result = $a/$b;
				break;
		}

		echo json_encode((array(
			'fulfillmentText' => 'El resultado es: *'.$result
				.'*. Realiza otra operación escribiendo el comando *MathsApp*',
			'source' => 'MathsApp')));

	}

	function logRequest($input){
		switch ($input) {
			case (is_array($input)):
				ob_start();
				print_r($input);
				$input = ob_get_contents();
				ob_end_clean();
				file_put_contents('request.log',$input.PHP_EOL,FILE_APPEND);
				break;
			
			default:
				file_put_contents('vars.log',$input.PHP_EOL,FILE_APPEND);
				break;
		}
	}


  //obtenemos el post desde dw
  $req = file_get_contents("php://input");
  $req = json_decode($req, true);

	mathsApp($req);

?>