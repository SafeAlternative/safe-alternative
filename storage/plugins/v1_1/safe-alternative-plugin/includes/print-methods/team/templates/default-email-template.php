<?php

$default_template = 'Buna ziua,
<br/>
Comanda dumneavoastra cu numarul: [nr_comanda] din data de [data_comanda] a fost expediata prin serviciu de curierat TeamCourier si este in curs de livrare.
<br/>
Nota de transport (AWB-ul) are numarul [nr_awb] si poate fi urmarita aici: <a href="https://app.couriermanager.eu/cscourier/Main?tracking=true&appcont=1311&onlyCodes=false&awbno=[nr_awb]" target="_blank">Status comanda</a>
<br/>
In maximum 2 zile lucratoare de la data expedierii, curierul va va contacta telefonic si se va prezenta la adresa de livrare pentru a va preda coletul.
<br/>
In functie de zona in care locuiti este posibil sa va fie livrat coletul fara sa mai fiti contactat in prealabil, fiind contactat doar in situatia in care curierul nu reuseste sa va livreze coletul. Caz in care va rugam sa agreati de comun acord o data la care sa va faca livrarea.
<br/>
Daca nu sunteti contactat in maximum 2 zile lucratoare de la plasarea comenzii, va rog sa contactati compania TeamCourier la telefon +40766 131 415.
<br/>
Va felicitam pentru alegerea facuta si va asteptam cu drag sa va onoram si alte comenzi.
<br/>
Detalii comanda:
<br/>[tabel_produse]<br/>';      

add_option('team_email_template', $default_template);
register_setting('team-plugin-settings', 'team_email_template');