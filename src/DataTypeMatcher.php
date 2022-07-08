<?php

namespace Mrzkit\LaravelExtensionHierarchical;

class DataTypeMatcher
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $comment;

    public function __construct(string $field, string $type, string $comment)
    {
        $this->field   = $field;
        $this->type    = $type;
        $this->comment = $comment;
    }

    /**
     * @return String
     */
    public function getField() : string
    {
        return $this->field;
    }

    /**
     * @return String
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @return String
     */
    public function getComment() : string
    {
        return $this->comment;
    }

    /**
     * @desc
     * @return array
     */
    public function matchInt() : array
    {
        $type = strtolower($this->getType());

        if (preg_match('/unsigned/', $type)) {
            $isSigned = true;
        } else {
            $isSigned = false;
        }

        if (preg_match('/^int/', $type)) {
            //
            $result["type"] = "integer";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = 4294967295;
            } else {
                // 有符号
                $result["min"] = -((4294967295 >> 1) + 1);
                $result["max"] = (4294967295 >> 1);
            }
        } else if (preg_match('/^tinyint/', $type)) {
            //
            $result["type"] = "integer";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = 255;
            } else {
                // 有符号
                $result["min"] = -128;
                $result["max"] = 127;
            }
        } else if (preg_match('/^smallint/', $type)) {
            //
            $result["type"] = "integer";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = 65536;
            } else {
                // 有符号
                $result["min"] = -32768;
                $result["max"] = 32767;
            }
        } else if (preg_match('/^mediumint/', $type)) {
            //
            $result["type"] = "integer";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = 16777215;
            } else {
                // 有符号
                $result["min"] = -8388608;
                $result["max"] = 8388607;
            }
        } else if (preg_match('/^bigint/', $type)) {
            //
            $result["type"] = "integer";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = PHP_INT_MAX;
            } else {
                // 有符号
                $result["min"] = -(PHP_INT_MAX + 1);
                $result["max"] = PHP_INT_MAX;
            }
        } else {
            //
            $result = [];
        }

        return $result;
    }

    /**
     * @desc
     * @return array
     */
    public function matchString() : array
    {
        $type = strtolower($this->getType());

        if (preg_match('/^char/', $type)) {
            //
            $result["type"] = "string";
            if (preg_match('/\((\d+)\)/', $type, $matches)) {
                $result["min"] = 0;
                $result["max"] = $matches[1];
            } else {
                $result["min"] = 0;
                $result["max"] = 255;
            }
        } else if (preg_match('/^varchar/', $type)) {
            //
            $result["type"] = "string";
            if (preg_match('/\((\d+)\)/', $type, $matches)) {
                $result["min"] = 0;
                $result["max"] = $matches[1];
            } else {
                $result["min"] = 0;
                $result["max"] = 65535;
            }
        } else if (preg_match('/^tinytext/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 255;
        } else if (preg_match('/^text/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 65535;
        } else if (preg_match('/^mediumtext/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 1677215;
        } else if (preg_match('/^longtext/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 4294967295;
        } else if (preg_match('/^tinyblob/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 255;
        } else if (preg_match('/^blob/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 65535;
        } else if (preg_match('/^mediumblob/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 1677215;
        } else if (preg_match('/^longblob/', $type)) {
            $result["type"] = "string";
            $result["min"]  = 0;
            $result["max"]  = 4294967295;
        } else {
            //
            $result = [];
        }

        return $result;
    }

    /**
     * @desc
     * @return array
     */
    public function matchFloat() : array
    {
        $type = strtolower($this->getType());

        if (preg_match('/unsigned/', $type)) {
            $isSigned = true;
        } else {
            $isSigned = false;
        }

        if (preg_match('/^decimal/', $type)) {
            //
            $result["type"] = "double";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = PHP_FLOAT_MAX;
            } else {
                // 有符号
                $result["min"] = PHP_FLOAT_MIN;
                $result["max"] = PHP_FLOAT_MAX;
            }
        } else if (preg_match('/^double/', $type)) {
            //
            $result["type"] = "double";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = PHP_FLOAT_MAX;
            } else {
                // 有符号
                $result["min"] = PHP_FLOAT_MIN;
                $result["max"] = PHP_FLOAT_MAX;
            }
        } else if (preg_match('/^float/', $type)) {
            //
            $result["type"] = "double";
            if ($isSigned) {
                // 无符号
                $result["min"] = 0;
                $result["max"] = PHP_FLOAT_MAX;
            } else {
                // 有符号
                $result["min"] = PHP_FLOAT_MIN;
                $result["max"] = PHP_FLOAT_MAX;
            }
        } else {
            //
            $result = [];
        }

        return $result;
    }

    /**
     * @desc
     * @return array
     */
    public function matchDate() : array
    {
        $type = strtolower($this->getType());

        if (preg_match('/^date/', $type)) {
            //
            $result["type"] = "string";
            $result["min"]  = "";
            $result["max"]  = "";
        } else if (preg_match('/^time/', $type)) {
            //
            $result["type"] = "string";
            $result["min"]  = "";
            $result["max"]  = "";
        } else if (preg_match('/^year/', $type)) {
            //
            $result["type"] = "string";
            $result["min"]  = "";
            $result["max"]  = "";
        } else if (preg_match('/^datetime/', $type)) {
            //
            $result["type"] = "string";
            $result["min"]  = "";
            $result["max"]  = "";
        } else if (preg_match('/^timestamp/', $type)) {
            //
            $result["type"] = "string";
            $result["min"]  = "";
            $result["max"]  = "";
        } else {
            //
            $result = [];
        }

        return $result;
    }
}
