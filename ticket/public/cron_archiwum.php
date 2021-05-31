<?php

require_once dirname(__FILE__) . '/../share/config/development.php';

class lotto {
    // Zawartość strony http://lotto.pl/wyniki-gier
    // jako obiekt DOMDocument
    protected $strona = NULL;
    // Powiązany DOMXPath
    protected $xpath = NULL;

    // Specjalne zachowania dla gier
    const NONE = 0;
    const PLUS = 1;
    const LOTTOPLUS = 2;
    const EKSTRA = 3;

    // Lista gier.
    protected $gry = array(
        // nazwa
        'lotto', 'mini-lotto',
        'kaskada', 'multi-multi',
        'joker', 'ekstra-pensja'
    );

    public $gameId = array(
        'lotto' => 2,
        'mini-lotto' => 1
    );

    // Spróbuj pobrać stronę http://lotto.pl/wyniki-gier
    // do zmiennej $this->strona z użyciem pliku cache.
    function __construct() {
        $cache = 'lotto_archiwum_cache.txt';

        // Sprawdź, czy da się skorzystać z pliku cache.
        if( ( !file_exists($cache) AND !is_writable(dirname($cache)) )
            OR ( file_exists($cache) AND !(is_writable($cache)) ) ) {
            // Nie, nie da się.
            $cache = '';
        }
        else
        {
            // Tak, da się.
            // Sprawdź, czy dane są aktualne.
            if(@filemtime($cache)<strtotime('yesterday 22:45') && time()<=strtotime('14:30')) {
                $recent = FALSE;
            }
            elseif( ( time()>=strtotime('14:30') && @filemtime($cache)<strtotime('14:30') )
                OR ( time()>=strtotime('22:45') && @filemtime($cache)<strtotime('22:45') ) ) {
                $recent = FALSE;
            }
            else
            {
                $recent = TRUE;
            }
        }

        $strona = '';
        $this->strona = new DOMDocument();

        if($cache == '' OR !$recent) {
            // Dane są nieaktualne, więc pobieramy je ponownie
            $strona = @file_get_contents('http://megalotto.pl/wyniki/mini-lotto/10-ostatnich-losowan');
            if(!$strona) {
                throw new Exception('Nie udało się pobrać wyników.');
            }

            // Można zapisać do cache'a...
            if($cache != '') {
                // ...więc zapamiętujemy arkusz.
                file_put_contents($cache, $strona);
            }

            @$this->strona->loadHtml($strona);
        }
        else
        {
            // Dane w cache są aktualne, więc załaduj je.
            @$this->strona->loadHtmlFile($cache);
        }

        $this->xpath = new DOMXPath($this->strona);
    }

    // Znajduje odpowiedni element w pliku HTML lub zwraca błąd.
    protected function wytnij($zapytanie, $gdzie = NULL, $blad = 'Brak danych') {
        $rezultat = $this->xpath->query($zapytanie, $gdzie);
        if(!$rezultat OR $rezultat->length <= 0) {
            throw new Exception($blad);
        }
        return $rezultat;
    }

    // Zwraca wynik gry (domyślnie lotto).
    function wynik() {
        $wyniki = $this->wyniki();
        if(!isset($wyniki[0])) {
            throw new Exception('Brak wyników dla gry.');
        }
        return $wyniki[0];
    }

    // Zwraca $liczba ostatnich wyników gry (domyślnie lotto),
    // jednak nie więcej niż 5 (tyle jest na stronie Totalizatora).
    function wyniki() {

        $rezultaty = array();

        $daty = $this->wytnij('//div[@class="lista_ostatnich_losowan"]/ul', null,
            'Nie znaleziono informacji o losowanu gry ');


        for($l = 0; $l < $daty->length; $l++) {
            $rezultat = array();

            // Znajdź $l-tą datę losowania i jego wynik
            $data = $daty->item($l);

            try {

                $rezultat['game_id'] = 1;
                $record = $this->wytnij('li', $data, 'Nie znaleziono '.$l.' daty losowania gry ');

                $record2 = $this->wytnij('a', $data, 'Nie znaleziono '.$l.' daty losowania gry ');
                $url = 'http://megalotto.pl' . $record2->item(0)->getAttribute('href');

                $dom = new DOMDocument();

                $strona = @file_get_contents($url);
                $dom->loadHtml($strona);
                $xpath = new DOMXPath($dom);

                $wygrane = $xpath->query('//table[@class="dl_wygrane_table"]/tr');

                $tmpData = explode('-', trim($record->item(1)->textContent));
                $rezultat['result_date'] = $tmpData[2].'-'.$tmpData[1].'-'.$tmpData[0];

                $rezultat['num1'] = trim($record->item(2)->textContent);
                $rezultat['num2'] = trim($record->item(3)->textContent);
                $rezultat['num3'] = trim($record->item(4)->textContent);
                $rezultat['num4'] = trim($record->item(5)->textContent);
                $rezultat['num5'] = trim($record->item(6)->textContent);

                $rezultat['win1'] = trim(floatval(str_replace(" ", "", $wygrane->item(1)->getElementsByTagName('td')->item(2)->textContent)));
                $rezultat['win2'] = trim(floatval(str_replace(" ", "", $wygrane->item(2)->getElementsByTagName('td')->item(2)->textContent)));
                $rezultat['win3'] = trim(floatval(str_replace(" ", "", $wygrane->item(3)->getElementsByTagName('td')->item(2)->textContent)));

            } catch(Exception $e) {
                break;
            }

            $rezultaty[] = $rezultat;
        }

        return $rezultaty;
    }
}

try {
    $lotto = new lotto();

    $gameId = 'mini-lotto';
    $ml = $lotto->wyniki();

    $modelResult = new \Result\Model\Result();
    foreach($ml as $row) {
//        $modelResult->insert($row);
        $one = $modelResult->getOne(array(
            'game_id' => 1,
            'result_date' => $row['result_date']
        ));

        if(is_array($one) && count($one) > 0 && array_key_exists('lotto_result_id', $one)) {
            if($one['win3'] < 1 && $row['win3'] > 0) {
                $modelResult->update($row, $one['lotto_result_id']);
            }
        } else {
            $modelResult->insert($row);
        }
    }

}
catch(Exception $e) {
    // Coś się nie powiodło - pokaż błąd.
    echo $e->getMessage();
}