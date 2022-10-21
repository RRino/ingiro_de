

---------------------------------------------------------------------------------------

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

creato il 26-ottobre 2021 dura 30 gg
-------------------
// per richiedere un token

https://github.com/settings/tokens

------------------

Procedura per creare un repository


Come suggerito nell'aiuto di GitHub :

Crea un nuovo repository su GitHub.

Apri Git Bash.

Cambia la directory di lavoro corrente nel tuo progetto locale.

Inizializza la directory locale come repository Git.

 $ git init
Aggiungi i file nel tuo nuovo repository locale. Questo li mette in scena per il primo commit.

 $ git add .
Conferma i file che hai messo in scena nel tuo repository locale.

 $ git commit -m "First commit"
Nella parte superiore della pagina Configurazione rapida del tuo repository GitHub, fai clic per copiare l'URL del repository remoto.

Nel prompt dei comandi, aggiungi l'URL per il repository remoto in cui verrà eseguito il push del tuo repository locale.

 $ git remote add origin <remote repository URL>
 # Sets the new remote
 $ git remote -v
 # Verifies the new remote URL
Invia le modifiche nel tuo repository locale a GitHub se è stato chiamato un ramo remoto master(o mainse è quello che stai usando)

 $ git push origin master
Altrimenti dovrai nominare prima la filiale locale con

 $ git branch -m <new_name>
e poi premilo per aggiungere un nuovo ramo chiamato <new_name>

 $ git push origin -u <new_name>
Se riscontri ancora errori come "Gli aggiornamenti sono stati rifiutati perché il telecomando contiene del lavoro che non hai localmente", questo è normalmente dovuto al fatto che il repository remoto è stato creato manualmente di recente. Assicurati di non sovrascrivere nulla sull'estremità remota prima di forzare il push della cartella git locale utilizzando

$ git push origin -u -f <new_name>
