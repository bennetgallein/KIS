## Admin Interface:

## - Kundenübersicht
	- Kundenauflistung
	- Einnahmen (7 Tage, 30 Tage, 1 Jahr)
	- Guthabenverwaltung (Hinzufügen/Beschreibung) Einen User Guthaben geben, mit der Beschreibung
	- Produktübersicht von dem Kunden (bestellte Server, stornierte Server, beendete Server)
	- Kontaktdetails Übersicht
	- Passwort Zurücksetzen (Passwort wird dem Kunden per E-Mail gesendet / random generiertes)
	- Eine generierte Kundennummer (beim registrieren soll diese Kundennummer dann automatisch generiert werden) (HEX)
	- Vom Interface aus ein Admin Ticket erstellen für den Kunden
	- Kuntenkonto schließen (kein Einloggen mehr möglich für den Kunden)
	- Rechnungsübersicht (bezahlte, nicht bezahlte und stornierte Rechnungen auflisten mit der ID. Hierbei kann man dann auch nachsehen was gekauft wurde.)
	- Partnerprogramm übersicht (Affilite System)



## - Support
	- Support-Ticket übersicht für Supporter/Administratoren)
	- Support-Abteilungen
	- Betroffenes Produkt kann der Kunde auswählen und soll dann markiert werden im Admin Interface
	- Automatische Schließung eines Produkts ggf. nach 3 Tagen, wenn keine Kundenantwort erfolgt
	- Ticket vom Interface (Admin) schließen.

## - Berichte / Übersicht
	- Eine Übersicht der Monatlichen Transaktionen
	- Eine Übersicht für neue Kunden
	- Übersicht für stornierte Produkte


## - Produktkonfiguration
	- Produkte erstellen / entfernen
	- Preis anpassen für 30 Tage Periode
	- Beschreibung / Title
	- Konfigurierbare Funktionen (Addon Installation etc) 

## - Kundeninterface:
	- Nur Aktive Produkte sollen im Interface stehen (evt per Filter regeln bei Stornierte/Beendete Produkte)
	- Guthabenansicht
	- Kontaktinformationen bearbeiten, E-Mail Adresse ändern, Passwort ändern.
	- Support Tickets übersicht
	- Server Verwaltung bei Proxmox/TeamSpeak Servern
	- Domain Übersicht
	- DNS Verwaltung für Domain



## - Anderes:
    -E-Mail Template muss auch erstellt werden für Rechnungen etc.

APIs to use: [Proxmox](https://pve.proxmox.com/wiki/Proxmox_VE_API), TeamSpeak, Domain (AutoDNS)