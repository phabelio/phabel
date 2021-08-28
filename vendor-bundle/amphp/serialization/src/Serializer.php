<?php

namespace Phabel\Amp\Serialization;

interface Serializer
{
    /**
     * @param mixed $data
     *
     * @return string
     *
     * @throws SerializationException
     */
    public function serialize($data);
    /**
     * @param string $data
     *
     * @return mixed The unserialized data.
     *
     * @throws SerializationException
     */
    public function unserialize($data);
}
