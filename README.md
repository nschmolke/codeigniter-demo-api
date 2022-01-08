# Beispiel-API in CodeIgniter 4 für die Verwendung mit JavaScript-Frameworks

Dieses Repository enthält eine Beispiel-API, die für die folgenden drei JavaScript-Framework-Projekte genutzt werden kann:
- [React](https://github.com/nschmolke/codeigniter-demo-react)
- [Vue.js](https://github.com/nschmolke/codeigniter-demo-vuejs)
- [Svelte](https://github.com/nschmolke/codeigniter-demo-svelte)

## Installation
Zunächst muss dieses Repository geklont werden. Die benötigten Dependencies werden mittels Composer installiert.
```
git clone https://github.com/nschmolke/codeigniter-demo-api.git .
composer install
```

Nun muss die Datei `env` nach `.env` kopiert werden. In der `.env` Datei müssen mindestens die baseURL und die Datenbank-Verbindungsdaten gesetzt sein.

Zum Beispiel wie folgt:
```
app.baseURL = 'https://api.codeigniter-demo.test'

database.default.hostname = localhost
database.default.database = ci4
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
database.default.DBPrefix =
```

Abschließend muss die Datenbank Migration durchlaufen.

```
php spark migrate 
```

Wenn alles korrekt ist, sollte die Datenbank die beiden Tabellen `migrations` und ` blogposts` enthalten.

## Starten der API

Sollte kein Webserver installiert sein, kann die API mit dem Befehl `php spark serve` gestartet werden. Die Standard-URL ist https://localhost:8080. Bei Bedarf kann hier der Port noch gesetzt werden: `php spark serve --port 8081`

Weitere Informationen zu CodeIgniter selbst können der [offiziellen Website](http://codeigniter.com) entnommen werden.