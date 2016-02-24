<?php

namespace Octopuce\Acme\IntegrationTests;
use Symfony\Component\Yaml\Yaml;

class Common extends \PHPUnit_Extensions_Database_TestCase {

    protected $_connectionMock;
    protected $_db_connection;
    protected $datasetsPath;

    /**
     *
     */
    public function setUp() {
        $this->datasetsPath = APP_PATH."/tests/datasets/";
        
        // Build ACME client
        $config = Yaml::parse(file_get_contents(CONFIG_FILE));
        $db = $config["db"];
        $dsn = "${db["driver"]}://${db["user"]}:${db["pass"]}@${db["host"]}/${db["dbname"]}";
        $params = array(
            'params' => array(
                'database' => $dsn,
                'api' => $config["api"],
                'challenge' => $config["challenge"]
            ),
        );
        $client = new \Octopuce\Acme\Client($params);
        parent::setUp();
    }

    /**
     *
     *@return type
     **/
    protected function getConnection(){
        $this->_connectionMock  = null;
        return $this->_connectionMock;
    }

    /**
     * Must be overriden
     * @return type
     */
    protected function getDataSet(){
        throw new \Exception("Please override.");
    }

    /**
     *
     *@param string $fileList
     *@return \PHPUnit_Extensions_Database_DataSet_YamlDataSet
     *@throws \Exception
     **/
    public function loadDataSet($fileList)
    {
        if (empty($fileList)) {
            throw new \Exception("No files specified");
        }
        if( !is_array($fileList)){
            $fileList       = array($fileList);
        }
        $datasetList        = array();
        foreach ($fileList as $file_name) {
            $file               =  $this->datasetsPath."/$file_name";
            if( !is_file($file) ){
                throw new \Exception("missing $file");
            }
            $dataSet            = new \PHPUnit_Extensions_Database_DataSet_YamlDataSet($file);
            $datasetList[]      = $dataSet;
        }
        $compositeDataSet            = new \PHPUnit_Extensions_Database_DataSet_CompositeDataSet($datasetList);
        return $compositeDataSet;
    } 

    /**
     * 
     * @param type $classList
     * @return \Octopuce\Acme\IntegrationTests\PHPUnit_Extensions_Database_DataSet_CompositeDataSet
     * @throws \Exception
     */
    public function loadArrayDataSet($classList){
        if (empty($classList)) {
            throw new \Exception("No class specified");
        }
        if( !is_array($classList)){
            $classList       = array($classList);
        }
        $datasetList        = array();
        foreach ($classList as $class_name) {
            $dataSet            =   new $class_name($this);
            $datasetList[]      =   $dataSet;
        }
        $compositeDataSet            = new \PHPUnit_Extensions_Database_DataSet_CompositeDataSet($datasetList);
        return $compositeDataSet;
    }

    /**
     *
     *@param type $dataset
     *@return \PHPUnit_Extensions_Database_DataSet_YamlDataSet
     **/
    protected function buildYamlDataSet($dataset) {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            $this->datasetsPath . $dataset
        );
    }

}
