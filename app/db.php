<?php

class DB {
    
    private $result = NULL;
    private $mysqli = NULL;
    private $current_sql = '';
    private $prefix = NULL;
    private $query_count = NULL;
    private static $instance = NULL;
    
    public static function getInstance($config = array(DB_HOST,
                                                       DB_USER, 
                                                       DB_PASSWORD, 
                                                       DB_NAME, 
                                                       DB_PREFIX) )
    {
        if ( is_null(self::$instance) )
        {
            self::$instance = new self($config);
            return self::$instance;
        }
        return FALSE;
    }
    
    public function __construct(array $config = array() )
    {
        list($host, $user, $password, $database, $prefix) = $config;
        if ( is_null($this->mysqli) )
        {
            $this->mysqli = new mysqli($host, $user, $password, $database);
            if ( mysqli_connect_errno() )
            {
                error_msg('Error connecting to MySQL: '.mysli_connect_error());
            }
            else
            {
                $this->mysqli->query('SET NAMES '.DB_CHARSET);
                $this->prefix = $prefix;
            }
        }
    }    
    
    public function query($sql)
    {
        $this->current_sql = $sql;
        if ( is_string($sql) and !empty($sql) )
        {
            $this->result = $this->mysqli->query($sql);
            if ( $this->mysqli->error )
            {
            //    error_msg('SQL Query Error: '.$this->mysqli->error);
                return FALSE;
            }
            else
            {
                $this->query_count++;
            }
        }
        return $this->result;
    }
    
    public function sql_first_row($sql, $assoc = FALSE)
    {
        return ($this->result = $this->query($sql)) ? $this->result_first_row($this->result, $assoc) : FALSE;
    }
    
    public function get_prefix($table_name)
    {
        return $this->prefix.$table_name;
    }
    
    public function sql_rows($sql, $assoc = FALSE)
    {
        $data = $assoc ? array() : new stdClass;
        $index_num = -1;
        if ( $this->result = $this->query($sql) )
        {
            while ( $row = $this->result_first_row($this->result, $assoc) )
            {
                $index_num++;
                if ( $assoc )
                {
                    $data[$index_num] = $row;
                }
                else
                {
                    $data->{$index_num} = $row;
                }
            }
            $this->free_result();
            return ($index_num > -1) ? $data : FALSE;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function insert($table_name, $data)
    {
        return $this->insert_replace($this->prefix.$table_name, $data, 'INSERT');
    }
    
    public function replace($table_name, $data)
    {
        return $this->insert_replace($this->prefix.$table_name, $data, 'REPLACE');
    }
    
    private function insert_replace($table_name, $data, $type= 'INSERT')
    {
        if ( !in_array(strtoupper($type), array('REPLACE', 'INSERT')) )
        {
            return FALSE;
        }
        foreach ( $data as $key => $val )
        {
            if ( !is_numeric($val) )
            {
                $data[$key] = '\''.$val.'\'';
            }
        }
        $sql = sprintf($type.' INTO %s (%s) VALUES (%s)', $table_name,
                             implode(', ', array_keys($data)),
                             implode(', ', array_values($data)) );
        return $this->query($sql);
    }
    
    public function update($table_name, $data, $where = '1')
    {
        $ret = array();
        foreach ( $data as $key => $value )
        {
            $ret[] = $key.'=\''.$value.'\'';
        }
        $sql = 'UPDATE '.$table_name.' SET '.implode(', ', $ret).' WHERE '.$where;
        return $this->query($sql);
    }
    
    public function result_all_table($table_name, $assoc = FALSE)
    {
        $data = $assoc ? array() : new stdClass;
        $index_num = -1;
        if ( $this->result = $this->query('SELECT * FROM '.$this->prefix.$table_name) )
        {
            while ( $row = $this->result_first_row($this->result, $assoc) )
            {
                $index_num++;
                if ( $assoc )
                {
                    $data[$index_num] = $row;
                }
                else
                {
                    $data->{$index_num} = $row;
                }
            }
            $this->free_result();
            return ($index_num > -1) ? $data : FALSE;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function num_rows($result = NULL)
    {
        return is_null($result) ? $this->result->num_rows : $result->num_rows;
    }
    
    public function result_first_row($result = NULL, $assoc = FALSE)
    {
        if ( $result == NULL )
        {
            $result = $this->result;
        }
        if ( empty($result) )
        {
            return FALSE;
        }
        return $assoc ? $result->fetch_assoc() : $result->fetch_object();
    }    
    
    public function get_current_sql()
    {
        return $this->current_sql;
    }
    
    public function affected_rows()
    {
        return $this->mysqli->affected_rows;
    }
    
    public function result_first_row_col()
    {
        if ( !empty($this->result) )
        {
            $row = $this->result->fetch_array();
            return $row[0];
        }
        else
        {
            return FALSE;
        }
    }
    
    public function total($table, $where = '1')
    {
        $sql = 'SELECT count(*) FROM '.$this->prefix.$table.' WHERE '.$where;
        $this->query($sql);
        return $this->result_first_row_col();
    }
    
    public function insert_id()
    {
        return $this->mysqli->insert_id;
    }
    
    public function __destruct()
    {
        $this->mysqli->close();
    }
    
    public function free_result($result = NULL)
    {
        return is_null($result) ? @mysqli_free_result($this->result) : @mysqli_free_result($result);
    }
    
    public function list_tables($db = DB_NAME)
    {
        return $this->sql_rows('SHOW TABLES FROM '.$db);
    }
    
    public function field_count()
    {
        return $this->result->field_count;
    }
}

/* End of file model.php */