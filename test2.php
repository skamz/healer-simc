<?php

use Spells\Priest\AscendedBlast;
use Spells\SpellSchool\Shadow;

require_once(__DIR__ . "/autoloader.php");

$spell = new AscendedBlast();
echo $spell->getName();