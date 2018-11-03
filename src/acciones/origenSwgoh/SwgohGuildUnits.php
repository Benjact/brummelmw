<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;
use BrummelMW\core\Utils;
use Exception;
use NoRewindIterator;
use SplFileObject;

class SwgohGuildUnits implements iSWGOH
{


    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/guild/" . ID_GREMIO . "/";
        if (!Utils::comprobarExisteRuta($ruta)) {
            throw new ExcepcionRuta($ruta);
        }

        $largefile = new BigFile($ruta);
        $iterator = $largefile->iterate("Text"); // Text or Binary based on your file type
        foreach ($iterator as $line) {
            echo $line;
        }
        return json_decode(file_get_contents($ruta), true);
    }
}

class BigFile
{
    protected $file;

    public function __construct($filename, $mode = "r")
    {
        if (!file_exists($filename)) {

            throw new Exception("File not found");

        }

        $this->file = new SplFileObject($filename, $mode);
    }

    protected function iterateText()
    {
        $count = 0;

        while (!$this->file->eof()) {

            yield $this->file->fgets();

            $count++;
        }
        return $count;
    }

    protected function iterateBinary($bytes)
    {
        $count = 0;

        while (!$this->file->eof()) {

            yield $this->file->fread($bytes);

            $count++;
        }
    }

    public function iterate($type = "Text", $bytes = NULL)
    {
        if ($type == "Text") {

            return new NoRewindIterator($this->iterateText());

        } else {

            return new NoRewindIterator($this->iterateBinary($bytes));
        }

    }
}