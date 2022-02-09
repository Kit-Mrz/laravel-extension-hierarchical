<?php

namespace Mrzkit\LaravelExtensionHierarchical;

class CodeTemplate
{
    /**
     * @desc 代码模板
     * @param array $fields
     * @return string
     */
    public function codeTplItem(array $fields, string $varName = '$row') : string
    {
        $rs = '';
        foreach ($fields as $item) {
            $rs .= sprintf('"%s" => %s["%s"],', $item['hump'], $varName, $item['under']);
            $rs .= "\r\n";
        }

        return $rs;
    }

    /**
     * @desc 代码模板
     * @param array $fields
     * @return string
     */
    public function codeTplItemHump(array $fields, string $varName = '$row') : string
    {
        $rs = '';
        foreach ($fields as $item) {
            $rs .= sprintf('"%s" => %s["%s"],', $item['under'], $varName, $item['hump']);
            $rs .= "\r\n";
        }

        return $rs;
    }

    /**
     * @desc 代码模板
     * @param array $fields
     * @return string
     */
    public function codeTplItemIf(array $fields, string $varName = '$row') : string
    {
        $rs = '';

        foreach ($fields as $item) {
            $tmp = '
                if (isset($row["%s"])) {
                    $item["%s"] = $row["%s"];
                }
            ';

            $rs .= sprintf($tmp, $item['under'], $item['hump'], $item['under']);
        }

        return $rs;
    }

    // ------

    /**
     * @desc 代码模板
     * @param array $fields
     * @return string
     */
    public function codeTplUpdate(array $fields) : string
    {
        $rs = '';
        foreach ($fields as $field => $item) {
            $str = '
        if (isset($inputParams["' . $item['hump'] . '"])) {
            $data["' . $item['under'] . '"] = $inputParams["' . $item['hump'] . '"];
        }
        ';
            $rs  .= $str;
        }

        return $rs;
    }

    /**
     * @desc 代码模板
     * @param array $fields
     * @return string
     */
    public function codeTplStore(array $fields) : string
    {
        $rs = '';
        foreach ($fields as $field => $item) {
            $str = '"' . $item['under'] . '" => $params["' . $item['hump'] . '"],';
            $rs  .= "\r\n";
            $rs  .= $str;
        }

        return $rs;
    }

    /**
     * @desc 代码模板
     * @param array $fields
     * @return string
     */
    public function codeTplIndex(array $fields) : string
    {
        $rs = '';
        foreach ($fields as $field => $item) {
            $str = '"' . $item['hump'] . '" => $obj["' . $item['under'] . '"],';
            $rs  .= "\r\n";
            $rs  .= $str;
        }

        return $rs;
    }
}
