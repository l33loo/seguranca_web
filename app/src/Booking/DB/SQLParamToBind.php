<?php declare(strict_types = 1);

namespace App\DB;

class SQLParamToBind
{
    protected string|int $param;
    protected mixed $var;
    protected int $type = \PDO::PARAM_STR;
    protected int $maxLength = 0;
    protected mixed $driverOptions = null;

    public function __construct(
        string|int $param,
        mixed $var,
        int $type = \PDO::PARAM_STR,
        int $maxLength = 0,
        mixed $driverOptions = null
    ) {
        $this->param = $param;
        $this->var = $var;
        $this->type = $type;
        $this->maxLength = $maxLength;
        $this->driverOptions = $driverOptions;
    }
    

    /**
     * Get the value of param
     */ 
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set the value of param
     *
     * @return  self
     */ 
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Get the value of var
     */ 
    public function getVar()
    {
        return $this->var;
    }

    /**
     * Set the value of var
     *
     * @return  self
     */ 
    public function setVar($var)
    {
        $this->var = $var;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of maxLength
     */ 
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * Set the value of maxLength
     *
     * @return  self
     */ 
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * Get the value of driverOptions
     */ 
    public function getDriverOptions()
    {
        return $this->driverOptions;
    }

    /**
     * Set the value of driverOptions
     *
     * @return  self
     */ 
    public function setDriverOptions($driverOptions)
    {
        $this->driverOptions = $driverOptions;

        return $this;
    }
}