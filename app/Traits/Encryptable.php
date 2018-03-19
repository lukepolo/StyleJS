<?php

namespace App\Traits;

trait Encryptable
{
    /**
     * @param $key
     * @return string
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable) && ! empty($value)) {
            $value = decrypt($value);
        }

        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable) && ! empty($value)) {
            $value = encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
}
