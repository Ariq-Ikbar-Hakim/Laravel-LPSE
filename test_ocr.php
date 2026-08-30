<?php
require 'vendor/autoload.php';
use Symfony\Component\Process\Process;
 = new Process(['tesseract', '--version']);
->run();
echo ->getOutput();
