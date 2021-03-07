<?php


//Soluzione esercizio aereoporti di Matteo Fattorini
//Vogliamo trovare il volo più economico da un punto A ad un punto B, supponendo al massimo uno scalo.

// Immaginiamo di avere raccolto i dati riguardo ai voli da un database su un server.

$flights = [
    ["departure" => "BRI", "arrival" => "BDS", "price" => 20],
    ["departure" => "BRI", "arrival" => "ANC", "price" => 5],
    ["departure" => "ANC", "arrival" => "CRV", "price" => 3],
    ["departure" => "CRV", "arrival" => "BDS", "price" => 4],
    ["departure" => "CRV", "arrival" => "BRI", "price" => 2],
    ["departure" => "CRV", "arrival" => "XLR", "price" => 3],
    ["departure" => "SUF", "arrival" => "BDS", "price" => 5],
    ["departure" => "FLR", "arrival" => "NAP", "price" => 10],
    ["departure" => "FLR", "arrival" => "BDS", "price" => 1],
    ["departure" => "FLR", "arrival" => "FRA", "price" => 5],
    ["departure" => "NAP", "arrival" => "CRV", "price" => 12],
    ["departure" => "NAP", "arrival" => "BDS", "price" => 16], 
    ["departure" => "NAP", "arrival" => "FLR", "price" => 1],
    ["departure" => "XLR", "arrival" => "BRI", "price" => 3],
    ["departure" => "BOL", "arrival" => "FRA", "price" => 3],
    ["departure" => "BOL", "arrival" => "FLR", "price" => 10],
    ["departure" => "FRA", "arrival" => "NAP", "price" => 3],

];

// Vogliamo creare una funzione che restituisca il prezzo minore tra due destinazioni, assumendo al massimo uno scalo. Esempio NAP-->BDS = 16 ma NAP --> FLR ---> BDS = 2, quindi ci aspettiamo "2" come risultato con input NAP-BDS


function giveMeLower($departure, $arrival)
{
    global $flights;
    $price = 0;

    // Prendiamo il prezzo base del tragitto diretto verso la rotta scelta 

    foreach ($flights as $flight) {
        if (($flight["departure"] == $departure) and ($flight["arrival"] == $arrival)) {
            $price = $flight["price"];
        }
    }

    //Adesso vediamo se c'è un percorso più vantaggioso:
    // Per ogni rotta, vediamo se partendo dalla partenza in input ci sono altri voli

    foreach ($flights as $volo) {
        if (($volo["departure"] == $departure)) {

            // se si, memorizziamo il prezzo del primo scalo
            
            $tempPrice = $volo["price"];
            $scalo = $volo["arrival"];

            // se esistono rotte che partendo dallo scalo arrivano dove vogliamo
            // valutiamo se il prezzo totale è più vantaggioso, e se lo è riassegnamo la variabile price

            foreach ($flights as $volo_int) {
                if (($volo_int["departure"] == $scalo) and $volo_int["arrival"] == $arrival) {
                    $tempPrice += $volo_int["price"];
                    if ($tempPrice < $price || $price == 0) {
                        $price = $tempPrice;
                    }
                }
            }
        }
    }

    // Se a questo punto del codice il prezzo è ancora 0 allora non esiste nessuna rotta
    return $price == 0 ? "Nessun volo trovato" : $price;
}

//Non ci sono scali più convenienti, expected: 3
var_dump(giveMeLower("BOL", "FRA"));

//Non ci sono scali che vanno nello stesso posto, expected: 3
var_dump(giveMeLower("CRV", "XLR"));

// 1 scalo con NAP-FLR(1) + FLR-BDS(2), expected:2
var_dump(giveMeLower("NAP", "BDS"));

// Più di uno scalo, expected 20 
var_dump(giveMeLower("BRI", "BDS"));

// Non esiste un volo diretto, ma esiste uno scalo, expected:11
var_dump(giveMeLower("BOL", "BDS"));

// Non esiste una rotta con queste coordinate: expected: "Nessun volo trovato"
var_dump(giveMeLower("NOR", "XPA"));
