<?php

class CurlSOAPClient
{
    /**
     * @var CurlSOAPClient
     */
    private static $instance;
    /**
     * @var string
     */
    protected $address = '';
    /**
     * @var string
     */
    protected $folder = '';
    /**
     * @var string
     */
    protected $ext = '';
    /**
     * @var string
     */
    protected $_error = '';
    /**
     * @var string
     */
    protected $_response = '';

    /**
     * Class Constructor
     * @param $Options array
     */
    public function __construct($Options)
    {
        foreach ($Options as $Key => $Option) {
            if (isset($this->$Key)) {
                $this->$Key = $Option;
            }
        }

    }

    /**
     * @param $Options array
     * @return CurlSOAPClient
     */
    public static function getInstance($Options)
    {
        $InstanceID = md5(implode('|', $Options));
        if (!isset(self::$instance[$InstanceID]) or self::$instance[$InstanceID] === null) {
            self::$instance[$InstanceID] = new self($Options);
        }
        return self::$instance[$InstanceID];

    }

    /**
     * @param $Request
     * @param array $Params
     * @return bool
     */
    public function MakeRequest($Request, $Params = array())
    {
        //get xml request body from file
        $RequestData = $this->GetRequestData($Request);
        if (empty($RequestData)) {
            $this->setError('Request File Data Not Found.');
            return false;
        }
        //Bind params to marked strings
        $Request = $this->SetRequestParams($RequestData, $Params);
        if (!$Request) {
            $this->setError('Params Bind Error.');
            return false;
        }
        //Make SOAP Request
        $this->_response = trim($this->DoRequest($Request));
        if (empty($this->_response)) {
            $this->setError('Request Failed.');
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param null $StartTag
     * @return string
     */
    public function GetXML($StartTag = null)
    {
        if (!empty($StartTag)) {
            return $this->GetTagNode($StartTag);
        } else {
            return $this->_response;
        }
    }

    /**
     * @param null $StartTag
     * @return object
     */
    public function GetObject($StartTag = null)
    {
        $Array = $this->GetArray($StartTag);
        return (object)$Array;
    }

    /**
     * @param null $StartTag
     * @return array
     */
    public function GetArray($StartTag = null)
    {
        $TagNode = $this->GetXML($StartTag);
        if (empty($TagNode)) {
            $this->setError('No Data Found.');
            return array();
        } else {
            $Array = json_decode(json_encode((array)simplexml_load_string($TagNode)), 1);
            return $Array;
        }
    }


    /**
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->_error = $error;
    }

    /**
     * @param $Request
     * @return String
     */
    public function DoRequest($Request)
    {
        if (empty($Request)) {
            $this->setError('Request Data Empty.');
            return false;
        }
        $ch = curl_init($this->address);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('POST', 'Content-Type: text/xml; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Request);
        $buffer = curl_exec($ch);
        curl_close($ch);
        return $buffer;

    }

    /**
     * @param $Method
     * @return string
     */
    private function GetRequestData($Method)
    {
        if (empty($Method)) {
            $this->setError('Request Method Not Defined');
            return false;
        }
        $RequestFile = $this->folder . DIRECTORY_SEPARATOR . $Method . '.' . $this->ext;
        if (!is_file($RequestFile)) {
            $this->setError('Request File Not Found.');
            return false;
        }
        $RequestData = file_get_contents($RequestFile);
        return $RequestData;

    }

    /**
     * @param $Tag
     * @return string
     */
    public function GetTagValue($Tag)
    {
        $regV = '/(?<=^|>)[^><]+?(?=<\/' . $Tag . '|$)/i';
        preg_match($regV, $this->_response, $result);
        if (!empty($result)) {
            return $result[0];
        } else {
            return '';
        }
    }

    /**
     * @param $Tag
     * @return string
     */
    protected function GetTagNode($Tag)
    {
        $regV = '/<' . $Tag . '[\s\S]*<\/' . $Tag . '>/i';
        preg_match($regV, $this->_response, $result);
        if (!empty($result)) {
            return $result[0];
        } else {
            return '';
        }
    }

    /**
     * @param $RequestData
     * @param $Params
     * @return String
     */
    private function SetRequestParams($RequestData, $Params)
    {
        if (is_array($Params)) {
            foreach ($Params as $k => $v) {
                $RequestData = preg_replace('/\{' . $k . '\}/i', $v, $RequestData);
            }
        }
        return $RequestData;
    }

}
