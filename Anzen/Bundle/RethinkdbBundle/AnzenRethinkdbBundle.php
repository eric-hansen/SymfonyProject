<?php

namespace Anzen\Bundle\RethinkdbBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnzenRethinkdbBundle extends Bundle
{
    protected function __construct($conn=null, $tbl="", $query=null){
        $this->conn = $conn;
        $this->tbl = $tbl;
        $this->query = $query;
    }
    
    protected function getQuery(){
        return $this->query;
    }
    
    public function connect($db, $host='localhost', $port=28015, $auth_key=""){
        if(!isset($this->conn))
            $this->conn = r\connect($host, $port, $db, $auth_key);
        
        return new self($this->conn, $this->tbl, $this->query);
    }
    
    public function table($tbl="", $get_outdated=false){
        if(empty($tbl)){
            if(!$this->query)
                $this->query = r\table($this->tbl, $get_outdated);
        } else{
            $this->tbl = $tbl;
        }
        
        return $this;
    }
    
    public function close($reconn=false, $noreplywait=true){
        if($reconn)
            $this->conn->reconnect($noreplywait);
        else
            $this->conn->close($noreplywait);
        
        return $this;
    }
    
    public function insert(array $data = array(), array $opts = array()){
        $this->query = $this->query->insert($data, $opts);
        
        return $this;
    }
    
    public function delete(array $opts = array()){
        $this->query = $this->query->delete($opts);
        
        return $this;
    }
    
    public function replace(array $new_values = array(), array $opts = array()){
        $this->query = $this->query->update($new_values, $opts);
        
        return $this;
    }
    
    public function sync(){
        $this->query = $this->query->sync();
        
        return $this->run();
    }
    
    public function select($key, $all=false, array $opts = array()){
        $this->query = ($all == false) ? $this->query->get($key) : $this->query->getAll($key, $opts);
        
        return $this->run();
    }
    
    public function between($low, $high, $index="id", $left_bound="closed", $right_bound="open"){
        $this->query = $this->query->between($low, $high, array("index" => $index, "left_bound" => $left_bound, "right_bound" => $righ_bound));
        
        return $this->run();
    }
    
    public function filter($filter){
        $this->query = $this->query->filter($filter);
        
        return $this;
    }
    
    public function join($type, $tbl, $args=null){
        $tbl_ref = $this->table($tbl);
        $tbl_ref = $tbl_ref->getQuery();
        
        switch($type){
            case "outer":
                $this->query = $this->query->outerJoin($tbl_ref, $args);
                break;
            
            case "eq":
                if(!isset($args['index']))
                    $args['index'] = "id";
                
                $this->query = $this->query->eqJoin($args['attr'], $tbl_ref, array("index" => $args['index']));
                break;
            
            case "inner":
            default:
                $this->query = $this->query->innerJoin($tbl_ref, $args);
                break;
        }
        
        return $this;
    }
    
    public function zip(){
        $this->query = $this->query->zip();
        
        return $this;
    }
    
    public function orderBy($args){
        $this->query = $this->query->orderBy($args);
        
        return $this;
    }
    
    public function skip($records_skip){
        $this->query = $this->query->skip($records_skip);
        
        return $this;
    }
    
    public function limit($records){
        $this->query = $this->query->limit($records);
        
        return $this;
    }
    
    public function slice($start, $end){
        $this->query = $this->query->slice($start, $end);
        
        return $this;
    }
    
    public function nth($n){
        $this->query = $this->query->nth($n);
        
        return $this->run();
    }
    
    public function indexesOf($param){
        $this->query = $this->query->indexesOf($param);
        
        return $this;
    }
    
    public function isEmpty(){
        $this->query = $this->query->isEmpty();
        
        return $this->run();
    }
    
    public function union($seq){
        $this->query = $this->query->union($seq);
        
        return $this;
    }
    
    public function sample($number){
        $this->query = $this->query->sample($number);
        
        return $this;
    }
    
    public function count($filter=null){
        $this->query->count($filter);
        
        return $this->run();
    }
    
    public function run($remove_query=false){
        $res = $this->query->run($this->conn);
        
        if($remove_query)
            $this->query = null;
        
        return $res;
    }
    
    public function distinct(){
        $this->query = $this->query->distinct();
        
        return $this;
    }
    
    public function groupBy($selectors, $reduction){
        $this->query = $this->query->groupBy($selectors, $reduction);
        
        return $this;
    }
    
    public function contains($value){
        $this->query = $this->query->contains($value);
        
        return $this->run();
    }
    
    public function _count(){
        return r\count();
    }
    
    public function _sum($attr){
        return r\sum($attr);
    }
    
