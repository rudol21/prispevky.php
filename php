<?php
class Prispevok {
    private $nazov;
    private $urlko;
    private $datum;

    function __construct($pNazov, $pUrlko, $pDatum)
    {
        $this->nazov = $pNazov;
        $this->urlko = $pUrlko;
        $this->datum = $pDatum;
    }

    /**
     * @return mixed
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * @return mixed
     */
    public function getNazov()
    {
        return $this->nazov;
    }

    /**
     * @return mixed
     */
    public function getUrlko()
    {
        return $this->urlko;
    }
}

class Stranka
{
    private $zoznamPrispevkov = array();

    public function pridajPrispevok($prispevok)
    {
        array_push($this->zoznamPrispevkov, $prispevok);
    }

    public function vypisPrispevky()
    {
        foreach ($this->zoznamPrispevkov as $prispevok)
        {
            echo '<h2>'. $prispevok->getNazov().'</h2></br>';
            echo '<img src="'. $prispevok->getUrlko().'" </img></br>';
            echo $prispevok->getDatum().'</br>';
        }

    }
    public function zapisPrispevok($prispevok)
    {
        $myfile = fopen("file.txt", "a");
        $riadok= $prispevok->getNazov()."|".$prispevok->getUrlko()."|".$prispevok->getDatum()."\n";
        fwrite($myfile, $riadok);
        fclose($myfile);
    }
    public function nacitajPrispevky()
    {
        $file = fopen("file.txt", "r");
        if ($file) {
            $i = 0;
            while (($riadok = fgets($file)) !== false) {
                $stuff = explode("|", $riadok);
                array_push($this->zoznamPrispevkov, new Prispevok($stuff[0], $stuff[1], $stuff[2]));
            }
            fclose($file);
        }
    }
}

$stranka = new Stranka();
session_start();
if(isset($_GET['nadpis']))
{
    if(isset($_GET['adresa']))
    {
        $stranka->zapisPrispevok(new Prispevok($_GET['nadpis'], $_GET['adresa'], date('Y-m-d h:i:s')));
        header("Location: index.php");
    }
}
if(isset($_GET['name']) && isset($_GET['password']))
    if($_GET['name'] == '123' && $_GET['password'] == '456')
        $_SESSION['prihlasenie'] = 0;
if(isset($_GET['odhlasenie']))
{
    $_SESSION['prihlasenie'] = 1;
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TosoBlog</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
    if($_SESSION['prihlasenie'] == 0) {
        $stranka->nacitajPrispevky();
        $stranka->vypisPrispevky();
        echo '<form method="get">';
        echo '<label for="nazov" class="nazov">Nazov</label><br>';
        echo '<textarea rows="1" name="nadpis"></textarea><br>';
        echo '<label for="adresa">URL</label><br>';
        echo '<textarea rows="1" name="adresa"></textarea><br>';
        echo '<input type="submit">';
        echo '</form>';
        echo '<form method="get">';
        echo '<input type="hidden" name="odhlasenie">';
        echo '<button>'."Odhlasit".'</button>';
        echo '</form>';
    }
    else
    {
        echo '<form>';
        echo 'Meno: <input type="text" name="name" class="name"><br>';
        echo 'Heslo: <input type="password" name="password" class="password"><br>';
        echo '<input type="submit" value="Login" formmethod="get">';
        echo '</form>';
    }
    ?>
</body>
</html>
