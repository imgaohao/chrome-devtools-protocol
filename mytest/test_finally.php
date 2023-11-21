<?php

class Test {
    public function __destruct()
    {
        echo 5;
    }
}

try {
    $a = new Test();
    echo 1;
    echo 2;
} finally {
    echo 3;
}