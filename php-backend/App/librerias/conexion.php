<?PHP


class conexMsql {

    private $host;
    private $usuario;
    private $contrasena;
    private $nombreBD;
    private $validacionConexion;

    function __construct() {
        //produccion
        $this->host = "192.168.0.10";
        //Local
       // $this->host = "192.168.0.12";
        $this->usuario = "joseluis";
        $this->contrasena = "Treseditores2018";
        $this->nombreBD = "joseluis_db";
        $this->validacionConexion = mysqli_connect($this->host, $this->usuario, $this->contrasena, $this->nombreBD) or die('Connection failed !!' . mysqli_connect_error());
        mysqli_set_charset($this->validacionConexion,'utf8');
    }
    public function cadenaConexion ()
    {
        return $this->validacionConexion;
    }

    public function consultaComplejaNorAso($sql)
    {
        $result = mysqli_query($this->validacionConexion, $sql);
        if (mysqli_num_rows($result)>0)
        {
            return mysqli_fetch_assoc($result);
        }else
        {
            return 0;
        }

    }
    public function consultaComplejaAso($sql)
    {
        $result = mysqli_query($this->validacionConexion, $sql);
        if (mysqli_num_rows($result)>0)
        {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }else
        {
            $data=0;
        }

        return $data;
    }
    public function consultaComplejaNor($sql)
    {
        $consulta= mysqli_query($this->validacionConexion, $sql);
        return $consulta;
    }
    public function consultaSimple($sql) {
        mysqli_query($this->validacionConexion, $sql);
    }
    public function consultaSimpleReturnId($sql) {
        mysqli_query($this->validacionConexion, $sql);
        return $this->validacionConexion->insert_id;
    }


}


use Symfony\Component\Yaml\Yaml;

class conexPG
{
    private $validacionConexion;

    function __construct()
    {
        $config = Yaml::parseFile(__DIR__ . './../../Config/config.yml');
        $this->validacionConexion = pg_connect("host=192.168.0.3 dbname=sabersaidb user=postgres password=P0stgressql options='--client_encoding=UTF8'") or die('NO HAY CONEXION: ' . pg_last_error());
        pg_client_encoding($this->validacionConexion);
    }

    public function getConexion()
    {
        return $this->validacionConexion;
    }
    public function consultaComplejaNorAso($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);
        if (pg_num_rows($result)>0)
        {
            return pg_fetch_assoc($result);
        }else
        {
            return 0;
        }

    }
    public function consultaComplejaNor($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);

        return $result;
    }
    public function consultaComplejaAso($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);
        if (pg_num_rows($result)>0)
        {
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
        }else
        {
            $data=0;
        }

        return $data;
    }

    public function consultaSimple($sql)
    {
        pg_query($this->validacionConexion, $sql);
    }
}
class conexPGSeguridad
{
    private $validacionConexion;

    function __construct()
    {
        $config = Yaml::parseFile(__DIR__ . './../../Config/config.yml');
        $this->validacionConexion = pg_connect("host=localhost dbname=ltesoftwarefinal user=postgres password=root options='--client_encoding=UTF8'") or die('NO HAY CONEXION: ' . pg_last_error());
        pg_client_encoding($this->validacionConexion);
    }

    public function getConexion()
    {
        return $this->validacionConexion;
    }
    public function consultaComplejaNorAso($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);
        if (pg_num_rows($result)>0)
        {
            return pg_fetch_assoc($result);
        }else
        {
            return 0;
        }

    }
    public function consultaComplejaNor($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);

        return $result;
    }
    public function consultaComplejaAso($sql)
    {
        $result = pg_query($this->validacionConexion, $sql);
        if (pg_num_rows($result)>0)
        {
            while ($row = pg_fetch_assoc($result)) {
                $data[] = $row;
            }
        }else
        {
            $data=0;
        }

        return $data;
    }

    public function consultaSimple($sql)
    {
        pg_query($this->validacionConexion, $sql);
    }
}

