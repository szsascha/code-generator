<?php
namespace App\Service;

class CodeGenerator
{
    public function generateTemplateModel(string $json): array
    {
        $result = json_decode($json, true);

        // Prepare the array so you can be sure that your array looks always the same 
        $result = $this->prepareArray($result);

        return $result;
    }

    private function prepareArray(array $array): array 
    {
        // Normalize keys
        $array = array_change_key_case($array, CASE_LOWER);

        $array['class'] = $this->prepareClass($array['class']);
        $array['attributes'] = $this->prepareAttributes($array['attributes']);
        $array['methods'] = $this->prepareMethods($array['methods'], $array['attributes']);

        return $array;
    }

    private function prepareClass(array $array): array
    {
        // Normalize keys
        $array = array_change_key_case($array, CASE_LOWER);

        // Always uppercase first letter of classname
        $array['name'] = ucfirst($array['name']);

        return $array;
    }

    private function prepareAttributes(array $array): array
    {
        // Normalize keys
        $array = array_change_key_case($array, CASE_LOWER);

        foreach ($array as &$attribute) {
            // Set default visibility if isn't set
            if (isset($attribute['visibility'])) {
                // Normalize visibility to always complete uppercase
                $attribute['visibility'] = strtoupper($attribute['visibility']);
            } else {
                $attribute['visibility'] = 'PRIVATE';
            }

            // Set default type if isn't set
            if (isset($attribute['type'])) {
                // Normalize type to always complete uppercase
                $attribute['type'] = strtoupper($attribute['type']);
            } else {
                $attribute['type'] = 'OBJECT';
            }

            // Always lowercase first letter of attributename
            $attribute['name'] = lcfirst($attribute['name']);
        }

        return $array;
    }

    private function prepareMethods(array $array, array $attributes = array()): array
    {
        // Normalize keys
        $array = array_change_key_case($array, CASE_LOWER);

        // Normalized and prepared method array
        $arrayOutput = array();

        foreach ($array as $method) {
            // Set default visibility if isn't set
            if (isset($array['visibility'])) {
                // Normalize visibility to always complete uppercase
                $method['visibility'] = strtoupper($method['visibility']);
            } else {
                $method['visibility'] = 'PUBLIC';
            }

            // Set default returnType if isn't set
            if (isset($method['returnType'])) {
                // Normalize type to always complete uppercase
                $method['returnType'] = strtoupper($method['returnType']);
            } else {
                $method['returnType'] = 'VOID';
            }

            // Set arguments array if isn't set
            if (!isset($method['arguments']) || !is_array($method['arguments'])) {
                $method['arguments'] = [];
            }

            // Always lowercase first letter of attributename
            $method['name'] = lcfirst($method['name']);

            // Prepare arguments
            foreach ($method['arguments'] as &$argument) {
                // Normalize keys
                $argument = array_change_key_case($argument, CASE_LOWER);

                // Set default type if isn't set
                if (isset($argument['type'])) {
                    // Normalize type to always complete uppercase
                    $argument['type'] = strtoupper($argument['type']);
                } else {
                    $argument['type'] = 'OBJECT';
                }
            }

            // Special handling "ALL_ATTRIBUTES"
            if ($method['name'] == '*ALL_ATTRIBUTES') {
                $methodbase = $method;
                foreach ($attributes as $attribute) {
                    $newMethod = $methodbase;
                    $newMethod['name'] = $attribute['name'];
                    $newMethod['returnType'] = $attribute['type'];
                    $arrayOutput[] = $newMethod;
                }
            }

            // Add method to array output
            if ($method['name'][0] != '*') $arrayOutput[] = $method;
        }

        return $arrayOutput;
    }
}