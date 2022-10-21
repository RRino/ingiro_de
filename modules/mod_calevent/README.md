Descrizione

MODULI CALEVENT è composta da 4 MODULI

# mod_calevent 
visualizza gli calevent trekking eccetera prelevando dal database tabella #__segnalacalevent_calevent
per copiare da joomla3 cambiare il campo 'catid' in 'catidev'
nel modulo c'e un parametro che deve contenere il nome del menu che richiama l'articolo Mod_caleventadd il quale richiama il modulo mod_caleventadd (da un menu nascosto)
per accedere all'aggiunta di calevent è necessario appartenere al gruppo AddCalevent (id 5) derivato da  Publisher al quale , AddCalevent, bisogna escludere tutto e escludere anche la possibilita di edit dell'articolo che richiama il modulo
creare utenti  derivata da AddCalevent (id 10 )ai quali è permesso aggiungere calevent
Creare utenti come Author (id 3) ai quali è permesso la gestione utenti

# mod_caleventadd
Inserimento e modifica calevent trekking ecc lavora sul database tabella #__segnalacalevent_calevent

# mod_caleventiscriz
Permette di iscriversi ai terkking calevent ecc usa la tabella #__segnalcalevent_iscrizioni

# mod_caleventgest
Per la gestione e stampa delle  iscrizioni

TUTTI I MODULI hanno un articolo che richiama il modulo e viene poi richiamato dal programma o menu
passando eventualmente alcuni valori che vengono poi recuperati nel modulo richiamato


gli articoli sono sotto la categoria USO_INTERNO

i moduli vengono richiamati utlizzando il nome posisiozione non l'ID

login accompagnatori
Alias: modulo-login-accompagnatori
Category: Uso_interno

				
Calevent gestione
Alias: calevent-gestione
Category: Uso_interno
			
Calevent Iscrizioni
Alias: calevent-iscrizioni
Category: Uso_interno
				
Calevent aggiungi
Alias: calevent-aggiungi
Category: Uso_interno

Select Calevent lista				
Calevent lista
Alias: calevent-lista
Category: Uso_interno

