# Pilates Directory Stockholm

En komplett WordPress-baserad directory-webbplats för pilates-studios i Stockholm, utvecklad som skoluppgift.

## Funktioner

### 🏢 Studio Management
- Lägga till och redigera pilates-studios
- Detaljerad information: namn, adress, telefon, öppettider, beskrivning, bilder, priser
- Organisera studios efter område/stadsdel i Stockholm

### 🔍 Sök & Filter
- Sökfunktion på studionamn och område
- Filtrera efter:
  - Område/stadsdel i Stockholm
  - Prisklass (budget/mellan/premium)
  - Öppettider (morgon/lunch/kväll)
  - Specialiteter (gratis prova-på, nybörjarvänligt, avancerat, reformer pilates)

### ⭐ Recensionssystem
- Betyg 1-5 stjärnor
- Kommentarer från användare
- Genomsnittsbetyg per studio

### 🗺️ Google Maps Integration
- Visa alla studios på interaktiv karta
- Enskilda kartor på detaljsidor
- Automatisk geokodning av adresser

### 🎨 Design
- Minimalistiskt och professionellt tema
- Färgschema: vit, beige, svart med accenter i brunt och mörkgrönt
- Fullt responsiv design för mobil och desktop

## Installation

### 1. Starta Docker-miljön

```bash
docker-compose up -d
```

### 2. Första installation

1. Öppna: `http://localhost:8080`
2. WordPress setup:
   - **Databas:** `pilates_directory`
   - **Användarnamn:** `wpuser`
   - **Lösenord:** `wppassword123`
   - **Värd:** `db`

### 3. Aktivera plugin och tema

1. Admin: `http://localhost:8080/wp-admin`
2. Aktivera "Pilates Directory Stockholm" plugin
3. Aktivera "Pilates Stockholm" tema

### 4. Google Maps API

1. Skapa API-nyckel på Google Cloud Console
2. Ersätt `YOUR_API_KEY` i plugin-filen
3. Aktivera Maps JavaScript API

### 5. Skapa innehåll

Lägg till områden, prisklasser, specialiteter och studios via WordPress admin.

## Användning

### Shortcodes

```php
[pilates_directory]        # Alla studios med filter
[pilates_map]             # Interaktiv karta
[pilates_search]          # Sökwidget
```

## Teknisk Stack

- WordPress 6.4+ / PHP 8.0+
- MySQL 8.0
- Docker + Nginx
- Custom plugin + tema
- Google Maps API

## Utveckling

```bash
docker-compose logs -f     # Loggar
docker-compose restart     # Starta om
docker-compose down        # Stoppa
```

## Felsökning

1. **Sidan laddas inte:** Kontrollera `docker-compose ps`
2. **Databas:** Vänta några minuter efter första start
3. **Kartor:** Kontrollera API-nyckel och billing

**Lycka till! 🧘‍♀️**