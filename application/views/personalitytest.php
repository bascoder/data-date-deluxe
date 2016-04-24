<div>
    <b>Vragenlijst MBTI persoonlijkheidstest</b>
    <form>
<?php 
$neutral = "Ik zit er eigenlijk tussenin.";
$categorys = array('E5', 'N4', 'T4','J6');
$questions = array(
    'E1' =>array(
        'Ik geef de voorkeur aan grote groepen mensen, met een grote diversiteit.',
        'Ik geef de voorkeur aan intieme bijeenkomsten met uitsluitend goede vrienden.', 
        ),
    'E2' =>array(
        'Ik doe eerst, en dan denk ik.',
        'Ik denk eerst, en dan doe ik.', 
        ),
    'E3' =>array(
        'Ik ben makkelijk afgeleid, met minder aandacht voor een enkele specifieke taak.',
        'Ik kan me goed focussen, met minder aandacht voor het grote geheel.', 
        ),
    'E4' =>array(
        'Ik ben een makkelijke prater en ga graag uit.',
        'Ik ben een goede luisteraar en meer een privé-persoon.', 
        ),
    'E5' =>array(
        'Als gastheer/-vrouw ben ik altijd het centrum van de belangstelling.',
        'Als gastheer/-vrouw ben altijd achter de schermen bezig om te zorgen dat alles soepeltjes verloopt.', 
        ),
    'N1' =>array(
        'Ik geef de voorkeur aan concepten en het leren op basis van associaties.',
        'Ik geef de voorkeur aan observaties en het leren op basis van feiten.',
        ),
    'N2' =>array(
        'Ik heb een groot inbeeldingsvermogen en heb een globaal overzicht van een project.',
        'Ik ben pragmatisch ingesteld en heb een gedetailleerd beeld van elke stap.',
        ),
    'N3' =>array(
        'Ik kijk naar de toekomst.',
        'Ik houd mijn blik op het heden gericht.',
        ),
    'N4' =>array(
        'Ik houd van de veranderlijkheid in relaties en taken.',
        'Ik houd van de voorspelbaarheid in relaties en taken.',
        ),
    'T1' =>array(
        'Ik overdenk een beslissing helemaal.',
        'Ik beslis met mijn gevoel.',
        ),
    'T2' =>array(
        'Ik werk het beste met een lijst plussen en minnen.',
        'Ik beslis op basis van de gevolgen voor mensen.',
        ),
    'T3' =>array(
        'Ik ben van nature kritisch.',
        'Ik maak het mensen graag naar de zin.',
        ),
    'T4' =>array(
        'Ik ben eerder eerlijk dan tactisch.',
        'Ik ben eerder tactisch dan eerlijk.',
        ),
    'J1' =>array(
        'Ik houd van vertrouwde situaties.',
        'Ik houd van flexibele situaties.',
        ),
    'J2' =>array(
        'Ik plan alles, met een to-do lijstje in mijn hand.',
        'Ik wacht tot er meerdere ideeën opborrelen en kies dan on-the-fly wat te doen.',
        ),
    'J3' =>array(
        'Ik houd van het afronden van projecten.',
        'Ik houd van het opstarten van projecten.',
        ),
    'J4' =>array(
        'Ik ervaar stress door een gebrek aan planning en abrupte wijzigingen.',
        'Ik ervaar gedetailleerde plannen als benauwend en kijk uit naar veranderingen.',
        ),
    'J5' =>array(
        'Het is waarschijnlijker dat ik een doel bereik.',
        'Het is waarschijnlijker dat ik een kans zie.',
        ),
    'J6' =>array(
        'All play and no work maakt dat het project niet afkomt.',
        'All work and no play maakt je maar een saaie pief.',
        ),
    );

for ($i=0; $i < count($categorys); $i++) { 
    $cat = $categorys[$i];
    for ($isis=1; $isis < $cat[1]; $isis++) { 
        printQuestion($cat[0].$isis,50/$cat[1],$questions,$neutral);
        if($i < count($categorys) - 1 || $isis < $cat[1] -1){
             echo "<hr/>";
         }
    }
}

function printQuestion($questionCode, $valCange, $questions, $neutral){
    $thisQuestion = $questions[$questionCode];
    echo "<label class='PQ'>";
    if(rand(0,1) < 1){
        printQuestionLine($thisQuestion[0],$valCange,$questionCode);
        printQuestionLine($thisQuestion[1],-$valCange,$questionCode);
    } else {
        printQuestionLine($thisQuestion[1],-$valCange,$questionCode);
        printQuestionLine($thisQuestion[0],$valCange,$questionCode);
    }
    printQuestionLine($neutral, 0,$questionCode);
    echo "</label>";
}

function printQuestionLine($text, $valuechange, $name){
 echo "<input required type='radio' value='".$valuechange."' name='".$name."'>".$text . "<br/>";
}

?>
</form>
</div>