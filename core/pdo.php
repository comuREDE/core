 <?php 

/**
Classe que gerencia toda a logica de BD. Compativel com mysql postgresql
*/


class BD {
	private static $instance;
	private static $cn;

	function __construct($type="mysql"){

		if($type==="mysql"){
			$dsn = sprintf("mysql:host=%s;dbname=%s",HOST,NAME);
			$options = [ PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'", ]; 
			#$options = [];
		} else {
			$options = [];
			$dsn = sprintf("pgsql:host=%s;dbname=%s",HOST,NAME);
		}
		
		try {
		    self::$cn = new PDO($dsn,USER,PASS,$options);
		} catch (PDOException $e) {
		    echo "Exception do PDO :".$e->getMessage();
		}

	}

	/**
	Utilizacao do padrao de projeto singleton, garantira uma unica instancia da conexao BD
	*/

	public static function getInstance(){
		if(empty(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}

	private static function getCN(){
		self::getInstance();
		return self::$cn;
	}


	/**
	Essa e a funcao que faz toda a logica de persistencia e crud, ja foi bem testada
	Voltada para prepared statements. Suporta paginacao e ordenacao
	Se quiser aqui vc faz o escape de toda a saida do BD
	Mandar o order em string pura mesmo por causa de asc e desc
	*/

	function query(string $sql, array $campos=[],string $order=null,int $pagina=null) { 
	    $pdo = self::getCN();

	    #echo $sql;

		$tipo = strtolower(substr($sql, 0, 6));
		
		#echo $tipo;

		if($order){
			$sql .= " ORDER BY ". $order;
		}
		if($pagina){
			$per_pag=5;

		    $offset = $pagina > 1? ($pagina * $per_pag)-$per_pag : 0;
		    $limit = $per_pag;
		    $sql .= sprintf(" LIMIT %d OFFSET %d",$limit,$offset);
		}
 		
		#echo $sql,'<br>';

        $stmt = $pdo->prepare($sql);
        if(count($campos)>0){
            foreach ($campos as $i => $valor) {
                $i++;
                #$stmt->bindValue($i,$valor);
                $stmt->bindValue($i,$valor,$this->param_type($valor)); 
                #ta com erro em param_type, postgre nao funciona
            }
        }

        #echo $sql;
        #echo print_r($campos);

        #echo "<hr>";

        if(!$stmt->execute()){
       		return $stmt->errorInfo()[2]; # a chave 2 e que contem a descricao do erro
        
        } else {
        	#echo $tipo;
			switch ($tipo) {
				case 'select':
		            #if($stmt->columnCount() == 1){
		            # return $stmt->fetchColumn(); 
		            # nao pode usar este recurso, pois no caso de select campo from x da erro	
		            #} 
					#No mysql ele vem sempre no formato da chave 0 contendo os dados
		        	$res = $stmt->fetchAll(PDO::FETCH_ASSOC); #array vazio se nao tem nada 
		        	#$array = array_walk_recursive($res, 'htmlspecialchars');
		        	#var_dump($res);die;
		        	#return $res?$res[0]:false;
		        	return $res;
				break;
				
				case 'insert':
		            return (int) $pdo->lastInsertId();
				break;
				
				case 'update':
				case 'delete':
					return (int) $stmt->rowCount(); 
					#se ele nao mudar nada no mysql vem 0, no postgre parece que vem sempre 1
				break;
				
				default:
					echo "metodo nao suportado";die;
				break;
			}

        }

	} 

	/**
	Funcao aux que serve para informar o tipo de dado no prepared st
	Somente mysql. No postgre nao funciona
	*/

	private function param_type($param) {
	   	if (ctype_digit((string) $param))
	        return $param <= PHP_INT_MAX ? PDO::PARAM_INT :  PDO::PARAM_STR;
	    if (is_bool($param))
	        return PDO::PARAM_BOOL;
	    if (is_null($param))
	        return PDO::PARAM_NULL;

	    return PDO::PARAM_STR;

	}

	/**
	Funcao que monta a query para se debugar erros de SQL, pois com prepared st vc nao consegue printar a query do jeito correto
	*/

	protected function debugSQL($tabela, $sql,$post,$valores,$where){

		if(substr($sql,0,6)==="UPDATE"){
	        $set = "";
	        foreach ($post as $key => $value) {
	        	$set .= "$key = '$value',";
	        }
	        $set = trim($set,",");
    	    echo "UPDATE $tabela SET $set WHERE $where;";

		} else if(substr($sql,0,6)==="INSERT"){
	        echo "INSERT INTO $tabela ($post) VALUES ('". trim(implode("','",$valores),',') ."');<br>";
		} else {
			echo "ERRO em DEBUG";
		}

	}

}


class Model extends BD{

	private $table;
	private $pk='id';

	/**

	*/
	public function setTable($t){
		$this->table=$t;
		return $this;
	}

	public function getTable(){
		return $this->table;
	}

	/**

	*/
	public function setPK($pk){
		$this->pk=$pk;
	}

	public function getPK(){
		return $this->pk;
	}


	/**

	*/
	function getOneBy($valor,string $campos='*',string $where='id') {
	    $PK=$where."=?";
	    $tabela = $this->getTable();
	    $sql = sprintf("SELECT %s FROM %s WHERE %s LIMIT 1;",$campos,$tabela,$PK);

	    $valores=[$valor];
	    $res = $this->query($sql, $valores);

	    return array_key_exists(0, $res)?$res[0]:$res; 
	}


	/**

	*/
	function getOne($valor,string $campos='*') {
	    $PK=$this->getPK()."=?";
	    $sql = sprintf("SELECT %s FROM %s WHERE %s LIMIT 1;",$campos,$this->getTable(),$PK);
	    $valores=[(int) $valor];

	    #echo $sql;
	
	    $res = $this->query($sql, $valores);

	    return array_key_exists(0, $res)?$res[0]:$res; 
	}


	#function getAllBySQL($sql)
	#manda a funca para a query direto formato : SELECT xxx FROM tabela WHERE a=? and b=? -- junto com ['a'=>123,'b'=>'abc']


	/**

	*/
	function getAll(string $campos='*') {
	    $sql = sprintf("SELECT $campos FROM %s;",$this->getTable());
	    $retorno = $this->query($sql);
	    #var_dump($retorno);die;
	    return $retorno;
	}

	/**

	*/
	function getAllPaginate(string $campos='*',string $order='id ASC',int $pag=1){
	    $sql = sprintf("SELECT $campos FROM %s ",$this->getTable());
		return $this->query($sql, [], $order, $pag);
	}

	/**

	*/
	function count(): int{
		$res = $this->getAll('count(1) as total');
		#var_dump($res);die;
		return $res[0]['total'];
	}


	/**

	*/
	function save(array $post){
		$pk = $this->getPK();
		return array_key_exists($pk, $post) && is_numeric($post[$pk])? $this->upd($post) : $this->add($post);
	}



	/**

	*/
	function upd(array $post){
	    $tabela = $this->getTable();
	    #var_dump($post);
	    if(count($post) > 0){
	    	#echo "aaaaaaaaaaaaaaaaaaaaaaaaaaa";
	    
	    	$pk = $this->getPK();
	    	#var_dump($post);
	        ########################## usar array_push para por o id no final
	        if(!array_key_exists($pk, $post)){
  	        	echo json_encode(['ERROR'=>'Nao tem chave primaria'],true);die;
	        }

	        $id=(int) $post[$pk];
	        unset($post[$pk]);
	        $where=$pk."=?";

	        $valores=array_values($post);#fazer antes de eliminar a pk
	        array_push($valores, $id); #colocar o id no final
	        $campos=array_keys($post);
	        $campos=implode("=?,",$campos);
	        $campos.="=?";
	        
	        #echo 'cccccccccccc';
	        $sql = "UPDATE $tabela SET %s WHERE %s;";
	        #echo $sql;
	        #echo 'w:',$where;

	        $q = sprintf($sql, $campos, $where);

	        #$this->debugSQL($tabela, $sql,$post,$valores,'id='.$id);

	        return $this->query($q, $valores);

	    } else {
        	echo json_encode(['ERROR'=>'array vazio'],true);die;
	    }
		
	}



	/**

	*/
	function add(array $post,$incluirPK=false){
	    $tabela = $this->getTable();
	    if(count($post) > 0){
	        if($incluirPK && array_key_exists($this->getPK(), $post)){
	        	unset($post[$this->getPK()]);
	        }

	        $campos=implode(',',array_keys($post));
	        $valores=array_values($post);
	        $values = trim(str_repeat('?,',count($post)),',');

	        #$q = "INSERT INTO $tabela (nome) VALUES (?) ON CONFLICT (nome) DO UPDATE SET nome=?;";
	        $sql = "INSERT INTO $tabela (%s) VALUES (%s);";
	        
	        $q = sprintf($sql, $campos, $values);

		#	$this->debugSQL($tabela, $sql, $campos, $valores,null);


	        return $this->query($q, $valores);
	    } else {
        	echo json_encode(['ERROR'=>'array vazio'],true);die;
	    }
	}

	#limit no psql e diferente
	/**

	*/
	function delOneBy($valor,$campo='id'){
	    $tabela = $this->getTable();
	    $PK=$campo."=?";
	    $sql = sprintf("DELETE FROM %s WHERE %s;",$tabela,$PK);
	    $valores=[$valor];

	    return (bool) $this->query($sql, $valores); # se repetir 2x da erro pois ele deleta oq ja foi deletado	
	}


}
