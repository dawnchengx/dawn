<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/11
 * Time: 15:27
 */

class base
{
    private $pdo = null;
    private $keys = '';
    private $where = '';
    private $sql = '';
    private $db = null;
    public function __construct($mc)
    {
        $this->pdo = new PDO("{$mc['type']}:host={$mc['host']};dbname={$mc['dbname']}", $mc['user'], $mc['pass']);
    }

    public function db($dbname) {
        $this->db = $dbname;
        return $this;
    }

    public function where($key = '', $type = '', $value = '', $operate = ''){
        $this->where = "$operate $key $type $value";
        return $this;
    }

    public function insert($data){
        $this->sql ="INSERT INTO {$this->db} ";
        $keys = '';
        $values = '';
        foreach($data as $k => $v){
            $keys .= ",$k";
            $values .= ",$v";
        }
        $keys = substr($keys, 1);
        $values = substr($values, 1);
        $this->sql .= "({$keys}) VALUES ({$values})";
        $this->pdo->exec($this->sql);
        return [
          'status' => 0,
          'id' => $this->pdo->lastInsertId()
        ];
    }

    public function delete(){
        $this->sql .= "delete from {$this->db} where {$this->where}";
        $this->pdo->exec($this->sql);
        return [
            'status' => 0,
        ];
    }

    public function update($data){
        $this->sql = "UPDATE {$this->db} SET ";
        $kv = '';
        foreach($data as $k => $v){
            $kv = ",$k = $v";
        }
        $kv = substr($kv, 1);
        $this->sql .= "{$kv} WHERE $this->where";
        $this->pdo->exec($this->sql);
        return [
            'status' => 0,
        ];
    }

    public function query(){
        if('' == $this->keys) {
            $this->keys = '*';
        }
        $this->sql = "SELECT {$this->keys} FROM {$this->db} ";
        if('' != $this->where) {
            $this->sql .= "WHERE {$this->where}";
        }
        $stmt = $this->pdo->prepare($this->sql);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'status' => 0,
            'res' => $res
        ];
    }
}