    public function _avg($attr){
        return r\avg($attr);
    }
    
    public function row($attr=null){
        return r\row($attr);
    }
    
    public function pluck($selectors){
        $this->query = $this->query->pluck($selectors);
        
        return $this;
    }
    
    public function without($selectors){
        $this->query = $this->query->without($selectors);
        
        return $this;
    }
    
    public function merge($obj){
        $this->query = $this->query->merge($obj);
        
        return $this;
    }
    
    public function append($value){
        $this->query = $this->query->append($value);
        
        return $this;
    }
    
    public function prepend($value){
        $this->query = $this->query->prepend($value);
        
        return $this;
    }
    
    public function difference($value){
        $this->query = $this->query->difference($value);
        
        return $this;
    }
    
    public function hasFields($selectors){
        $this->query = $this->query->hasFields($selectors);
        
        return $this;
    }
    
    public function at($index, $action, $params=null){
        switch($action){
            case "insert":
                $this->query = $this->query->insertAt($index, $params);
                break;
            
            case "splice":
                $this->query = $this->query->spliceAt($index, $params);
                break;
            
            case "delete":
                $this->query = $this->query->deleteAt($index, $params);
                break;
            
            case "change":
                $this->query = $this->query->changeAt($index, $params);
                break;
        }
        
        return $this;
    }
    
    public function keys(){
        $this->query = $this->query->keys();
        
        return $this;
    }
    
    public function add($val){
        $this->query = $this->query->add($val);
        
        return $this;
    }
    
    public function sub($val){
        $this->query = $this->query->sub($val);
        
        return $this;
    }
    
    public function mul($val){
        $this->query = $this->query->mul($val);
        
        return $this;
    }
    
    public function div($val){
        $this->query = $this->query->div($val);
        
        return $this;
    }
    
    public function mod($val){
        $this->query = $this->query->mod($val);
        
        return $this;
    }
    
    public function rAnd($val){
        $this->query = $this->query->rAnd($val);
        
        return $this->run();
    }
    
    public function rOr($val){
        $this->query = $this->query->rOr($val);
        
        return $this->run();
    }
    
    public function eq($val){
        $this->query = $this->query->eq($val);
        
        return $this->run();
    }
    
    public function ne($val){
        $this->query = $this->query->ne($val);
        
        return $this->run();
    }
    
    public function gt($val){
        $this->query = $this->query->gt($val);
        
        return $this->run();
    }
    
    public function ge($val){
        $this->query = $this->query->ge($val);
        
        return $this->run();
    }
    
    public function lt($val){
        $this->query = $this->query->lt($val);
        
        return $this->run();
    }
    
    public function le($val){
        $this->query = $this->query->le($val);
        
        return $this->run();
    }
    
    public function not(){
        $this->query = $this->query->not();
        
        return $this->run();
    }
    
    public function match($regex){
        $this->query = $this->query->match($regex);
        
        return $this;
    }
    
    public function now(){
        return r\now();
    }
    
    public function time(array $params = array()){
        return $this->call("time", $params);
    }
    
    public function epoch($time=false){
        return ($time === false) ? time() : r\epochTime($time);
    }
    
    public function iso8601($date, array $args = array()){
        $opts = array($date);
        $opts = array_merge($opts, $args);
        
        return $this->call("iso8601", $opts);
    }
    
    public function branch($test, $true_res, $false_res){
        return r\branch($test, $true_res, $false_res);
    }
    
    public function error($msg){
        return $this->call("error", array($msg));
    }
    
    public function expr($data){
        $param = is_array($data) ? $data : array($data);
        
        return $this->call("expr", $param);
    }
    
    public function js($js, $timeout=5){
        return $this->call("js", array($js, $timeout));
    }
    
    public function coerceTo($newtype){
        $this->query = $this->query->coerceTo($newtype);
        
        return $this;
    }
    
    public function typeOf(){
        $this->query = $this->query->typeOf();
        
        return $this->run();
    }
    
    public function info(){
        $this->query = $this->query->info();
        
        return $this->run();
    }
    
    public function json($data){
        return $this->call("json", array($data));
    }
    
    public function systeminfo(){
        return $this->call("syteminfo");
    }
    
    public function useDb($newdb){
        $this->conn->useDb($newdb);
        
        return $this;
    }
    
    public function dbCreate($db){
        return $this->call("dbCreate", array($db));
    }
    
    public function dbDrop($db){
        return $this->call("dbDrop", array($db));
    }
    
    public function dbList($db){
        $res = $this->call("dbList", array($db));
        
        return $res->run($this->conn);
    }
    
    public function call($method, array $args = array()){
        return call_user_func_array('r\\' . $method, $args);
    }
}
