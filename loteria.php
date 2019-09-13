<?php

class Loteria
{

	private $qtd_dezenas;
	private $resultado;
	private $total_jogos;
	private $jogos;
	
	public function __construct($qtd_dezenas, $total_jogos)
	{
		$this->qtd_dezenas = $qtd_dezenas;
		$this->total_jogos = $total_jogos;
	}
	
	public function setQtdDezenas($qtd_dezenas)
	{
		$this->qtd_dezenas = $qtd_dezenas;
		return $this;
	}
	
	public function getQtdDezenas()
	{
		return $this->qtd_dezenas;
	}
	
	public function setResultado($resultado)
	{
		$this->resultado = $resultado;
		return $this;
	}
	
	public function getResultado()
	{
		return $this->resultado;
	}
	
	public function setTotalJogos($total_jogos)
	{
		$this->total_jogos = $total_jogos;
		return $this;
	}
	
	public function getTotalJogos()
	{
		return $this->total_jogos;
	}
	
	public function setJogos($jogos)
	{
		$this->jogos = $jogos;
		return $this;
	}
	
	public function getJogos()
	{
		return $this->jogos;
	}
	
	public function criarDezenas()
	{
		$dezenas = [];
		$qtd_dezenas = $this->getQtdDezenas();
		
		do{
			do{
				$dezena = str_pad(rand(1,60), 2, 0, STR_PAD_LEFT); 
			}while(in_array($dezena, $dezenas));
				   
			$dezenas[] = $dezena;  	
		}while(count($dezenas) < $qtd_dezenas);
		
		array_multisort($dezenas);
		
		return $dezenas;
	}
	
	public function criarJogos()
	{
		$jogos = [];
		$total_jogos = $this->getTotalJogos();
		
		for($i = 0; $i < $total_jogos; ++$i){
			
			do{
				$dezena = $this->criarDezenas();
			}while(in_array($jogos, $dezena));
			   
			array_push($jogos, $dezena);	
		}
		
		array_multisort($jogos);
		
		$this->setJogos($jogos);
	}
	
	public function sorteio()
	{
		$resultado = $this->criarDezenas();
		
		$this->setResultado($resultado);
	}
	
	public function gerarResultado()
	{
		$jogos = $this->getJogos();
		$resultado = $this->getResultado();
		$resultado_txt = implode(", ", $resultado);
		
		$html = "<html><body><table>";
		
		foreach($jogos as $jogo){
			
			$dezenas_sorteadas = [];
			$html .= "<tr>";
			
			foreach($jogo as $index => $dezena){
				
				$html .= "<td>" . $dezena . "</td>";
				
				foreach ($resultado as $dezena_resultado){
					
					if($dezena === $dezena_resultado){
						array_push($dezenas_sorteadas, $dezena);
					}
				}
			}
			
			$dezenas_sorteadas_txt = implode(", ", $dezenas_sorteadas);
			
			$html .= "<td> || Dezenas Sorteadas " . 
				count($dezenas_sorteadas)  
				. " ==> " . 
				$dezenas_sorteadas_txt 
				. "</td>";
			$html .= "</tr>";
		}
		
		$html .= "</table>";
		$html .= "<p> Resultado: " . $resultado_txt . "</p></body></html>";
		
		return $html;
	}
};


$loteria = new Loteria(6,10); #Instancia

$loteria->criarJogos(); #criar os jogos de acordo com a variavel qtd_jogos

$loteria->sorteio(); #realiza o sorteio das dezenas premiadas

echo $loteria->gerarResultado(); #retorna o html com os resultados
