<?php

namespace atoum\atoum\report\fields\runner\failures\execute\macos;

use atoum\atoum\report\fields\runner\failures;

class macvim extends failures\execute
{
    public function __construct()
    {
        parent::__construct('mvim --remote-silent +%2$s %1$s');
    }
}
